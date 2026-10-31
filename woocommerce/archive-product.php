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
    // Split products: featured (new drops) first, then apparel, then boards
    $featured = [];
    $boards = [];
    $apparel = [];
    if ( $products->have_posts() ) :
      while ( $products->have_posts() ) : $products->the_post();
        if ( wc_get_product( get_the_ID() )->is_featured() ) {
          $featured[] = get_the_ID();
        } elseif ( has_term( array( 'softgoods', 'apparel' ), 'product_cat', get_the_ID() ) ) {
          $apparel[] = get_the_ID();
        } else {
          $boards[] = get_the_ID();
        }
      endwhile;
      wp_reset_postdata();
    endif;

    $render_card = function ( $pid ) {
      $product  = wc_get_product( $pid );
      $is_board = ! has_term( array( 'softgoods', 'apparel' ), 'product_cat', $pid );
      $img      = has_post_thumbnail( $pid ) ? wp_get_attachment_image_src( get_post_thumbnail_id( $pid ), 'full' ) : false;
      $img_url  = $img ? $img[0] : '';
      // Portrait photos are already enlarged by the square crop — CSS skips the extra crop-in
      $classes  = 'shop-img-link' . ( $is_board ? '' : ' shop-img-link--non-board' ) . ( $img && $img[2] > $img[1] * 1.1 ? ' shop-img-link--portrait' : '' );
      ?>
        <a href="<?php echo esc_url( get_permalink( $pid ) ); ?>" class="<?php echo esc_attr( $classes ); ?>" data-cats="<?php echo $is_board ? 'boards' : 'apparel'; ?>">
          <?php if ( $img_url ) : ?>
            <img src="<?php echo esc_url( $img_url ); ?>" class="shop-board-img" alt="<?php echo esc_attr( get_the_title( $pid ) ); ?>" loading="lazy" decoding="async">
          <?php else : ?>
            <div class="shop-img-placeholder"><span>Photo Coming Soon</span></div>
          <?php endif; ?>
          <div class="shop-product-info">
            <span class="shop-product-name"><?php echo esc_html( get_the_title( $pid ) ); ?></span>
            <span class="shop-product-price"><?php echo $product->get_price_html(); ?></span>
          </div>
        </a>
      <?php
    };
    ?>

    <?php
    // One continuous grid (featured → apparel → boards) so rows always fill —
    // separate grids left a hole whenever a group had an odd count on mobile.
    $ordered = array_merge( $featured, $apparel, $boards );
    if ( ! empty( $ordered ) ) : ?>
    <div class="shop-img-grid">
      <?php foreach ( $ordered as $pid ) { $render_card( $pid ); } ?>
    </div>
    <?php endif; ?>

    <?php if ( empty( $ordered ) ) : ?>
      <p class="shop-empty">No products found.</p>
    <?php endif; ?>

  </div>
</main>



<?php get_footer(); ?>
