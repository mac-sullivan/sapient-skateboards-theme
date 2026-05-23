<?php
/**
 * Countdown component — live countdown to a target datetime.
 * Wired by [data-countdown] handler in assets/js/main.js.
 */
$target = '2026-06-22T00:00:00-05:00'; // June 22, 2026 12:00 AM CDT
$pretty = 'Launching June 22, 2026 — 12:00 AM CDT';
?>
<section class="section-countdown" data-countdown data-target="<?php echo esc_attr( $target ); ?>">
  <div class="container">
    <!-- <span class="countdown-eyebrow">Release</span> -->
    <h2 class="countdown-heading">Production Run 001 — 2026</h2>

    <div class="countdown-grid" aria-live="polite" aria-atomic="false">
      <div class="countdown-unit">
        <span class="countdown-value" data-d>00</span>
        <span class="countdown-label">Days</span>
      </div>
      <div class="countdown-unit">
        <span class="countdown-value" data-h>00</span>
        <span class="countdown-label">Hours</span>
      </div>
      <div class="countdown-unit">
        <span class="countdown-value" data-m>00</span>
        <span class="countdown-label">Minutes</span>
      </div>
      <div class="countdown-unit">
        <span class="countdown-value" data-s>00</span>
        <span class="countdown-label">Seconds</span>
      </div>
    </div>

    <h3><?php echo esc_html( $pretty ); ?></h3>
  </div>
</section>
