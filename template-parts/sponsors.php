<?php
$label    = pt_field( 'sponsors_label', 'Our Awesome Sponsors' );
$sponsors = get_posts( [ 'post_type' => 'pt_sponsor', 'numberposts' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ] );
?>
<section class="sponsors">
  <div class="container">
    <h3>
      <?php
      // Highlight the last word in purple
      $words = explode( ' ', $label );
      $last  = array_pop( $words );
      echo esc_html( implode( ' ', $words ) ) . ' <span>' . esc_html( $last ) . '</span>';
      ?>
    </h3>

    <div class="sponsors-logos">
      <?php if ( $sponsors ) :
        foreach ( $sponsors as $sponsor ) :
          $logo = get_the_post_thumbnail_url( $sponsor->ID, 'medium' );
          $url  = get_field( 'sponsor_url', $sponsor->ID );
          if ( $logo ) : ?>
            <?php if ( $url ) : ?>
              <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener">
            <?php endif; ?>
              <img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( $sponsor->post_title ); ?>">
            <?php if ( $url ) : ?>
              </a>
            <?php endif; ?>
          <?php endif;
        endforeach;
      else : ?>
        <!-- Placeholder logos until sponsors are added via WP Admin -->
        <span style="color:#aaa;font-size:0.9rem;">Sponsors appear here once added in WP Admin → Sponsors</span>
      <?php endif; ?>
    </div>
  </div>
</section>
