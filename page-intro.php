<?php
/**
 * Template Name: Intro Page
 * Video splash — fullscreen, logo + enter button centered over video.
 */
defined( 'ABSPATH' ) || exit;

$video_url     = get_field( 'intro_video' );
$poster_url    = get_field( 'intro_video_poster' );
$logo_img      = get_field( 'intro_logo_image' );
$button        = get_field( 'intro_button' );
$button_text   = $button['title']  ?? 'ENTER WEBSITE';
$button_url    = $button['url']    ?? home_url( '/shop/' );
$button_target = ! empty( $button['target'] ) ? $button['target'] : '_self';
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="intro-page">
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php bloginfo( 'name' ); ?></title>
  <?php wp_head(); ?>
  <style>
    html.intro-page, body.intro-page { margin: 0 !important; padding: 0 !important; overflow: hidden !important; background: #000 !important; }
    #wpadminbar { display: none !important; }
  </style>
</head>
<body class="intro-page">

<div class="intro-wrap">

  <?php if ( $video_url ) : ?>
  <video
    id="intro-video"
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

  <!-- Centered overlay: logo + enter button -->
  <div class="intro-overlay">

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

    <a
      href="<?php echo esc_url( $button_url ); ?>"
      class="intro-enter-btn"
      target="<?php echo esc_attr( $button_target ); ?>"
    >
      <?php echo esc_html( $button_text ); ?>
    </a>

  </div>

  <!-- Video controls -->
  <?php if ( $video_url ) : ?>
  <div class="intro-video-controls">
    <button class="intro-btn-mute" aria-label="Unmute" title="Unmute">
      <svg class="icon-muted" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
        <line x1="23" y1="9" x2="17" y2="15"/><line x1="17" y1="9" x2="23" y2="15"/>
      </svg>
      <svg class="icon-unmuted" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none">
        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
        <path d="M19.07 4.93a10 10 0 010 14.14"/><path d="M15.54 8.46a5 5 0 010 7.07"/>
      </svg>
    </button>
  </div>
  <?php endif; ?>

</div>

<script>
(function() {
  var video = document.getElementById('intro-video');
  if (!video) return;
  var muteBtn     = document.querySelector('.intro-btn-mute');
  var iconMuted   = muteBtn ? muteBtn.querySelector('.icon-muted')   : null;
  var iconUnmuted = muteBtn ? muteBtn.querySelector('.icon-unmuted') : null;
  if (muteBtn) {
    muteBtn.addEventListener('click', function() {
      video.muted = !video.muted;
      iconMuted.style.display   = video.muted ? '' : 'none';
      iconUnmuted.style.display = video.muted ? 'none' : '';
      muteBtn.setAttribute('aria-label', video.muted ? 'Unmute' : 'Mute');
      muteBtn.setAttribute('title',      video.muted ? 'Unmute' : 'Mute');
    });
  }
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
