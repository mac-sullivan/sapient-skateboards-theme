<?php
/**
 * Interior page hero — floating inset dark card
 * Used by page.php for generic interior pages
 */
$title    = get_the_title();
$slug     = get_post_field('post_name', get_the_ID());
$eyebrow  = function_exists('get_field') ? (get_field('page_eyebrow') ?: '') : '';
$subtitle = function_exists('get_field') ? (get_field('page_subtitle') ?: '') : '';

$eyebrow_map = [
  'contact' => 'Get in touch',
  'donate'  => 'Support us',
  'about'   => 'Who we are',
];
if (!$eyebrow) $eyebrow = $eyebrow_map[$slug] ?? '';

$subtitle_map = [
  'contact' => 'We respond to every message — usually within 24 hours.',
];
if (!$subtitle) $subtitle = $subtitle_map[$slug] ?? '';
?>

<div class="page-hero">
  <div class="container">
    <?php if ($eyebrow) : ?>
      <span class="page-hero-eyebrow"><?php echo esc_html($eyebrow); ?></span>
    <?php endif; ?>
    <h1 class="page-hero-title"><?php echo esc_html($title); ?></h1>
    <?php if ($subtitle) : ?>
      <p class="page-hero-sub"><?php echo esc_html($subtitle); ?></p>
    <?php endif; ?>
  </div>
</div>
