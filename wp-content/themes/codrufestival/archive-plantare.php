<?php
/**
 * All Plantare CODRU editions, served at /plantari/.
 */

$editions = codrufestival_plantations('past');

get_header();
?>

<div class="codru-plantations">
    <div class="container">
        <?php get_template_part('template-parts/plantations/intro'); ?>

        <section class="codru-plantations__editions" aria-labelledby="codru-plantations-editions-title">
            <h2 class="codru-plantations__section-title" id="codru-plantations-editions-title">
                <?php echo esc_html(get_multilingual_text('Edițiile de plantare', 'Planting editions')); ?>
            </h2>

            <?php if ($editions) : ?>
                <div class="codru-plantations__grid">
                    <?php foreach ($editions as $plantation) : ?>
                        <?php get_template_part('template-parts/plantations/card', null, array('plantation' => $plantation)); ?>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="codru-plantations__empty">
                    <?php echo esc_html(get_multilingual_text('Edițiile vor apărea aici în curând.', 'Editions will appear here soon.')); ?>
                </p>
            <?php endif; ?>
        </section>
    </div>
</div>

<?php get_footer(); ?>
