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
          src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/SapientSkateboardCoLogo.svg"
          alt="Sapient Skateboard Co."
          class="ss-logo"
          width="612"
          height="216"
        >
      </a>
              <?php if ( false && function_exists( 'WC' ) ) : // cart temporarily hidden — flip back to function_exists('WC') to re-enable
          $cart       = WC()->cart;
          $count      = $cart ? $cart->get_cart_contents_count() : 0;
          $cart_items = $cart ? $cart->get_cart() : [];
        ?>
        <div class="nav-cart-wrap">
          <button class="nav-cart" aria-label="Cart" aria-expanded="false" data-cart-toggle>
            <span class="cart-label">Cart</span>
            <span class="cart-count<?php echo $count ? ' has-items' : ''; ?>"><?php echo esc_html( $count ?: 0 ); ?></span>
          </button>

          <div class="cart-preview" data-cart-preview>
            <?php if ( empty( $cart_items ) ) : ?>
              <p class="cart-preview-empty">Your cart is empty.</p>
            <?php else : ?>
              <table class="cart-preview-table">
                <thead>
                  <tr>
                    <th class="cpt-img"></th>
                    <th class="cpt-name">Product</th>
                    <th class="cpt-qty">Qty</th>
                    <th class="cpt-price">Price</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ( $cart_items as $item ) :
                    $product  = $item['data'];
                    $img_id   = $product->get_image_id();
                    $img_url  = $img_id ? wp_get_attachment_image_url( $img_id, 'thumbnail' ) : wc_placeholder_img_src();
                  ?>
                  <tr class="cpt-row">
                    <td class="cpt-img"><img src="<?php echo esc_url( $img_url ); ?>" loading="lazy" decoding="async" alt="<?php echo esc_attr( $product->get_name() ); ?>"></td>
                    <td class="cpt-name"><?php echo esc_html( $product->get_name() ); ?></td>
                    <td class="cpt-qty"><?php echo esc_html( $item['quantity'] ); ?></td>
                    <td class="cpt-price"><?php echo wc_price( $product->get_price() ); ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
              <div class="cart-preview-footer">
                <span class="cart-preview-total">Total: <?php echo WC()->cart->get_cart_total(); ?></span>
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="btn-primary cart-preview-btn">View Cart</a>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>

        <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false">
          <span></span><span></span><span></span>
        </button>
    </div>
  </div>

  <!-- Row 2: Navigation bar -->
  <div class="header-nav">
    <div class="header-nav-inner">

      <nav id="site-nav" class="nav-links-desktop" aria-label="Primary navigation">
        <?php wp_nav_menu( [
          'theme_location' => 'primary',
          'menu_class'     => 'nav-links',
          'container'      => false,
          'fallback_cb'    => false,
          'depth'          => 2,
        ] ); ?>
      </nav>

      <div class="nav-utilities">
        <div class="search-inline" data-search>
          <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search-inline-form">
            <input class="search-input" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" autocomplete="off" tabindex="-1" aria-label="Search">
          </form>
          <button type="button" class="search-toggle-btn" aria-label="Search" aria-expanded="false" data-search-toggle>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
          </button>
        </div>
      </div>

    </div>
  </div>
  </div>
</header>

<!-- Mobile nav overlay -->
<nav id="site-nav-mobile" class="nav-mobile-overlay" aria-label="Mobile navigation">
  <?php wp_nav_menu( [
    'theme_location' => 'primary',
    'menu_class'     => 'nav-links-mobile-list',
    'container'      => false,
    'fallback_cb'    => false,
    'depth'          => 2,
  ] ); ?>
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
