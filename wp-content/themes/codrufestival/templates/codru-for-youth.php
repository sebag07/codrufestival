<?php
/**
 * Template Name: CODRU For Youth
 */

$theme_uri = get_template_directory_uri();
$youth_dir = $theme_uri . '/images/youth';

// Edit copy here. Description fields accept HTML and stay hidden when empty.
$youth_intro = array(
    'kicker' => 'CODRU For Youth',
    'title' => get_the_title() ?: 'CODRU For Youth',
    'lede' => 'Proiect desfășurat din aprilie 2026, cu formări de voluntari și două instalații în Timișoara.',
    'funding' => 'CODRU for YOUth este un proiect finanțat de Primăria Municipiului Timișoara, prin Centrul de Proiecte.',
);

$youth_installations = array(
    array(
        'id' => 'take-a-seat',
        'label' => 'Take a Seat',
        'title' => 'Take a Seat',
        'kicker' => 'Open Call',
        'subtitle' => 'Atelier de creație și intervenție urbană',
        'meta' => array(
            '13 — 16 iulie 2026',
            'Artiști emergenți',
        ),
        'description' => '',
        'images' => array(
            array(
                'src' => $youth_dir . '/take-a-seat-open-call.jpg',
                'alt' => 'Take a Seat — Open Call pentru artiști emergenți',
            ),
        ),
    ),
    array(
        'id' => 'oxygen-booth',
        'label' => 'Oxygen Booth',
        'title' => 'Oxygen Booth',
        'kicker' => 'Open Call',
        'subtitle' => 'Atelier de creație multimedia',
        'meta' => array(
            '4 — 7 august 2026',
            'Vernisaj 8 august 2026, Piața Unirii',
            'Artiști emergenți',
        ),
        'description' => '',
        'images' => array(
            array(
                'src' => $youth_dir . '/oxygen-booth-open-call.jpg',
                'alt' => 'Oxygen Booth — Open Call pentru artiști emergenți',
            ),
            array(
                'src' => $youth_dir . '/oxygen-booth-open-call-story.jpg',
                'alt' => 'Oxygen Booth — Open Call, 4 — 7 august 2026',
            ),
            array(
                'src' => $youth_dir . '/oxygen-booth-open-call-banner.jpg',
                'alt' => 'Oxygen Booth — Atelier de creație multimedia',
            ),
            array(
                'src' => $youth_dir . '/oxygen-booth-vernisaj.jpg',
                'alt' => 'Oxygen Booth — Vernisaj, 8 august 2026, Piața Unirii',
            ),
            array(
                'src' => $youth_dir . '/oxygen-booth-vernisaj-wide.jpg',
                'alt' => 'Oxygen Booth — Vernisaj CODRU For Youth',
            ),
        ),
    ),
);

get_header();
?>

<div class="codru-youth-page">
    <div class="container">
        <header class="codru-youth-page__intro">
            <?php if (!empty($youth_intro['kicker'])) : ?>
                <p class="codru-youth-page__kicker"><?php echo esc_html($youth_intro['kicker']); ?></p>
            <?php endif; ?>
            <h1 class="codru-youth-page__title"><?php echo esc_html($youth_intro['title']); ?></h1>
            <?php if (!empty($youth_intro['lede'])) : ?>
                <p class="codru-youth-page__lede"><?php echo esc_html($youth_intro['lede']); ?></p>
            <?php endif; ?>
        </header>

        <?php
        codrufestival_react_island('YouthInstallations', array(
            'defaultId' => 'take-a-seat',
            'installations' => $youth_installations,
        ));
        ?>

        <?php if (!empty($youth_intro['funding'])) : ?>
            <p class="codru-youth-page__funding"><?php echo esc_html($youth_intro['funding']); ?></p>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
