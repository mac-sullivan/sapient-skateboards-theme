<?php
$video_field = get_field('hero_video');
$video_url   = is_array($video_field) ? ($video_field['url'] ?? '') : $video_field;
$heading     = get_field('hero_heading') ?: "BUILT IN\nCHICAGO.";
$subtext     = get_field('hero_subtext') ?: 'Handmade decks. Chicago, IL. Start to finish.';
$cta1_text   = get_field('hero_cta_primary_text') ?: 'SHOP BOARDS';
$cta1_url    = get_field('hero_cta_primary_url') ?: home_url('/shop');
$cta2_text   = get_field('hero_cta_secondary_text') ?: 'OUR PROCESS';
$cta2_url    = get_field('hero_cta_secondary_url') ?: home_url('/process');
?>
<section class="section-hero">

  <?php if ($video_url) : ?>
    <video class="hero-video" autoplay muted loop playsinline>
      <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
    </video>
  <?php else : ?>
    <div class="hero-video-fallback"></div>
  <?php endif; ?>

  <div class="hero-overlay" aria-hidden="true"></div>

  <div class="hero-content">
    <span class="hero-eyebrow">CHICAGO, IL &mdash; EST. 2018</span>
    <h1 class="hero-heading"><?php echo nl2br(esc_html($heading)); ?></h1>
    <p class="hero-subtext"><?php echo esc_html($subtext); ?></p>
    <div class="hero-actions">
      <a href="<?php echo esc_url($cta1_url); ?>" class="btn-primary"><?php echo esc_html($cta1_text); ?></a>
      <a href="<?php echo esc_url($cta2_url); ?>" class="btn-outline"><?php echo esc_html($cta2_text); ?></a>
    </div>
  </div>

</section>
