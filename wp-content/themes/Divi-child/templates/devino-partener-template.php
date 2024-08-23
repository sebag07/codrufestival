<?php /*  Template Name: Devino partener  */ ?>
<?php get_header(); ?>


<?php
$post_id = get_the_ID(); // Get current post ID
?>

    <section id="devino-partener" style="overflow-x:hidden;" class="dark-background">
    <h2 class="sectionTitle"><?php echo get_the_title(); ?></h2>
        <div class="sectionPadding container homepage-info-section">
            <?php
            $count = 0;
            if (have_rows('content-image-repeater', $post_id)):
            while (have_rows('content-image-repeater', $post_id)) : the_row();
                if ($count % 2 == 0) {
                    $class_name = "even";
                    $col_order = "order-md-0 order-1";
                } else {
                    $class_name = "odd";
                    $col_order = "order-md-1 order-1";
                }
                $repeaterTitle = get_sub_field('title');
                $repeaterContent = get_sub_field('content');
                $repeaterButtonURL = get_sub_field('button_url');
                $repeaterButtonText = get_sub_field('button_text');
                $repeaterImage = get_sub_field('image');
                $imageBGColor = get_sub_field('image_background_hex');
                ?>
                <div class="row pt-5 pb-5 <?php echo $class_name ?>">
                    <div class="col-md-6 align-items-start <?php echo $col_order ?> justify-content-center d-flex flex-column homepage-info-container">
                        <h2 class="homepage-info-title mb-4"><?php echo $repeaterTitle ?></h2>
                        <span class="homepage-info-content mb-4"><?php echo $repeaterContent ?></span>
                        <a class="homepage-info-button codru-general-button" href="<?php echo $repeaterButtonURL ?>"
                           target="_blank"><?php echo $repeaterButtonText ?></a>
                    </div>
                    <div class="homepage-info-section-image-container col-md-6 my-md-auto p-relative z-1 mb-5">
                        <img class="homepage-info-section-image" src="<?php echo $repeaterImage ?>" alt="Lineup">
                        <div class="homepage-info-section-image-underlay"
                             style="background-color:<?php echo $imageBGColor ?>"></div>
                    </div>
                </div>
                <?php
                $count++;
            endwhile;
        endif;
            ?>
        </div>
    </section>


<?php get_footer(); ?>