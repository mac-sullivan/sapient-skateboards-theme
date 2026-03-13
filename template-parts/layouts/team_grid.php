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

$skull_url = get_stylesheet_directory_uri() . '/assets/images/skull.svg';
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
          $position = get_field( 'team_position' );
          $socials  = get_field( 'team_socials' );
          $social_icons = [
            'instagram' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>',
            'twitter'   => '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.737-8.835L1.254 2.25H8.08l4.259 5.63zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
            'website'   => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/></svg>',
          ];
          ?>
          <div class="team-card">
            <a href="<?php the_permalink(); ?>" class="team-card-link" aria-label="View <?php the_title(); ?>"></a>

            <div class="team-card-photo">
              <img src="<?php echo esc_url( $skull_url ); ?>"
                   alt="<?php echo esc_attr( get_the_title() ); ?>"
                   class="team-card-skull">

            </div>

            <div class="team-card-body">
              <div class="team-card-meta">
                <h3 class="team-card-name"><?php the_title(); ?></h3>
                <?php if ( $position ) : ?>
                  <span class="team-card-position"><?php echo esc_html( $position ); ?></span>
                <?php endif; ?>
              </div>

              <?php if ( $socials ) : ?>
                <ul class="team-card-socials">
                  <?php foreach ( $socials as $link ) :
                    $platform = $link['platform'] ?? '';
                    $url      = $link['url'] ?? '';
                    if ( ! $url ) continue;
                    $icon = $social_icons[ $platform ] ?? $social_icons['website'];
                  ?>
                    <li>
                      <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener"
                         aria-label="<?php echo esc_attr( ucfirst( $platform ) ); ?>"
                         class="team-social-link">
                        <?php echo $icon; ?>
                      </a>
                    </li>
                  <?php endforeach; ?>
                </ul>
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
