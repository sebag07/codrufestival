<?php /*  Template Name: Apeluri Artisti  */ ?>
<?php get_header(); ?>

<div class="container apeluriArtisti termsPage pt-5 pb-5">
    <h1 class="pt-5 pb-4 text-center" style="font-weight: 600;"><?php echo get_the_title(); ?></h1>
    <div class="newsContainer row">
        <?php
            $args = array(
                'posts_per_page'    => 3, 
                'orderby'           => 'post_date',
                'order'             => 'ASC',
                'category_name'     => 'apeluri-artisti',
                'exclude'           => array(get_the_id()));
            $postslist = get_posts($args);
            foreach ($postslist as $post) : {
              $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
              $read_more = get_field('news_read_more', 'options');
                echo "<div class='homepageNews col-lg-4 col-md-6 col-12'>
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


<?php get_footer(); ?>
