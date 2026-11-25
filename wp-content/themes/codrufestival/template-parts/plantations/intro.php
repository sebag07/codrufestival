<?php
/**
 * Plantare CODRU introduction at the top of the plantations archive.
 * Same layout as the homepage "CODRU, festivalul care plantează" block: text and figures beside a photo.
 */

$cover_id = 0;
foreach (codrufestival_plantations('past') as $edition) {
    $cover_id = (int) get_post_thumbnail_id($edition['id']);
    if ($cover_id) {
        break;
    }
}
?>

<div class="row even codru-plantations-intro">
    <div class="col-md-6 order-md-0 order-1 d-flex flex-column justify-content-center align-items-start codru-plantations-intro__copy">
        <p class="codru-plantations__kicker">Plantare CODRU</p>
        <h1 class="codru-plantations-intro__title">
            <?php echo esc_html(get_multilingual_text('Un bilet cumpărat, un copac plantat.', 'One ticket bought, one tree planted.')); ?>
        </h1>
        <p class="codru-plantations-intro__lede">
            <?php echo esc_html(get_multilingual_text(
                'Pentru fiecare bilet cumpărat la CODRU Festival, plantăm un copac. Am început în 2019, în Pădurea Bistra. De atunci, am plantat împreună cu voluntari, familii, comunități locale și echipele companiilor partenere, în mai multe locuri din județul Timiș.',
                'For every ticket bought at CODRU Festival, we plant a tree. We started in 2019, in Bistra Forest. Since then, we have planted together with volunteers, families, local communities and teams from our partner companies, in several places across Timiș County.'
            )); ?>
        </p>
        <p class="codru-plantations-intro__body">
            <?php echo esc_html(get_multilingual_text(
                'La plantările CODRU poți veni cu familia, cu prietenii sau cu echipa de la lucru. Plantăm împreună, ascultăm muzică și petrecem o zi în natură, cu jocuri și activități pentru toate vârstele.',
                'Come to a CODRU planting with your family, your friends or your team from work. We plant together, listen to music and spend a day in nature, with games and activities for all ages.'
            )); ?>
        </p>
        <?php get_template_part('template-parts/plantations/stats', null, array('compact' => true)); ?>
    </div>

    <?php if ($cover_id) : ?>
        <div class="col-md-6 order-md-1 order-0 my-md-auto homepage-info-section-image-container codru-plantations-intro__media">
            <?php echo wp_get_attachment_image($cover_id, 'large', false, array(
                'class' => 'homepage-info-section-image codru-plantations-intro__image',
                'alt' => get_multilingual_text('Plantare CODRU', 'CODRU tree planting'),
            )); ?>
            <div class="homepage-info-section-image-underlay" style="background-color:#61d72f"></div>
        </div>
    <?php endif; ?>
</div>
