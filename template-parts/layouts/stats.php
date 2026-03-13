<?php
$heading = get_sub_field('stats_heading');
$bg      = get_sub_field('stats_bg') ?: 'dark';
$items   = get_sub_field('stats_items');
?>
<section class="section-stats bg-<?php echo esc_attr($bg); ?>">
  <div class="container">
    <?php if ($heading) : ?>
      <h2 class="stats-heading"><?php echo esc_html($heading); ?></h2>
    <?php endif; ?>
    <div class="stats-grid">
      <?php if ($items) : foreach ($items as $item) : ?>
        <div class="stat-item">
          <span class="stat-number"><?php echo esc_html($item['stat_number']); ?></span>
          <span class="stat-label"><?php echo esc_html($item['stat_label']); ?></span>
        </div>
      <?php endforeach; endif; ?>
    </div>
  </div>
</section>
