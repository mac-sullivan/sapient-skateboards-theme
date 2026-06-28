<?php
/**
 * home.php — Blog index page (assigned as "Posts page" in WP Settings > Reading)
 * Uses ACF flex fields from the assigned static page (archive page ID 71).
 */

// Load flex field data from the static posts page
$posts_page_id = get_option( 'page_for_posts' );

get_header( sapient_get_active_header() );
?>

<main id="main-content">
  <?php if ( $posts_page_id && have_rows( 'page_sections', $posts_page_id ) ) : ?>

    <?php while ( have_rows( 'page_sections', $posts_page_id ) ) : the_row(); ?>
      <?php
      $layout = get_row_layout();
      $part   = 'template-parts/layouts/' . $layout;
      if ( locate_template( $part . '.php' ) ) {
        get_template_part( $part );
      }
      ?>
    <?php endwhile; ?>

  <?php else : ?>
    <?php get_template_part( 'template-parts/layouts/blog_posts' ); ?>
  <?php endif; ?>
</main>

<?php get_footer(); ?>
