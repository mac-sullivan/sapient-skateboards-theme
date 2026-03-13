<?php
$eyebrow = 'HOW IT IS MADE';
$steps = [];

if (have_rows('process_steps')) :
  while (have_rows('process_steps')) : the_row();
    $steps[] = [
      'number'      => get_sub_field('step_number') ?: '',
      'title'       => get_sub_field('step_title') ?: '',
      'description' => get_sub_field('step_description') ?: '',
    ];
  endwhile;
endif;

// Fallback hardcoded steps
if (empty($steps)) :
  $steps = [
    ['number' => '01', 'title' => 'SELECT THE MAPLE', 'description' => 'We source North American hard maple — the same species used in professional boards. Grain orientation matters. We choose every sheet.'],
    ['number' => '02', 'title' => 'PRESS + SHAPE', 'description' => 'Seven plies, cold-pressed in our Chicago shop. Custom molds give each model its concave, nose, and tail geometry.'],
    ['number' => '03', 'title' => 'FINISH + GRAPHIC', 'description' => 'Hand-sanded edges, heat-transfer graphics applied in-house. Every deck inspected before it ships.'],
  ];
endif;
?>
<section class="section-process-strip <?php echo pt_spacing_classes(); ?>">

  <div class="process-strip-top-rule" aria-hidden="true"></div>

  <div class="container">

    <span class="section-eyebrow process-eyebrow"><?php echo esc_html($eyebrow); ?></span>

    <div class="process-steps-grid">
      <?php foreach ($steps as $i => $step) : ?>
        <div class="process-step<?php echo $i > 0 ? ' process-step--border' : ''; ?>">
          <span class="process-step-number"><?php echo esc_html($step['number']); ?></span>
          <h3 class="process-step-title"><?php echo esc_html($step['title']); ?></h3>
          <p class="process-step-desc"><?php echo esc_html($step['description']); ?></p>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="process-strip-footer">
      <a href="<?php echo esc_url(home_url('/process')); ?>" class="process-link">SEE THE FULL PROCESS &rarr;</a>
    </div>

  </div><!-- .container -->

  <div class="process-strip-bottom-rule" aria-hidden="true"></div>

</section>
