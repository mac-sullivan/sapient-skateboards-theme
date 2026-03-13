<?php
/**
 * Flex layout: Blog Posts Grid
 * Supports "all posts" mode with orderby, or manual post selection.
 */
$eyebrow        = get_sub_field( 'eyebrow' )  ?: 'Blog';
$heading        = get_sub_field( 'heading' )  ?: 'Stories, Updates, and Community.';
$subtext        = get_sub_field( 'subtext' )  ?: '';
$post_mode      = get_sub_field( 'post_mode' ) ?: 'all';
$manual_posts   = get_sub_field( 'manual_posts' );
$orderby_raw    = get_sub_field( 'orderby' ) ?: 'date';
$posts_per_page = get_sub_field( 'posts_per_page' ) ?: 9;

// Build query args
if ( $post_mode === 'manual' && ! empty( $manual_posts ) ) {
    // Use manually selected posts in the order they were selected
    $post_ids = wp_list_pluck( (array) $manual_posts, 'ID' );
    $blog_query = new WP_Query( [
        'post_type'      => 'post',
        'post__in'       => $post_ids,
        'orderby'        => 'post__in',
        'posts_per_page' => count( $post_ids ),
        'post_status'    => 'publish',
    ] );
} else {
    // All posts with selected order
    $orderby = 'date';
    $order   = 'DESC';
    if ( $orderby_raw === 'date_asc' )    { $orderby = 'date';       $order = 'ASC'; }
    if ( $orderby_raw === 'title' )       { $orderby = 'title';      $order = 'ASC'; }
    if ( $orderby_raw === 'menu_order' )  { $orderby = 'menu_order'; $order = 'ASC'; }

    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

    $blog_query = new WP_Query( [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => (int) $posts_per_page,
        'paged'          => $paged,
        'orderby'        => $orderby,
        'order'          => $order,
    ] );
}
?>

<section class="section-blog-posts <?php echo pt_spacing_classes(); ?>">

  <!-- Header -->
  <div class="blog-archive-header">
    <div class="container">
      <?php if ( $eyebrow ) : ?>
        <span class="blog-eyebrow section-eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
      <?php endif; ?>
      <?php if ( $heading ) : ?>
        <h1 class="blog-archive-title"><?php echo esc_html( $heading ); ?></h1>
      <?php endif; ?>
      <?php if ( $subtext ) : ?>
        <p class="blog-archive-sub"><?php echo esc_html( $subtext ); ?></p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Grid -->
  <div class="blog-archive-body">
    <div class="container">
      <?php if ( $blog_query->have_posts() ) : ?>
        <div class="blog-grid">
          <?php while ( $blog_query->have_posts() ) : $blog_query->the_post();
            $thumb    = get_the_post_thumbnail_url( get_the_ID(), 'large' );
            $cats     = get_the_category();
            $cat_name = $cats ? esc_html( $cats[0]->name ) : '';
            $excerpt  = get_the_excerpt();
          ?>
          <article class="blog-card">
            <a href="<?php the_permalink(); ?>" class="blog-card-img-link" tabindex="-1" aria-hidden="true">
              <div class="blog-card-img">
                <?php if ( $thumb ) : ?>
                  <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                <?php else : ?>
                  <div class="blog-card-img-placeholder"></div>
                <?php endif; ?>
              </div>
              <?php if ( $cat_name ) : ?>
                <span class="blog-card-cat-overlay"><?php echo $cat_name; ?></span>
              <?php endif; ?>
            </a>
            <div class="blog-card-body">
              <h2 class="blog-card-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </h2>
              <p class="blog-card-excerpt"><?php echo wp_trim_words( $excerpt, 22, '…' ); ?></p>
              <div class="blog-card-meta">
                <span class="blog-card-date"><?php echo get_the_date( 'M j, Y' ); ?></span>
                <a href="<?php the_permalink(); ?>" class="blog-card-read-more">
                  Read story
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
              </div>
            </div>
          </article>
          <?php endwhile; wp_reset_postdata(); ?>
        </div>

        <!-- Pagination (only in "all" mode) -->
        <?php if ( $post_mode !== 'manual' && $blog_query->max_num_pages > 1 ) : ?>
          <div class="blog-pagination">
            <?php echo paginate_links( [
              'total'     => $blog_query->max_num_pages,
              'current'   => $paged ?? 1,
              'prev_text' => '← Newer',
              'next_text' => 'Older →',
              'mid_size'  => 2,
            ] ); ?>
          </div>
        <?php endif; ?>

      <?php else : ?>
        <p class="blog-empty">No posts yet — check back soon.</p>
      <?php endif; ?>
    </div>
  </div>

</section>
