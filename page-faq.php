<?php
/**
 * Template Name: FAQ Page
 * Slug: faq
 */
get_header();
?>

<main id="main-content">

  <div class="blog-archive-header">
    <div class="container">
      <span class="blog-eyebrow section-eyebrow">Support</span>
      <h1 class="blog-archive-title">FAQ.</h1>
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
