<?php
/**
 * Template Name: Suppliers
 */
get_header( sapient_get_active_header() );
?>

<main id="main-content">

  <div class="blog-archive-header">
    <div class="container">
      <?php get_template_part( 'template-parts/breadcrumbs' ); ?>
      <span class="blog-eyebrow section-eyebrow">Where To Buy</span>
      <h1 class="blog-archive-title">Our Suppliers</h1>
    </div>
  </div>

  <section class="section-suppliers">
    <div class="container">

      <?php
      $suppliers = get_field( 'suppliers' );
      if ( $suppliers ) :
      ?>

        <div class="suppliers-list">
          <div class="suppliers-list-header">
            <span class="sl-col-no">#</span>
            <span class="sl-col-name">Store</span>
            <span class="sl-col-address">Address</span>
            <span class="sl-col-web">Website</span>
          </div>

          <?php foreach ( $suppliers as $i => $s ) :
            $name    = $s['supplier_name']    ?? '';
            $address = $s['supplier_address'] ?? '';
            $website = $s['supplier_website'] ?? '';
            $host    = $website ? preg_replace('/^https?:\/\/(www\.)?/', '', rtrim($website, '/')) : '';
            $tag     = $website ? 'a' : 'div';
            $attrs   = $website ? sprintf(' href="%s" target="_blank" rel="noopener"', esc_url($website)) : '';
          ?>
            <<?php echo $tag; ?> class="supplier-row<?php echo $website ? ' supplier-row--link' : ''; ?>"<?php echo $attrs; ?>>
              <span class="sl-col-no"><?php echo str_pad( $i + 1, 2, '0', STR_PAD_LEFT ); ?></span>
              <span class="sl-col-name"><?php echo esc_html( $name ); ?></span>
              <?php if ( $address ) : ?>
                <span class="sl-col-address"><?php echo esc_html( $address ); ?></span>
              <?php else : ?>
                <span class="sl-col-address sl-col-empty">—</span>
              <?php endif; ?>
              <span class="sl-col-web">
                <?php if ( $host ) : ?>
                  <span class="supplier-url"><?php echo esc_html( $host ); ?></span>
                <?php else : ?>
                  <span class="sl-col-empty">—</span>
                <?php endif; ?>
              </span>
              <?php if ( $website ) : ?>
                <span class="sl-arrow">↗</span>
              <?php endif; ?>
            </<?php echo $tag; ?>>
          <?php endforeach; ?>
        </div>

      <?php else : ?>
        <p class="suppliers-empty">No suppliers listed yet.</p>
      <?php endif; ?>

    </div>
  </section>

</main>

<?php get_footer(); ?>
