<?php
$newsletter_video = get_field( 'footer_newsletter_video', 'option' )
    ?: 'http://sapient-skateboards.local/wp-content/uploads/2026/03/sapient-x-fa_2-web-1.mp4';
?>
<section class="newsletter-banner newsletter-banner--video">
  <?php if ( $newsletter_video ) : ?>
  <video class="newsletter-banner-video" autoplay muted loop playsinline>
    <source src="<?php echo esc_url( $newsletter_video ); ?>" type="video/mp4">
  </video>
  <?php endif; ?>
  <div class="newsletter-banner-overlay"></div>
  <div class="container newsletter-banner-inner">
    <div class="newsletter-banner-text">
      <h2 class="newsletter-banner-headline">Built in Chicago.</h2>
      <p class="newsletter-banner-sub">Get on the list. We'll send you a free archive print. Stay in the loop.</p>
    </div>
    <form class="newsletter-banner-form" id="footer-newsletter-form" novalidate>
      <?php wp_nonce_field( 'sapient_newsletter', 'newsletter_nonce' ); ?>
      <div class="nbf-fields">
        <input class="nbf-input" type="email" name="email" placeholder="Email address" required>
        <button type="submit" class="nbf-btn">Subscribe</button>
      </div>
      <p class="nbf-msg" aria-live="polite"></p>
    </form>
  </div>

</section>
