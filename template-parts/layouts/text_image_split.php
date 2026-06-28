<?php
/**
 * Layout: Text + Image Split
 *
 * Clean, minimal two-column layout with text on one side
 * and an image on the other. Reusable across any page.
 */

$eyebrow  = get_sub_field( 'eyebrow' );
$heading  = get_sub_field( 'heading' );
$body     = get_sub_field( 'body' );
$image    = get_sub_field( 'image' );
$img_pos  = get_sub_field( 'image_position' ) ?: 'right';
$theme    = get_sub_field( 'theme' ) ?: 'white';
?>
<section class="section-tis section-tis--<?php echo esc_attr( $theme ); ?> <?php echo pt_spacing_classes(); ?>">
  <div class="container">
    <div class="tis-grid <?php echo $img_pos === 'left' ? 'tis-grid--img-left' : ''; ?>">

      <div class="tis-text">
        <?php if ( $eyebrow ) : ?>
          <span class="section-eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
        <?php endif; ?>
        <?php if ( $heading ) : ?>
          <h2 class="tis-heading"><?php echo esc_html( $heading ); ?></h2>
        <?php endif; ?>
        <?php if ( $body ) : ?>
          <p class="tis-body"><?php echo wp_kses_post( $body ); ?></p>
        <?php endif; ?>
      </div>

      <?php if ( $image ) : ?>
      <div class="tis-image">
        <img loading="lazy" decoding="async"
          src="<?php echo esc_url( $image['url'] ); ?>"
          alt="<?php echo esc_attr( $image['alt'] ?? '' ); ?>"
          <?php if ( ! empty( $image['width'] ) ) : ?>width="<?php echo esc_attr( $image['width'] ); ?>"<?php endif; ?>
          <?php if ( ! empty( $image['height'] ) ) : ?>height="<?php echo esc_attr( $image['height'] ); ?>"<?php endif; ?>
          loading="lazy"
        >
      </div>
      <?php endif; ?>

    </div>
  </div>
</section>
