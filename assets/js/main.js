/* ─── Sapient Skateboards — main.js ──────────────────────────────── */

document.addEventListener('DOMContentLoaded', function () {

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

  // ─── Desktop nav: parent items open dropdown only ──────────
  document.querySelectorAll('.nav-links-desktop .menu-item-has-children > a').forEach(function (link) {
    link.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      var item = link.parentElement;
      var isOpen = item.classList.contains('is-open');
      // Close all
      document.querySelectorAll('.nav-links-desktop .menu-item-has-children.is-open').forEach(function (el) {
        el.classList.remove('is-open');
      });
      // Toggle: only open if it was closed
      if (!isOpen) item.classList.add('is-open');
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
    const hidden = wrap.querySelector('#sapient_griptape_val');
    if (hidden) hidden.value = btn.dataset.value;
  });

});

// ─── Nav active underline — snap, no slide ────────────────────
(function () {
  const navList = document.querySelector('#site-nav .nav-links');
  if (!navList) return;

  const bar = document.createElement('span');
  bar.className = 'nav-underline';
  bar.style.transition = 'none';
  navList.appendChild(bar);

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

  function showInlineMsg() {
    var msg = document.getElementById('cart-added-inline');
    if (!msg) return;
    msg.classList.add('is-visible');
  }

  function showToast() {
    var toast = document.getElementById('cart-toast');
    if (!toast) return;
    toast.classList.add('is-visible');
    setTimeout(function () { toast.classList.remove('is-visible'); }, 4500);
  }

  function updateCartCount(count) {
    document.querySelectorAll('.cart-count').forEach(function (el) {
      el.textContent = count;
      el.classList.toggle('has-items', count > 0);
    });
  }

  // Single product page — AJAX via custom WP handler
  $(document).on('submit', 'form.cart', function (e) {
    var $form = $(this);
    var productId = $form.find('[name="add-to-cart"]').val();
    if (!productId) return; // no product ID yet (variation not chosen) — let WC handle

    e.preventDefault();

    var ajaxUrl = (typeof sapientAjax !== 'undefined') ? sapientAjax.url : '/wp-admin/admin-ajax.php';

    var data = {
      action:       'sapient_add_to_cart',
      product_id:   productId,
      quantity:     $form.find('[name="quantity"]').val() || 1,
      variation_id: $form.find('[name="variation_id"]').val() || 0,
    };

    // Include variation attributes
    $form.find('[name^="attribute_"]').each(function () {
      data[$(this).attr('name')] = $(this).val();
    });

    $.post(ajaxUrl, data)
      .done(function (response) {
        if (response && response.success) {
          showInlineMsg();
          updateCartCount(response.data.count);
          // Swap all cart preview dropdowns with fresh HTML
          if (response.data.preview_html) {
            document.querySelectorAll('[data-cart-preview]').forEach(function (el) {
              el.innerHTML = response.data.preview_html;
            });
          }
        } else {
          $form.off('submit').submit();
        }
      })
      .fail(function () {
        $form.off('submit').submit();
      });
  });

  // Shop/archive pages — WooCommerce native AJAX event
  $(document).on('added_to_cart', function () { showToast(); });

  // Toast dismiss
  $(document).on('click', '.cart-toast-close', function () {
    var toast = document.getElementById('cart-toast');
    if (toast) toast.classList.remove('is-visible');
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

  // ─── Listing card stagger animation ───────────────────────
  (function () {
    var cards = document.querySelectorAll('.blog-card, .team-card, .product-card');
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
    }, { threshold: 0.08, rootMargin: '0px 0px -30px 0px' });

    cards.forEach(function (card) { observer.observe(card); });
  }());

}(jQuery));
