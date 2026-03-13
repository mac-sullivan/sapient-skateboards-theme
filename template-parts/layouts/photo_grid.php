<?php
$heading = get_sub_field('grid_heading') ?: 'Follow the Journey';
$accent  = get_sub_field('grid_accent') ?: 'Journey';
$handle  = get_sub_field('grid_handle') ?: '';
$url     = get_sub_field('grid_url') ?: '';
$photos  = get_sub_field('grid_photos');

$heading_html = str_ireplace(
    $accent,
    '<em>' . esc_html($accent) . '</em>',
    esc_html($heading)
);
?>
<section class="section-photo-grid">
  <div class="container">

    <div class="photo-grid-header">
      <h2><?php echo $heading_html; ?></h2>
      <?php if ($handle) : ?>
        <a class="grid-handle" href="<?php echo esc_url($url ?: '#'); ?>" target="_blank" rel="noopener">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
          <?php echo esc_html($handle); ?>
        </a>
      <?php endif; ?>
    </div>

    <?php if ($photos) : ?>
      <!-- Swiper carousel -->
      <div class="swiper photo-swiper">
        <div class="swiper-wrapper">
          <?php foreach ($photos as $photo) :
            $img_url = is_array($photo) ? $photo['url'] : wp_get_attachment_url($photo);
            $img_alt = is_array($photo) ? ($photo['alt'] ?: 'Sapient Skateboards') : get_post_meta($photo, '_wp_attachment_image_alt', true);
          ?>
            <div class="swiper-slide">
              <div class="photo-grid-item">
                <?php if ($url) : ?><a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener"><?php endif; ?>
                  <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($img_alt ?: 'Sapient Skateboards'); ?>" loading="eager">
                <?php if ($url) : ?></a><?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <!-- Navigation -->
        <div class="swiper-button-prev photo-swiper-prev"></div>
        <div class="swiper-button-next photo-swiper-next"></div>
        <!-- Pagination -->
        <div class="swiper-pagination photo-swiper-pagination"></div>
      </div>

      <?php if ($url) : ?>
        <div class="photo-grid-cta">
          <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener" class="btn btn-primary">
            Follow on Instagram
          </a>
        </div>
      <?php endif; ?>

    <?php else : ?>
      <p class="grid-placeholder">Upload photos via the page editor → Photo Grid section</p>
    <?php endif; ?>

  </div>
</section>
