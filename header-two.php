<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php include( get_stylesheet_directory() . '/template-parts/icons/money-bag-symbol.php' ); ?>

<header id="site-header" class="site-header-v2">

  <div class="h2-inner">

    <!-- Logo + Cart group -->
    <div class="h2-logo-group">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="h2-logo" aria-label="Sapient Manufacturing Co.">
      <img
        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/sapient-skateboard-co-logo.svg"
        alt="Sapient Skateboard Co."
        class="ss-logo"
        width="535"
        height="134"
      >
    </a>
    </div><!-- /.h2-logo-group -->

    <!-- Nav (center) -->
    <nav id="site-nav" class="h2-nav nav-links-desktop" aria-label="Primary navigation">
      <?php wp_nav_menu( [
        'theme_location' => 'primary',
        'menu_class'     => 'nav-links',
        'container'      => false,
        'fallback_cb'    => false,
        'depth'          => 2,
      ] ); ?>
    </nav>

    <!-- Utilities: mobile toggle + Search + Cart (cart sits far right) -->
    <div class="h2-utilities">

      <!-- Mobile menu button -->
      <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false">
        <span class="nav-toggle-label" data-open="MENU" data-close="CLOSE">MENU</span>
      </button>

      <!-- Search -->
      <div class="h2-search search-inline" data-search>
        <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search-inline-form">
          <input class="search-input" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" autocomplete="off" tabindex="-1" aria-label="Search">
        </form>
        <button type="button" class="search-toggle-btn" aria-label="Search" aria-expanded="false" data-search-toggle>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
          </svg>
        </button>
      </div>

      <!-- Cart -->
      <?php if ( function_exists( 'WC' ) ) :
        $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
      ?>
      <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="h2-cart-link" aria-label="Cart">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
        </svg>
        <span class="h2-cart-count<?php echo $count ? ' has-items' : ''; ?>" id="header-cart-count"><?php echo esc_html( $count ); ?></span>
      </a>
      <?php endif; ?>

    </div>

  </div>

</header>

<!-- Mobile nav overlay -->
<nav id="site-nav-mobile" class="nav-mobile-overlay" aria-label="Mobile navigation">

  <!-- Search lives in the menu on mobile (header icon is desktop-only) -->
  <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="mobile-search-form">
    <svg class="mobile-search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
    </svg>
    <input class="mobile-search-input" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="Search" aria-label="Search" autocomplete="off">
    <button type="submit" class="mobile-search-btn" aria-label="Submit search">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
      </svg>
    </button>
  </form>

  <?php wp_nav_menu( [
    'theme_location' => 'primary',
    'menu_class'     => 'nav-links-mobile-list',
    'container'      => false,
    'fallback_cb'    => false,
    'depth'          => 2,
  ] ); ?>

  <?php
  $mobile_email     = ( function_exists( 'get_field' ) ? get_field( 'footer_contact_email', 'option' ) : '' ) ?: 'info@sapientskateboards.com';
  $mobile_instagram = ( function_exists( 'get_field' ) ? get_field( 'footer_instagram', 'option' ) : '' ) ?: 'https://www.instagram.com/sapientskateboards/';
  ?>
  <div class="mobile-nav-footer">
    <a href="mailto:<?php echo esc_attr( $mobile_email ); ?>"><?php echo esc_html( $mobile_email ); ?></a>
    <a href="<?php echo esc_url( $mobile_instagram ); ?>" target="_blank" rel="noopener">Instagram</a>
  </div>

</nav>

<script>
(function () {
  var toggle  = document.querySelector('.nav-toggle');
  var overlay = document.getElementById('site-nav-mobile');
  var header  = document.getElementById('site-header');

  function setHeaderOffset() {
    if (!header) return;
    var rect = header.getBoundingClientRect();
    document.body.style.setProperty('--header-offset', (rect.bottom + 16) + 'px');
    document.body.style.setProperty('--header-h', header.offsetHeight + 'px');
    document.body.style.setProperty('--header-bottom', rect.bottom + 'px');
  }
  setHeaderOffset();
  window.addEventListener('resize', setHeaderOffset, { passive: true });
  window.addEventListener('load',   setHeaderOffset);

  if (toggle && overlay) {
    var toggleLabel = toggle.querySelector('.nav-toggle-label');
    function setMenu(open) {
      overlay.classList.toggle('open', open);
      toggle.classList.toggle('is-open', open);
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      // Lock the page behind the menu so only the menu scrolls
      document.documentElement.classList.toggle('nav-mobile-open', open);
      if (toggleLabel) {
        toggleLabel.textContent = open
          ? toggleLabel.getAttribute('data-close')
          : toggleLabel.getAttribute('data-open');
      }
    }
    toggle.addEventListener('click', function () {
      setMenu(!overlay.classList.contains('open'));
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && overlay.classList.contains('open')) { setMenu(false); toggle.focus(); }
    });
    // Following a link (not a submenu toggle) closes the menu
    overlay.addEventListener('click', function (e) {
      var link = e.target.closest('a');
      if (link && !link.parentElement.classList.contains('menu-item-has-children')) setMenu(false);
    });
  }

  document.querySelectorAll('.nav-links-mobile-list .menu-item-has-children > a').forEach(function(link) {
    link.addEventListener('click', function(e) {
      var parent = link.parentElement;
      var sub = parent.querySelector('.sub-menu');
      if (!sub) return;
      e.preventDefault();
      var open = parent.classList.toggle('is-open');
      sub.classList.toggle('is-open', open);
    });
  });
})();
</script>
