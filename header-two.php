<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="site-header">
<div class="header-inner">
  <!-- Row 1: Centered logo -->
  <div class="header-brand">
    <div class="header-brand-inner">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand-logo" aria-label="Sapient Manufacturing Co.">
        <img
          src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/sapient-manufacturing-logo.webp"
          alt="Sapient Manufacturing Co."
          class="ss-logo"
          width="260"
          height="63"
        >
      </a>

        <div class="header-nav">
    <div class="header-nav-inner">

      <?php
        $nav_pages = [
          'WEB STORE' => '/shop',
          'PROCESS'   => '/process',
          'CREW'      => '/crew',
          'ARCHIVE'   => '/archive',
          'SUPPLIERS' => '/suppliers',
          'CONTACT US'=> '/contact',
        ];
        $shop_children = [
          'Skateboards' => '/product-category/skateboards',
          'Apparel'     => '/product-category/apparel',
          'Other'       => '/product-category/other',
        ];
        $current_uri = untrailingslashit( ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
      ?>
      <nav id="site-nav" class="nav-links-desktop" aria-label="Primary navigation">
        <ul class="nav-links">
          <?php foreach ( $nav_pages as $label => $path ) :
            $url          = home_url( $path );
            $current      = strpos( $current_uri, untrailingslashit( $url ) ) === 0;
            $has_children = ( $label === 'WEB STORE' );
            $li_classes   = array_filter( [
              $current      ? 'current-menu-item' : '',
              $has_children ? 'menu-item-has-children' : '',
            ] );
          ?>
          <li<?php echo $li_classes ? ' class="' . implode( ' ', $li_classes ) . '"' : ''; ?>>
            <a href="<?php echo esc_url( $url ); ?>"<?php echo $current ? ' aria-current="page"' : ''; ?>>
              <?php echo esc_html( $label ); ?>
            </a>
            <?php if ( $has_children ) : ?>
            <ul class="sub-menu">
              <?php foreach ( $shop_children as $child_label => $child_path ) : ?>
              <li><a href="<?php echo esc_url( home_url( $child_path ) ); ?>"><?php echo esc_html( $child_label ); ?></a></li>
              <?php endforeach; ?>
            </ul>
            <?php endif; ?>
          </li>
          <?php endforeach; ?>
        </ul>
      </nav>

      <div class="nav-utilities">
        <div class="search-inline">
          <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search-inline-form">
            <input type="search" name="s" placeholder="Search..." value="<?php echo get_search_query(); ?>" autocomplete="off">
          </form>
          <button class="search-toggle" aria-label="Search">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
          </button>
        </div>
      </div>

    </div>
              <?php if ( function_exists( 'WC' ) && WC()->cart ) : $count = WC()->cart->get_cart_contents_count(); ?>
        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="nav-cart" aria-label="Cart">
              <span class="cart-text">Cart</span>   
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
            <line x1="3" y1="6" x2="21" y2="6"/>
            <path d="M16 10a4 4 0 01-8 0"/>
          </svg>
          <span class="cart-count<?php echo $count ? ' has-items' : ''; ?>"><?php echo esc_html( $count ); ?></span>
        </a>
        <?php elseif ( function_exists( 'WC' ) ) : ?>
        <a href="<?php echo esc_url( home_url( '/cart' ) ); ?>" class="nav-cart" aria-label="Cart">
  
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
            <line x1="3" y1="6" x2="21" y2="6"/>
            <path d="M16 10a4 4 0 01-8 0"/>
          </svg>
          <span class="cart-count">0</span>
        </a>
        <?php endif; ?>

        <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false">
          <span></span><span></span><span></span>
        </button>
    </div>
  </div>

  <!-- Row 2: Navigation bar -->

  </div>
</div>
</header>

<!-- Mobile nav overlay -->
<nav id="site-nav-mobile" class="nav-mobile-overlay" aria-label="Mobile navigation">
  <ul class="nav-links-mobile-list">
    <?php foreach ( $nav_pages as $label => $path ) :
      $url          = home_url( $path );
      $current      = strpos( $current_uri, untrailingslashit( $url ) ) === 0;
      $has_children = ( $label === 'WEB STORE' );
      $li_classes   = array_filter( [
        $current      ? 'current-menu-item' : '',
        $has_children ? 'menu-item-has-children' : '',
      ] );
    ?>
    <li<?php echo $li_classes ? ' class="' . implode( ' ', $li_classes ) . '"' : ''; ?>>
      <a href="<?php echo esc_url( $url ); ?>"<?php echo $current ? ' aria-current="page"' : ''; ?>>
        <?php echo esc_html( $label ); ?>
      </a>
      <?php if ( $has_children ) : ?>
      <ul class="sub-menu">
        <?php foreach ( $shop_children as $child_label => $child_path ) : ?>
        <li><a href="<?php echo esc_url( home_url( $child_path ) ); ?>"><?php echo esc_html( $child_label ); ?></a></li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
    </li>
    <?php endforeach; ?>
  </ul>
</nav>

<script>
(function () {
  var toggle = document.querySelector('.nav-toggle');
  var overlay = document.getElementById('site-nav-mobile');
  var header = document.getElementById('site-header');

  // Set --header-offset so CSS can push content below the fixed header
  function setHeaderOffset() {
    if (!header) return;
    var rect = header.getBoundingClientRect();
    var offset = rect.bottom + 30;
    document.body.style.setProperty('--header-offset', offset + 'px');
    document.body.style.setProperty('--header-h', header.offsetHeight + 'px');
  }
  setHeaderOffset();
  window.addEventListener('resize', setHeaderOffset, { passive: true });
  // Recalculate after fonts/images load
  window.addEventListener('load', setHeaderOffset);

  // Mobile toggle
  if (toggle && overlay) {
    toggle.addEventListener('click', function () {
      var open = overlay.classList.toggle('open');
      toggle.classList.toggle('is-open', open);
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  // Search — submit form on button click
  var searchToggle = document.querySelector('.search-toggle');
  var searchForm = document.querySelector('.search-inline-form');
  if (searchToggle && searchForm) {
    searchToggle.addEventListener('click', function (e) {
      e.preventDefault();
      searchForm.submit();
    });
  }

  // Dropdown menus — mobile sub-menu toggles
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
