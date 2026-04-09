<?php
/**
 * Layout: Contact Form — clean two-column with CF7
 */
?>
<section class="section-contact">
  <div class="container">
    <?php get_template_part( 'template-parts/breadcrumbs' ); ?>
  </div>
  <div class="container">
    <div class="contact-layout">

      <!-- Left: info -->
      <div class="contact-info">
        <!-- <span class="contact-eyebrow">Contact</span> -->
        <h1 class="contact-title">Contact Us</h1>
        <p class="contact-sub">Questions about gear, wholesale, or team inquiries — we respond to every message.</p>

        <ul class="contact-details-list">
          <li>
            <span class="detail-label">Email</span>
            <a href="mailto:sapientskateboards@gmail.com">sapientskateboards@gmail.com</a>
          </li>
          <li>
            <span class="detail-label">Phone</span>
            <a href="tel:+16306242595">(630) 624-2595</a>
          </li>
          <li>
            <span class="detail-label">Location</span>
            <span>Bellwood, IL — Chicago Area</span>
          </li>
        </ul>

        <div class="contact-social">
          <a href="https://www.instagram.com/sapientskateboards/" target="_blank" rel="noopener" aria-label="Instagram">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="0.5" fill="currentColor" stroke="none"/></svg>
          </a>
          <a href="https://www.youtube.com/channel/UC0_OW66brzYAY-x3xl-9s_w/featured" target="_blank" rel="noopener" aria-label="YouTube">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 0 0 1.46 6.42 29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="currentColor" stroke="none"/></svg>
          </a>
          <a href="https://www.facebook.com/people/Sapient-Skateboards/100028139635368/" target="_blank" rel="noopener" aria-label="Facebook">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
          </a>
        </div>

        <p class="contact-response-note"><strong>We respond within 24 hours.</strong> For urgent orders, call us directly.</p>
      </div>

      <!-- Right: CF7 form -->
      <div class="contact-form-wrap">
        <?php echo do_shortcode( '[contact-form-7 id="122" title="Contact form 1"]' ); ?>
      </div>

    </div>
  </div>
</section>
