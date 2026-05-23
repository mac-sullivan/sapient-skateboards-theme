<?php
$heading = get_sub_field('events_heading') ?: 'Upcoming Events';
$count   = get_sub_field('events_count') ?: 3;
$events  = get_posts(['post_type'=>'pt_event','numberposts'=>$count,'orderby'=>'meta_value','meta_key'=>'event_date','order'=>'ASC']);
?>
<section class="section-events">
  <div class="container">
    <h2><?php echo esc_html($heading); ?></h2>
    <?php if ($events) : ?>
      <div class="events-grid">
        <?php foreach ($events as $event) :
          $date     = get_field('event_date', $event->ID);
          $location = get_field('event_location', $event->ID);
          $link     = get_permalink($event->ID);
        ?>
          <article class="event-card">
            <?php if (has_post_thumbnail($event->ID)) : ?>
              <div class="event-thumb">
                <img src="<?php echo get_the_post_thumbnail_url($event->ID, 'medium'); ?>" alt="<?php echo esc_attr($event->post_title); ?>">
              </div>
            <?php endif; ?>
            <div class="event-info">
              <?php if ($date) : ?><p class="event-date"><?php echo esc_html($date); ?></p><?php endif; ?>
              <h3><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($event->post_title); ?></a></h3>
              <?php if ($location) : ?><p class="event-location"><?php echo esc_html($location); ?></p><?php endif; ?>
              <p><?php echo esc_html(get_the_excerpt($event)); ?></p>
              <a href="<?php echo esc_url($link); ?>" class="btn btn-primary">Learn More</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <p class="events-placeholder">No upcoming events. Add events via WP Admin → Events.</p>
    <?php endif; ?>
  </div>
</section>
