<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

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

<header id="site-header" class="site-header-v2">

  <div class="h2-inner">

    <!-- Logo -->
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="h2-logo" aria-label="Sapient Manufacturing Co.">
      <img
        src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/sapient-manufacturing-logo.webp"
        alt="Sapient Manufacturing Co."
        class="ss-logo"
        width="260"
        height="63"
      >
    </a>

    <!-- Nav (center) -->
    <nav id="site-nav" class="h2-nav nav-links-desktop" aria-label="Primary navigation">
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

    <!-- Utilities: Cart on top, Search below -->
    <div class="h2-utilities">

      <!-- Cart -->
      <?php if ( function_exists( 'WC' ) ) :
        $cart       = WC()->cart;
        $count      = $cart ? $cart->get_cart_contents_count() : 0;
        $cart_items = $cart ? $cart->get_cart() : [];
      ?>
      <div class="nav-cart-wrap">
        <button class="nav-cart h2-cart" aria-label="Cart" aria-expanded="false" data-cart-toggle>
          <span class="money-bag-icon">
            <svg class="money-bag-svg" viewBox="0 0 28 34" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <ellipse cx="14" cy="4.5" rx="5" ry="2.5" fill="currentColor"/>
              <rect x="10" y="7" width="8" height="3.5" rx="1" fill="currentColor"/>
              <circle cx="14" cy="22" r="10" fill="currentColor"/>
              <path d="M14.5 17v-.5h-1V17c-1.1.28-1.9 1.1-1.9 2.1 0 1.3 1.1 2.1 2.9 2.1.9 0 1.4.45 1.4.9 0 .6-.4 1-1.4 1H13v-1h-1v1h1v.5h1V24c1.1-.28 1.9-1.1 1.9-2.1 0-1.3-1.1-2.1-2.9-2.1-.9 0-1.4-.45-1.4-.9 0-.6.4-1 1.4-1h1.5v1h1v-1h-1z" fill="white"/>
            </svg>
            <span class="money-bag-count<?php echo $count ? ' has-items' : ''; ?>"><?php echo esc_html( $count ?: 0 ); ?></span>
          </span>
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

      <!-- Search (below cart) -->
      <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="h2-search">
        <input type="search" name="s" placeholder="Search…" value="<?php echo get_search_query(); ?>" autocomplete="off" aria-label="Search">
        <button type="submit" aria-label="Submit search">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
          </svg>
        </button>
      </form>

      <!-- Mobile menu button -->
      <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false">
        <span class="nav-toggle-label" data-open="MENU" data-close="CLOSE">MENU</span>
      </button>

    </div>

  </div>

</header>

<!-- Mobile nav overlay -->
<nav id="site-nav-mobile" class="nav-mobile-overlay" aria-label="Mobile navigation">

  <form class="mobile-search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <input class="mobile-search-input" type="search" name="s" placeholder="Search…" value="<?php echo get_search_query(); ?>" autocomplete="off">
    <button class="mobile-search-btn" type="submit" aria-label="Search">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    </button>
  </form>

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
    toggle.addEventListener('click', function () {
      var open = overlay.classList.toggle('open');
      toggle.classList.toggle('is-open', open);
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      if (toggleLabel) {
        toggleLabel.textContent = open
          ? toggleLabel.getAttribute('data-close')
          : toggleLabel.getAttribute('data-open');
      }
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
