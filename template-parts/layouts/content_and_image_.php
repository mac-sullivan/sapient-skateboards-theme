<?php
/**
 * Layout: Content and Image
 *
 * Image + WYSIWYG content. Image position controlled by the
 * `layout` select (image-top / image-right / image-left).
 *
 * PERF: when this layout is the FIRST flex-content row on a page (e.g.
 * the homepage hero) the image is the LCP candidate, so we serve it
 * eagerly with fetchpriority="high" and let WordPress emit the full
 * srcset via wp_get_attachment_image(). Subsequent occurrences keep
 * lazy-loading.
 */

$image   = get_sub_field( 'image' );
$layout  = get_sub_field( 'layout' ) ?: 'image-top';
$content = get_sub_field( 'content' );

// Track how many cai blocks have rendered on this page so far.
static $pt_cai_index = 0;
$pt_cai_index++;
$is_lcp = ( $pt_cai_index === 1 );
?>
<section class="section-cai section-cai--<?php echo esc_attr( $layout ); ?> <?php echo pt_spacing_classes(); ?>">
  <div class="container">
    <div class="cai-grid">

      <?php if ( $image ) : ?>
        <div class="cai-image">
          <?php
          // Prefer wp_get_attachment_image() — it emits a full srcset
          // (multiple WP-generated sizes) plus width/height for free.
          if ( ! empty( $image['ID'] ) ) {
              $attrs = [
                  'alt'         => $image['alt'] ?? '',
                  'class'       => 'cai-image-img',
                  'decoding'    => 'async',
                  // 'sizes' hint: the image column is roughly 100vw on
                  // mobile and capped around 720px on desktop.
                  'sizes'       => '(max-width: 720px) 100vw, 720px',
              ];
              if ( $is_lcp ) {
                  // LCP-critical: eager + high priority, no lazy.
                  $attrs['loading']       = 'eager';
                  $attrs['fetchpriority'] = 'high';
              } else {
                  $attrs['loading']       = 'lazy';
              }
              echo wp_get_attachment_image( (int) $image['ID'], 'large', false, $attrs );
          } else {
              // Fallback if the field returned only a URL (older ACF formats).
              ?>
              <img
                src="<?php echo esc_url( $image['url'] ); ?>"
                alt="<?php echo esc_attr( $image['alt'] ?? '' ); ?>"
                <?php if ( ! empty( $image['width'] ) ) : ?>width="<?php echo esc_attr( $image['width'] ); ?>"<?php endif; ?>
                <?php if ( ! empty( $image['height'] ) ) : ?>height="<?php echo esc_attr( $image['height'] ); ?>"<?php endif; ?>
                <?php if ( $is_lcp ) : ?>fetchpriority="high"<?php else : ?>loading="lazy"<?php endif; ?>
                decoding="async"
              >
              <?php
          }
          ?>
        </div>
      <?php endif; ?>

      <?php if ( $content ) : ?>
        <div class="cai-content">
          <?php echo apply_filters( 'the_content', $content ); ?>
        </div>
      <?php endif; ?>

    </div>
  </div>
</section>
