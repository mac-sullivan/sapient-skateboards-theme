<?php
/**
 * WooCommerce fallback template.
 * Wraps all WooCommerce pages (shop, cart, checkout, account)
 * in the Sapient theme structure.
 */

get_header( sapient_get_active_header() );
?>
<main id="main-content">

  <?php if ( is_shop() || is_product_taxonomy() ) : ?>

    <!-- ── Shop Hero ──────────────────────────────────────────── -->
    <section class="shop-hero">
      <div class="container">
        <span class="section-eyebrow">Sapient Manufacturing Co.</span>
        <h1 class="shop-hero-heading">Shop</h1>
        <p class="shop-hero-sub">Handcrafted decks, apparel, and gear. Built in Chicago with intention.</p>
      </div>
    </section>

    <!-- ── Product Grid ───────────────────────────────────────── -->
    <section class="shop-products">
      <div class="container">

        <?php if ( woocommerce_product_loop() ) : ?>

          <div class="shop-toolbar">
            <?php woocommerce_result_count(); ?>
            <?php woocommerce_catalog_ordering(); ?>
          </div>

          <div class="shop-grid">
            <?php
            while ( have_posts() ) : the_post();
              global $product;
            ?>
              <a href="<?php the_permalink(); ?>" class="product-card">
                <div class="product-card-image">
                  <?php if ( has_post_thumbnail() ) : ?>
                    <?php the_post_thumbnail( 'medium', [ 'class' => 'product-img' ] ); ?>
                  <?php else : ?>
                    <div class="product-img-placeholder"></div>
                  <?php endif; ?>
                </div>
                <div class="product-card-info">
                  <h3 class="product-name"><?php the_title(); ?></h3>
                  <?php
                  $terms = get_the_terms( get_the_ID(), 'pa_color' );
                  if ( $terms && ! is_wp_error( $terms ) ) :
                  ?>
                    <span class="product-colorway"><?php echo esc_html( $terms[0]->name ); ?></span>
                  <?php endif; ?>
                  <span class="product-price"><?php echo $product->get_price_html(); ?></span>
                  <span class="product-add-btn">
                    <?php echo $product->is_in_stock() ? 'Add to Cart' : 'Sold Out'; ?>
                  </span>
                </div>
              </a>
            <?php endwhile; ?>
          </div>

          <?php woocommerce_pagination(); ?>

        <?php else : ?>

          <!-- Placeholder products -->
          <div class="shop-grid">
            <?php
            $placeholders = [
              [ 'name' => 'The Chicago 8.0',    'color' => 'Natural / Black',  'price' => '$70.00' ],
              [ 'name' => 'The Wicker 8.25',    'color' => 'Stain / White',    'price' => '$72.00' ],
              [ 'name' => 'The Pilsen 8.5',     'color' => 'Raw Maple',        'price' => '$75.00' ],
              [ 'name' => 'The Bridgeport 8.0', 'color' => 'Black / Red',      'price' => '$70.00' ],
              [ 'name' => 'The Loop Tee',       'color' => 'Bone / Charcoal',  'price' => '$38.00' ],
              [ 'name' => 'The Wabash Cap',     'color' => 'Olive',            'price' => '$32.00' ],
            ];
            foreach ( $placeholders as $p ) :
            ?>
              <div class="product-card product-card--placeholder">
                <div class="product-card-image">
                  <div class="product-img-placeholder"></div>
                </div>
                <div class="product-card-info">
                  <h3 class="product-name"><?php echo esc_html( $p['name'] ); ?></h3>
                  <span class="product-colorway"><?php echo esc_html( $p['color'] ); ?></span>
                  <span class="product-price"><?php echo esc_html( $p['price'] ); ?></span>
                  <span class="product-add-btn">Add to Cart</span>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

        <?php endif; ?>

      </div>
    </section>

  <?php elseif ( is_product() ) : ?>

    <!-- ── Single Product ─────────────────────────────────────── -->
    <?php while ( have_posts() ) : the_post();
      global $product;
      $title       = get_the_title();
      $price_html  = $product->get_price_html();
      $description = apply_filters( 'the_content', $product->get_description() );
      $short_desc  = apply_filters( 'the_content', $product->get_short_description() );
      $in_stock    = $product->is_in_stock();
      $sku         = $product->get_sku();
      $thumb_id    = get_post_thumbnail_id();
      $gallery_ids = $product->get_gallery_image_ids();
      $all_images  = $thumb_id ? array_merge([$thumb_id], $gallery_ids) : $gallery_ids;
    ?>
    <main id="main-content" class="product-single-page">
      <div class="container">
        <div class="product-single-inner">

          <!-- Image Panel -->
          <div class="product-single-gallery">
            <?php if ( ! empty($all_images) ) : ?>
              <div class="product-gallery-main">
                <img src="<?php echo esc_url( wp_get_attachment_image_url($all_images[0], 'large') ); ?>"
                     alt="<?php echo esc_attr($title); ?>"
                     class="product-gallery-main-img" id="product-main-img">
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

          <!-- Details Panel -->
          <div class="product-single-details">
            <?php woocommerce_breadcrumb( [
              'delimiter'   => ' <span class="bc-sep">/</span> ',
              'wrap_before' => '<nav class="product-breadcrumb" aria-label="Breadcrumb">',
              'wrap_after'  => '</nav>',
              'before'      => '<span class="bc-item">',
              'after'       => '</span>',
            ] ); ?>

            <h1 class="product-single-title"><?php echo esc_html($title); ?></h1>

            <?php if ($sku) : ?>
              <span class="product-single-sku">SKU: <?php echo esc_html($sku); ?></span>
            <?php endif; ?>

            <div class="product-single-price"><?php echo $price_html; ?></div>

            <?php if ($short_desc) : ?>
              <div class="product-single-short-desc wysiwyg"><?php echo $short_desc; ?></div>
            <?php endif; ?>

            <?php
            // Pull in all WooCommerce product attributes
            $attributes = $product->get_attributes();
            if ( ! empty($attributes) ) :
            ?>
              <div class="product-single-attributes">
                <?php foreach ( $attributes as $attribute ) :
                  $attr_name   = wc_attribute_label( $attribute->get_name() );
                  $attr_values = $attribute->is_taxonomy()
                    ? wp_get_post_terms( get_the_ID(), $attribute->get_name(), [ 'fields' => 'names' ] )
                    : $attribute->get_options();
                ?>
                  <div class="product-attr-row">
                    <span class="product-attr-label"><?php echo esc_html($attr_name); ?></span>
                    <span class="product-attr-value"><?php echo esc_html( implode(', ', (array) $attr_values) ); ?></span>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

            <!-- ── Size selector ──────────────────────── -->
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
            <?php if ( has_term( 'boards', 'product_cat', get_the_ID() ) ) : ?>
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

            <?php
            // Add to cart — handles simple, variable, grouped products
            woocommerce_template_single_add_to_cart();
            ?>

            <?php if ($description) : ?>
              <div class="product-single-description wysiwyg"><?php echo $description; ?></div>
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

  <?php elseif ( is_cart() || is_checkout() ) : ?>

    <!-- ── Cart / Checkout ────────────────────────────────────── -->
    <section class="wc-cart-page">
      <div class="container">
        <div class="cart-page-header">
          <h1 class="cart-page-title"><?php echo is_checkout() ? 'Checkout' : 'Cart'; ?></h1>
        </div>
        <?php woocommerce_content(); ?>
      </div>
    </section>

  <?php else : ?>

    <!-- ── Account, etc. ──────────────────────────────────────── -->
    <section class="wc-page-section">
      <div class="container">
        <?php while ( have_posts() ) : the_post(); ?>
          <h1 class="wc-page-heading"><?php the_title(); ?></h1>
          <div class="wc-page-content">
            <?php the_content(); ?>
          </div>
        <?php endwhile; ?>
      </div>
    </section>

  <?php endif; ?>

</main>
<?php get_footer(); ?>
