<?php
/**
 * Flex layout: Team Grid
 * Cards link to individual team member pages.
 * Grid shows skull placeholder; real photo lives on single-team.php.
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

    <?php if ( $members->have_posts() ) : ?>
      <div class="team-grid">
        <?php while ( $members->have_posts() ) : $members->the_post(); ?>
          <?php
          $photo    = get_field( 'team_photo' );
          $socials  = get_field( 'team_socials' );
          // Find Instagram handle from socials
          $ig_url    = '';
          $ig_handle = '';
          if ( $socials ) {
            foreach ( $socials as $link ) {
              if ( ( $link['platform'] ?? '' ) === 'instagram' && ! empty( $link['url'] ) ) {
                $ig_url = $link['url'];
                // Extract @handle from URL
                $ig_handle = '@' . trim( parse_url( $link['url'], PHP_URL_PATH ), '/' );
                break;
              }
            }
          }
          ?>
          <div class="team-card">

            <div class="team-card-media">
              <?php if ( $photo ) : ?>
                <img src="<?php echo esc_url( $photo['url'] ); ?>"
                     alt="<?php echo esc_attr( get_the_title() ); ?>"
                     class="team-card-gif">
              <?php elseif ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'large', [ 'class' => 'team-card-gif', 'alt' => get_the_title() ] ); ?>
              <?php endif; ?>
            </div>

            <div class="team-card-info">
              <h3 class="team-card-name"><?php the_title(); ?></h3>
              <?php if ( $ig_handle ) : ?>
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
