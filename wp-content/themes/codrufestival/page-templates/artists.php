<?php /*  Template Name: Artisti  */ ?>
<?php get_header(); ?>

<?php
$artist_cards = [];

foreach (codrufestival_get_artists_from_json() as $artist) {
    $artist_card = codrufestival_build_artist_card_from_json($artist);

    if ($artist_card) {
        $artist_cards[] = $artist_card;
    }
}
?>

<div class="container-fluid single-page artists-page header-padding">
    <h1 class="text-center sectionTitle" style="font-weight: 600;"><?php echo esc_html(get_the_title()); ?></h1>
    <section>
        <div class="sectionPadding container">
            <?php if (function_exists('codrufestival_react_island')): ?>
                <?php
                codrufestival_react_island('ArtistExpandableCards', [
                    'artists' => $artist_cards,
                    'filters' => codrufestival_get_artist_performance_day_filters(),
                    'stageFilters' => codrufestival_get_artist_stage_filters(),
                    'showFilters' => true,
                    'showStageFilters' => true,
                    'eyebrow' => 'CODRU Festival',
                    'emptyText' => 'Artists will be announced soon.',
                    'filteredEmptyText' => get_multilingual_text(
                        'Nu există artiști pentru filtrele selectate.',
                        'No artists match the selected filters.',
                        'ro'
                    ),
                    'showDayLabels' => true,
                    'showStageLabels' => true,
                    'showPerformanceMeta' => false,
                ], [
                    'class' => 'codru-artists-page__artist-cards',
                ]);
                ?>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php get_footer(); ?>
