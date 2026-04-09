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

       
      </div>

      <!-- Right: CF7 form -->
      <div class="contact-form-wrap">
        <?php echo do_shortcode( '[contact-form-7 id="122" title="Contact form 1"]' ); ?>
      </div>

    </div>
  </div>
</section>
