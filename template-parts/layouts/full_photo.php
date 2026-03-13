<?php
$img     = get_sub_field('full_photo_image');
$height  = get_sub_field('full_photo_height') ?: 'clamp(320px, 55vw, 580px)';
$overlay = get_sub_field('full_photo_overlay');
$text    = get_sub_field('full_photo_text');
?>
<section class="section-full-photo" style="height: <?php echo esc_attr($height); ?>;">
  <?php if ($img) : ?>
    <img src="<?php echo esc_url($img['url']); ?>" alt="<?php echo esc_attr($img['alt']); ?>" loading="lazy">
  <?php else : ?>
    <div class="full-photo-placeholder"></div>
  <?php endif; ?>
  <?php if ($overlay) : ?>
    <div class="full-photo-overlay">
      <?php if ($text) : ?>
        <p><?php echo esc_html($text); ?></p>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</section>
