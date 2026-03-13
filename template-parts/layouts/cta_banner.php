<?php
/**
 * CTA Banner — reusable pre-footer section.
 *
 * Works in two modes:
 *  1. Inside ACF flexible content loop → reads sub-fields
 *  2. Standalone (get_template_part from any template) → uses $pt_cta or defaults
 *
 * To customise when calling standalone, set before get_template_part():
 *   set_query_var('pt_cta', [
 *     'eyebrow'  => 'Get involved',
 *     'heading'  => 'Help us keep kids rolling.',
 *     'accent'   => 'rolling.',   // word(s) to highlight in purple inside heading
 *     'subtext'  => '...',
 *     'btn1_label'=> 'Donate Now',
 *     'btn1_url'  => '/donate',
 *     'btn2_label'=> 'Volunteer Instead',
 *     'btn2_url'  => '/contact',
 *   ]);
 */

// ── Resolve data ──────────────────────────────────────────────
global $pt_cta_override;
$override = is_array($pt_cta_override) ? $pt_cta_override : [];

// ACF sub-fields (only truthy when inside have_rows loop)
$acf_heading  = get_sub_field('cta_heading');
$acf_subtext  = get_sub_field('cta_subtext');
$acf_btn1     = get_sub_field('cta_button_text') ?: get_sub_field('cta_btn_label');
$acf_btn1_url = get_sub_field('cta_button_url') ?: get_sub_field('cta_btn_url');
$acf_btn2     = get_sub_field('cta_btn2_label');
$acf_btn2_url = get_sub_field('cta_btn2_url');
$acf_eyebrow  = get_sub_field('cta_eyebrow');
$acf_accent   = get_sub_field('cta_accent_word');

$donate_url = pt_field('donate_url', home_url('/donate'), 'option');

// Use ?: so we fall through on false (ACF returns false outside loop) AND null/empty
$eyebrow    = ($override['eyebrow']    ?? '') ?: ($acf_eyebrow   ?: '');
$heading    = ($override['heading']    ?? '') ?: ($acf_heading    ?: 'Ready to Ride?');
$accent     = array_key_exists('accent', $override) ? $override['accent'] : ($acf_accent ?: '');
$subtext    = ($override['subtext']    ?? '') ?: ($acf_subtext    ?: '');
$btn1_label = ($override['btn1_label'] ?? '') ?: ($acf_btn1      ?: 'Shop Now');
$btn1_url   = ($override['btn1_url']   ?? '') ?: ($acf_btn1_url  ?: home_url('/shop'));
$btn2_label = ($override['btn2_label'] ?? '') ?: ($acf_btn2      ?: '');
$btn2_url   = ($override['btn2_url']   ?? '') ?: ($acf_btn2_url  ?: home_url('/contact'));

// Highlight the accent word inside the heading
$heading_html = $heading;
if ($accent) {
    $heading_html = str_replace(
        esc_html($accent),
        '<em>' . esc_html($accent) . '</em>',
        esc_html($heading)
    );
} else {
    $heading_html = esc_html($heading);
}

// Reset global so it doesn't bleed into the next call
$pt_cta_override = null;
?>

<section class="section-cta-banner <?php echo pt_spacing_classes(); ?>">
  <div class="cta-banner-noise" aria-hidden="true"></div>
  <div class="cta-banner-glow" aria-hidden="true"></div>

  <div class="container cta-banner-inner">
    <div class="cta-banner-text">
      <span class="cta-banner-eyebrow"><?php echo esc_html($eyebrow); ?></span>
      <h2 class="cta-banner-heading"><?php echo $heading_html; ?></h2>
      <?php if ($subtext) : ?>
        <p class="cta-banner-sub"><?php echo esc_html($subtext); ?></p>
      <?php endif; ?>
    </div>
    <div class="cta-banner-actions">
      <a href="<?php echo esc_url($btn1_url); ?>" class="btn btn-primary cta-banner-btn">
        <?php echo esc_html($btn1_label); ?>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
      <?php if ($btn2_label) : ?>
        <a href="<?php echo esc_url($btn2_url); ?>" class="btn btn-ghost"><?php echo esc_html($btn2_label); ?></a>
      <?php endif; ?>
    </div>
  </div>
</section>
