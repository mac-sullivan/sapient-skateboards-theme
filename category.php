<?php
/**
 * category.php — Category archive page
 * Matches the blog archive design with active filter state.
 */
get_header( sapient_get_active_header() );

$current_cat = get_queried_object();
$all_cats    = get_categories( [ 'hide_empty' => true, 'orderby' => 'name', 'order' => 'ASC' ] );
$paged       = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$blog_query = new WP_Query( [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'cat'            => $current_cat->term_id,
    'posts_per_page' => 9,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
] );
?>

<main id="main-content">
<section class="section-blog-posts">

  <!-- Header -->
  <div class="blog-archive-header">
    <div class="container">
      <?php get_template_part( 'template-parts/breadcrumbs' ); ?>
      <h1 class="blog-archive-title"><?php echo esc_html( $current_cat->name ); ?></h1>
    </div>
  </div>

  <!-- Category Filter -->
  <?php if ( ! empty( $all_cats ) ) : ?>
  <div class="blog-filter-wrap">
    <div class="container">
      <div class="blog-filter" role="group" aria-label="Filter by category">
        <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"
           class="blog-filter-btn">
          All
        </a>
        <?php foreach ( $all_cats as $cat ) : ?>
          <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"
             class="blog-filter-btn<?php echo ( $current_cat->term_id === $cat->term_id ) ? ' is-active' : ''; ?>">
            <?php echo esc_html( $cat->name ); ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Grid -->
  <div class="blog-archive-body">
    <div class="container">
      <?php if ( $blog_query->have_posts() ) : ?>
        <div class="blog-grid">
          <?php while ( $blog_query->have_posts() ) : $blog_query->the_post();
            $thumb    = get_the_post_thumbnail_url( get_the_ID(), 'large' );
            $cats     = get_the_category();
            $cat_name = $cats ? esc_html( $cats[0]->name ) : '';
          ?>
          <article class="blog-card">
            <a href="<?php the_permalink(); ?>" class="blog-card-link" aria-label="<?php the_title_attribute(); ?>"></a>
            <a href="<?php the_permalink(); ?>" class="blog-card-img-link" tabindex="-1" aria-hidden="true">
              <div class="blog-card-img">
                <?php if ( $thumb ) : ?>
                  <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                <?php else : ?>
                  <div class="blog-card-img-placeholder"></div>
                <?php endif; ?>
              </div>
            </a>
            <div class="blog-card-body">
              <h2 class="blog-card-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </h2>
              <div class="blog-card-meta">
                <span class="blog-card-date"><?php echo get_the_date( 'M j, Y' ); ?></span>
                <?php if ( $cat_name ) : ?>
                  <span class="blog-card-cat"><?php echo esc_html( $cat_name ); ?></span>
                <?php endif; ?>
              </div>
            </div>
          </article>
          <?php endwhile; wp_reset_postdata(); ?>
        </div>

        <?php if ( $blog_query->max_num_pages > 1 ) : ?>
          <div class="blog-pagination">
            <?php echo paginate_links( [
              'total'     => $blog_query->max_num_pages,
              'current'   => $paged,
              'prev_text' => '← Newer',
              'next_text' => 'Older →',
              'mid_size'  => 2,
            ] ); ?>
          </div>
        <?php endif; ?>

      <?php else : ?>
        <p class="blog-empty">No posts in this category yet.</p>
      <?php endif; ?>
    </div>
  </div>

</section>
</main>

<?php get_footer(); ?>
