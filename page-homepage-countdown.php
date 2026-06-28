<?php
/**
 * Template Name: Homepage – Countdown
 *
 * Mirrors the live homepage's flex-content render, with a countdown
 * clock injected at the top. Use this template on a duplicate of the
 * home page to preview the countdown without touching the live site.
 */
get_header( sapient_get_active_header() );
?>
<main id="main-content">

  <?php if ( have_rows( 'page_sections' ) ) : ?>
    <?php while ( have_rows( 'page_sections' ) ) : the_row();
      $layout = get_row_layout();

      // Special handling for the Content + Image layout: inject the
      // countdown clock between the image and the body text, and strip
      // the duplicate h1/h2 (Production Run + release date) from the
      // content since the countdown now expresses that information.
      if ( $layout === 'content_and_image_' ) {
          $image       = get_sub_field( 'image' );
          $cai_layout  = get_sub_field( 'layout' ) ?: 'image-top';
          $content     = (string) get_sub_field( 'content' );
          $stripped    = preg_replace( '#<h[12][^>]*>.*?</h[12]>#is', '', $content );
          ?>
          <section class="section-cai section-cai--<?php echo esc_attr( $cai_layout ); ?> section-cai--with-countdown <?php echo pt_spacing_classes(); ?>">
            <div class="container">
              <div class="cai-grid">
                <?php if ( $image ) : ?>
                  <div class="cai-image">
                    <img
                      src="<?php echo esc_url( $image['url'] ); ?>"
                      alt="<?php echo esc_attr( $image['alt'] ?? '' ); ?>"
                      <?php if ( ! empty( $image['width'] ) ) : ?>width="<?php echo esc_attr( $image['width'] ); ?>"<?php endif; ?>
                      <?php if ( ! empty( $image['height'] ) ) : ?>height="<?php echo esc_attr( $image['height'] ); ?>"<?php endif; ?>
                      loading="lazy" decoding="async"
                    >
                  </div>
                <?php endif; ?>

                <?php get_template_part( 'template-parts/components/countdown' ); ?>

                <?php if ( trim( strip_tags( $stripped ) ) !== '' ) : ?>
                  <div class="cai-content">
                    <?php echo apply_filters( 'the_content', $stripped ); ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </section>
          <?php
          continue;
      }

      $part = 'template-parts/layouts/' . $layout;
      if ( locate_template( $part . '.php' ) ) {
        get_template_part( $part );
      }
    endwhile; ?>
  <?php else : ?>
    <section class="wc-page-section">
      <div class="container">
        <?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?>
      </div>
    </section>
  <?php endif; ?>

</main>
<?php get_footer(); ?>
