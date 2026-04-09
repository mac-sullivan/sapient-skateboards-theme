<?php get_header( sapient_get_active_header() ); the_post();
$thumb    = get_the_post_thumbnail_url( get_the_ID(), 'full' );
$cats     = get_the_category();
$cat_name = $cats ? esc_html($cats[0]->name) : '';
$cat_url  = $cats ? get_category_link($cats[0]->term_id) : '';
?>

<article class="single-post container">

  <!-- Hero -->
  <div class="blog-archive-header single-post-hero">
    <div class="container single-hero-content">
      <?php get_template_part( 'template-parts/breadcrumbs' ); ?>
      <?php if ($cat_name) : ?>
        <a href="<?php echo esc_url($cat_url); ?>" class="blog-eyebrow single-post-cat"><?php echo $cat_name; ?></a>
      <?php endif; ?>
      <h1 class="blog-archive-title single-post-title"><?php the_title(); ?></h1>
      <div class="single-post-meta">
        <span><?php echo get_the_date('F j, Y'); ?></span>
        <span class="meta-sep">·</span>
        <span><?php echo ceil(str_word_count(strip_tags(get_the_content())) / 200); ?> min read</span>
      </div>
    </div>
  </div>

  <!-- Content -->
  <div class="single-post-body">
    <div class="container">
      <div class="single-post-layout">

        <!-- Main content -->
        <div class="single-post-main">
          <div class="single-post-content">
            <?php the_content(); ?>
          </div>

          <!-- Back link + post nav -->
          <div class="single-post-footer">
            <a href="<?php echo get_post_type_archive_link('post') ?: home_url('/blog'); ?>" class="single-back-link">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
              Back to Blog
            </a>

            <?php
            $prev = get_previous_post();
            $next = get_next_post();
            if ($prev || $next) : ?>
              <div class="single-post-nav">
                <?php if ($next) : ?>
                  <a href="<?php echo get_permalink($next); ?>" class="post-nav-link post-nav-prev">
                    <span class="nav-direction">← Previous</span>
                    <span class="nav-title"><?php echo get_the_title($next); ?></span>
                  </a>
                <?php endif; ?>
                <?php if ($prev) : ?>
                  <a href="<?php echo get_permalink($prev); ?>" class="post-nav-link post-nav-next">
                    <span class="nav-direction">Next →</span>
                    <span class="nav-title"><?php echo get_the_title($prev); ?></span>
                  </a>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Sidebar -->
        <aside class="single-post-sidebar">

          <?php
          // Sidebar image — featured image or fallback skate photo
          $sidebar_img = has_post_thumbnail()
            ? get_the_post_thumbnail_url(null, 'large')
            : wp_get_attachment_url(80);
          ?>
          <?php if ($sidebar_img) : ?>
            <div class="sidebar-image">
              <img src="<?php echo esc_url($sidebar_img); ?>" loading="lazy" decoding="async" alt="">
            </div>
          <?php endif; ?>

          <!-- Post meta card -->
          <div class="sidebar-meta-card">
            <div class="sidebar-meta-row">
              <span class="sidebar-meta-label">Published</span>
              <span class="sidebar-meta-val"><?php echo get_the_date('M j, Y'); ?></span>
            </div>
            <div class="sidebar-meta-row">
              <span class="sidebar-meta-label">Read time</span>
              <span class="sidebar-meta-val"><?php echo ceil(str_word_count(strip_tags(get_the_content())) / 200); ?> min</span>
            </div>
            <?php if ($cat_name) : ?>
            <div class="sidebar-meta-row">
              <span class="sidebar-meta-label">Category</span>
              <span class="sidebar-meta-val"><?php echo $cat_name; ?></span>
            </div>
            <?php endif; ?>
          </div>

          <!-- Donate nudge -->
          <div class="sidebar-cta">
            <p>Every story here is made possible by donors like you.</p>
            <a href="/donate" class="sidebar-cta-btn">
              Support Us
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
          </div>

        </aside>
      </div>
    </div>
  </div>

</article>

<?php get_footer(); ?>
