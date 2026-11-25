<?php
/**
 * Homepage "Plantare CODRU": the latest edition and the three before it.
 * It continues the "CODRU, festivalul care plantează" block above, which carries the intro and figures.
 */

// The latest edition is featured; the three before it are shown as cards.
$editions = codrufestival_plantations('past', 4);
$latest = array_shift($editions);
$latest_photos = $latest ? array_slice(codrufestival_plantation_photos($latest['id']), 0, 4) : array();
$latest_content = $latest ? apply_filters('the_content', (string) get_post_field('post_content', $latest['id'])) : '';
?>

<section id="plantare-codru" class="codru-plantations codru-plantations--home dark-background">
    <div class="container sectionPadding">
        <?php if ($latest) : ?>
            <article class="codru-plantations-latest">
                <div class="codru-plantations-latest__media">
                    <a class="codru-plantations-latest__cover" href="<?php echo esc_url($latest['url']); ?>">
                        <?php if ($latest['image']) : ?>
                            <img src="<?php echo esc_url($latest['image']); ?>" alt="<?php echo esc_attr($latest['image_alt']); ?>" loading="lazy">
                        <?php else : ?>
                            <?php echo codrufestival_plantation_image_placeholder(); ?>
                        <?php endif; ?>
                    </a>
                    <?php if (count($latest_photos) > 1) : ?>
                        <div class="codru-plantations-latest__thumbs">
                            <?php foreach (array_slice($latest_photos, 1, 3) as $photo) : ?>
                                <a href="<?php echo esc_url($latest['url'] . '#fotografii'); ?>">
                                    <?php echo wp_get_attachment_image($photo['id'], 'medium_large', false, array(
                                        'loading' => 'lazy',
                                        'alt' => $photo['alt'],
                                    )); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="codru-plantations-latest__copy">
                    <p class="codru-plantations__kicker">
                        <?php echo esc_html(implode(' · ', array_filter(array($latest['period'], $latest['location'])))); ?>
                    </p>
                    <h3 class="codru-plantations-latest__title">
                        <a href="<?php echo esc_url($latest['url']); ?>"><?php echo esc_html($latest['title']); ?></a>
                    </h3>
                    <?php if ($latest['headline']) : ?>
                        <p class="codru-plantations-latest__headline"><?php echo esc_html($latest['headline']); ?></p>
                    <?php endif; ?>
                    <div class="codru-plantations-latest__content"><?php echo wp_kses_post($latest_content); ?></div>
                    <a class="codru-general-button" href="<?php echo esc_url($latest['url'] . '#fotografii'); ?>">
                        <?php echo esc_html($latest['location']
                            ? sprintf(get_multilingual_text('Vezi fotografiile de la %s', 'See the photos from %s'), $latest['location'])
                            : get_multilingual_text('Vezi fotografiile', 'See the photos')); ?>
                    </a>
                </div>
            </article>
        <?php endif; ?>

        <?php if ($editions) : ?>
            <section class="codru-plantations__editions" aria-labelledby="codru-plantations-previous-title">
                <div class="codru-plantations__section-head">
                    <h3 class="codru-plantations__section-title" id="codru-plantations-previous-title">
                        <?php echo esc_html(get_multilingual_text('Ediții anterioare', 'Previous editions')); ?>
                    </h3>
                    <a class="codru-plantations__link" href="<?php echo esc_url(codrufestival_plantations_archive_url()); ?>">
                        <?php echo esc_html(get_multilingual_text('Toate edițiile', 'All editions')); ?> →
                    </a>
                </div>
                <div class="codru-plantations__grid">
                    <?php foreach ($editions as $plantation) : ?>
                        <?php get_template_part('template-parts/plantations/card', null, array('plantation' => $plantation)); ?>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    </div>
</section>
