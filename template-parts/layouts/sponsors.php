<?php
// "Become a Sponsor" recruitment section
$tiers = [
  [
    'label'  => 'Community Friend',
    'amount' => '$250 / yr',
    'perks'  => ['Name in program materials', 'Social media shoutout', 'Community hero status'],
    'color'  => 'bronze',
  ],
  [
    'label'  => 'Program Partner',
    'amount' => '$500 / yr',
    'perks'  => ['Logo on website & gear', 'Event signage', 'Quarterly impact report', 'Everything in Community Friend'],
    'color'  => 'silver',
    'featured' => true,
  ],
  [
    'label'  => 'Lead Sponsor',
    'amount' => '$1,000+ / yr',
    'perks'  => ['Premier logo placement', 'Named event opportunities', 'Press + media mentions', 'Everything in Program Partner'],
    'color'  => 'gold',
  ],
];
?>
<section class="section-sponsors reveal">
  <div class="sponsors-bg-glow" aria-hidden="true"></div>

  <div class="container">

    <!-- Header -->
    <div class="sponsors-header">
      <span class="section-eyebrow sponsors-eyebrow">Partnerships</span>
      <h2 class="sponsors-headline"><span>Partner with us.</span><span>Change a kid's life.</span></h2>
      <p class="sponsors-sub">Partner with Sapient Skateboards and put your brand behind authentic skateboarding culture. Real riders, real product, real reach.</p>
    </div>

    <!-- Impact stats row -->
    <div class="sponsors-impact-row">
      <div class="sponsors-impact-stat">
        <span class="impact-number">500+</span>
        <span class="impact-label">Youth Served Annually</span>
      </div>
      <div class="sponsors-impact-divider" aria-hidden="true"></div>
      <div class="sponsors-impact-stat">
        <span class="impact-number">8+</span>
        <span class="impact-label">Communities Reached</span>
      </div>
      <div class="sponsors-impact-divider" aria-hidden="true"></div>
      <div class="sponsors-impact-stat">
        <span class="impact-number">100%</span>
        <span class="impact-label">Free to Participants</span>
      </div>
      <div class="sponsors-impact-divider" aria-hidden="true"></div>
      <div class="sponsors-impact-stat">
        <span class="impact-number">501(c)(3)</span>
        <span class="impact-label">Tax-Deductible Giving</span>
      </div>
    </div>

    <!-- Tier cards -->
    <div class="sponsors-tiers">
      <?php foreach ($tiers as $tier) : ?>
        <div class="sponsor-tier sponsor-tier--<?php echo $tier['color']; ?><?php echo !empty($tier['featured']) ? ' sponsor-tier--featured' : ''; ?>">
          <?php if (!empty($tier['featured'])) : ?>
            <div class="tier-badge">Most Popular</div>
          <?php endif; ?>
          <div class="tier-header">
            <span class="tier-label"><?php echo esc_html($tier['label']); ?></span>
            <span class="tier-amount"><?php echo esc_html($tier['amount']); ?></span>
          </div>
          <ul class="tier-perks">
            <?php foreach ($tier['perks'] as $perk) : ?>
              <li>
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                <?php echo esc_html($perk); ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- CTA -->
    <div class="sponsors-cta">
      <a href="<?php echo home_url('/contact'); ?>" class="btn btn-primary sponsors-cta-btn">
        Get in Touch
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
      <p class="sponsors-cta-note">Custom packages available. Let's build something together.</p>
    </div>

  </div>
</section>
