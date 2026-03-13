<?php
$heading = pt_field( 'mission_heading', 'Skateboards built for those who <em>never stop pushing</em>' );
$sub     = pt_field( 'mission_subtext', 'Every deck we build starts with purpose. Premium maple, precision pressed, designed to perform.' );
?>
<section class="mission">
  <div class="container">
    <h2><?php echo wp_kses( $heading, [ 'em' => [], 'strong' => [], 'br' => [] ] ); ?></h2>
    <p><?php echo esc_html( $sub ); ?></p>
  </div>
</section>
