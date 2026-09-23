<?php
/**
 * Template Name: Gallery
 */

$gallery_images = array();
$photos = function_exists('get_field') ? get_field('festival_photos') : null;

if (is_array($photos)) {
    foreach ($photos as $photo) {
        if (!is_array($photo) || empty($photo['url'])) {
            continue;
        }

        $sizes = isset($photo['sizes']) && is_array($photo['sizes']) ? $photo['sizes'] : array();
        $thumb = '';

        foreach (array('large', '1536x1536', 'medium_large', 'medium') as $size) {
            if (!empty($sizes[$size]) && $sizes[$size] !== $photo['url']) {
                $thumb = $sizes[$size];
                break;
            }
        }

        $gallery_images[] = array(
            'src' => $thumb ? $thumb : $photo['url'],
            'full' => $photo['url'],
            'alt' => !empty($photo['alt']) ? $photo['alt'] : get_the_title(),
        );
    }
}

get_header();
?>

<div class="codru-gallery-page">
    <div class="codru-gallery-page__wrap">
        <header class="codru-gallery-page__intro">
            <h1 class="codru-gallery-page__title"><?php echo esc_html(get_the_title()); ?></h1>
        </header>

        <?php if ($gallery_images) : ?>
            <?php
            codrufestival_react_island('FestivalGallery', array(
                'images' => $gallery_images,
            ));
            ?>
        <?php else : ?>
            <p class="codru-gallery-page__empty">
                <?php echo esc_html(get_multilingual_text('Fotografiile vor apărea aici în curând.', 'Photos will appear here soon.')); ?>
            </p>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
