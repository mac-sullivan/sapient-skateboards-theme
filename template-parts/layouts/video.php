<?php
$heading = get_sub_field('video_heading');
$url     = get_sub_field('video_url');
$bg      = get_sub_field('video_bg') ?: 'white';
if (!$url) return;
?>
<section class="section-video bg-<?php echo esc_attr($bg); ?>">
  <div class="container">
    <?php if ($heading) : ?><h2><?php echo esc_html($heading); ?></h2><?php endif; ?>
    <div class="video-embed">
      <?php echo wp_oembed_get(esc_url($url), ['width'=>960]); ?>
    </div>
  </div>
</section>
