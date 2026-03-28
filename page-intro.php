<?php
/**
 * Template Name: Intro Page
 * Full-screen video splash — no header/footer.
 */
defined( 'ABSPATH' ) || exit;

$video_url     = get_field( 'intro_video' );
$poster_url    = get_field( 'intro_video_poster' );
$logo_img      = get_field( 'intro_logo_image' );
$content       = get_field( 'intro_content' );
$button        = get_field( 'intro_button' );
$button_text   = $button['title']  ?? 'ENTER WEBSITE';
$button_url    = $button['url']    ?? home_url( '/shop/' );
$button_target = ! empty( $button['target'] ) ? $button['target'] : '_self';
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php bloginfo( 'name' ); ?></title>
  <?php wp_head(); ?>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { width: 100%; height: 100%; overflow: hidden; background: #282828; }
  </style>
</head>
<body class="intro-page">

<div class="intro-wrap">

  <?php if ( $video_url ) : ?>
  <video
    class="intro-video"
    src="<?php echo esc_url( $video_url ); ?>"
    <?php if ( $poster_url ) echo 'poster="' . esc_url( $poster_url ) . '"'; ?>
    autoplay
    muted
    loop
    playsinline
    preload="auto"
  ></video>
  <?php elseif ( $poster_url ) : ?>
  <div class="intro-video intro-video--img" style="background-image:url('<?php echo esc_url($poster_url); ?>')"></div>
  <?php endif; ?>

  <div class="intro-overlay"></div>

  <div class="intro-content">

    <?php if ( $logo_img ) : ?>
    <div class="intro-logo">
      <img
        src="<?php echo esc_url( $logo_img['url'] ); ?>"
        alt="<?php echo esc_attr( $logo_img['alt'] ?: get_bloginfo('name') ); ?>"
        width="<?php echo esc_attr( $logo_img['width'] ); ?>"
        height="<?php echo esc_attr( $logo_img['height'] ); ?>"
      >
    </div>
    <?php endif; ?>

    <?php /* intro_content intentionally hidden per client direction (Mar 2026) */ ?>

    <a
      href="<?php echo esc_url( $button_url ); ?>"
      class="intro-enter-btn"
      target="<?php echo esc_attr( $button_target ); ?>"
    >
      <?php echo esc_html( $button_text ); ?>

    </a>

  </div>

</div>

<?php wp_footer(); ?>
</body>
</html>
