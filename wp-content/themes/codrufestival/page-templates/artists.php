<?php /*  Template Name: Artisti  */ ?>
<?php get_header(); ?>

<?php
$artist_levels = [
    'level1' => ['label' => 'Headliners'],
    'level2' => ['label' => 'Main Acts'],
    'level3' => ['label' => 'Supporting Acts'],
    'level4' => ['label' => 'Level 4'],
    'level5' => ['label' => 'Level 5'],
    'level6' => ['label' => 'Level 6'],
];

$artists_json_path = get_stylesheet_directory() . '/data/artists.json';
$artists = [];

if (file_exists($artists_json_path)) {
    $artists_json = file_get_contents($artists_json_path);
    $artists_payload = json_decode($artists_json, true);

    if (json_last_error() === JSON_ERROR_NONE && !empty($artists_payload['artists']) && is_array($artists_payload['artists'])) {
        $artists = $artists_payload['artists'];
    }
}

$artist_cards = [];
foreach ($artists as $artist) {
    if (empty($artist['name'])) {
        continue;
    }

    $level_key = $artist['level'] ?? 'level3';
    if (!isset($artist_levels[$level_key])) {
        $level_key = 'level3';
    }

    $spotify_id = $artist['spotify_id'] ?? '';
    $spotify_url = !empty($artist['spotify_url']) ? $artist['spotify_url'] : ($spotify_id ? "https://open.spotify.com/artist/{$spotify_id}" : '');
    $spotify_embed_url = !empty($artist['spotify_embed_url']) ? $artist['spotify_embed_url'] : ($spotify_id ? "https://open.spotify.com/embed/artist/{$spotify_id}?utm_source=generator" : '');
    $genres = isset($artist['genres']) && is_array($artist['genres']) ? $artist['genres'] : [];
    $socials = isset($artist['socials']) && is_array($artist['socials']) ? $artist['socials'] : [];
    if ($spotify_url && empty($socials['spotify'])) {
        $socials['spotify'] = $spotify_url;
    }

    $artist_cards[] = [
        'id' => $artist['id'] ?? sanitize_title($artist['name']),
        'title' => $artist['name'],
        'image' => $artist['image'] ?? '',
        'level' => $artist_levels[$level_key]['label'] ?? '',
        'details' => $artist['description'] ?? $artist['details'] ?? (!empty($genres) ? implode(', ', $genres) : ''),
        'link' => $spotify_url,
        'spotifyUrl' => $spotify_url,
        'spotifyEmbedUrl' => $spotify_embed_url,
        'socials' => $socials,
        'genres' => $genres,
        'followers' => $artist['followers'] ?? null,
        'popularity' => $artist['popularity'] ?? null,
    ];
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
                    'eyebrow' => 'CODRU Festival',
                    'emptyText' => 'Artists will be announced soon.',
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
