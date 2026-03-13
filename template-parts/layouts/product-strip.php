<?php
$eyebrow       = get_sub_field('section_eyebrow') ?: 'LATEST DROPS';
$heading       = get_sub_field('section_heading') ?: 'SHOP BOARDS';
$product_count = (int)(get_sub_field('product_count') ?: 4);
?>
<section class="section-product-strip <?php echo pt_spacing_classes(); ?>">
  <div class="container">

    <div class="product-strip-header">
      <div class="product-strip-header-left">
        <span class="section-eyebrow"><?php echo esc_html($eyebrow); ?></span>
        <h2 class="product-strip-heading"><?php echo esc_html($heading); ?></h2>
      </div>
      <a href="<?php echo esc_url(home_url('/shop')); ?>" class="product-strip-viewall">VIEW ALL &rarr;</a>
    </div>

    <hr class="section-rule">

    <div class="product-grid">
      <?php
      if (class_exists('WooCommerce')) :
        $args = [
          'post_type'      => 'product',
          'posts_per_page' => $product_count,
          'tax_query'      => [
            [
              'taxonomy' => 'product_cat',
              'field'    => 'slug',
              'terms'    => 'boards',
            ],
          ],
        ];
        $products = new WP_Query($args);
        if ($products->have_posts()) :
          while ($products->have_posts()) : $products->the_post();
            global $product;
      ?>
          <a href="<?php the_permalink(); ?>" class="product-card">
            <div class="product-card-image">
              <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium', ['class' => 'product-img']); ?>
              <?php else : ?>
                <div class="product-img-placeholder"></div>
              <?php endif; ?>
            </div>
            <div class="product-card-info">
              <h3 class="product-name"><?php the_title(); ?></h3>
              <?php
              $terms = get_the_terms(get_the_ID(), 'pa_color');
              if ($terms && !is_wp_error($terms)) :
              ?>
                <span class="product-colorway"><?php echo esc_html($terms[0]->name); ?></span>
              <?php endif; ?>
              <span class="product-price"><?php echo $product->get_price_html(); ?></span>
              <span class="product-add-btn">Add to Cart</span>
            </div>
          </a>
      <?php
          endwhile;
          wp_reset_postdata();
        else :
          // Fallback: placeholder cards
          for ($i = 0; $i < $product_count; $i++) :
      ?>
          <div class="product-card product-card--placeholder">
            <div class="product-card-image">
              <div class="product-img-placeholder"></div>
            </div>
            <div class="product-card-info">
              <h3 class="product-name">DECK MODEL <?php echo $i + 1; ?></h3>
              <span class="product-colorway">NATURAL / BLACK</span>
              <span class="product-price">$<?php echo 65 + ($i * 5); ?>.00</span>
              <span class="product-add-btn">Add to Cart</span>
            </div>
          </div>
      <?php
          endfor;
        endif;
      else :
        // No WooCommerce: placeholder cards
        $placeholders = [
          ['name' => 'THE CHICAGO 8.0', 'colorway' => 'NATURAL / BLACK', 'price' => '$70.00'],
          ['name' => 'THE WICKER 8.25', 'colorway' => 'STAIN / WHITE', 'price' => '$72.00'],
          ['name' => 'THE PILSEN 8.5', 'colorway' => 'RAW MAPLE', 'price' => '$75.00'],
          ['name' => 'THE BRIDGEPORT 8.0', 'colorway' => 'BLACK / RED', 'price' => '$70.00'],
        ];
        foreach (array_slice($placeholders, 0, $product_count) as $p) :
      ?>
          <div class="product-card product-card--placeholder">
            <div class="product-card-image">
              <div class="product-img-placeholder"></div>
            </div>
            <div class="product-card-info">
              <h3 class="product-name"><?php echo esc_html($p['name']); ?></h3>
              <span class="product-colorway"><?php echo esc_html($p['colorway']); ?></span>
              <span class="product-price"><?php echo esc_html($p['price']); ?></span>
              <span class="product-add-btn">Add to Cart</span>
            </div>
          </div>
      <?php
        endforeach;
      endif;
      ?>
    </div><!-- .product-grid -->

  </div><!-- .container -->
</section>
