<?php
/**
 * Layout: Content and Image
 *
 * Image + WYSIWYG content. Image position controlled by the
 * `layout` select (image-top / image-right / image-left).
 */

$image   = get_sub_field( 'image' );
$layout  = get_sub_field( 'layout' ) ?: 'image-top';
$content = get_sub_field( 'content' );
?>
<section class="section-cai section-cai--<?php echo esc_attr( $layout ); ?> <?php echo pt_spacing_classes(); ?>">
  <div class="container">
    <div class="cai-grid">

      <?php if ( $image ) : ?>
        <div class="cai-image">
          <img
            src="<?php echo esc_url( $image['url'] ); ?>"
            alt="<?php echo esc_attr( $image['alt'] ?? '' ); ?>"
            <?php if ( ! empty( $image['width'] ) ) : ?>width="<?php echo esc_attr( $image['width'] ); ?>"<?php endif; ?>
            <?php if ( ! empty( $image['height'] ) ) : ?>height="<?php echo esc_attr( $image['height'] ); ?>"<?php endif; ?>
            loading="lazy"
            decoding="async"
          >
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
