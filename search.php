<?php get_header( sapient_get_active_header() ); ?>

<section class="section-search-results">
  <div class="container">

    <div class="search-results-header">
      <h1 class="search-results-heading">
        <?php if ( have_posts() ) : ?>
          Search results for <span class="search-query">&ldquo;<?php echo esc_html( get_search_query() ); ?>&rdquo;</span>
        <?php else : ?>
          No results found
        <?php endif; ?>
      </h1>

      <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search-results-form">
        <input type="search" name="s" placeholder="Search again..." value="<?php echo get_search_query(); ?>">
        <button type="submit" aria-label="Search">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
          </svg>
        </button>
      </form>
    </div>

    <?php if ( have_posts() ) : ?>
      <div class="search-results-count">
        <?php
        global $wp_query;
        printf( '%d %s found', $wp_query->found_posts, $wp_query->found_posts === 1 ? 'result' : 'results' );
        ?>
      </div>

      <div class="search-results-list">
        <?php while ( have_posts() ) : the_post(); ?>
          <article class="search-result-item">
            <?php if ( has_post_thumbnail() ) : ?>
              <a href="<?php the_permalink(); ?>" class="search-result-thumb">
                <?php the_post_thumbnail( 'medium' ); ?>
              </a>
            <?php endif; ?>
            <div class="search-result-content">
              <span class="search-result-type"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?></span>
              <h2 class="search-result-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </h2>
              <?php if ( has_excerpt() || get_the_content() ) : ?>
                <p class="search-result-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 30 ); ?></p>
              <?php endif; ?>
              <a href="<?php the_permalink(); ?>" class="search-result-link">View &rarr;</a>
            </div>
          </article>
        <?php endwhile; ?>
      </div>

      <div class="search-pagination">
        <?php
        the_posts_pagination( [
          'mid_size'  => 2,
          'prev_text' => '&larr; Previous',
          'next_text' => 'Next &rarr;',
        ] );
        ?>
      </div>

    <?php else : ?>
      <div class="search-no-results">
        <p>Sorry, nothing matched your search for <strong>&ldquo;<?php echo esc_html( get_search_query() ); ?>&rdquo;</strong>. Try a different term or browse our pages below.</p>
        <div class="search-suggestions">
          <a href="<?php echo esc_url( home_url( '/shop' ) ); ?>">Shop</a>
          <a href="<?php echo esc_url( home_url( '/about' ) ); ?>">About</a>
          <a href="<?php echo esc_url( home_url( '/process' ) ); ?>">Process</a>
          <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>">Contact</a>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>

<?php get_footer(); ?>
