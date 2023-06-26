<?php

get_header('codru2023live');
?>


<div class="container-fluid singlePostContainer">
	<div class="container"><h1><?php echo get_the_title(); ?></h1></div>
</div>
<div class="container singlePostContent">

<div class="singlePostTopContainer">
	<img class="singlePostMainImg" src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
</div>

<div class="singlePostInnerContent paddingBottom">
	<?php echo get_the_content(); ?>
</div>
</div>

<div class="container otherNewsSection sectionPadding">
            <h2 class="text-center sectionTitle paddingBottom">Alte noutăți</h2>
	<div class="otherNewsPostsContainer">
        <?php
            $args = array('posts_per_page' => 3, 'orderby' => 'post_date', 'post__not_in' => array( get_the_ID() ));
            $postslist = get_posts($args);
            foreach ($postslist as $post) :
                $shortDescription = get_field('short_description');
                if (has_post_thumbnail($post->ID)) {
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                } else {
                    $image[0] = "";
                }
                echo "<div class='otherNewsPosts'>
            <a href='$post->guid' class='otherNewsLink'>
            <div class='otherNewsImage text-center'><img src='$image[0]' alt=''></div>
            <div class='otherNewsTitle'><h3>$post->post_title</h3></div>
            <div class='otherNewsContent'><p>$shortDescription</p></div>
            </a>
        </div>";
            endforeach;
            ?>
	</div>
</div>


<?php
get_footer('codru2023');
?>

