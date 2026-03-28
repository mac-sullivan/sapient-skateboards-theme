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
  <!-- Row 1: Logo / Search / Actions -->
  <div class="header-top">
    <div class="container header-top-inner">

      <div class="nav-logo">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Sapient Manufacturing Co.">
          <img
            src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/sapient-manufacturing-logo.webp"
            alt="Sapient Manufacturing Co."
            class="ss-logo ss-logo--manufacturing"
            width="260"
            height="63"
          >
        </a>
      </div>

      <div>
      
     <nav id="site-nav" class="nav-links-desktop" aria-label="Primary navigation">
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
          'Skateboards' => '/shop/skateboards',
          'Apparel'     => '/shop/apparel',
          'Other'       => '/shop/other',
        ];
        wp_nav_menu( [
          'theme_location' => 'primary',
          'container'      => false,
          'menu_class'     => 'nav-links',
          'fallback_cb'    => function() use ( $nav_pages, $shop_children ) {
            $current_uri = untrailingslashit( ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
            echo '<ul class="nav-links">';
            foreach ( $nav_pages as $label => $path ) {
              $url     = home_url( $path );
              $current = untrailingslashit( $url ) === $current_uri;
              $has_children = ( $label === 'WEB STORE' );
              $li_class = array_filter( [
                $current ? 'current-menu-item' : '',
                $has_children ? 'menu-item-has-children' : '',
              ] );
              $li_attr  = ! empty( $li_class ) ? ' class="' . implode( ' ', $li_class ) . '"' : '';
              $aria     = $current ? ' aria-current="page"' : '';
              echo '<li' . $li_attr . '>';
              echo '<a href="' . esc_url( $url ) . '"' . $aria . '>' . esc_html( $label ) . '</a>';
              if ( $has_children ) {
                echo '<ul class="sub-menu">';
                foreach ( $shop_children as $child_label => $child_path ) {
                  echo '<li><a href="' . esc_url( home_url( $child_path ) ) . '">' . esc_html( $child_label ) . '</a></li>';
                }
                echo '</ul>';
              }
              echo '</li>';
            }
            echo '</ul>';
          },
        ] );
        ?>
      </nav>
    </div>



      <div class="nav-cta-wrap">
        <div class="search-inline">
          <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search-inline-form">
            <input type="search" name="s" placeholder="Search..." value="<?php echo get_search_query(); ?>" autocomplete="off">
          </form>
          <button class="search-toggle" aria-label="Search" aria-expanded="false">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
          </button>
        </div>

        <?php if ( function_exists( 'WC' ) && WC()->cart ) : $count = WC()->cart->get_cart_contents_count(); ?>
        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="nav-cart" aria-label="Cart">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
            <line x1="3" y1="6" x2="21" y2="6"/>
            <path d="M16 10a4 4 0 01-8 0"/>
          </svg>
          <span class="cart-count<?php echo $count ? ' has-items' : ''; ?>"><?php echo esc_html( $count ); ?></span>
        </a>
        <?php elseif ( function_exists( 'WC' ) ) : ?>
        <a href="<?php echo esc_url( home_url( '/cart' ) ); ?>" class="nav-cart" aria-label="Cart">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
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
  </div>

</header>

<!-- Mobile nav overlay — outside <header> so header z-index always wins -->
<nav id="site-nav-mobile" class="nav-mobile-overlay" aria-label="Mobile navigation">
  <?php
  wp_nav_menu( [
    'theme_location' => 'primary',
    'container'      => false,
    'menu_class'     => 'nav-links-mobile-list',
    'fallback_cb'    => function() use ( $nav_pages, $shop_children ) {
      $current_uri = untrailingslashit( ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
      echo '<ul class="nav-links-mobile-list">';
      foreach ( $nav_pages as $label => $path ) {
        $url     = home_url( $path );
        $current = untrailingslashit( $url ) === $current_uri;
        $has_children = ( $label === 'WEB STORE' );
        $li_class = array_filter( [
          $current ? 'current-menu-item' : '',
          $has_children ? 'menu-item-has-children' : '',
        ] );
        $li_attr  = ! empty( $li_class ) ? ' class="' . implode( ' ', $li_class ) . '"' : '';
        $aria     = $current ? ' aria-current="page"' : '';
        echo '<li' . $li_attr . '>';
        echo '<a href="' . esc_url( $url ) . '"' . $aria . '>' . esc_html( $label ) . '</a>';
        if ( $has_children ) {
          echo '<ul class="sub-menu">';
          foreach ( $shop_children as $child_label => $child_path ) {
            echo '<li><a href="' . esc_url( home_url( $child_path ) ) . '">' . esc_html( $child_label ) . '</a></li>';
          }
          echo '</ul>';
        }
        echo '</li>';
      }
      echo '</ul>';
    },
  ] );
  ?>
  <a href="<?php echo esc_url( home_url( '/shop' ) ); ?>" class="btn-nav-cta mobile-cta">SHOP</a>
</nav>

<script>
(function () {
  var toggle = document.querySelector('.nav-toggle');
  var overlay = document.getElementById('site-nav-mobile');

  // Glassmorphism on scroll
  var header = document.getElementById('site-header');
  if (header) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 10) {
        header.classList.add('is-scrolled');
      } else {
        header.classList.remove('is-scrolled');
      }
    }, { passive: true });
  }

  // Mobile toggle
  if (toggle && overlay) {
    toggle.addEventListener('click', function () {
      var open = overlay.classList.toggle('open');
      toggle.classList.toggle('is-open', open);
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  // Inline search expand
  var searchInline = document.querySelector('.search-inline');
  var searchToggle = document.querySelector('.search-toggle');
  var searchInput = searchInline ? searchInline.querySelector('input[type="search"]') : null;

  function openSearch() {
    searchInline.classList.add('is-open');
    searchToggle.setAttribute('aria-expanded', 'true');
    setTimeout(function () { if (searchInput) searchInput.focus(); }, 300);
  }

  function closeSearch() {
    searchInline.classList.remove('is-open');
    searchToggle.setAttribute('aria-expanded', 'false');
    if (searchInput) searchInput.value = '';
  }

  if (searchToggle && searchInline) {
    searchToggle.addEventListener('click', function (e) {
      e.preventDefault();
      var isOpen = searchInline.classList.contains('is-open');
      if (isOpen && searchInput && searchInput.value.trim()) {
        searchInline.querySelector('form').submit();
      } else if (isOpen) {
        closeSearch();
      } else {
        openSearch();
      }
    });

    // Close on Escape
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && searchInline.classList.contains('is-open')) closeSearch();
    });

    // Close when clicking outside
    document.addEventListener('click', function (e) {
      if (searchInline.classList.contains('is-open') && !searchInline.contains(e.target)) {
        closeSearch();
      }
    });
  }

  // ── Dropdown menus ────────────────────────────────────────
  document.querySelectorAll('.nav-links .menu-item-has-children').forEach(function(item) {
    // Desktop: click toggles (for touch devices)
    item.addEventListener('click', function(e) {
      if (window.innerWidth > 768) return; // let CSS :hover handle desktop
    });
  });

  // Mobile sub-menu toggles
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

  // ── Sliding nav indicator (animates between pages) ─────────
  var navList = document.querySelector('.nav-links-desktop .nav-links');
  if (!navList) return;

  var indicator = document.createElement('span');
  indicator.className = 'nav-indicator';
  navList.appendChild(indicator);

  var allLinks = navList.querySelectorAll('li');
  var activeLi = navList.querySelector(':scope > li.current-menu-item') ||
                 navList.querySelector(':scope > li[aria-current="page"]');

  function getMetrics(el) {
    if (!el) return null;
    var a = el.querySelector('a');
    if (!a) return null;
    var listRect = navList.getBoundingClientRect();
    var linkRect = a.getBoundingClientRect();
    return {
      left: linkRect.left - listRect.left,
      width: linkRect.width
    };
  }

  function setIndicator(left, width) {
    indicator.style.left = left + 'px';
    indicator.style.width = width + 'px';
    indicator.classList.add('is-visible');
  }

  var activeIndex = -1;
  allLinks.forEach(function (li, i) {
    if (li === activeLi) activeIndex = i;
  });

  // localStorage no longer used for position — always derive from server state
  var prev = null;

  if (activeLi) {
    var current = getMetrics(activeLi);
    if (!current) return;

    // Always use the server-determined active item — ignore stale localStorage
    indicator.style.transition = 'none';
    setIndicator(current.left, current.width);
    requestAnimationFrame(function () {
      requestAnimationFrame(function () {
        indicator.style.transition = '';
      });
    });

    try {
      localStorage.setItem('sapient_nav_indicator', JSON.stringify({
        left: current.left,
        width: current.width,
        index: activeIndex
      }));
    } catch (e) {}
  } else {
    // No active item — clear indicator
    try { localStorage.removeItem('sapient_nav_indicator'); } catch(e) {}
  }

  allLinks.forEach(function (li) {
    li.addEventListener('click', function () {
      if (!activeLi) return;
      var m = getMetrics(activeLi);
      if (m) {
        try {
          localStorage.setItem('sapient_nav_indicator', JSON.stringify({
            left: m.left,
            width: m.width,
            index: activeIndex
          }));
        } catch (e) {}
      }
    });
  });

  window.addEventListener('resize', function () {
    if (activeLi) {
      var m = getMetrics(activeLi);
      if (m) setIndicator(m.left, m.width);
    }
  }, { passive: true });
})();
</script>
