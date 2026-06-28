<?php
/**
 * Breadcrumbs — auto-detects context including WooCommerce.
 * Uses product-breadcrumb styles (Engravers Gothic, uppercase).
 */

$home   = '<a href="' . esc_url( home_url( '/' ) ) . '">Home</a>';
$crumbs = [ $home ];

// ── WooCommerce ───────────────────────────────────────────────
if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {

    $shop_url  = get_permalink( wc_get_page_id( 'shop' ) );
    $shop_link = '<a href="' . esc_url( $shop_url ) . '">Shop</a>';

    if ( is_shop() ) {
        $crumbs[] = '<span class="breadcrumb-current">Shop</span>';

    } elseif ( is_product_category() ) {
        $term     = get_queried_object();
        $crumbs[] = $shop_link;
        $crumbs[] = '<span class="breadcrumb-current">' . esc_html( $term->name ) . '</span>';

    } elseif ( is_product() ) {
        $crumbs[] = $shop_link;
        $terms = get_the_terms( get_the_ID(), 'product_cat' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            $term = $terms[0];
            $crumbs[] = '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';
        }
        $crumbs[] = '<span class="breadcrumb-current">' . get_the_title() . '</span>';
    }

// ── Blog / archive ────────────────────────────────────────────
} elseif ( is_singular( 'post' ) ) {
    $crumbs[] = '<a href="' . esc_url( home_url( '/archive/' ) ) . '">Archive</a>';
    $crumbs[] = '<span class="breadcrumb-current">' . get_the_title() . '</span>';

} elseif ( is_singular( 'team' ) ) {
    $crumbs[] = '<a href="' . esc_url( home_url( '/about/crew/' ) ) . '">Crew</a>';
    $crumbs[] = '<span class="breadcrumb-current">' . get_the_title() . '</span>';

} elseif ( is_home() || is_archive() ) {
    $crumbs[] = '<span class="breadcrumb-current">Archive</span>';

// ── Standard pages ────────────────────────────────────────────
} elseif ( is_page() ) {
    $ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );
    foreach ( $ancestors as $id ) {
        $crumbs[] = '<a href="' . esc_url( get_permalink( $id ) ) . '">' . esc_html( get_the_title( $id ) ) . '</a>';
    }
    $crumbs[] = '<span class="breadcrumb-current">' . get_the_title() . '</span>';
}

if ( empty( $crumbs ) ) return;
?>
<nav class="product-breadcrumb" aria-label="Breadcrumb">
  <?php echo implode( '<span class="breadcrumb-sep">/</span>', $crumbs ); ?>
</nav>
