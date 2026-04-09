<?php
/**
 * Template Name: Process Page
 * Slug: process
 */
get_header( sapient_get_active_header() );

$hero_img  = get_field( 'process_hero_image' );
$hero_head = get_field( 'process_hero_headline' ) ?: 'Built here. No exceptions.';
$hero_sub  = get_field( 'process_hero_sub' ) ?: 'We don\'t outsource the hard part.';
$steps     = get_field( 'process_steps_repeater' );
?>

<main id="main-content" class="process-page">

  <!-- ── Hero ──────────────────────────────────────────────── -->
  <section class="process-hero">
    <div class="container">
        <!-- Split: text left, image right -->
        <div class="process-hero-bottom">
          <div class="process-hero-left">
            <?php get_template_part( 'template-parts/breadcrumbs' ); ?>
            <p class="process-hero-eyebrow">How It's Made</p>
            <h1 class="process-hero-headline"><?php echo esc_html( $hero_head ); ?></h1>
            <?php if ( $hero_sub ) : ?>
              <p class="process-hero-sub"><?php echo esc_html( $hero_sub ); ?></p>
            <?php endif; ?>
          </div>

          <div class="process-hero-right">
            <?php
            $hero_img_url = '';
            if ( is_array( $hero_img ) ) { $hero_img_url = $hero_img['url']; }
            elseif ( $hero_img ) { $hero_img_url = wp_get_attachment_url( $hero_img ); }
            ?>
            <?php if ( $hero_img_url ) : ?>
              <div class="process-hero-visual">
                <img src="<?php echo esc_url( $hero_img_url ); ?>" alt="Sapient warehouse" loading="eager" fetchpriority="high" decoding="async" class="process-hero-img">
              </div>
            <?php else : ?>
              <div class="process-hero-visual"><div class="process-hero-img-placeholder"></div></div>
            <?php endif; ?>
          </div>
        </div>
    </div>
  </section>

  <!-- ── Steps ─────────────────────────────────────────────── -->
  <?php if ( ! empty( $steps ) ) : ?>
    <div class="process-steps">
      <?php foreach ( $steps as $i => $step ) :
        $odd = ( $i % 2 === 0 );
        $img  = $step['step_image'];
        $img_url = is_array( $img ) ? $img['url'] : wp_get_attachment_url( $img );
        $img_alt = is_array( $img ) ? $img['alt'] : '';
      ?>
      <section class="process-step<?php echo $odd ? '' : ' process-step--flip'; ?>">
        <div class="container">
          <div class="process-step-inner">

            <!-- Image side -->
            <div class="process-step-media">
              <?php if ( $img_url ) : ?>
                <img
                  src="<?php echo esc_url( $img_url ); ?>"
                  alt="<?php echo esc_attr( $img_alt ); ?>"
                  loading="lazy" decoding="async" class="process-step-img"
                >
                <?php if ( ! empty( $step['step_image_caption'] ) ) : ?>
                  <p class="process-step-caption"><?php echo esc_html( $step['step_image_caption'] ); ?></p>
                <?php endif; ?>
              <?php else : ?>
                <div class="process-step-img-placeholder"></div>
              <?php endif; ?>
            </div>

            <!-- Text side -->
            <div class="process-step-text">
              <?php if ( ! empty( $step['step_eyebrow'] ) ) : ?>
                <h2 class="process-step-eyebrow"><?php echo esc_html( $step['step_eyebrow'] ); ?></h2>
              <?php endif; ?>
              <?php if ( ! empty( $step['step_headline'] ) ) : ?>
                <p class="process-step-headline"><?php echo esc_html( $step['step_headline'] ); ?></p>
              <?php endif; ?>
              <?php if ( ! empty( $step['step_body'] ) ) : ?>
                <div class="process-step-body"><?php echo wp_kses_post( $step['step_body'] ); ?></div>
              <?php endif; ?>
            </div>

          </div>
        </div>
      </section>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</main>

<?php get_footer(); ?>
