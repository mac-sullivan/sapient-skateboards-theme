<?php
$heading = pt_field( 'newsletter_heading', 'Stay in the loop' );
$sub     = pt_field( 'newsletter_subtext', 'New drops, team news, and first access to limited runs. No spam, just signal.' );
?>
<section class="newsletter">
  <div class="container">
    <span class="section-eyebrow">The Sapient List</span>
    <h2><?php echo wp_kses( $heading, [ 'em' => [], 'strong' => [] ] ); ?></h2>
    <p><?php echo esc_html( $sub ); ?></p>

    <ul class="newsletter-perks">
      <li>New product drops</li>
      <li>Team riders &amp; events</li>
      <li>Early access to collabs</li>
    </ul>

    <?php
    // If using a form plugin (e.g. WPForms/Gravity Forms), drop shortcode here via ACF
    $form_shortcode = pt_field( 'newsletter_form_shortcode' );
    if ( $form_shortcode ) :
      echo do_shortcode( $form_shortcode );
    else : ?>
      <!-- Default fallback form — wire up to Mailchimp/ConvertKit via plugin -->
      <form class="newsletter-form" method="post" action="#">
        <?php wp_nonce_field( 'pt_newsletter', 'pt_newsletter_nonce' ); ?>
        <input type="email" name="email" placeholder="Your email address" required aria-label="Email address">
        <button type="submit" class="btn btn-primary">Subscribe</button>
      </form>
    <?php endif; ?>
  </div>
</section>
