<?php
$heading    = get_sub_field('donation_heading') ?: 'Make a Difference Today';
$subtext    = get_sub_field('donation_subtext') ?: 'Every dollar goes directly to keeping kids on wheels.';
$form_id    = get_sub_field('donation_form_id') ?: '162';
$show_tiers = get_sub_field('donation_show_tiers') !== false;
?>
<section class="section-donation">
  <div class="container">
    <div class="donation-grid">

      <!-- Left: Impact info -->
      <div class="donation-info reveal-left">
        <span class="section-eyebrow">Support Us</span>
        <h2><?php echo esc_html($heading); ?></h2>
        <p><?php echo esc_html($subtext); ?></p>

        <?php if ($show_tiers) : ?>
        <div class="donation-tiers">
          <div class="donation-tier">
            <span class="tier-amount">$25</span>
            <span class="tier-desc">Covers a helmet &amp; pads for one child for a full season</span>
          </div>
          <div class="donation-tier">
            <span class="tier-amount">$50</span>
            <span class="tier-desc">Provides a refurbished skateboard to a kid in need</span>
          </div>
          <div class="donation-tier">
            <span class="tier-amount">$100</span>
            <span class="tier-desc">Sponsors 4 free skate sessions for a group of 10 kids</span>
          </div>
          <div class="donation-tier">
            <span class="tier-amount">$500</span>
            <span class="tier-desc">Funds a full community skate jam event</span>
          </div>
        </div>
        <?php endif; ?>

        <p class="donation-legal">Sapient Skateboards — Phoenix, AZ</p>
      </div>

      <!-- Right: Give form -->
      <div class="donation-form-wrap reveal-right">
        <div class="donation-card">
          <h3>Choose an Amount</h3>
          <?php echo do_shortcode('[give_form id="' . esc_attr($form_id) . '" show_title="false" show_goal="false" display_style="buttons"]'); ?>
        </div>
      </div>

    </div>
  </div>
</section>
