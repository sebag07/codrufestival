<?php
/**
 * One plantation edition: thumbnail, period · location, title and a short text.
 *
 * @var array $args {
 *     @type array $plantation From codrufestival_plantation_data().
 * }
 */

$plantation = $args['plantation'] ?? null;

if (!$plantation) {
    return;
}
?>

<article class="codru-plantation-card">
    <a class="codru-plantation-card__link" href="<?php echo esc_url($plantation['url']); ?>">
        <figure class="codru-plantation-card__media">
            <?php if ($plantation['image']) : ?>
                <img src="<?php echo esc_url($plantation['image']); ?>" alt="<?php echo esc_attr($plantation['image_alt']); ?>" loading="lazy">
            <?php else : ?>
                <?php echo codrufestival_plantation_image_placeholder(); ?>
            <?php endif; ?>
            <?php if ($plantation['location'] || $plantation['period']) : ?>
                <figcaption class="codru-plantation-card__badges">
                    <?php if ($plantation['location']) : ?>
                        <span class="codru-plantation-card__badge"><?php echo esc_html($plantation['location']); ?></span>
                    <?php endif; ?>
                    <?php if ($plantation['period']) : ?>
                        <span class="codru-plantation-card__badge"><?php echo esc_html($plantation['period']); ?></span>
                    <?php endif; ?>
                </figcaption>
            <?php endif; ?>
        </figure>
        <div class="codru-plantation-card__body">
            <h3 class="codru-plantation-card__title"><?php echo esc_html($plantation['title']); ?></h3>
            <?php if ($plantation['excerpt']) : ?>
                <p class="codru-plantation-card__text"><?php echo esc_html(wp_strip_all_tags($plantation['excerpt'])); ?></p>
            <?php endif; ?>
            <?php $facts = codrufestival_plantation_facts($plantation); ?>
            <?php if ($facts) : ?>
                <p class="codru-plantation-card__facts"><?php echo esc_html(implode(' · ', $facts)); ?></p>
            <?php endif; ?>
            <span class="codru-plantation-card__button"><?php echo esc_html(get_multilingual_text('Vezi ediția', 'See edition')); ?></span>
        </div>
    </a>
</article>
