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
      <p class="suppliers-subheading">Support Your Local Skateshop</p>
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

  <section class="section-supplier-cta">
    <div class="container supplier-cta-inner">
      <h2 class="supplier-cta-title">Interested in becoming a Sapient supplier?</h2>
      <div class="supplier-cta-body">
        <p>We firmly believe in the essential role of brick-and-mortar skate shops as cultural hubs that cultivate and sustain the longevity of skateboarding's rich history and authentic future. In an economy that prioritizes scale and speed at the expense of craft and community, it is our responsibility as skateboarders to invest in the local institutions truly committed to the growth and development of our scene on the ground level.</p>
        <p>As an independent manufacturer, we understand there may be hesitations around product quality, scale, and consistency. If you are interested in carrying Sapient but would like to first experience the craftsmanship and feel of our boards, we are happy to offer a sample.</p>
        <p>To request a sample, please reach out via an official shop email to <a href="mailto:info@sapientskateboards.com">info@sapientskateboards.com</a> with your shipping address and preferred shape from our current lineup. We will send a board at no cost for evaluation.</p>
        <p>Thank you for your consideration. We look forward to the opportunity to work and skate together.</p>
      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>
