/* ─── Sapient Skateboards — main.js ──────────────────────────────── */

document.addEventListener('DOMContentLoaded', function () {

  // ─── Countdown clock ───────────────────────────────────────
  // Drives any [data-countdown] section. Reads target datetime from
  // data-target (ISO 8601), updates the four data-d/h/m/s slots every
  // second. Stops at 00:00:00:00 when the deadline passes.
  document.querySelectorAll('[data-countdown]').forEach(function (el) {
    var target = new Date(el.getAttribute('data-target'));
    if (isNaN(target.getTime())) return;
    var dEl = el.querySelector('[data-d]');
    var hEl = el.querySelector('[data-h]');
    var mEl = el.querySelector('[data-m]');
    var sEl = el.querySelector('[data-s]');
    var pad = function (n) { return n < 10 ? '0' + n : '' + n; };
    var timer;
    function tick() {
      var diff = Math.max(0, target - new Date());
      var secs = Math.floor(diff / 1000);
      var days = Math.floor(secs / 86400);
      var hrs  = Math.floor((secs % 86400) / 3600);
      var mins = Math.floor((secs % 3600) / 60);
      var s    = secs % 60;
      if (dEl) dEl.textContent = pad(days);
      if (hEl) hEl.textContent = pad(hrs);
      if (mEl) mEl.textContent = pad(mins);
      if (sEl) sEl.textContent = pad(s);
      if (diff === 0 && timer) clearInterval(timer);
    }
    tick();
    timer = setInterval(tick, 1000);
  });

  // ─── Page fade-in ──────────────────────────────────────────
  // Only runs on the first navigation in a session — the inline <head>
  // script (see pt_inline_fade_in_init in functions.php) adds the
  // `fade-in` class to <html> on first visit. We flip `.loaded` here on
  // a paint boundary so the transition plays smoothly.
  requestAnimationFrame(function () { document.documentElement.classList.add('loaded'); });

  // ─── Expandable search ─────────────────────────────────────
  // Behavior: icon click is the only thing that toggles state.
  //   - closed              → open + focus input
  //   - open + has text     → submit the form
  //   - open + empty input  → close
  document.querySelectorAll('[data-search-toggle]').forEach(function (btn) {
    var wrap = btn.closest('[data-search]');
    if (!wrap) return;
    var input = wrap.querySelector('.search-input');
    var form  = wrap.querySelector('form');
    btn.addEventListener('click', function (e) {
      e.stopPropagation();

      // If the mobile menu is open, close it first so search can take focus.
      var navOverlay = document.getElementById('site-nav-mobile');
      if (navOverlay && navOverlay.classList.contains('open')) {
        navOverlay.classList.remove('open');
        var navBtn = document.querySelector('.nav-toggle');
        if (navBtn) {
          navBtn.classList.remove('is-open');
          navBtn.setAttribute('aria-expanded', 'false');
          var navLabel = navBtn.querySelector('.nav-toggle-label');
          if (navLabel && navLabel.getAttribute('data-open')) {
            navLabel.textContent = navLabel.getAttribute('data-open');
          }
        }
      }

      var open = wrap.classList.contains('is-open');
      if (!open) {
        wrap.classList.add('is-open');
        btn.setAttribute('aria-expanded', 'true');
        if (input) {
          // Focus must be SYNCHRONOUS within the user-gesture handler
          // for iOS Safari to open the on-screen keyboard.
          input.removeAttribute('tabindex');
          input.focus();
        }
        return;
      }
      // Already open: submit if there's a query, otherwise close.
      if (input && input.value.trim() !== '') {
        if (form) form.submit();
        return;
      }
      wrap.classList.remove('is-open');
      btn.setAttribute('aria-expanded', 'false');
      if (input) {
        input.setAttribute('tabindex', '-1');
        input.blur();
      }
    });
  });


  // ─── Mobile nav toggle ─────────────────────────────────────
  const toggle      = document.querySelector('.nav-toggle');
  const mobileLinks = document.querySelector('.nav-links--mobile');
  const siteHeader  = document.getElementById('site-header');

  if (toggle && mobileLinks) {
    toggle.addEventListener('click', () => {
      const open = mobileLinks.classList.toggle('open');
      toggle.classList.toggle('is-open', open);
      toggle.setAttribute('aria-expanded', open);
      document.body.classList.toggle('menu-open', open);
      if (siteHeader) siteHeader.classList.toggle('menu-open', open);
    });
  }

  // ─── Header scroll state ───────────────────────────────────
  const header = document.getElementById('site-header');
  if (header) {
    const onScroll = () => {
      header.classList.toggle('scrolled', window.scrollY > 80);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  // ─── Dynamic header height offset ─────────────────────────
  function applyHeaderOffset() {
    if (!header) return;
    const h = header.getBoundingClientRect().height;
    document.documentElement.style.setProperty('--header-h', h + 'px');
  }
  applyHeaderOffset();
  window.addEventListener('resize', applyHeaderOffset);

  // ─── Quantity +/- buttons ──────────────────────────────────
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.qty-btn');
    if (!btn) return;
    const wrap  = btn.closest('.sapient-qty');
    const input = wrap && wrap.querySelector('input.qty');
    if (!input) return;
    const step = parseFloat(input.step) || 1;
    const min  = parseFloat(input.min)  || 1;
    const max  = parseFloat(input.max)  || Infinity;
    let val    = parseFloat(input.value) || min;
    if (btn.classList.contains('qty-plus'))  val = Math.min(val + step, max);
    if (btn.classList.contains('qty-minus')) val = Math.max(val - step, min);
    input.value = val;
    input.dispatchEvent(new Event('change', { bubbles: true }));
  });

  // ─── Desktop nav: parent items navigate on click; dropdown on hover (CSS) ──
  // Clicking a parent nav link navigates directly — no preventDefault
  // Dropdown open/close is handled by CSS :hover and mouseleave cleanup
  document.querySelectorAll('.nav-links-desktop .menu-item-has-children').forEach(function (item) {
    item.addEventListener('mouseleave', function () {
      item.classList.remove('is-open');
    });
  });

  // Close desktop dropdown on outside click
  document.addEventListener('click', function (e) {
    if (!e.target.closest('.nav-links-desktop')) {
      document.querySelectorAll('.nav-links-desktop .menu-item-has-children.is-open').forEach(function (el) {
        el.classList.remove('is-open');
      });
    }
  });

  // ─── Griptape toggle buttons ───────────────────────────────
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.griptape-btn');
    if (!btn) return;
    const wrap = btn.closest('.product-griptape-choices');
    wrap.querySelectorAll('.griptape-btn').forEach(b => b.classList.remove('is-active'));
    btn.classList.add('is-active');
    var hidden = document.getElementById('sapient_griptape_input');
    if (hidden) hidden.value = btn.dataset.value;
  });

});

// ─── Nav active underline — snap, no slide ────────────────────
// The bar sits inside a presentational <li> so the parent <ul> stays
// HTML-valid (a <ul> may only contain <li>, <script>, or <template>).
// aria-hidden + role="presentation" tells screen readers to ignore it.
(function () {
  const navList = document.querySelector('#site-nav .nav-links');
  if (!navList) return;

  const li = document.createElement('li');
  li.setAttribute('role', 'presentation');
  li.setAttribute('aria-hidden', 'true');
  li.className = 'nav-underline-host';
  const bar = document.createElement('span');
  bar.className = 'nav-underline';
  bar.style.transition = 'none';
  li.appendChild(bar);
  navList.appendChild(li);

  const activeLink = navList.querySelector(
    ':scope > li.current-menu-item > a, :scope > li[aria-current="page"] > a'
  );
  if (!activeLink) return;

  const listRect = navList.getBoundingClientRect();
  const rect     = activeLink.getBoundingClientRect();
  bar.style.left    = (rect.left - listRect.left) + 'px';
  bar.style.width   = rect.width + 'px';
  bar.style.opacity = '1';
})();

// ── Cart preview dropdown ─────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
  const toggles = document.querySelectorAll('[data-cart-toggle]');

  toggles.forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      const preview = btn.closest('.nav-cart-wrap').querySelector('[data-cart-preview]');
      if (!preview) return;
      const isOpen = preview.classList.contains('is-open');

      // Close all other open previews
      document.querySelectorAll('[data-cart-preview].is-open').forEach(function (p) {
        p.classList.remove('is-open');
        p.closest('.nav-cart-wrap').querySelector('[data-cart-toggle]').setAttribute('aria-expanded', 'false');
      });

      if (!isOpen) {
        preview.classList.add('is-open');
        btn.setAttribute('aria-expanded', 'true');
      }
    });
  });

  // Close on outside click
  document.addEventListener('click', function () {
    document.querySelectorAll('[data-cart-preview].is-open').forEach(function (p) {
      p.classList.remove('is-open');
      p.closest('.nav-cart-wrap').querySelector('[data-cart-toggle]').setAttribute('aria-expanded', 'false');
    });
  });

  document.querySelectorAll('[data-cart-preview]').forEach(function (p) {
    p.addEventListener('click', function (e) { e.stopPropagation(); });
  });
});


// ── Cart added notification ───────────────────────────────────
(function ($) {

  function showLightbox() {
    var lb = document.getElementById('cart-lightbox');
    if (!lb) return;
    lb.classList.add('is-visible');
    document.body.style.overflow = 'hidden';
  }

  function closeLightbox() {
    var lb = document.getElementById('cart-lightbox');
    if (!lb) return;
    lb.classList.remove('is-visible');
    document.body.style.overflow = '';
  }

  function updateCartCount(count) {
    document.querySelectorAll('.cart-count, .h2-cart-count, .money-bag-count').forEach(function (el) {
      el.textContent = count;
      el.classList.toggle('has-items', count > 0);
    });
  }

  // Single product page — show lightbox after add-to-cart redirect.
  // Only on product pages (body.single-product) to avoid triggering on cart/checkout.
  if (document.body.classList.contains('single-product') &&
      (document.querySelector('.woocommerce-message') || window.location.search.indexOf('added_to_cart') !== -1)) {
    showLightbox();
  }

  // Shop/archive pages — WooCommerce native AJAX event
  $(document).on('added_to_cart', function (e, fragments) {
    showLightbox();
    // Update count from WC fragments if available
    if (fragments && fragments['.cart-count']) {
      var tmp = document.createElement('span');
      tmp.innerHTML = fragments['.cart-count'];
      var count = parseInt(tmp.textContent, 10) || 0;
      updateCartCount(count);
    }
  });

  // Lightbox dismiss — close button, continue shopping, or overlay click
  $(document).on('click', '.cart-lightbox-close, .cart-lightbox-continue, .cart-lightbox-overlay', function () {
    closeLightbox();
  });
  // Dismiss on Escape key
  $(document).on('keydown', function (e) {
    if (e.key === 'Escape') closeLightbox();
  });

  // ── Cart page: auto-update quantity via AJAX ──────────────
  var cartUpdateTimer = null;

  function ajaxUpdateCart(cartKey, qty) {
    var ajaxUrl = (typeof sapientAjax !== 'undefined') ? sapientAjax.url : '/wp-admin/admin-ajax.php';
    $.post(ajaxUrl, {
      action:   'sapient_update_cart',
      nonce:    (typeof sapientAjax !== 'undefined') ? sapientAjax.cart_nonce : '',
      cart_key: cartKey,
      quantity: qty,
    }).done(function (response) {
      if (response && response.success) {
        var d = response.data;
        updateCartCount(d.count);
        // Update totals on page
        $('.cart_totals .cart-subtotal td').html(d.subtotal);
        $('.cart_totals .order-total td').html(d.total);
        if (d.empty) {
          location.reload();
        } else {
          // Update the row subtotal by triggering WC native update
          $('[name="update_cart"]').prop('disabled', false).trigger('click');
        }
      }
    });
  }

  // Quantity +/- buttons on cart page
  $(document).on('click', '.woocommerce-cart-form .qty-btn', function () {
    var $row = $(this).closest('tr[data-cart-key]');
    if (!$row.length) return;
    var cartKey = $row.data('cart-key');
    var qty = parseInt($row.find('.qty').val(), 10) || 1;
    clearTimeout(cartUpdateTimer);
    cartUpdateTimer = setTimeout(function () { ajaxUpdateCart(cartKey, qty); }, 400);
  });

  // Manual quantity input change on cart page
  $(document).on('change', '.woocommerce-cart-form .qty', function () {
    var $row = $(this).closest('tr[data-cart-key]');
    if (!$row.length) return;
    var cartKey = $row.data('cart-key');
    var qty = parseInt($(this).val(), 10) || 1;
    ajaxUpdateCart(cartKey, qty);
  });

  // Remove item — intercept and do via AJAX
  $(document).on('click', '.woocommerce-cart-form a.remove', function (e) {
    e.preventDefault();
    var $row = $(this).closest('tr[data-cart-key]');
    if (!$row.length) return;
    var cartKey = $row.data('cart-key');
    $row.css('opacity', '0.4');
    ajaxUpdateCart(cartKey, 0);
  });

  // ── Newsletter signup form ────────────────────────────────
  $('#footer-newsletter-form').on('submit', function (e) {
    e.preventDefault();
    var $form = $(this);
    var $btn  = $form.find('.fnf-btn');
    var $msg  = $form.find('.fnf-msg');

    $btn.prop('disabled', true).text('Sending…');
    $msg.removeClass('is-success is-error').text('');

    $.post(sapientAjax.url, {
      action: 'sapient_newsletter',
      nonce:  sapientAjax.newsletter_nonce,
      email:  $form.find('#fnf-email').val(),
      phone:  $form.find('#fnf-phone').val(),
    })
    .done(function (res) {
      if (res && res.success) {
        $msg.addClass('is-success').text(res.data.message);
        $form.find('.fnf-input').val('');
      } else {
        $msg.addClass('is-error').text(res.data ? res.data.message : 'Something went wrong.');
      }
    })
    .fail(function () {
      $msg.addClass('is-error').text('Could not connect. Please try again.');
    })
    .always(function () {
      $btn.prop('disabled', false).text('Subscribe');
    });
  });

}(jQuery));

// ─── Listing card stagger animation ─────────────────────────
// Must be in its own DOMContentLoaded so WooCommerce product
// cards are in the DOM before we query and observe them.
document.addEventListener('DOMContentLoaded', function () {
  var cards = document.querySelectorAll('.blog-card, .team-card, .product-card:not(.product-card--placeholder), .shop-img-link');
  if (!cards.length) return;

  // Assign stagger index per card within its parent grid
  var grids = new Map();
  cards.forEach(function (card) {
    var parent = card.parentElement;
    if (!grids.has(parent)) grids.set(parent, []);
    grids.get(parent).push(card);
  });
  grids.forEach(function (gridCards) {
    gridCards.forEach(function (card, i) {
      card.style.setProperty('--stagger-i', i);
    });
  });

  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('card-in');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.05, rootMargin: '0px 0px 400px 0px' });

  cards.forEach(function (card) { observer.observe(card); });
});
