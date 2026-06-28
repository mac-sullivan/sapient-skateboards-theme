<?php
/**
 * Flex Layout: FAQ List
 * Pulls all published FAQ posts and renders as an accordion.
 */

$eyebrow = get_sub_field( 'eyebrow' ) ?: 'FAQ';
$heading = get_sub_field( 'heading' ) ?: 'Frequently Asked Questions';
$spacing = pt_spacing_classes();

$faqs = get_posts( [
    'post_type'      => 'faq',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
] );
?>

<section class="faq-section <?php echo esc_attr( $spacing ); ?>">
  <div class="container">

    <?php if ( ! empty( $faqs ) ) : ?>
    <div class="faq-list" role="list">
      <?php foreach ( $faqs as $i => $faq ) :
        $answer = get_field( 'faq_answer', $faq->ID );
        $item_id = 'faq-' . $faq->ID;
      ?>
      <div class="faq-item" role="listitem">
        <button
          class="faq-question"
          aria-expanded="false"
          aria-controls="<?php echo esc_attr( $item_id ); ?>"
          id="<?php echo esc_attr( $item_id . '-btn' ); ?>"
        >
          <span class="faq-question-text"><?php echo esc_html( $faq->post_title ); ?></span>
          <span class="faq-icon" aria-hidden="true">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
              <line class="faq-icon-h" x1="0" y1="9" x2="18" y2="9" stroke="currentColor" stroke-width="1.5"/>
              <line class="faq-icon-v" x1="9" y1="0" x2="9" y2="18" stroke="currentColor" stroke-width="1.5"/>
            </svg>
          </span>
        </button>
        <div
          class="faq-answer"
          id="<?php echo esc_attr( $item_id ); ?>"
          role="region"
          aria-labelledby="<?php echo esc_attr( $item_id . '-btn' ); ?>"
          hidden
        >
          <div class="faq-answer-inner">
            <?php echo wp_kses_post( $answer ); ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

  </div>
</section>

<script>
(function() {
  document.querySelectorAll('.faq-question').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var expanded = btn.getAttribute('aria-expanded') === 'true';
      var panel    = document.getElementById(btn.getAttribute('aria-controls'));

      // Close all others
      document.querySelectorAll('.faq-question').forEach(function(b) {
        b.setAttribute('aria-expanded', 'false');
        var p = document.getElementById(b.getAttribute('aria-controls'));
        if (p) p.hidden = true;
        b.closest('.faq-item').classList.remove('is-open');
      });

      // Toggle this one
      if (!expanded) {
        btn.setAttribute('aria-expanded', 'true');
        if (panel) panel.hidden = false;
        btn.closest('.faq-item').classList.add('is-open');
      }
    });
  });
})();
</script>
