<?php /*  Template Name: codrufestival-partners-template  */ ?>
<?php get_header('codru2023live'); ?>

<div class="container heroContainer secondPage sectionPaddingHero p-0 m-auto">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-12 pt-5">
            <h2><?php echo get_field('above_repeater_title')?></h2>
            <?php if ( have_rows( 'hero_repeater' ) ): ?>

                <?php while( have_rows( 'hero_repeater' ) ) : the_row(); ?>

                    <?php if( $repeaterImg = get_sub_field('hero_repeater_number_image') ) { 
                        
                        $repeaterText = get_sub_field('hero_repeater_text');

                        echo "<span class='textRepeater'>
                            <img src='$repeaterImg' alt=''>
                            <p>$repeaterText</p>
                            </span>";            
                    } ?>

                <?php endwhile; ?>

            <?php endif; ?>  
        </div>
        <div class="col-lg-6 col-md-6 col-12 heroRightImg">
                <img src="/wp-content/themes/divi-child/images/rightimg.png" alt="">
        </div>
    </div>
</div>


<script src="/wp-content/themes/Divi-child/js/fslightbox.js"></script>
<?php get_footer('codru2023live'); ?>