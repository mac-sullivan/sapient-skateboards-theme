<?php
$hero_image   = pt_field( 'hero_image' );
$hero_heading = pt_field( 'hero_heading', 'Built for the<br><em>Street</em>' );
$hero_sub     = pt_field( 'hero_subtext', 'Sapient Skateboards crafts premium decks, trucks, and gear for riders who demand more. Handmade. Tested on streets. Made to last.' );
$cta_label    = pt_field( 'hero_cta_label', 'Shop Now' );
$cta_url      = pt_field( 'hero_cta_url', home_url('/shop') );
$story_url    = pt_field( 'hero_story_url', home_url('/about') );
?>
<section class="hero">

  <?php if ( $hero_image ) : ?>
    <img loading="eager" fetchpriority="high" decoding="async" class="hero-bg"
         src="<?php echo esc_url( $hero_image['url'] ); ?>"
         alt="<?php echo esc_attr( $hero_image['alt'] ); ?>"
         loading="eager">
  <?php else : ?>
    <!-- Placeholder — replace via ACF in WP Admin -->
    <div class="hero-bg" style="background: #1a1a1a;"></div>
  <?php endif; ?>

  <div class="hero-overlay"></div>

  <div class="container">
    <div class="hero-content">
      <span class="hero-eyebrow">Phoenix, AZ — Est. 2024</span>
      <h1><?php echo wp_kses( $hero_heading, [ 'br' => [], 'em' => [], 'strong' => [] ] ); ?></h1>
      <p><?php echo esc_html( $hero_sub ); ?></p>
      <div class="hero-actions">
        <a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn-primary">
          <?php echo esc_html( $cta_label ); ?>
        </a>
        <a href="<?php echo esc_url( $story_url ); ?>" class="btn btn-outline">
          Our Story
        </a>
      </div>
    </div>
  </div>

</section>
