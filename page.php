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
          <h1 class="wc-page-heading"><?php the_title(); ?></h1>
          <div class="wc-page-content">
            <?php
            $page_content = trim( get_the_content() );
            if ( $page_content !== '' ) {
                the_content();
            } else {
                echo '<p class="wc-page-empty">This page is being finalized. Please check back soon, or <a href="' . esc_url( home_url( '/contact-us/' ) ) . '">contact us</a> for any questions.</p>';
            }
            ?>
          </div>
        <?php endwhile; ?>
      </div>
    </section>

  <?php endif; ?>
</main>
<?php get_footer(); ?>
