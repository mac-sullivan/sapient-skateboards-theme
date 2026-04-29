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



    <div class="shop-img-grid<?php if ( is_product_category( 'softgoods' ) ) echo ' shop-img-grid--apparel'; ?>">
      <?php if ( $products->have_posts() ) : ?>
        <?php while ( $products->have_posts() ) : $products->the_post();
          // Get category slugs for this product
          $p_cats = get_the_terms( get_the_ID(), 'product_cat' );
          $cat_slugs = [];
          if ( $p_cats && ! is_wp_error( $p_cats ) ) {
            foreach ( $p_cats as $pc ) {
              if ( $pc->slug !== 'uncategorized' ) $cat_slugs[] = $pc->slug;
            }
          }
          // Products in Uncategorized only → treat as 'boards' for filtering
          if ( empty( $cat_slugs ) ) $cat_slugs[] = 'boards';
          $data_cats = implode( ' ', $cat_slugs );
        ?>
          <?php $is_board = has_term( 'skateboards', 'product_cat', get_the_ID() ); ?>
          <a
            href="<?php the_permalink(); ?>"
            class="shop-img-link<?php echo ! $is_board ? ' shop-img-link--non-board' : ''; ?>"
            data-cats="<?php echo esc_attr( $data_cats ); ?>"
          >
            <?php if ( has_post_thumbnail() ) : ?>
              <?php the_post_thumbnail( 'large', [ 'class' => 'shop-board-img', 'alt' => get_the_title() ] ); ?>
            <?php else : ?>
              <div class="shop-img-placeholder"><span>Photo Coming Soon</span></div>
            <?php endif; ?>
            <div class="shop-product-info">
              <span class="shop-product-name"><?php the_title(); ?></span>
              <?php $product = wc_get_product( get_the_ID() ); if ( $product ) : ?>
                <span class="shop-product-price"><?php echo $product->get_price_html(); ?></span>
              <?php endif; ?>
            </div>
          </a>
        <?php endwhile; wp_reset_postdata(); ?>
      <?php else : ?>
        <p class="shop-empty">No products found.</p>
      <?php endif; ?>
    </div>

  </div>
</main>



<?php get_footer(); ?>
