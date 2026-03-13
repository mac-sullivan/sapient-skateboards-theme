<?php
// ── Footer options ─────────────────────────────────────────────
$footer_description = get_field( 'footer_description', 'option' )
    ?: "Handcrafted skateboards built with intention. Rooted in Chicago's manufacturing legacy.";
$footer_instagram   = get_field( 'footer_instagram', 'option' ) ?: 'https://www.instagram.com/sapientskateboards/';
$footer_facebook    = get_field( 'footer_facebook',  'option' ) ?: '';
$footer_youtube     = get_field( 'footer_youtube',   'option' ) ?: '';
$footer_email       = get_field( 'footer_contact_email', 'option' )
    ?: 'info@sapientskateboards.com';
$footer_location    = get_field( 'footer_contact_location', 'option' )
    ?: 'Chicago, IL';
?>
<footer id="site-footer">

  <!-- ── Main footer body ────────────────────────────────────── -->
  <div class="footer-body">
    <div class="container footer-grid">

      <!-- Col 1: Brand -->
      <div class="footer-brand">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo-link" aria-label="Sapient Skateboards">
          <img
            src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/sapient-manufacturing-logo.webp"
            alt="Sapient Manufacturing Co."
            class="ss-logo ss-logo--footer"
            width="200"
            height="48"
          >
        </a>
        <p class="footer-description"><?php echo esc_html( $footer_description ); ?></p>
        <div class="footer-social">
          <?php if ( $footer_instagram ) : ?>
          <a href="<?php echo esc_url( $footer_instagram ); ?>" target="_blank" rel="noopener" aria-label="Instagram">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
          </a>
          <?php endif; ?>
          <?php if ( $footer_facebook ) : ?>
          <a href="<?php echo esc_url( $footer_facebook ); ?>" target="_blank" rel="noopener" aria-label="Facebook">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/></svg>
          </a>
          <?php endif; ?>
          <?php if ( $footer_youtube ) : ?>
          <a href="<?php echo esc_url( $footer_youtube ); ?>" target="_blank" rel="noopener" aria-label="YouTube">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23.495 6.205a3.007 3.007 0 00-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 00.527 6.205a31.247 31.247 0 00-.522 5.805 31.247 31.247 0 00.522 5.783 3.007 3.007 0 002.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 002.088-2.088 31.247 31.247 0 00.5-5.783 31.247 31.247 0 00-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/></svg>
          </a>
          <?php endif; ?>
        </div>
      </div>

      <!-- Col 2: Navigate -->
      <div class="footer-links-col">
        <h4 class="footer-col-label">Navigate</h4>
        <?php
        wp_nav_menu( [
          'theme_location' => 'footer',
          'container'      => false,
          'menu_class'     => 'footer-menu',
          'depth'          => 1,
          'fallback_cb'    => function() {
            $links = [
              'About'            => '/about',
              'Process'          => '/process',
              'Archive'          => '/archive',
              'Shop'             => '/shop',
              'Contact'          => '/contact',
              'Privacy Policy'   => '/privacy-policy',
              'Terms of Service' => '/terms',
            ];
            echo '<ul class="footer-menu">';
            foreach ( $links as $label => $path ) {
              echo '<li><a href="' . esc_url( home_url( $path ) ) . '">' . esc_html( $label ) . '</a></li>';
            }
            echo '</ul>';
          },
        ] );
        ?>
      </div>

      <!-- Col 3: Contact -->
      <div class="footer-contact-col">
        <h4 class="footer-col-label">Contact</h4>
        <ul class="footer-contact-list">
          <li>
            <span class="footer-contact-label">Email</span>
            <a href="mailto:<?php echo esc_attr( $footer_email ); ?>"><?php echo esc_html( $footer_email ); ?></a>
          </li>
          <li>
            <span class="footer-contact-label">Phone</span>
            <a href="tel:+16306242595">(630) 624-2595</a>
          </li>
          <li>
            <span class="footer-contact-label">Location</span>
            <span><?php echo esc_html( $footer_location ); ?></span>
          </li>
        </ul>
      </div>

    </div><!-- .footer-grid -->
  </div><!-- .footer-body -->

  <!-- ── Bottom bar ──────────────────────────────────────────── -->
  <div class="footer-bottom">
    <div class="container footer-bottom-inner">
      <span>&copy; <?php echo date('Y'); ?> Sapient Manufacturing Co.</span>
      <div class="footer-legal">
        <a href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>">Privacy Policy</a>
        <a href="<?php echo esc_url( home_url( '/terms' ) ); ?>">Terms of Service</a>
      </div>
      <span class="footer-made-in">
        Made in Chicago, USA
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/Chicago-01-1.svg" alt="Chicago flag" class="footer-chicago-flag" width="24" height="16" aria-hidden="true">
      </span>
    </div>
  </div>

</footer>

<?php wp_footer(); ?>
</body>
</html>
