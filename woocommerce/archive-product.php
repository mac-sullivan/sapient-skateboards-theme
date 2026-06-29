<?php
/**
 * WooCommerce Shop / Category Archive — Minimal image-only grid with category filters.
 */
defined( 'ABSPATH' ) || exit;

get_header( sapient_get_active_header() );

// Build query args
$query_args = [
  'post_type'      => 'product',
  'post_status'    => 'publish',
  'posts_per_page' => -1,
  'orderby'        => 'menu_order',
  'order'          => 'ASC',
];

if ( is_product_category() ) {
  $term = get_queried_object();
  $query_args['tax_query'] = [ [
    'taxonomy' => 'product_cat',
    'field'    => 'term_id',
    'terms'    => $term->term_id,
  ] ];
} elseif ( is_product_tag() ) {
  $term = get_queried_object();
  $query_args['tax_query'] = [ [
    'taxonomy' => 'product_tag',
    'field'    => 'term_id',
    'terms'    => $term->term_id,
  ] ];
}

$products = new WP_Query( $query_args );

// (Category nav removed — handled by main navigation dropdown)
?>
<?php if ( is_shop() ) : ?>
<div class="blog-archive-header">
  <div class="container">
    <?php get_template_part( 'template-parts/breadcrumbs' ); ?>
    <span class="blog-eyebrow section-eyebrow">Sapient Skateboards</span>
    <h1 class="blog-archive-title">Shop All</h1>
  </div>
</div>
<?php elseif ( is_product_category() ) :
  $term = get_queried_object();
?>
<div class="blog-archive-header">
  <div class="container">
    <?php get_template_part( 'template-parts/breadcrumbs' ); ?>
    <span class="blog-eyebrow section-eyebrow">— Shop</span>
    <h1 class="blog-archive-title"><?php echo esc_html( $term->name ); ?></h1>
  </div>
</div>
<?php endif; ?>

<main id="main-content" class="shop-main">
  <div class="container">



    <?php
    // Split products into boards and apparel
    $boards = [];
    $apparel = [];
    if ( $products->have_posts() ) :
      while ( $products->have_posts() ) : $products->the_post();
        if ( has_term( array( 'softgoods', 'apparel' ), 'product_cat', get_the_ID() ) ) {
          $apparel[] = get_the_ID();
        } else {
          $boards[] = get_the_ID();
        }
      endwhile;
      wp_reset_postdata();
    endif;
    ?>

    <?php if ( ! empty( $boards ) ) : ?>
    <div class="shop-img-grid">
      <?php foreach ( $boards as $pid ) : setup_postdata( $GLOBALS['post'] =& get_post( $pid ) ); ?>
        <a href="<?php the_permalink(); ?>" class="shop-img-link" data-cats="boards">
          <?php if ( has_post_thumbnail() ) : ?>
            <?php
              $thumb_id = get_post_thumbnail_id();
              $img_url  = wp_get_attachment_image_url( $thumb_id, 'full' );
            ?>
            <img src="<?php echo esc_url( $img_url ); ?>" class="shop-board-img" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy" decoding="async">
          <?php else : ?>
            <div class="shop-img-placeholder"><span>Photo Coming Soon</span></div>
          <?php endif; ?>
          <div class="shop-product-info">
            <span class="shop-product-name"><?php the_title(); ?></span>
            <span class="shop-product-price"><?php echo wc_get_product( get_the_ID() )->get_price_html(); ?></span>
          </div>
        </a>
      <?php endforeach; wp_reset_postdata(); ?>
    </div>
    <?php endif; ?>

    <?php if ( ! empty( $apparel ) ) : ?>
    <div class="shop-img-grid shop-img-grid--apparel">
      <?php foreach ( $apparel as $pid ) : setup_postdata( $GLOBALS['post'] =& get_post( $pid ) ); ?>
        <a href="<?php the_permalink(); ?>" class="shop-img-link shop-img-link--non-board" data-cats="apparel">
          <?php if ( has_post_thumbnail() ) : ?>
            <?php
              $thumb_id = get_post_thumbnail_id();
              $img_url  = wp_get_attachment_image_url( $thumb_id, 'full' );
            ?>
            <img src="<?php echo esc_url( $img_url ); ?>" class="shop-board-img" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy" decoding="async">
          <?php else : ?>
            <div class="shop-img-placeholder"><span>Photo Coming Soon</span></div>
          <?php endif; ?>
          <div class="shop-product-info">
            <span class="shop-product-name"><?php the_title(); ?></span>
            <span class="shop-product-price"><?php echo wc_get_product( get_the_ID() )->get_price_html(); ?></span>
          </div>
        </a>
      <?php endforeach; wp_reset_postdata(); ?>
    </div>
    <?php endif; ?>

    <?php if ( empty( $boards ) && empty( $apparel ) ) : ?>
      <p class="shop-empty">No products found.</p>
    <?php endif; ?>

  </div>
</main>



<?php get_footer(); ?>
