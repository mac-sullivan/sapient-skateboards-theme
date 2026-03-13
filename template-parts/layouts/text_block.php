<?php
$heading   = get_sub_field('text_heading');
$content   = get_sub_field('text_content');
$bg        = get_sub_field('text_bg') ?: 'white';
$align     = get_sub_field('text_align') ?: 'left';
$cta_label = get_sub_field('text_cta_label');
$cta_url   = get_sub_field('text_cta_url');
?>
<section class="section-text-block bg-<?php echo esc_attr($bg); ?> align-<?php echo esc_attr($align); ?> <?php echo pt_spacing_classes(); ?>">
  <div class="container">
    <?php if ($heading) : ?>
      <h2><?php echo esc_html($heading); ?></h2>
    <?php endif; ?>
    <?php if ($content) : ?>
      <div class="text-block-content"><?php echo wp_kses_post($content); ?></div>
    <?php endif; ?>
    <?php if ($cta_label && $cta_url) : ?>
      <div class="text-block-cta">
        <a href="<?php echo esc_url($cta_url); ?>" class="btn btn-primary"><?php echo esc_html($cta_label); ?></a>
      </div>
    <?php endif; ?>
  </div>
</section>
