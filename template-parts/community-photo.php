<?php
$image = pt_field( 'community_photo' );
?>
<section class="community-photo">
  <?php if ( $image ) : ?>
    <img src="<?php echo esc_url( $image['url'] ); ?>" loading="lazy" decoding="async"
         alt="<?php echo esc_attr( $image['alt'] ?: 'Sapient Skateboards' ); ?>">
  <?php else : ?>
    <div style="width:100%;height:100%;background:#1a1a1a;display:flex;align-items:center;justify-content:center;">
      <span style="color:rgba(255,255,255,0.3);">Community photo — set via ACF</span>
    </div>
  <?php endif; ?>
</section>
