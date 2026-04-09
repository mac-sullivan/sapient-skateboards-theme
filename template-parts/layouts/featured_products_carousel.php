<?php
$eyebrow  = get_sub_field('section_eyebrow') ?: 'FEATURED';
$heading  = get_sub_field('section_heading') ?: 'SHOP BOARDS';
$products = get_sub_field('featured_products');

if ( empty($products) ) {
    // Fallback: pull latest products
    $q = new WP_Query([
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => 9,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ]);
    $products = $q->posts;
    wp_reset_postdata();
}

if ( empty($products) ) return;
?>

<section class="section-featured-carousel <?php echo pt_spacing_classes(); ?>">
  <div class="container">

    <div class="featured-carousel-header">
      <?php if ($eyebrow) : ?>
        <span class="section-eyebrow"><?php echo esc_html($eyebrow); ?></span>
      <?php endif; ?>
      <div class="featured-carousel-header-row">
        <h2 class="featured-carousel-heading"><?php echo esc_html($heading); ?></h2>
        <a href="<?php echo esc_url(home_url('/shop')); ?>" class="featured-carousel-viewall">VIEW ALL &rarr;</a>
      </div>
    </div>

    <div class="swiper featured-swiper">
      <div class="swiper-wrapper">
        <?php foreach ($products as $p) :
          $post_id   = is_object($p) ? $p->ID : $p;
          $permalink = get_permalink($post_id);
          $title     = get_the_title($post_id);
          $thumb_id  = get_post_thumbnail_id($post_id);
          $thumb_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'large') : '';
        ?>
          <div class="swiper-slide">
            <a href="<?php echo esc_url($permalink); ?>" class="featured-product-card">
              <div class="featured-product-img-wrap">
                <?php if ($thumb_url) : ?>
                  <img src="<?php echo esc_url($thumb_url); ? loading="lazy" decoding="async">" alt="<?php echo esc_attr($title); ?>" class="featured-product-img">
                <?php else : ?>
                  <div class="featured-product-img-placeholder"></div>
                <?php endif; ?>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>

  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
  if (typeof Swiper === 'undefined') return;
  new Swiper('.featured-swiper', {
    slidesPerView: 1.2,
    spaceBetween: 16,
    loop: true,
    navigation: {
      nextEl: '.featured-swiper .swiper-button-next',
      prevEl: '.featured-swiper .swiper-button-prev',
    },
    breakpoints: {
      600:  { slidesPerView: 2.2, spaceBetween: 20 },
      1024: { slidesPerView: 3,   spaceBetween: 24 },
    }
  });
});
</script>
