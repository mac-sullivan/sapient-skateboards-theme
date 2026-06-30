<?php
/**
 * Cart Page — Sapient custom template
 * Column order: thumbnail | name | price | quantity | subtotal | remove
 */
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );
?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
  <?php do_action( 'woocommerce_before_cart_table' ); ?>

  <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
    <thead>
      <tr>
        <th class="product-thumbnail"><span class="screen-reader-text">Image</span></th>
        <th scope="col" class="product-name">Product</th>
        <th scope="col" class="product-price">Price</th>
        <th scope="col" class="product-quantity">Qty</th>
        <th scope="col" class="product-subtotal">Subtotal</th>
        <th class="product-remove"><span class="screen-reader-text">Remove</span></th>
      </tr>
    </thead>
    <tbody>
      <?php do_action( 'woocommerce_before_cart_contents' ); ?>

      <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
        $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
        $product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );

        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
          $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
      ?>
        <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>" data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>">

          <!-- Image -->
          <td class="product-thumbnail">
            <?php
            $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'woocommerce_thumbnail' ), $cart_item, $cart_item_key );
            if ( ! $product_permalink ) {
              echo $thumbnail;
            } else {
              printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
            }
            ?>
          </td>

          <!-- Name -->
          <td scope="row" role="rowheader" class="product-name" data-title="Product">
            <?php
            if ( ! $product_permalink ) {
              echo wp_kses_post( $product_name );
            } else {
              echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name',
                sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ),
                $cart_item, $cart_item_key
              ) );
            }
            do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );
            echo wc_get_formatted_cart_item_data( $cart_item );
            if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
              echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>';
            }
            ?>
          </td>

          <!-- Price -->
          <td class="product-price" data-title="Price">
            <?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
          </td>

          <!-- Quantity -->
          <td class="product-quantity" data-title="Qty">
            <?php
            if ( $_product->is_sold_individually() ) {
              $min_qty = $max_qty = 1;
            } else {
              $min_qty = 0;
              $max_qty = $_product->get_max_purchase_quantity();
            }
            $product_quantity = woocommerce_quantity_input( [
              'input_name'   => "cart[{$cart_item_key}][qty]",
              'input_value'  => $cart_item['quantity'],
              'max_value'    => $max_qty,
              'min_value'    => $min_qty,
              'product_name' => $product_name,
            ], $_product, false );
            echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
            ?>
          </td>

          <!-- Subtotal -->
          <td class="product-subtotal" data-title="Subtotal">
            <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
          </td>

          <!-- Remove — far right, black square with × -->
          <td class="product-remove">
            <?php echo apply_filters( 'woocommerce_cart_item_remove_link',
              sprintf(
                '<a role="button" href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg"><line x1="1" y1="1" x2="9" y2="9" stroke="white" stroke-width="1.5" stroke-linecap="square"/><line x1="9" y1="1" x2="1" y2="9" stroke="white" stroke-width="1.5" stroke-linecap="square"/></svg></a>',
                esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
                esc_attr( $product_id ),
                esc_attr( $_product->get_sku() )
              ),
              $cart_item_key
            ); ?>
          </td>

        </tr>
      <?php endif; endforeach; ?>

      <?php do_action( 'woocommerce_cart_contents' ); ?>

      <tr>
        <td colspan="6" class="actions">
          <div class="cart-actions-inner">
            <?php if ( wc_coupons_enabled() ) : ?>
              <div class="coupon">
                <label for="coupon_code" class="screen-reader-text">Coupon:</label>
                <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="Coupon code">
                <button type="submit" class="button" name="apply_coupon" value="Apply coupon">Apply</button>
                <?php do_action( 'woocommerce_cart_coupon' ); ?>
              </div>
            <?php endif; ?>

            <?php // Update Cart button hidden — quantities auto-update via AJAX ?>
            <button type="submit" class="button cart-update-btn" name="update_cart" value="Update cart" style="display:none">Update cart</button>

            <?php do_action( 'woocommerce_cart_actions' ); ?>
            <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
          </div>
        </td>
      </tr>

      <?php do_action( 'woocommerce_after_cart_contents' ); ?>
    </tbody>
  </table>

  <?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>

<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

<div class="cart-collaterals">
  <?php do_action( 'woocommerce_cart_collaterals' ); ?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
