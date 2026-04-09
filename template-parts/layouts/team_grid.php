<?php
/**
 * Flex layout: Team Grid
 * Cards do NOT link to individual pages (single team pages removed).
 * Filter by team_category taxonomy.
 */
$eyebrow        = get_sub_field( 'eyebrow' );
$heading        = get_sub_field( 'heading' );
$member_mode    = get_sub_field( 'member_mode' ) ?: 'all';
$manual_members = get_sub_field( 'manual_members' );
$orderby_raw    = get_sub_field( 'orderby' ) ?: 'menu_order';

if ( $member_mode === 'manual' && ! empty( $manual_members ) ) {
    $member_ids = wp_list_pluck( (array) $manual_members, 'ID' );
    $members = new WP_Query( [
        'post_type'      => 'team',
        'post__in'       => $member_ids,
        'orderby'        => 'post__in',
        'posts_per_page' => count( $member_ids ),
        'post_status'    => 'publish',
    ] );
} else {
    $orderby = 'menu_order';
    $order   = 'ASC';
    if ( $orderby_raw === 'title' ) { $orderby = 'title'; $order = 'ASC'; }
    if ( $orderby_raw === 'date' )  { $orderby = 'date';  $order = 'DESC'; }

    $members = new WP_Query( [
        'post_type'      => 'team',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => $orderby,
        'order'          => $order,
    ] );
}

// Get all team categories that have published posts
$filter_terms = get_terms( [
    'taxonomy'   => 'team_category',
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
] );
$has_filter = ! is_wp_error( $filter_terms ) && ! empty( $filter_terms );

?>

<section class="section-team-grid <?php echo pt_spacing_classes(); ?>">
  <div class="container">

    <?php if ( $eyebrow || $heading ) : ?>
      <div class="team-grid-header">
        <?php if ( $eyebrow ) : ?>
          <span class="section-eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
        <?php endif; ?>
        <?php if ( $heading ) : ?>
          <h2 class="team-grid-heading"><?php echo esc_html( $heading ); ?></h2>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <?php if ( $has_filter ) : ?>
    <div class="team-filter" role="group" aria-label="Filter by category">
      <button class="team-filter-btn is-active" data-filter="all">All</button>
      <?php foreach ( $filter_terms as $term ) : ?>
        <button class="team-filter-btn" data-filter="<?php echo esc_attr( $term->slug ); ?>">
          <?php echo esc_html( $term->name ); ?>
        </button>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if ( $members->have_posts() ) : ?>
      <div class="team-grid">
        <?php while ( $members->have_posts() ) : $members->the_post();
          $photo    = get_field( 'team_image' );
          $position = get_field( 'team_position' );
          $socials  = get_field( 'team_socials' );

          // Get this member's team_category slugs for JS filter
          $member_cats = wp_get_post_terms( get_the_ID(), 'team_category', [ 'fields' => 'slugs' ] );
          $cats_attr   = ( ! is_wp_error( $member_cats ) && ! empty( $member_cats ) )
                           ? implode( ' ', $member_cats )
                           : '';

          // Instagram handle
          $ig_url    = '';
          $ig_handle = '';
          if ( $socials ) {
            foreach ( $socials as $link ) {
              if ( ( $link['platform'] ?? '' ) === 'instagram' && ! empty( $link['url'] ) ) {
                $ig_url    = $link['url'];
                $ig_handle = '@' . trim( parse_url( $link['url'], PHP_URL_PATH ), '/' );
                break;
              }
            }
          }
        ?>
          <div class="team-card" data-cats="<?php echo esc_attr( $cats_attr ); ?>">

            <?php if ( $ig_url ) : ?>
            <a href="<?php echo esc_url( $ig_url ); ?>" class="team-card-link" target="_blank" rel="noopener" aria-label="<?php the_title_attribute(); ?> on Instagram"></a>
            <?php endif; ?>

            <div class="team-card-media">
              <?php if ( $photo ) : ?>
                <img src="<?php echo esc_url( $photo['url'] ); ? loading="lazy" decoding="async">"
                     alt="<?php echo esc_attr( get_the_title() ); ?>"
                     class="team-card-gif">
              <?php elseif ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'large', [ 'class' => 'team-card-gif', 'alt' => get_the_title() ] ); ?>
              <?php endif; ?>
            </div>

            <div class="team-card-info">
              <h3 class="team-card-name"><?php the_title(); ?></h3>
              <?php if ( $ig_handle && $ig_url ) : ?>
                <a href="<?php echo esc_url( $ig_url ); ?>" class="team-card-handle" target="_blank" rel="noopener"><?php echo esc_html( $ig_handle ); ?></a>
              <?php endif; ?>
            </div>

          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    <?php else : ?>
      <p class="team-empty">No team members found.</p>
    <?php endif; ?>

  </div>
</section>

<?php if ( $has_filter ) : ?>
<script>
(function() {
  var btns  = document.querySelectorAll('.team-filter-btn');
  var cards = document.querySelectorAll('.team-card');

  btns.forEach(function(btn) {
    btn.addEventListener('click', function() {
      var filter = this.dataset.filter;

      btns.forEach(function(b) { b.classList.remove('is-active'); });
      this.classList.add('is-active');

      cards.forEach(function(card) {
        if (filter === 'all') {
          card.style.display = '';
        } else {
          var cats = (card.dataset.cats || '').split(' ');
          card.style.display = cats.indexOf(filter) !== -1 ? '' : 'none';
        }
      });
    });
  });
})();
</script>
<?php endif; ?>
