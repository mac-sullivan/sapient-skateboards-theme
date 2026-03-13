<?php
/**
 * Layout: Hero — Video in Frame
 *
 * Contained video frame with overlay image centered on top,
 * and WYSIWYG content below the frame. Industrial catalog style.
 */

$video     = get_sub_field( 'hero_video_file' );
$video_url = is_array( $video ) ? ( $video['url'] ?? '' ) : '';
$overlay   = get_sub_field( 'hero_overlay_image' );
$content   = get_sub_field( 'hero_content' );
?>
<section class="section-hero-video <?php echo pt_spacing_classes(); ?>">
  <div class="container">

    <!-- Video frame -->
    <div class="hero-video-frame">
      <?php if ( $video_url ) : ?>
      <video class="hero-video-bg" autoplay muted loop playsinline>
        <source src="<?php echo esc_url( $video_url ); ?>" type="video/mp4">
      </video>
      <?php else : ?>
      <div class="hero-video-bg hero-video-placeholder"></div>
      <?php endif; ?>

      <div class="hero-frame-overlay" aria-hidden="true"></div>

      <?php if ( $overlay ) : ?>
        <div class="hero-overlay-image">
          <img
            src="<?php echo esc_url( $overlay['url'] ); ?>"
            alt="<?php echo esc_attr( $overlay['alt'] ?? '' ); ?>"
            <?php if ( ! empty( $overlay['width'] ) ) : ?>width="<?php echo esc_attr( $overlay['width'] ); ?>"<?php endif; ?>
            <?php if ( ! empty( $overlay['height'] ) ) : ?>height="<?php echo esc_attr( $overlay['height'] ); ?>"<?php endif; ?>
            loading="eager"
          >
          <?php if ( $content ) : ?>
            <div class="hero-overlay-caption">
              <?php echo wp_kses_post( $content ); ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

  </div>

</section>
