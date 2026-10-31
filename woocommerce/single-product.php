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
          <button class="product-zoom-trigger" id="product-zoom-trigger" aria-label="Zoom image" aria-pressed="false">
            <img
              src="<?php echo esc_url( wp_get_attachment_image_url($all_images[0], 'full') ); ?>"
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

        <?php if ( count($all_images) > 1 ) : ?>
          <div class="product-gallery-thumbs">
            <?php foreach ($all_images as $i => $img_id) : ?>
              <button class="product-thumb-btn <?php echo $i === 0 ? 'is-active' : ''; ?>"
                data-src="<?php echo esc_url( wp_get_attachment_image_url($img_id, 'full') ); ?>"
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

      <div class="product-single-price"><?php echo $price_html; ?></div>

      <?php if ($short_desc) : ?>
        <div class="product-single-short-desc"><?php echo wp_kses_post($short_desc); ?></div>
      <?php endif; ?>

      <?php if ($description) : ?>
        <div class="product-single-description">
          <?php echo ($description); ?>
        </div>
      <?php endif; ?>

      <?php
      $stock_qty = $product->get_stock_quantity();
      $is_backorder = ( $stock_qty !== null && $stock_qty <= 0 );
      ?>

      <?php if ($in_stock || $is_backorder) : ?>

        <?php if ( $is_backorder ) : ?>
        <div class="product-made-to-order">
          <span class="product-made-to-order-badge">Made to Order</span>
          <?php if ( has_term( array( 'softgoods', 'apparel' ), 'product_cat', get_the_ID() ) ) : ?>
            <p class="product-made-to-order-text">This item is currently made to order. Please allow approximately 1.5 weeks before your order ships.</p>
          <?php else : ?>
            <p class="product-made-to-order-text">This model is currently built to order. Every Sapient deck is pressed, shaped, and finished in our Chicago workshop. Please allow approximately 2–3 weeks for production before your order ships.</p>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- ── Size selector ──────────────────────────── -->
        <?php
        $available_sizes = get_field( 'product_sizes', get_the_ID() );
        if ( ! empty( $available_sizes ) ) :
        ?>
        <div class="product-size-option">
          <span class="product-option-label">Size</span>
          <div class="product-size-choices">
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
        if ( ! $hide_griptape && ! has_term( array( 'softgoods', 'apparel' ), 'product_cat', get_the_ID() ) ) :
        ?>
        <div class="product-griptape-option">
          <span class="product-option-label">Griptape</span>
          <div class="product-griptape-choices">
            <button type="button" class="griptape-btn is-active" data-value="None">None</button>
            <button type="button" class="griptape-btn" data-value="Black (Applied) +$5">Black (Applied) <span>+$5</span></button>
            <button type="button" class="griptape-btn" data-value="Black (On Side) +$5">Black (On Side) <span>+$5</span></button>
          </div>
        </div>
        <?php endif; ?>

        <?php if ( ! $is_backorder ) : ?>
        <div class="product-availability-option">
          <span class="product-option-label">Availability</span>
          <?php if ( $stock_qty === 1 ) : ?>
            <span class="product-availability-value product-availability--low">Last one!</span>
          <?php elseif ( $stock_qty !== null && $stock_qty <= 3 ) : ?>
            <span class="product-availability-value product-availability--low">Only <?php echo $stock_qty; ?> left</span>
          <?php elseif ( $stock_qty !== null ) : ?>
            <span class="product-availability-value"><?php echo $stock_qty; ?> in stock</span>
          <?php else : ?>
            <span class="product-availability-value">In Stock</span>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php woocommerce_template_single_add_to_cart(); ?>
        <?php // Lightbox is rendered once in wp_footer via functions.php ?>
      <?php else : ?>
        <div class="product-made-to-order">
          <span class="product-made-to-order-badge">Made to Order</span>
          <?php if ( has_term( array( 'softgoods', 'apparel' ), 'product_cat', get_the_ID() ) ) : ?>
            <p class="product-made-to-order-text">This item is currently made to order. Please allow approximately 1.5 weeks before your order ships.</p>
          <?php else : ?>
            <p class="product-made-to-order-text">This model is currently built to order. Every Sapient deck is pressed, shaped, and finished in our Chicago workshop. Please allow approximately 2–3 weeks for production before your order ships.</p>
          <?php endif; ?>
        </div>
        <?php woocommerce_template_single_add_to_cart(); ?>
      <?php endif; ?>


    </div>

  </div>
  </div>
</main>


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

  // In-place zoom (mouse and touch): click/tap zooms into that spot; moving the
  // mouse or dragging a finger pans; click/tap again, Esc, or leaving exits.
  var zoomTrigger = document.getElementById('product-zoom-trigger');
  var galleryMain = zoomTrigger ? zoomTrigger.closest('.product-gallery-main') : null;
  var touchMoved  = false;

  function isZoomed() { return galleryMain.classList.contains('is-zoomed'); }
  function setZoomOrigin(x, y) {
    // Measure the unscaled frame — the image's own rect grows once it's zoomed
    var r = zoomTrigger.getBoundingClientRect();
    var px = Math.min(Math.max((x - r.left) / r.width, 0), 1) * 100;
    var py = Math.min(Math.max((y - r.top) / r.height, 0), 1) * 100;
    mainImg.style.transformOrigin = px + '% ' + py + '%';
  }
  function enterInlineZoom(e) {
    // Zoom to the photo's real resolution, kept between 2x and 3x
    var natural = mainImg.naturalWidth / zoomTrigger.getBoundingClientRect().width;
    var zoom = Math.min(Math.max(natural || 2, 2), 3);
    if (e && e.detail) { setZoomOrigin(e.clientX, e.clientY); } else { mainImg.style.transformOrigin = '50% 50%'; }
    mainImg.style.transform = 'scale(' + zoom + ')';
    galleryMain.classList.add('is-zoomed');
    zoomTrigger.setAttribute('aria-pressed', 'true');
    zoomTrigger.setAttribute('aria-label', 'Zoom out');
  }
  function exitInlineZoom() {
    if (!galleryMain || !isZoomed()) return;
    mainImg.style.transform = '';
    mainImg.style.transformOrigin = '';
    galleryMain.classList.remove('is-zoomed');
    zoomTrigger.setAttribute('aria-pressed', 'false');
    zoomTrigger.setAttribute('aria-label', 'Zoom image');
  }

  if (zoomTrigger) {
    zoomTrigger.addEventListener('click', function(e) {
      // A finger drag that ends on the image shouldn't count as a tap-to-exit
      if (touchMoved) { touchMoved = false; return; }
      isZoomed() ? exitInlineZoom() : enterInlineZoom(e);
    });
    zoomTrigger.addEventListener('mousemove', function(e) {
      if (isZoomed()) setZoomOrigin(e.clientX, e.clientY);
    });
    zoomTrigger.addEventListener('touchstart', function() { touchMoved = false; }, { passive: true });
    zoomTrigger.addEventListener('touchmove', function(e) {
      if (!isZoomed() || e.touches.length !== 1) return;
      e.preventDefault(); // pan the photo, not the page
      touchMoved = true;
      setZoomOrigin(e.touches[0].clientX, e.touches[0].clientY);
    }, { passive: false });
    zoomTrigger.addEventListener('mouseleave', exitInlineZoom);
    zoomTrigger.addEventListener('blur', exitInlineZoom);
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') exitInlineZoom();
    });
    thumbBtns.forEach(function(btn) { btn.addEventListener('click', exitInlineZoom); });
  }

  // Size selector — syncs visual buttons to the hidden input inside form.cart
  const sizeBtns = document.querySelectorAll('.size-btn');
  const sizeInput = document.getElementById('sapient_size_input');
  const sizeMsg   = document.querySelector('.size-required-msg');
  sizeBtns.forEach(function(btn) {
    btn.addEventListener('click', function() {
      sizeBtns.forEach(function(b) { b.classList.remove('is-active'); });
      btn.classList.add('is-active');
      if (sizeInput) sizeInput.value = btn.dataset.value;
      if (sizeMsg) sizeMsg.style.display = 'none';
    });
  });

  // Color selector
  const colorBtns = document.querySelectorAll('.color-btn');
  const colorInput = document.getElementById('sapient_color_input');
  const colorMsg   = document.querySelector('.color-required-msg');
  colorBtns.forEach(function(btn) {
    btn.addEventListener('click', function() {
      colorBtns.forEach(function(b) { b.classList.remove('is-active'); });
      btn.classList.add('is-active');
      if (colorInput) colorInput.value = btn.dataset.value;
      if (colorMsg) colorMsg.style.display = 'none';
    });
  });

  // Griptape selector
  const gripBtns  = document.querySelectorAll('.griptape-btn');
  const gripInput = document.getElementById('sapient_griptape_input');
  gripBtns.forEach(function(btn) {
    btn.addEventListener('click', function() {
      gripBtns.forEach(function(b) { b.classList.remove('is-active'); });
      btn.classList.add('is-active');
      if (gripInput) gripInput.value = btn.dataset.value;
    });
  });

  // Block add-to-cart if required option not chosen
  const form = document.querySelector('form.cart');
  if (form) {
    form.addEventListener('submit', function(e) {
      if (sizeBtns.length && (!sizeInput || !sizeInput.value)) {
        e.preventDefault();
        if (sizeMsg) sizeMsg.style.display = 'block';
        document.querySelector('.product-size-option')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
      }

      if (colorBtns.length && (!colorInput || !colorInput.value)) {
        e.preventDefault();
        if (colorMsg) colorMsg.style.display = 'block';
        document.querySelector('.product-color-option')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
      }
    });
  }
  // Waitlist form
  var waitlistForm = document.querySelector('.waitlist-form');
  if (waitlistForm) {
    waitlistForm.addEventListener('submit', function(e) {
      e.preventDefault();
      var email = waitlistForm.querySelector('.waitlist-email').value;
      var productId = waitlistForm.dataset.productId;
      var productName = waitlistForm.dataset.productName;

      // Store in localStorage (can be replaced with AJAX to backend/MailPoet)
      var waitlist = JSON.parse(localStorage.getItem('sapient_waitlist') || '[]');
      waitlist.push({ email: email, productId: productId, productName: productName, date: new Date().toISOString() });
      localStorage.setItem('sapient_waitlist', JSON.stringify(waitlist));

      waitlistForm.style.display = 'none';
      document.querySelector('.waitlist-success').style.display = 'block';
    });
  }
});
</script>

<?php endwhile; ?>

<?php get_footer(); ?>
