<?php
/**
 * Template Name: Crew Page
 * Slug: crew
 */
get_header( sapient_get_active_header() );
?>

<main id="main-content">

  <div class="blog-archive-header">
    <div class="container">
      <?php get_template_part( 'template-parts/breadcrumbs' ); ?>
      <span class="blog-eyebrow section-eyebrow">Meet the Team</span>
      <h1 class="blog-archive-title">Crew</h1>
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
  <?php else : ?>
    <?php get_template_part( 'template-parts/layouts/team_grid' ); ?>
  <?php endif; ?>

</main>

<?php get_footer(); ?>
