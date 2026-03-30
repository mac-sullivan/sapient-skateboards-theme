<?php get_header( sapient_get_active_header() ); ?>

<main class="error-404-page">
  <div class="container">

    <div class="e404-inner">
      <img
        src="<?php echo get_template_directory_uri(); ?>/assets/images/Sapient_Outline.png"
        alt="Sapient"
        class="e404-logo"
      >
      <p class="e404-code">404</p>
      <h1 class="e404-heading">Page Not Found</h1>
      <p class="e404-sub">The page you're looking for doesn't exist or may have moved.</p>
      <div class="e404-actions">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary">Go Home</a>
        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-outline">Shop Boards</a>
      </div>
    </div>

  </div>
</main>

<?php get_footer(); ?>
