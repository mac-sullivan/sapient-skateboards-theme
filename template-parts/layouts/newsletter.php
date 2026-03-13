<?php
$heading = get_sub_field('newsletter_heading') ?: 'Stay in the loop.';
$subtext  = get_sub_field('newsletter_subtext') ?: 'Events, stories, and ways to get involved — straight to your inbox. No spam, ever.';
?>
<section class="section-newsletter reveal">
  <div class="newsletter-glow" aria-hidden="true"></div>

  <div class="container">
    <div class="newsletter-inner">

      <div class="newsletter-text">
        <span class="newsletter-eyebrow">Newsletter</span>
        <h2 class="newsletter-heading"><?php echo esc_html($heading); ?></h2>
        <p class="newsletter-sub"><?php echo esc_html($subtext); ?></p>
        <ul class="newsletter-perks">
          <li>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            Event announcements &amp; skate sessions
          </li>
          <li>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            Youth success stories from the community
          </li>
          <li>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            Ways to volunteer, donate &amp; get involved
          </li>
        </ul>
      </div>

      <div class="newsletter-form-wrap">
        <form class="newsletter-form" id="pt-newsletter-form" novalidate>
          <?php wp_nonce_field('pt_newsletter_nonce', 'pt_nonce'); ?>

          <div class="newsletter-row">
            <div class="newsletter-field-group">
              <label for="nl-first-name">First name</label>
              <input
                type="text"
                id="nl-first-name"
                name="first_name"
                placeholder="Alex"
                autocomplete="given-name"
              >
            </div>
            <div class="newsletter-field-group">
              <label for="nl-email">Email address <span class="required">*</span></label>
              <input
                type="email"
                id="nl-email"
                name="email"
                placeholder="alex@email.com"
                required
                autocomplete="email"
              >
            </div>
          </div>

          <button type="submit" class="btn btn-primary newsletter-submit">
            <span class="btn-label">Subscribe — it's free</span>
            <svg class="btn-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="5" y1="12" x2="19" y2="12"/>
              <polyline points="12 5 19 12 12 19"/>
            </svg>
          </button>

          <p class="newsletter-privacy">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
            No spam. Unsubscribe anytime. We respect your privacy.
          </p>

          <div class="newsletter-message" id="nl-message" aria-live="polite" hidden></div>
        </form>
      </div>

    </div>
  </div>
</section>

<script>
(function() {
  const form = document.getElementById('pt-newsletter-form');
  if (!form) return;

  form.addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = form.querySelector('.newsletter-submit');
    const msg = document.getElementById('nl-message');
    const label = btn.querySelector('.btn-label');

    btn.disabled = true;
    label.textContent = 'Subscribing…';

    const body = new FormData(form);
    body.append('action', 'pt_newsletter_subscribe');

    try {
      const res = await fetch('<?php echo esc_url(admin_url("admin-ajax.php")); ?>', {
        method: 'POST',
        body,
      });
      const data = await res.json();
      msg.hidden = false;
      msg.className = 'newsletter-message ' + (data.success ? 'is-success' : 'is-error');
      msg.textContent = data.data?.message || (data.success ? 'You\'re in! 🎉' : 'Something went wrong.');
      if (data.success) {
        form.querySelector('#nl-email').value = '';
        form.querySelector('#nl-first-name').value = '';
        btn.style.display = 'none';
      } else {
        btn.disabled = false;
        label.textContent = 'Subscribe — it\'s free';
      }
    } catch {
      msg.hidden = false;
      msg.className = 'newsletter-message is-error';
      msg.textContent = 'Network error. Please try again.';
      btn.disabled = false;
      label.textContent = 'Subscribe — it\'s free';
    }
  });
})();
</script>
