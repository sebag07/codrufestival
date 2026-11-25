<?php
/**
 * The two Plantare CODRU figures: planting sessions held and trees planted.
 *
 * @var array $args {
 *     @type bool $compact Smaller figures side by side, for the homepage block.
 * }
 */

$compact = !empty($args['compact']);
?>

<dl class="codru-plantations-stats<?php echo $compact ? ' codru-plantations-stats--compact' : ''; ?>">
    <div class="codru-plantations-stats__item">
        <dt><?php echo esc_html(get_multilingual_text('sesiuni de plantare', 'planting sessions')); ?></dt>
        <dd><?php echo esc_html(codrufestival_plantations_format_number(codrufestival_plantations_count_past())); ?></dd>
    </div>
    <div class="codru-plantations-stats__item">
        <dt><?php echo esc_html(get_multilingual_text('arbori plantați, aproximativ', 'trees planted, approximately')); ?></dt>
        <dd>85.000</dd>
    </div>
</dl>
