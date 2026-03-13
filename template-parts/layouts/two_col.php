<?php
$heading   = get_sub_field('twocol_heading');
$content   = get_sub_field('twocol_content');
$image_id  = get_sub_field('twocol_image');
$img_side  = get_sub_field('twocol_image_side') ?: 'right';
$cta_label = get_sub_field('twocol_cta_label');
$cta_url   = get_sub_field('twocol_cta_url');
$bg        = get_sub_field('twocol_bg') ?: 'white';

// Resolve image
if (is_array($image_id)) {
  $img_url = $image_id['url'];
  $img_alt = $image_id['alt'];
} elseif (is_numeric($image_id)) {
  $img_url = wp_get_attachment_image_url($image_id, 'large');
  $img_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
} else {
  $img_url = $img_alt = '';
}
?>
<section class="section-two-col section-two-col--<?php echo esc_attr($bg); ?> img-<?php echo esc_attr($img_side); ?> reveal <?php echo pt_spacing_classes(); ?>">
  <div class="two-col-layout">

    <!-- Image panel -->
    <div class="two-col-image-panel">
      <?php if ($img_url) : ?>
        <div class="two-col-img-wrap">
          <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($img_alt); ?>" loading="lazy">
          <div class="two-col-img-overlay"></div>
        </div>
        <!-- Floating stat accent -->
        <div class="two-col-stat-badge">
          <span class="badge-number">500+</span>
          <span class="badge-label">Kids Served</span>
        </div>
      <?php endif; ?>
    </div>

    <!-- Text panel -->
    <div class="two-col-text-panel">
      <div class="two-col-text-inner">
        <span class="section-eyebrow">About Us</span>

        <?php if ($heading) : ?>
          <h2><?php echo wp_kses($heading, ['br' => [], 'em' => [], 'strong' => []]); ?></h2>
        <?php endif; ?>

        <?php if ($content) : ?>
          <div class="two-col-content"><?php echo wp_kses_post($content); ?></div>
        <?php endif; ?>

        <?php if ($cta_label && $cta_url) : ?>
          <a href="<?php echo esc_url($cta_url); ?>"
             class="btn <?php echo $bg === 'dark' ? 'btn-outline' : 'btn-primary'; ?>">
            <?php echo esc_html($cta_label); ?>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
        <?php endif; ?>
      </div>
    </div>

  </div>
</section>
