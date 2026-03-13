<?php
/**
 * Template for /contact/ — uses ACF flex field page builder
 */
add_filter('body_class', function($classes) { $classes[] = 'page-contact'; return $classes; });
get_header();
?>

<main id="main-content">
  <?php if ( have_rows( 'page_sections' ) ) : ?>
    <?php while ( have_rows( 'page_sections' ) ) : the_row(); ?>
      <?php
      $layout = get_row_layout();
      $part   = 'template-parts/layouts/' . $layout;
      if ( locate_template( $part . '.php' ) ) {
        get_template_part( $part );
      }
      ?>
    <?php endwhile; ?>
  <?php else : ?>
    <?php get_template_part( 'template-parts/layouts/contact_form' ); ?>
  <?php endif; ?>
</main>

<?php get_footer(); ?>
