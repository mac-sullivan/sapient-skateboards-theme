<?php
/**
 * Layout: Contact Form — clean two-column with CF7
 *
 * Reads ACF fields from the current page (Contact Us, ID 72).
 * Hardcoded fallbacks keep the original content visible until
 * the client fills in the fields.
 */

$title          = get_field( 'contact_title' )          ?: 'Contact Us';
$content        = get_field( 'contact_content' );
$email          = get_field( 'contact_email' )          ?: 'sapientskateboards@gmail.com';
$phone_display  = get_field( 'contact_phone_display' )  ?: '(630) 624-2595';
$phone_link     = get_field( 'contact_phone_link' )     ?: '+16306242595';
$location       = get_field( 'contact_location' );
$form_shortcode = get_field( 'contact_form_shortcode' ) ?: '[contact-form-7 id="122"]';

// WYSIWYG fallbacks — these get rendered as HTML.
if ( ! $content ) {
    $content = '<p>Please do not hesitate to reach out with any inquiries regarding our product line, wholesale opportunities, manufacturing capabilities, or general questions. We would be happy to connect.</p>';
}
if ( ! $location ) {
    $location = '<p>Bellwood, IL — Chicago Area</p>';
}
?>
<section class="section-contact">
  <div class="container">
    <?php get_template_part( 'template-parts/breadcrumbs' ); ?>
  </div>
  <div class="container">
    <div class="contact-layout">

      <!-- Left: info -->
      <div class="contact-info">
        <h1 class="contact-title"><?php echo esc_html( $title ); ?></h1>
        <div class="contact-content"><?php echo wp_kses_post( $content ); ?></div>

        <ul class="contact-details-list">
          <?php if ( $email ) : ?>
          <li>
            <span class="detail-label">Email</span>
            <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
          </li>
          <?php endif; ?>

          <?php if ( $phone_display ) : ?>
          <li>
            <span class="detail-label">Phone</span>
            <?php if ( $phone_link ) : ?>
              <a href="tel:<?php echo esc_attr( $phone_link ); ?>"><?php echo esc_html( $phone_display ); ?></a>
            <?php else : ?>
              <span><?php echo esc_html( $phone_display ); ?></span>
            <?php endif; ?>
          </li>
          <?php endif; ?>

          <?php if ( $location ) : ?>
          <li>
            <span class="detail-label">Location</span>
            <div class="contact-location"><?php echo wp_kses_post( $location ); ?></div>
          </li>
          <?php endif; ?>
        </ul>
      </div>

      <!-- Right: form (Gravity Forms / CF7 / Fluent — whatever shortcode is set) -->
      <div class="contact-form-wrap">
        <?php echo do_shortcode( $form_shortcode ); ?>
      </div>

    </div>
  </div>
</section>
