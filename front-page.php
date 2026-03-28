<?php
// If the front page is using the Intro template, hand off to it directly
$tpl = get_post_meta( get_the_ID(), '_wp_page_template', true );
if ( $tpl === 'page-intro.php' ) {
    $intro = locate_template( 'page-intro.php' );
    if ( $intro ) { load_template( $intro ); exit; }
}
?>
<?php get_header('two'); ?>
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
  <?php endif; ?>
</main>
<?php get_footer(); ?>
