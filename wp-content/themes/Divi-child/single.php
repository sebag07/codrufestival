<?php

get_header('codru2023live');
?>


<div class="container-fluid singlePostContainer">
    <div class="container p-0">
        <h1><?php echo get_the_title(); ?></h1>
    </div>
</div>
<div class="container singlePostContent">

    <div class="singlePostTopContainer">
        <img class="singlePostMainImg" src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
    </div>

    <div class="singlePostInnerContent paddingBottom">
        <?php the_content(); ?>
    </div>
</div>

<div class="container newsContainer postNewsContainer sectionPadding">
    <h2 class="sectionTitle">NEWS</h2>
    <div class="newsContainer row">
        <?php
            $args = array(
                'posts_per_page'    => 3, 
                'orderby'           => 'post_date',
                'category_name' => 'noutati',
                'exclude'           => array(get_the_id()));
            $postslist = get_posts($args);
            foreach ($postslist as $post) : {
              $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                echo "<div class='homepageNews col-lg-4 col-md-6 col-12'>
            <a href='$post->guid' class='homepageNewsLink'>
            <div class='homepageNewsImage text-center'><img src='$image[0]' alt=''></div>
            <div class='homepageNewsTitle'><h3>$post->post_title</h3><span><img src='/wp-content/themes/Divi-child/images/right-chevron.png' />READ MORE</span></div>
            </a>
        </div>";
            }
            endforeach;
            ?>

    </div>
</div>


<?php
get_footer('codru2023live');
?>