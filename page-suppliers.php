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

        <table class="suppliers-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Store</th>
              <th>Address</th>
              <th>Website</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ( $suppliers as $i => $s ) :
              $name    = $s['supplier_name']    ?? '';
              $address = $s['supplier_address'] ?? '';
              $website = $s['supplier_website'] ?? '';
              $host    = $website ? preg_replace('/^https?:\/\/(www\.)?/', '', rtrim($website, '/')) : '—';
            ?>
            <tr>
              <td data-label="#"><?php echo str_pad( $i + 1, 2, '0', STR_PAD_LEFT ); ?></td>
              <td data-label="Store">
                <?php if ( $website ) : ?>
                  <a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $name ); ?></a>
                <?php else : ?>
                  <?php echo esc_html( $name ); ?>
                <?php endif; ?>
              </td>
              <td data-label="Address"><?php echo esc_html( $address ?: '—' ); ?></td>
              <td data-label="Website">
                <?php if ( $website ) : ?>
                  <a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener" class="supplier-url"><?php echo esc_html( $host ); ?></a>
                <?php else : ?>
                  <span class="supplier-none">—</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

      <?php else : ?>
        <p class="suppliers-empty">No suppliers listed yet.</p>
      <?php endif; ?>

    </div>
  </section>

</main>

<?php get_footer(); ?>
