<?php
/**
 * Single Product — Custom Sapient Template
 */
defined( 'ABSPATH' ) || exit;

get_header( sapient_get_active_header() );

while ( have_posts() ) : the_post();
  global $product;
  $title       = get_the_title();
  $price_html  = $product->get_price_html();
  $description = apply_filters( 'the_content', get_the_content() );
  $short_desc  = $product->get_short_description();
  $in_stock    = $product->is_in_stock();
  $sku         = $product->get_sku();

  // Gallery images
  $thumb_id    = get_post_thumbnail_id();
  $gallery_ids = $product->get_gallery_image_ids();
  $all_images  = $thumb_id ? array_merge([$thumb_id], $gallery_ids) : $gallery_ids;
?>

<main id="main-content" class="product-single-page">
  <div class="container">
  <div class="product-single-inner">

    <!-- ── Image Panel ─────────────────────────────────────────── -->
    <div class="product-single-gallery">
      <?php if ( ! empty($all_images) ) : ?>
        <div class="product-gallery-main">
          <button class="product-zoom-trigger" id="product-zoom-trigger" aria-label="Enlarge image">
            <img
              src="<?php echo esc_url( wp_get_attachment_image_url($all_images[0], 'large') ); ?>"
              alt="<?php echo esc_attr($title); ?>"
              loading="eager" fetchpriority="high" decoding="async" class="product-gallery-main-img"
              id="product-main-img"
            >
            <span class="product-zoom-icon" aria-hidden="true">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                <line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/>
              </svg>
            </span>
          </button>
        </div>

        <!-- Lightbox -->
        <?php
        $lightbox_imgs = [];
        foreach ( $all_images as $img_id ) {
          $lightbox_imgs[] = wp_get_attachment_image_url( $img_id, 'full' );
        }
        ?>
        <div class="product-lightbox" id="product-lightbox" role="dialog" aria-modal="true" aria-label="Image enlarged"
             data-images="<?php echo esc_attr( json_encode( $lightbox_imgs ) ); ?>">
          <button class="product-lightbox-close" id="product-lightbox-close" aria-label="Close">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
          </button>
          <button class="product-lightbox-prev" id="product-lightbox-prev" aria-label="Previous image">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="15 18 9 12 15 6"/>
            </svg>
          </button>
          <div class="product-lightbox-inner">
            <img src="" alt="" class="product-lightbox-img" id="product-lightbox-img" loading="lazy" decoding="async">
          </div>
          <button class="product-lightbox-next" id="product-lightbox-next" aria-label="Next image">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="9 18 15 12 9 6"/>
            </svg>
          </button>
          <div class="product-lightbox-counter" id="product-lightbox-counter"></div>
        </div>
        <?php if ( count($all_images) > 1 ) : ?>
          <div class="product-gallery-thumbs">
            <?php foreach ($all_images as $i => $img_id) : ?>
              <button class="product-thumb-btn <?php echo $i === 0 ? 'is-active' : ''; ?>"
                data-src="<?php echo esc_url( wp_get_attachment_image_url($img_id, 'large') ); ?>"
                aria-label="View image <?php echo $i + 1; ?>">
                <img src="<?php echo esc_url( wp_get_attachment_image_url($img_id, 'thumbnail') ); ?>" loading="lazy" decoding="async" alt="">
              </button>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      <?php else : ?>
        <div class="product-gallery-placeholder"></div>
      <?php endif; ?>
    </div>

    <!-- ── Details Panel ───────────────────────────────────────── -->
    <div class="product-single-details">

      <?php
      // ── Breadcrumbs ──────────────────────────────────────────
      $cats      = get_the_terms( get_the_ID(), 'product_cat' );
      $main_cat  = null;
      if ( $cats && ! is_wp_error( $cats ) ) {
        foreach ( $cats as $cat ) {
          if ( $cat->slug !== 'uncategorized' ) { $main_cat = $cat; break; }
        }
        if ( ! $main_cat ) $main_cat = $cats[0];
      }
      ?>
      <nav class="product-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo esc_url( home_url('/shop/') ); ?>">Shop</a>
        <?php if ( $main_cat ) : ?>
          <span class="breadcrumb-sep">/</span>
          <a href="<?php echo esc_url( get_term_link( $main_cat ) ); ?>"><?php echo esc_html( $main_cat->name ); ?></a>
        <?php endif; ?>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current"><?php echo esc_html( $title ); ?></span>
      </nav>

      <h1 class="product-single-title"><?php echo esc_html($title); ?></h1>

      <?php if ($sku) : ?>
        <span class="product-single-sku">SKU: <?php echo esc_html($sku); ?></span>
      <?php endif; ?>

      <div class="product-single-price"><?php echo $price_html; ?></div>

      <?php if ($short_desc) : ?>
        <div class="product-single-short-desc"><?php echo wp_kses_post($short_desc); ?></div>
      <?php endif; ?>

      <?php if ($description) : ?>
        <div class="product-single-description">
          <?php echo ($description); ?>
        </div>
      <?php endif; ?>

      <?php if ($in_stock) : ?>

        <!-- ── Size selector ──────────────────────────── -->
        <?php
        $available_sizes = get_field( 'product_sizes', get_the_ID() );
        if ( ! empty( $available_sizes ) ) :
        ?>
        <div class="product-size-option">
          <span class="product-option-label">Size</span>
          <div class="product-size-choices">
            <input type="hidden" name="sapient_size" id="sapient_size_val" value="">
            <?php foreach ( $available_sizes as $row ) :
                $size = esc_attr( $row['size_value'] );
              ?>
              <button type="button" class="size-btn" data-value="<?php echo $size; ?>">
                <?php echo esc_html( $size ); ?>
              </button>
            <?php endforeach; ?>
          </div>
          <p class="size-required-msg" style="display:none;">Please select a size.</p>
        </div>
        <?php endif; ?>

        <!-- ── Color selector ────────────────────────── -->
        <?php
        $available_colors = get_field( 'product_colors', get_the_ID() );
        if ( ! empty( $available_colors ) ) :
        ?>
        <div class="product-color-option">
          <span class="product-option-label">Color</span>
          <div class="product-color-choices">
            <input type="hidden" name="sapient_color" id="sapient_color_val" value="">
            <?php foreach ( $available_colors as $row ) :
              $name = esc_attr( $row['color_name'] );
              $hex  = esc_attr( $row['color_hex'] );
            ?>
              <span class="color-swatch-wrap">
                <button type="button" class="color-btn"
                  data-value="<?php echo $name; ?>"
                  style="--swatch: <?php echo $hex; ?>"
                  title="<?php echo $name; ?>">
                </button>
                <span class="color-swatch-label"><?php echo esc_html( $row['color_name'] ); ?></span>
              </span>
            <?php endforeach; ?>
          </div>
          <p class="color-required-msg" style="display:none;">Please select a color.</p>
        </div>
        <?php endif; ?>

        <!-- ── Griptape Option (Boards only) ──────── -->
        <?php
        $hide_griptape = get_field( 'hide_griptape', get_the_ID() );
        if ( ! $hide_griptape && ! has_term( 'softgoods', 'product_cat', get_the_ID() ) ) :
        ?>
        <div class="product-griptape-option">
          <span class="product-option-label">Griptape</span>
          <div class="product-griptape-choices">
            <input type="hidden" name="sapient_griptape" id="sapient_griptape_val" value="None">
            <button type="button" class="griptape-btn is-active" data-value="None">None</button>
            <button type="button" class="griptape-btn" data-value="Black (Applied) +$5">Black (Applied) <span>+$5</span></button>
            <button type="button" class="griptape-btn" data-value="Black (On Side) +$5">Black (On Side) <span>+$5</span></button>
          </div>
        </div>
        <?php endif; ?>

        <?php woocommerce_template_single_add_to_cart(); ?>
        <div class="cart-added-inline" id="cart-added-inline" aria-live="polite">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          Item added to your cart — <a href="<?php echo esc_url( wc_get_cart_url() ); ?>">click here to view cart</a>
        </div>
      <?php else : ?>
        <div class="product-sold-out">Sold Out</div>
      <?php endif; ?>


    </div>

  </div>
  </div>
</main>

<?php if ( have_rows( 'page_sections' ) ) : ?>
  <div class="product-flex-sections">
    <?php while ( have_rows( 'page_sections' ) ) : the_row(); ?>
      <?php
      $layout = get_row_layout();
      $part   = 'template-parts/layouts/' . $layout;
      if ( locate_template( $part . '.php' ) ) {
        get_template_part( $part );
      }
      ?>
    <?php endwhile; ?>
  </div>
<?php endif; ?>

<?php
// ── Process steps — pulled globally from the Process page ──────
// Only show on deck products (not softgoods/apparel)
$is_softgoods = has_term( 'softgoods', 'product_cat', get_the_ID() );
$process_page_id = get_page_by_path( 'process' );
$process_page_id = $process_page_id ? $process_page_id->ID : null;
$steps = $process_page_id ? get_field( 'process_steps_repeater', $process_page_id ) : [];

if ( ! $is_softgoods && ! empty( $steps ) ) : ?>
<section class="product-process-steps">
  <div class="container"></div>
  <?php foreach ( $steps as $i => $step ) :
    $flip      = ( $i % 2 !== 0 ) ? 'process-step--flip' : '';
    $img_url   = '';
    if ( ! empty( $step['step_image'] ) ) {
      if ( is_array( $step['step_image'] ) ) {
        $img_url = $step['step_image']['url'];
      } else {
        $img_url = wp_get_attachment_url( $step['step_image'] );
      }
    }
  ?>
  <div class="process-step <?php echo esc_attr( $flip ); ?>">
    <div class="container">
      <div class="process-step-inner">
        <div class="process-step-media">
          <?php if ( $img_url ) : ?>
            <img src="<?php echo esc_url( $img_url ); ?>" loading="lazy" decoding="async" alt="<?php echo esc_attr( $step['step_eyebrow'] ?? '' ); ?>" class="process-step-img">
          <?php else : ?>
            <div class="process-step-img-placeholder"></div>
          <?php endif; ?>
          <?php if ( ! empty( $step['step_image_caption'] ) ) : ?>
            <p class="process-step-caption"><?php echo esc_html( $step['step_image_caption'] ); ?></p>
          <?php endif; ?>
        </div>
        <div class="process-step-text">
          <?php if ( ! empty( $step['step_eyebrow'] ) ) : ?>
            <p class="process-step-eyebrow"><?php echo esc_html( $step['step_eyebrow'] ); ?></p>
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
  </div>
  <?php endforeach; ?>
</section>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // Gallery thumbs
  const mainImg = document.getElementById('product-main-img');
  const thumbBtns = document.querySelectorAll('.product-thumb-btn');
  thumbBtns.forEach(function(btn) {
    btn.addEventListener('click', function() {
      mainImg.src = btn.dataset.src;
      thumbBtns.forEach(function(b) { b.classList.remove('is-active'); });
      btn.classList.add('is-active');
    });
  });

  // Lightbox with carousel + zoom + pan
  var lightbox    = document.getElementById('product-lightbox');
  var lightboxImg = document.getElementById('product-lightbox-img');
  var zoomTrigger = document.getElementById('product-zoom-trigger');
  var closeBtn    = document.getElementById('product-lightbox-close');
  var prevBtn     = document.getElementById('product-lightbox-prev');
  var nextBtn     = document.getElementById('product-lightbox-next');
  var counter     = document.getElementById('product-lightbox-counter');

  var images   = lightbox ? JSON.parse(lightbox.dataset.images || '[]') : [];
  var curIndex = 0;

  var scale = 1, isDragging = false, dragStartX = 0, dragStartY = 0, panX = 0, panY = 0;

  function applyTransform() {
    lightboxImg.style.transform = 'translate(' + panX + 'px,' + panY + 'px) scale(' + scale + ')';
  }
  function resetZoom() {
    scale = 1; panX = 0; panY = 0;
    lightboxImg.style.cursor = 'default';
    applyTransform();
  }
  function updateCounter() {
    if (counter) counter.textContent = (curIndex + 1) + ' / ' + images.length;
    if (prevBtn) prevBtn.style.display = images.length > 1 ? '' : 'none';
    if (nextBtn) nextBtn.style.display = images.length > 1 ? '' : 'none';
  }
  function goTo(index) {
    curIndex = (index + images.length) % images.length;
    lightboxImg.src = images[curIndex];
    resetZoom();
    updateCounter();
  }

  function openLightbox(startIndex) {
    curIndex = startIndex || 0;
    lightboxImg.src = images[curIndex];
    resetZoom();
    updateCounter();
    lightbox.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }
  function closeLightbox() {
    lightbox.classList.remove('is-open');
    document.body.style.overflow = '';
    setTimeout(resetZoom, 250);
  }

  // Open from main image — find its index
  if (zoomTrigger) {
    zoomTrigger.addEventListener('click', function() {
      var idx = images.indexOf(mainImg.src);
      openLightbox(idx >= 0 ? idx : 0);
    });
  }

  // Open from thumbs — pass index
  thumbBtns.forEach(function(btn, i) {
    btn.addEventListener('dblclick', function() { openLightbox(i); });
  });

  // Nav arrows
  if (prevBtn) prevBtn.addEventListener('click', function(e) { e.stopPropagation(); goTo(curIndex - 1); });
  if (nextBtn) nextBtn.addEventListener('click', function(e) { e.stopPropagation(); goTo(curIndex + 1); });

  if (closeBtn) closeBtn.addEventListener('click', closeLightbox);

  // Click backdrop to close
  if (lightbox) {
    lightbox.addEventListener('click', function(e) {
      if (e.target === lightbox) closeLightbox();
    });

    // Scroll to zoom
    lightbox.addEventListener('wheel', function(e) {
      e.preventDefault();
      var delta = e.deltaY > 0 ? -0.15 : 0.15;
      scale = Math.min(Math.max(scale + delta, 1), 5);
      lightboxImg.style.cursor = scale > 1 ? 'grab' : 'default';
      if (scale === 1) { panX = 0; panY = 0; }
      applyTransform();
    }, { passive: false });

    // Drag to pan
    lightbox.addEventListener('mousedown', function(e) {
      if (e.target === closeBtn || e.target === prevBtn || e.target === nextBtn || scale === 1) return;
      isDragging = true;
      dragStartX = e.clientX - panX;
      dragStartY = e.clientY - panY;
      lightboxImg.style.cursor = 'grabbing';
    });
    window.addEventListener('mousemove', function(e) {
      if (!isDragging) return;
      panX = e.clientX - dragStartX;
      panY = e.clientY - dragStartY;
      applyTransform();
    });
    window.addEventListener('mouseup', function() {
      if (!isDragging) return;
      isDragging = false;
      lightboxImg.style.cursor = scale > 1 ? 'grab' : 'default';
    });

    // Double-click to toggle zoom
    lightboxImg.addEventListener('dblclick', function() {
      if (scale > 1) { resetZoom(); } else { scale = 2.5; lightboxImg.style.cursor = 'grab'; applyTransform(); }
    });

    // Touch pinch-to-zoom + swipe to navigate
    var lastDist = 0, touchStartX = 0;
    lightbox.addEventListener('touchstart', function(e) {
      if (e.touches.length === 2) {
        lastDist = Math.hypot(e.touches[0].clientX - e.touches[1].clientX, e.touches[0].clientY - e.touches[1].clientY);
      } else if (e.touches.length === 1) {
        touchStartX = e.touches[0].clientX;
      }
    }, { passive: true });
    lightbox.addEventListener('touchmove', function(e) {
      if (e.touches.length === 2) {
        e.preventDefault();
        var dist = Math.hypot(e.touches[0].clientX - e.touches[1].clientX, e.touches[0].clientY - e.touches[1].clientY);
        scale = Math.min(Math.max(scale + (dist - lastDist) * 0.01, 1), 5);
        lastDist = dist;
        applyTransform();
      }
    }, { passive: false });
    lightbox.addEventListener('touchend', function(e) {
      if (scale === 1 && e.changedTouches.length === 1) {
        var dx = e.changedTouches[0].clientX - touchStartX;
        if (Math.abs(dx) > 50) goTo(dx < 0 ? curIndex + 1 : curIndex - 1);
      }
    }, { passive: true });
  }

  document.addEventListener('keydown', function(e) {
    if (!lightbox || !lightbox.classList.contains('is-open')) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowRight') goTo(curIndex + 1);
    if (e.key === 'ArrowLeft')  goTo(curIndex - 1);
    if (e.key === '=' || e.key === '+') { scale = Math.min(scale + 0.25, 5); applyTransform(); }
    if (e.key === '-') { scale = Math.max(scale - 0.25, 1); if(scale===1){panX=0;panY=0;} applyTransform(); }
  });

  // Size selector
  const sizeBtns = document.querySelectorAll('.size-btn');
  const sizeVal  = document.getElementById('sapient_size_val');
  const sizeMsg  = document.querySelector('.size-required-msg');
  sizeBtns.forEach(function(btn) {
    btn.addEventListener('click', function() {
      sizeBtns.forEach(function(b) { b.classList.remove('is-active'); });
      btn.classList.add('is-active');
      if (sizeVal) sizeVal.value = btn.dataset.value;
      if (sizeMsg) sizeMsg.style.display = 'none';
    });
  });

  // Color selector
  const colorBtns = document.querySelectorAll('.color-btn');
  const colorVal  = document.getElementById('sapient_color_val');
  const colorMsg  = document.querySelector('.color-required-msg');
  colorBtns.forEach(function(btn) {
    btn.addEventListener('click', function() {
      colorBtns.forEach(function(b) { b.classList.remove('is-active'); });
      btn.classList.add('is-active');
      if (colorVal) colorVal.value = btn.dataset.value;
      if (colorMsg) colorMsg.style.display = 'none';
    });
  });

  // Griptape selector
  const gripBtns = document.querySelectorAll('.griptape-btn');
  const gripVal  = document.getElementById('sapient_griptape_val');
  gripBtns.forEach(function(btn) {
    btn.addEventListener('click', function() {
      gripBtns.forEach(function(b) { b.classList.remove('is-active'); });
      btn.classList.add('is-active');
      if (gripVal) gripVal.value = btn.dataset.value;
    });
  });

  // Block add-to-cart if size not chosen
  const form = document.querySelector('form.cart');
  if (form) {
    form.addEventListener('submit', function(e) {
      let blocked = false;

      if (sizeBtns.length && (!sizeVal || !sizeVal.value)) {
        e.preventDefault();
        blocked = true;
        if (sizeMsg) sizeMsg.style.display = 'block';
        document.querySelector('.product-size-option')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }

      if (colorBtns.length && (!colorVal || !colorVal.value)) {
        e.preventDefault();
        blocked = true;
        if (colorMsg) colorMsg.style.display = 'block';
        if (!sizeBtns.length || (sizeVal && sizeVal.value)) {
          document.querySelector('.product-color-option')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      }
    });
  }
});
</script>

<?php endwhile; ?>

<?php get_footer(); ?>
