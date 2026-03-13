<?php
/**
 * WooCommerce Shop / Category Archive — Minimal image-only grid with category filters.
 */
defined( 'ABSPATH' ) || exit;

get_header();

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

// Collect categories used by these products
$used_cats       = [];
$has_uncategorized = false;
if ( $products->have_posts() ) {
  foreach ( $products->posts as $p ) {
    $cats     = get_the_terms( $p->ID, 'product_cat' );
    $non_unc  = [];
    if ( $cats && ! is_wp_error( $cats ) ) {
      foreach ( $cats as $cat ) {
        if ( $cat->slug !== 'uncategorized' ) {
          $non_unc[] = $cat;
          if ( ! isset( $used_cats[ $cat->term_id ] ) ) {
            $used_cats[ $cat->term_id ] = $cat;
          }
        }
      }
    }
    // Products with no real category → count as "Decks"
    if ( empty( $non_unc ) ) $has_uncategorized = true;
  }
}
usort( $used_cats, fn($a,$b) => $a->name <=> $b->name );

// Prepend a synthetic "Decks" entry if any uncategorized products exist
if ( $has_uncategorized ) {
  array_unshift( $used_cats, (object)[ 'term_id' => 0, 'slug' => 'boards', 'name' => 'Decks' ] );
}
?>
<?php if ( is_shop() ) : ?>
<div class="blog-archive-header">
  <div class="container">
    <span class="blog-eyebrow section-eyebrow">Sapient Skateboards</span>
    <h1 class="blog-archive-title">Shop.</h1>
  </div>
</div>
<?php elseif ( is_product_category() ) :
  $term = get_queried_object();
?>
<div class="blog-archive-header">
  <div class="container">
    <span class="blog-eyebrow section-eyebrow">— Shop</span>
    <h1 class="blog-archive-title"><?php echo esc_html( $term->name ); ?>.</h1>
  </div>
</div>
<?php endif; ?>

<main id="main-content" class="shop-main">
  <div class="container">

    <?php if ( is_shop() && ! empty( $used_cats ) ) : ?>
    <nav class="shop-cat-nav" role="tablist" aria-label="Filter by category">
      <button class="shop-cat-btn is-active" data-filter="all" role="tab" aria-selected="true">All</button>
      <?php foreach ( $used_cats as $cat ) : ?>
        <button
          class="shop-cat-btn"
          data-filter="<?php echo esc_attr( $cat->slug ); ?>"
          role="tab"
          aria-selected="false"
        ><?php echo esc_html( $cat->name ); ?></button>
      <?php endforeach; ?>
    </nav>
    <?php endif; ?>

    <div class="shop-img-grid">
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
          <a
            href="<?php the_permalink(); ?>"
            class="shop-img-link"
            data-cats="<?php echo esc_attr( $data_cats ); ?>"
          >
            <?php the_post_thumbnail( 'large', [ 'class' => 'shop-board-img', 'alt' => get_the_title() ] ); ?>
          </a>
        <?php endwhile; wp_reset_postdata(); ?>
      <?php else : ?>
        <p class="shop-empty">No products found.</p>
      <?php endif; ?>
    </div>

  </div>
</main>

<script>
(function() {
  var btns  = document.querySelectorAll('.shop-cat-btn');
  var items = document.querySelectorAll('.shop-img-link[data-cats]');
  if (!btns.length) return;

  function applyFilter(filter) {
    btns.forEach(function(b) {
      var active = b.dataset.filter === filter;
      b.classList.toggle('is-active', active);
      b.setAttribute('aria-selected', active ? 'true' : 'false');
    });
    items.forEach(function(item) {
      var cats = item.dataset.cats ? item.dataset.cats.split(' ') : [];
      item.style.display = (filter === 'all' || cats.indexOf(filter) !== -1) ? '' : 'none';
    });
  }

  btns.forEach(function(btn) {
    btn.addEventListener('click', function() {
      applyFilter(btn.dataset.filter);
    });
  });

  // Auto-activate from ?cat= URL param
  var params = new URLSearchParams(window.location.search);
  var catParam = params.get('cat');
  if (catParam) {
    applyFilter(catParam);
  }
})();
</script>

<?php get_footer(); ?>
