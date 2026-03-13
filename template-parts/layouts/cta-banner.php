<?php
$heading    = get_sub_field('cta_heading') ?: 'MADE IN CHICAGO. RIDDEN WORLDWIDE.';
$btn_text   = get_sub_field('cta_button_text') ?: 'SHOP NOW';
$btn_url    = get_sub_field('cta_button_url') ?: home_url('/shop');
?>
<section class="section-cta-banner">
  <div class="container cta-banner-inner">

    <h2 class="cta-banner-heading"><?php echo esc_html($heading); ?></h2>

    <div class="cta-banner-action">
      <a href="<?php echo esc_url($btn_url); ?>" class="cta-banner-btn"><?php echo esc_html($btn_text); ?></a>
    </div>

  </div>
</section>
