<?php

get_header();
?>

<?php
// Check if we're dealing with the activitati post type
if (get_post_type() === 'activitati') {
    // Use the custom taxonomy for activitati post type
    $terms = wp_get_post_terms(get_the_ID(), 'activitati_category');
    $category = !empty($terms) ? $terms[0]->slug : '';
} else {
    // Use standard categories for other post types
    $categories = get_the_category();
    $category = !empty($categories) ? $categories[0]->slug : '';
}
?>

<div class="container-fluid singlePostContainer">
    <div class="container p-0">
        <h1><?php echo get_the_title(); ?></h1>
    </div>
</div>
<div class="container singlePostContent">

    <div class="singlePostTopContainer">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12">
                <img class="singlePostMainImg" src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
            </div>
        </div>
    </div>

    <div class="singlePostInnerContent paddingBottom">
        <?php the_content(); ?>
    </div>
</div>
<
    <div class="container postNewsContainer mb-5 sectionPadding">
    <?php if ($category == "povestea-codru"): ?>
        <h2 class="sectionTitle"><?php echo get_field('codru_story_title', 'options'); ?></h2>
    <?php elseif ($category == "apeluri-artisti"): ?>
        <h2 class="sectionTitle">APELURI</h2>
    <?php else: ?>
        <h2 class="sectionTitle"><?php echo get_field('news_title', 'options'); ?></h2>
    <?php endif; ?>
    <div class="swiper single-related-swiper">
        <div class="swiper-wrapper">
            <?php
            // Set up query arguments based on post type
            if (get_post_type() === 'activitati') {
                $args = array(
                    'posts_per_page'    => 3,
                    'orderby'           => 'post_date',
                    'post_type'         => 'activitati',
                    'exclude'           => array(get_the_id())
                );
            } else {
                $args = array(
                    'posts_per_page'    => 3,
                    'orderby'           => 'post_date',
                    'category_name'     => $category,
                    'exclude'           => array(get_the_id())
                );
            }
            $postslist = get_posts($args);
            foreach ($postslist as $post) : {
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                    $read_more = get_field('news_read_more', 'options');
                    echo "<div class='swiper-slide'>
        <a href='$post->guid' class='homepageNewsLink'>
        <div class='homepageNewsImage text-center'><img src='$image[0]' alt=''></div>
        <div class='homepageNewsTitle'><h3>$post->post_title</h3><span><img src='/wp-content/themes/Divi-child/images/right-chevron.png' />$read_more</span></div>
        </a>
        </div>";
                }
            endforeach;
            ?>
        </div>
    </div>
    <div class='swiper-pagination'></div>

    <div class='swiper-button-next'></div>
    <div class='swiper-button-prev'></div>

    <!-- Initialize Swiper -->
    <script>
        var swiper = new Swiper(".single-related-swiper", {
            slidesPerView: 1,
            spaceBetween: 30,
            // Responsive breakpoints
            breakpoints: {
                767: {
                    slidesPerView: 2,
                    spaceBetweenSlides: 30,
                },
                991: {
                    slidesPerView: 3,
                    spaceBetweenSlides: 30,
                },
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    </script>
    </div>


    <?php
    get_footer();
    ?>