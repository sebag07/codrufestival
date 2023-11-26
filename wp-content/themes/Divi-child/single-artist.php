<?php

get_header();
?>


<div class="container-fluid singlePostContainer singleArtistContainer">
    <div class="container p-0">
        <div class="row sectionPadding">
            <div class="col-lg-8 col-md-8 col-12 artistImageContainer">
                <?php
                if( !empty(get_the_post_thumbnail()) ) {
                    echo '<img class="singlePostMainImg" src="' . get_the_post_thumbnail_url() . ' " alt="">';
                } else {
                  $thumbnailURL = get_template_directory_uri() . "-child/images/codru-2023.png";
                  echo '<img class="singlePostMainImg" src="' . $thumbnailURL . ' " alt="">';
                };
            ?>
                    <h1><?php echo get_the_title(); ?></h1>
            </div>
            <div class="col-lg-4 col-md-4 col-12 artistSocialsSpotify">
                <div class="spotifyIframeContainer">
                    <?php echo get_field('spotify_iframe')?>
                </div>
                    <span class="row pl-3 pr-3 m-0">
                    <?php if ( have_rows( 'social_repeater' ) ): ?>
            
                        <?php while( have_rows( 'social_repeater' ) ) : the_row(); ?>
            
                            <?php if( $socialImg = get_sub_field( 'social_img' ) ) { 
            
                                    $socialURL = get_sub_field( 'social_url' );
            
                                    echo "<a class='col-lg-2 col-md-2 col-3 artistSocials' href='$socialURL' target='_blank'><img src='$socialImg' /></a>";
                                    
                                        } ?>
            
                        <?php endwhile; ?>
            
            
                    <?php endif; ?>
                    </span>
            </div>
    </div>
</div>
<div class="container singlePostContent singleArtistContent">

    <div class="row">
        <div class="p-0 col-lg-12 col-md-12 col-12 artistText">
            <?php the_content(); ?>
        </div>
    </div>
</div>


</div>


<?php
get_footer();
?>
