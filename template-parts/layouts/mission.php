<?php
$eyebrow    = get_sub_field('mission_eyebrow') ?: 'OUR MISSION';
$heading    = get_sub_field('mission_heading') ?: 'SKATEBOARDS BUILT WITH INTENTION.';
$body       = get_sub_field('mission_body') ?: 'We press every board in our Chicago shop using North American hard maple. No overseas manufacturing. No shortcuts. Just hand-built decks made the right way, start to finish.';
$link_text  = get_sub_field('mission_link_text') ?: 'SEE HOW WE BUILD THEM &rarr;';
$link_url   = get_sub_field('mission_link_url') ?: home_url('/process');
$image      = get_sub_field('mission_image');
?>
<section class="section-mission <?php echo pt_spacing_classes(); ?>">
  <div class="mission-grid">

    <!-- Left: text panel -->
    <div class="mission-text">
      <span class="section-eyebrow"><?php echo esc_html($eyebrow); ?></span>
      <h2 class="mission-heading"><?php echo esc_html($heading); ?></h2>
      <p class="mission-body"><?php echo esc_html($body); ?></p>
      <a href="<?php echo esc_url($link_url); ?>" class="mission-link"><?php echo wp_kses($link_text, ['&rarr;' => []]); ?></a>
    </div>

    <!-- Right: image panel -->
    <div class="mission-image-panel">
      <?php if ($image) : ?>
        <img loading="lazy" decoding="async"
          src="<?php echo esc_url($image['url']); ?>"
          alt="<?php echo esc_attr($image['alt']); ?>"
          class="mission-img"
          loading="lazy"
        >
      <?php else : ?>
        <div class="mission-img-placeholder"></div>
      <?php endif; ?>
    </div>

  </div><!-- .mission-grid -->
</section>
