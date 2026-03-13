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
