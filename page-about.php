<?php
/**
 * Template Name: About Page
 * Slug: about
 */
get_header( sapient_get_active_header() );
?>

<main id="main-content">

  <div class="blog-archive-header">
    <div class="container">
      <?php get_template_part( 'template-parts/breadcrumbs' ); ?>
      <span class="blog-eyebrow section-eyebrow">Who We Are</span>
      <h1 class="blog-archive-title">Sapient Skateboards</h1>
    </div>
  </div>

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
  <?php endif; ?>

</main>

<?php get_footer(); ?>
