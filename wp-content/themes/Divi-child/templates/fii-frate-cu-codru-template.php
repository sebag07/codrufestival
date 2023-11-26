<?php /*  Template Name: Fii frate cu CODRU  */ ?>
<?php get_header(); ?>


<div class="container-fluid partnersPage m-auto">
    <div class="row valuesContainer">
        <div class="col-lg-12 col-md-12 col-12 text-center partnersTitle">
            <h2><?php echo get_field('above_repeater_title')?></h2>
        </div>
        <div class="col-lg-6 col-md-8 col-12 pt-5">
            <?php if ( have_rows( 'value_repeater' ) ): ?>

            <?php while( have_rows( 'value_repeater' ) ) : the_row(); ?>

            <?php if( $repeaterImg = get_sub_field('value_number') ) { 

                        echo "<div class='valueRepeater'>
                            <p class='value-step'>$repeaterImg</p>";
                            echo "<div class='value-text'>";
                            the_sub_field('value_text');
                            echo "</div>";
                            echo "</div>";            
                    } ?>

            <?php endwhile; ?>

            <?php endif; ?>
        </div>
        <div class="col-lg-6 col-md-4 col-12 partnerImage">
            <img src="/wp-content/themes/Divi-child/images/rightimg.png" alt="">
        </div>
    </div>
</div>


<?php get_footer(); ?>