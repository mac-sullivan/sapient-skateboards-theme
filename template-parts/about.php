<?php
$heading = pt_field( 'about_heading', 'About Us' );
$content = pt_field( 'about_content', '<p>Sapient Skateboards is a premium skateboard brand built in Phoenix, AZ. We believe skateboarding is more than a sport — it\'s a craft, a culture, and a way of life. Every board we build is a statement.</p><p>Started in a Phoenix garage with a press and a pile of maple sheets, Sapient has grown into a brand trusted by riders who refuse to settle for mass-produced gear. Boards built with intention.</p>' );
?>
<section class="about">
  <div class="container">
    <h2><?php echo esc_html( $heading ); ?></h2>
    <div class="about-content">
      <?php echo wp_kses_post( $content ); ?>
    </div>
  </div>
</section>
