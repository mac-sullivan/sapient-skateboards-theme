<?php
/**
 * Custom quantity input — overrides WooCommerce default
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if ( $max_value && $min_value === $max_value ) : ?>
  <div class="quantity hidden">
    <input type="hidden" id="<?php echo esc_attr( $input_id ); ?>"
           name="<?php echo esc_attr( $input_name ); ?>"
           value="<?php echo esc_attr( $min_value ); ?>"
           class="qty">
  </div>
<?php else : ?>
  <div class="quantity-wrap">
    <span class="product-option-label">Quantity</span>
    <div class="quantity sapient-qty">
    <button type="button" class="qty-btn qty-minus" aria-label="Decrease quantity">
      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square"><line x1="5" y1="12" x2="19" y2="12"/></svg>
    </button>
    <input
      type="number"
      id="<?php echo esc_attr( $input_id ); ?>"
      class="<?php echo esc_attr( join( ' ', (array) $classes ) ); ?>"
      name="<?php echo esc_attr( $input_name ); ?>"
      value="<?php echo esc_attr( $input_value ); ?>"
      aria-label="<?php esc_attr_e( 'Product quantity', 'woocommerce' ); ?>"
      size="4"
      min="<?php echo esc_attr( $min_value ); ?>"
      max="<?php echo esc_attr( $max_value ? $max_value : '' ); ?>"
      step="<?php echo esc_attr( $step ); ?>"
      placeholder="<?php echo esc_attr( $placeholder ); ?>"
      inputmode="numeric"
      autocomplete="<?php echo esc_attr( isset( $autocomplete ) ? $autocomplete : 'on' ); ?>"
    />
    <button type="button" class="qty-btn qty-plus" aria-label="Increase quantity">
      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    </button>
  </div>
  </div>
<?php endif; ?>
