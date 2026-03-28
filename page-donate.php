<?php
/**
 * Template for /donate/ — WordPress auto-loads page-donate.php
 */
get_header('two');
?>


<div class="donate-page">

  <!-- ── 1. PAGE HEADER ──────────────────────────────────────── -->
  <div class="page-archive-header">
    <div class="container">
      <span class="page-archive-eyebrow">Make a difference</span>
      <h1 class="page-archive-title">Every dollar keeps<br>a kid on wheels.</h1>
      <p class="page-archive-sub">100% of programs are free to kids. Your donation makes that possible.</p>
    </div>
  </div>

  <!-- ── 2. FORM + IMPACT ──────────────────────────────────────── -->
  <section class="donate-main">
    <div class="container donate-main-grid">

      <!-- Left: Impact context -->
      <div class="donate-impact reveal-left">
        <span class="section-eyebrow donate-section-eyebrow">Your impact</span>
        <h2 class="donate-impact-heading">See what<br>your gift does.</h2>

        <div class="donate-tiers">
          <div class="donate-tier">
            <span class="donate-tier-amount">$25</span>
            <div class="donate-tier-info">
              <strong>One session covered</strong>
              <span>Covers all equipment and coaching for one kid for an entire Saturday session.</span>
            </div>
          </div>
          <div class="donate-tier">
            <span class="donate-tier-amount">$50</span>
            <div class="donate-tier-info">
              <strong>Full safety kit</strong>
              <span>Provides a complete set of pads and a certified helmet for a new rider.</span>
            </div>
          </div>
          <div class="donate-tier">
            <span class="donate-tier-amount">$100</span>
            <div class="donate-tier-info">
              <strong>Monthly sponsor</strong>
              <span>Keeps one kid enrolled in free weekly sessions for an entire month.</span>
            </div>
          </div>
          <div class="donate-tier">
            <span class="donate-tier-amount">$250</span>
            <div class="donate-tier-info">
              <strong>Clinic day</strong>
              <span>Funds a full afternoon clinic for 15 kids — gear, coaching, and snacks included.</span>
            </div>
          </div>
        </div>

        <p class="donate-legal">Sapient Skateboards — Phoenix, AZ.</p>
      </div>

      <!-- Right: Form -->
      <div class="donate-form-col reveal-right">
        <div class="donate-form-card">
          <h3>Choose an amount</h3>
          <?php echo do_shortcode('[give_form id="162"]'); ?>
        </div>
      </div>

    </div>
  </section>

  <!-- ── 3. WHERE MONEY GOES ────────────────────────────────────── -->
  <section class="donate-breakdown">
    <div class="container">
      <div class="donate-breakdown-header reveal">
        <span class="section-eyebrow donate-section-eyebrow-light">Transparency</span>
        <h2 class="donate-breakdown-heading">Where your money goes.</h2>
        <p>We believe in full transparency. Here's exactly how every dollar is allocated.</p>
      </div>
      <div class="donate-bars">
        <div class="donate-bar-item" data-pct="60">
          <div class="donate-bar-meta">
            <span class="donate-bar-label">Programs &amp; Coaching</span>
            <span class="donate-bar-pct">60%</span>
          </div>
          <div class="donate-bar-track"><div class="donate-bar-fill"></div></div>
        </div>
        <div class="donate-bar-item" data-pct="25">
          <div class="donate-bar-meta">
            <span class="donate-bar-label">Equipment &amp; Gear</span>
            <span class="donate-bar-pct">25%</span>
          </div>
          <div class="donate-bar-track"><div class="donate-bar-fill"></div></div>
        </div>
        <div class="donate-bar-item" data-pct="10">
          <div class="donate-bar-meta">
            <span class="donate-bar-label">Community Events</span>
            <span class="donate-bar-pct">10%</span>
          </div>
          <div class="donate-bar-track"><div class="donate-bar-fill"></div></div>
        </div>
        <div class="donate-bar-item" data-pct="5">
          <div class="donate-bar-meta">
            <span class="donate-bar-label">Admin &amp; Operations</span>
            <span class="donate-bar-pct">5%</span>
          </div>
          <div class="donate-bar-track"><div class="donate-bar-fill"></div></div>
        </div>
      </div>
    </div>
  </section>

  <!-- ── 4. OTHER WAYS TO GIVE ──────────────────────────────────── -->
  <section class="donate-other">
    <div class="container">
      <div class="donate-other-header reveal">
        <span class="section-eyebrow donate-section-eyebrow">Other ways to help</span>
        <h2 class="donate-other-heading">Not just money.</h2>
      </div>
      <div class="donate-other-grid stagger-group">
        <div class="donate-other-card">
          <div class="donate-other-icon">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
          </div>
          <h3>Donate Gear</h3>
          <p>Questions about gear or orders? Hit us up anytime.</p>
          <a href="/contact" class="donate-other-link">Drop off gear →</a>
        </div>
        <div class="donate-other-card">
          <div class="donate-other-icon">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          </div>
          <h3>Volunteer</h3>
          <p>Coach a session, assist at events, or help with social media. Every hour of your time translates directly to more kids on boards.</p>
          <a href="/contact" class="donate-other-link">Get in touch →</a>
        </div>
        <div class="donate-other-card">
          <div class="donate-other-icon">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
          </div>
          <h3>Corporate Sponsor</h3>
          <p>Partner with us to put your brand in front of the community while doing real good. Custom packages available at any budget.</p>
          <a href="mailto:hello@sapientskateboards.com" class="donate-other-link">Email us →</a>
        </div>
      </div>
    </div>
  </section>

  <!-- ── 5. CTA BANNER ─────────────────────────────────────────── -->
  <?php // get_template_part('template-parts/layouts/cta_banner'); ?>

  <!-- ── 6. NEWSLETTER ──────────────────────────────────────────── -->
  <?php get_template_part('template-parts/layouts/newsletter'); ?>

</div>

<?php get_footer(); ?>
