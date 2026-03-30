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
          <img
            src="<?php echo esc_url( wp_get_attachment_image_url($all_images[0], 'large') ); ?>"
            alt="<?php echo esc_attr($title); ?>"
            class="product-gallery-main-img"
            id="product-main-img"
          >
        </div>
        <?php if ( count($all_images) > 1 ) : ?>
          <div class="product-gallery-thumbs">
            <?php foreach ($all_images as $i => $img_id) : ?>
              <button class="product-thumb-btn <?php echo $i === 0 ? 'is-active' : ''; ?>"
                data-src="<?php echo esc_url( wp_get_attachment_image_url($img_id, 'large') ); ?>"
                aria-label="View image <?php echo $i + 1; ?>">
                <img src="<?php echo esc_url( wp_get_attachment_image_url($img_id, 'thumbnail') ); ?>" alt="">
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
        // Prefer a non-uncategorized term
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
          <a href="<?php echo esc_url( home_url( '/shop/?cat=' . $main_cat->slug ) ); ?>"><?php echo esc_html( $main_cat->name ); ?></a>
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
            <?php foreach ( $available_sizes as $size ) : ?>
              <button type="button" class="size-btn" data-value="<?php echo esc_attr( $size ); ?>">
                <?php echo esc_html( $size ); ?>
              </button>
            <?php endforeach; ?>
          </div>
          <p class="size-required-msg" style="display:none;">Please select a size.</p>
        </div>
        <?php endif; ?>

        <!-- ── Griptape Option (Boards only) ──────── -->
        <?php if ( ! has_term( 'softgoods', 'product_cat', get_the_ID() ) ) : ?>
        <div class="product-griptape-option">
          <span class="product-option-label">Griptape</span>
          <div class="product-griptape-choices">
            <input type="hidden" name="sapient_griptape" id="sapient_griptape_val" value="No griptape">
            <button type="button" class="griptape-btn is-active" data-value="No griptape">None</button>
            <button type="button" class="griptape-btn" data-value="Black griptape (+$5)">Black <span>+$5</span></button>
            <button type="button" class="griptape-btn" data-value="Clear griptape (+$5)">Clear <span>+$5</span></button>
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

      <?php if ($description) : ?>
        <div class="product-single-description">
          <?php echo ($description); ?>
        </div>
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
  if (form && sizeBtns.length) {
    form.addEventListener('submit', function(e) {
      if (!sizeVal || !sizeVal.value) {
        e.preventDefault();
        if (sizeMsg) sizeMsg.style.display = 'block';
        document.querySelector('.product-size-option')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    });
  }
});
</script>

<?php endwhile; ?>

<?php get_footer(); ?>
