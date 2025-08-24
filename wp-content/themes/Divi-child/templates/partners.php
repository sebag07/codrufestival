<?php /*  Template Name: Parteneri  */ ?>
<?php get_header(); ?>

<div class="container-fluid partners termsPage pt-5 pb-5">
    <div class="container">
        <!-- <h1 class="pt-5 pb-4 text-center" style="font-weight: 600;"><?php echo get_the_title(); ?></h1> -->
        <div class="sectionTitle pt-5 pb-4 text-center">
            <h1><?php echo get_multilingual_text('Parteneri', 'Partners', 'en'); ?></h1>
        </div>

        <!-- Partner Level 1 -->
        <div class="partnersContainer">
            <div class="partnersLevel1">
                <?php
                if (have_rows('partners_level_1', 'options')):
                    while (have_rows('partners_level_1', 'options')) : the_row();
                        $partner_name = get_sub_field('partner_name');
                        $partner_image = get_sub_field('partner_image');
                ?>
                        <img src="<?php echo $partner_image; ?>" alt="<?php echo $partner_name; ?>">
                <?php
                    endwhile;
                endif;
                ?>
            </div>
        </div>

        <!-- Partner Level 2 -->
        <div class="partnersContainer">
            <div class="partnersLevel2">
                <?php
                if (have_rows('partners_level_2', 'options')):
                    while (have_rows('partners_level_2', 'options')) : the_row();
                        $partner_name = get_sub_field('partner_name');
                        $partner_image = get_sub_field('partner_image');
                ?>
                        <img src="<?php echo $partner_image; ?>" alt="<?php echo $partner_name; ?>">
                <?php
                    endwhile;
                endif;
                ?>
            </div>
        </div>

        <!-- Partner Level 3 -->
        <div class="partnersContainer">
            <div class="partnersLevel3">
                <?php
                if (have_rows('partners_level_3', 'options')):
                    while (have_rows('partners_level_3', 'options')) : the_row();
                        $partner_name = get_sub_field('partner_name');
                        $partner_image = get_sub_field('partner_image');
                ?>
                        <img src="<?php echo $partner_image; ?>" alt="<?php echo $partner_name; ?>">
                <?php
                    endwhile;
                endif;
                ?>
            </div>
        </div>

        <!-- Partner Level 4 -->
        <div class="partnersContainer">
            <div class="partnersLevel4">
                <?php
                if (have_rows('partners_level_4', 'options')):
                    while (have_rows('partners_level_4', 'options')) : the_row();
                        $partner_name = get_sub_field('partner_name');
                        $partner_image = get_sub_field('partner_image');
                ?>
                        <img src="<?php echo $partner_image; ?>" alt="<?php echo $partner_name; ?>">
                <?php
                    endwhile;
                endif;
                ?>
            </div>
        </div>

        <!-- Partner Level 5 -->
        <div class="partnersContainer">
            <div class="partnersLevel5">
                <?php
                if (have_rows('partners_level_5', 'options')):
                    while (have_rows('partners_level_5', 'options')) : the_row();
                        $partner_name = get_sub_field('partner_name');
                        $partner_image = get_sub_field('partner_image');
                ?>
                        <img src="<?php echo $partner_image; ?>" alt="<?php echo $partner_name; ?>">
                <?php
                    endwhile;
                endif;
                ?>
            </div>
        </div>

        <!-- Produced By -->
        <div class="partnersContainer">
            <div class="producedBy">
                <?php
                if (have_rows('produced_by', 'options')):
                    while (have_rows('produced_by', 'options')) : the_row();
                        $partner_name = get_sub_field('producer_name');
                        $partner_image = get_sub_field('producer_image');
                        $partner_link = get_sub_field('producer_link');
                ?>
                        <?php if ($partner_link): ?>
                            <a href="<?php echo $partner_link; ?>" target="_blank">
                                <img src="<?php echo $partner_image; ?>" alt="<?php echo $partner_name; ?>">
                            </a>
                        <?php else: ?>
                            <img src="<?php echo $partner_image; ?>" alt="<?php echo $partner_name; ?>">
                        <?php endif; ?>
                <?php
                    endwhile;
                endif;
                ?>
            </div>
        </div>

        <!-- Partner Level 6 -->
        <div class="partnersContainer">
            <div class="partnersLevel6">
                <?php
                if (have_rows('partners_level_6', 'options')):
                    while (have_rows('partners_level_6', 'options')) : the_row();
                        $partner_name = get_sub_field('partner_name');
                        $partner_image = get_sub_field('partner_image');
                ?>
                        <img src="<?php echo $partner_image; ?>" alt="<?php echo $partner_name; ?>">
                <?php
                    endwhile;
                endif;
                ?>
            </div>
        </div>

        <!-- Partner Level 7 -->
        <div class="partnersContainer">
            <div class="partnersLevel7">
                <?php
                if (have_rows('partners_level_7', 'options')):
                    while (have_rows('partners_level_7', 'options')) : the_row();
                        $partner_name = get_sub_field('partner_name');
                        $partner_image = get_sub_field('partner_image');
                ?>
                        <img src="<?php echo $partner_image; ?>" alt="<?php echo $partner_name; ?>">
                <?php
                    endwhile;
                endif;
                ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>