<?php

get_header();

while (have_posts()) :
    the_post();

    $plantation = codrufestival_plantation_data(get_the_ID());
    $photos = codrufestival_plantation_photos(get_the_ID());
    $facts = codrufestival_plantation_facts($plantation);
    $thumbnail_id = codrufestival_plantation_thumbnail_id(get_the_ID());
    $others = codrufestival_plantations('past', 3, array(get_the_ID()));
    ?>

    <div class="codru-plantations codru-plantation">
        <div class="container">
            <a class="codru-plantations__link codru-plantation__back" href="<?php echo esc_url(codrufestival_plantations_archive_url()); ?>">
                ← <?php echo esc_html(get_multilingual_text('Toate edițiile de plantare', 'All planting editions')); ?>
            </a>

            <header class="codru-plantation__header">
                <p class="codru-plantations__kicker">
                    <?php echo esc_html(implode(' · ', array_filter(array($plantation['period'], $plantation['location'])))); ?>
                </p>
                <h1 class="codru-plantation__title"><?php the_title(); ?></h1>
                <?php if ($plantation['headline']) : ?>
                    <p class="codru-plantation__headline"><?php echo esc_html($plantation['headline']); ?></p>
                <?php endif; ?>
                <?php if ($facts || ($plantation['is_upcoming'] && $plantation['signup_url'])) : ?>
                    <div class="codru-plantation__facts">
                        <?php foreach ($facts as $fact) : ?>
                            <span class="codru-plantation__fact"><?php echo esc_html($fact); ?></span>
                        <?php endforeach; ?>
                        <?php if ($plantation['is_upcoming'] && $plantation['signup_url']) : ?>
                            <a class="codru-general-button" href="<?php echo esc_url($plantation['signup_url']); ?>" target="_blank" rel="noopener">
                                <?php echo esc_html(get_multilingual_text('Înscrie-te', 'Sign up')); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </header>

            <?php if ($thumbnail_id) : ?>
                <figure class="codru-plantation__banner">
                    <?php echo wp_get_attachment_image($thumbnail_id, 'full', false, array(
                        'alt' => get_the_title(),
                        'sizes' => '(max-width: 1480px) 100vw, 1480px',
                    )); ?>
                </figure>
            <?php endif; ?>

            <div class="codru-plantation__content"><?php the_content(); ?></div>

            <?php if ($photos) : ?>
                <section class="codru-plantation__photos" id="fotografii" aria-labelledby="codru-plantation-photos-title">
                    <h2 class="codru-plantations__section-title" id="codru-plantation-photos-title">
                        <?php echo esc_html(get_multilingual_text('Fotografii', 'Photos')); ?>
                    </h2>
                    <?php codrufestival_react_island('FestivalGallery', array(
                        'images' => array_map(static fn(array $photo): array => array(
                            'src' => $photo['src'],
                            'full' => $photo['full'],
                            'alt' => $photo['alt'],
                        ), $photos),
                    )); ?>
                </section>
            <?php endif; ?>

            <?php if ($others) : ?>
                <section class="codru-plantations__editions" aria-labelledby="codru-plantation-others-title">
                    <h2 class="codru-plantations__section-title" id="codru-plantation-others-title">
                        <?php echo esc_html(get_multilingual_text('Alte ediții', 'More editions')); ?>
                    </h2>
                    <div class="codru-plantations__grid">
                        <?php foreach ($others as $other) : ?>
                            <?php get_template_part('template-parts/plantations/card', null, array('plantation' => $other)); ?>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </div>

    <?php
endwhile;

get_footer();
