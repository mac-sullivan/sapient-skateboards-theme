<?php get_header( sapient_get_active_header() ); ?>
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

  <?php elseif ( function_exists('is_cart') && ( is_cart() || is_checkout() ) ) : ?>

    <!-- ── Cart / Checkout ────────────────────────────────── -->
    <section class="wc-cart-page">
      <div class="container">
        <div class="cart-page-header">
          <h1 class="cart-page-title"><?php echo is_checkout() ? 'Checkout' : 'Cart'; ?></h1>
        </div>
        <?php woocommerce_output_all_notices(); ?>
        <?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?>
      </div>
    </section>

  <?php else : ?>

    <!-- Fallback: render page content directly -->
    <section class="wc-page-section">
      <div class="container">
        <?php while ( have_posts() ) : the_post(); ?>
          <div class="wc-page-content">
            <?php the_content(); ?>
          </div>
        <?php endwhile; ?>
      </div>
    </section>

  <?php endif; ?>
</main>
<?php get_footer(); ?>
