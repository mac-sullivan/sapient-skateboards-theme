<?php
$heading = pt_field( 'journey_heading', 'Follow the Journey' );
$photos  = pt_field( 'journey_photos' ); // ACF Gallery field
$ig_url  = pt_field( 'instagram_url', '#', 'option' );
?>
<section class="journey">
  <div class="container">
    <h2><?php echo wp_kses( $heading, [ 'em' => [], 'strong' => [] ] ); ?></h2>

    <?php if ( $photos ) : ?>
      <div class="journey-grid">
        <?php foreach ( array_slice( $photos, 0, 6 ) as $photo ) : ?>
          <img src="<?php echo esc_url( $photo['url'] ); ?>" loading="lazy" decoding="async"
               alt="<?php echo esc_attr( $photo['alt'] ?: 'Sapient Skateboards' ); ?>"
               loading="lazy">
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <p style="color:var(--gray-600);">
        Photos will appear here once added via ACF → Journey Photos.
      </p>
    <?php endif; ?>

    <?php if ( $ig_url && $ig_url !== '#' ) : ?>
      <div style="margin-top:2rem;">
        <a href="<?php echo esc_url( $ig_url ); ?>" target="_blank" rel="noopener" class="btn btn-primary">
          Follow on Instagram
        </a>
      </div>
    <?php endif; ?>
  </div>
</section>
