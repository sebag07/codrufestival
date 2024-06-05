<?php /*  Template Name: Parteneri  */ ?>
<?php get_header(); ?>

<div class="container-fluid partners termsPage pt-5 pb-5">
    <div class="container">
        <!-- <h1 class="pt-5 pb-4 text-center" style="font-weight: 600;"><?php echo get_the_title(); ?></h1> -->
        <div class="sectionTitle pt-5 pb-4 text-center">
            <h1>Partners</h1>
        </div>
        <div class="partnersContainer">
            <div class="partnersLevel1">
                <?php 

    if( have_rows('main_partners', 'options') ):
        while( have_rows('main_partners', 'options') ) : the_row();

            $partner_name = get_sub_field('main_partner_name');
            $partner_image = get_sub_field('main_partner_logo');

        ?>
                <img src="<?php echo $partner_image; ?>" alt="<?php echo $partner_name; ?>">
                <?php
        endwhile;
    else :
        // Do something...
    endif;
        ?>
            </div>
        </div>
        <div class="partnersContainer">
            <div class="partnersLevel2">
                <?php 

    if( have_rows('official_partners', 'options') ):
        while( have_rows('official_partners', 'options') ) : the_row();

            $partner_name = get_sub_field('official_partner_name');
            $partner_image = get_sub_field('official_partner_image');

        ?>
                <img src="<?php echo $partner_image; ?>" alt="<?php echo $partner_name; ?>">
                <?php
        endwhile;
    else :
        // Do something...
    endif;
        ?>
            </div>
        </div>

        <div class="partnersContainer">
            <div class="partnersLevel3">
                <?php 

    if( have_rows('supporters', 'options') ):
        while( have_rows('supporters', 'options') ) : the_row();

            $partner_name = get_sub_field('supporter_name');
            $partner_image = get_sub_field('supporter_image');

        ?>
                <img src="<?php echo $partner_image; ?>" alt="<?php echo $partner_name; ?>">
                <?php
        endwhile;
    else :
        // Do something...
    endif;
        ?>
            </div>
        </div>

        <div class="partnersContainer">
            <div class="partnersLevel4">
                <?php 

    if( have_rows('hospitality_partners', 'options') ):
        while( have_rows('hospitality_partners', 'options') ) : the_row();

            $partner_name = get_sub_field('hospitality_partner_name');
            $partner_image = get_sub_field('hospitality_partner_image');

        ?>
                <img src="<?php echo $partner_image; ?>" alt="<?php echo $partner_name; ?>">
                <?php
        endwhile;
    else :
        // Do something...
    endif;
        ?>
            </div>
        </div>

        <div class="partnersMediaContainer row m-0">
            <div class="kissFMPartner col-12">
                <?php 

            if( have_rows('horeca_partners', 'options') ):
                while( have_rows('horeca_partners', 'options') ) : the_row();

                    $partner_name = get_sub_field('horeca_partner_name');
                    $partner_image = get_sub_field('horeca_partner_image');

                ?>
                <img src="<?php echo $partner_image; ?>" alt="<?php echo $partner_name; ?>">
                <?php
                endwhile;
            else :
                // Do something...
            endif;
                ?>
            </div>
            <div class="partnersMedia col-12">
                <?php 

    if( have_rows('media_partners', 'options') ):
        while( have_rows('media_partners', 'options') ) : the_row();

            $partner_name = get_sub_field('media_partner_name');
            $partner_image = get_sub_field('media_partner_image');

        ?>
                <img src="<?php echo $partner_image; ?>" alt="<?php echo $partner_name; ?>">
                <?php
        endwhile;
    else :
        // Do something...
    endif;
        ?>
            </div>
        </div>

        <div class="partnersCofinanceContainer">
            <div class="partnersCofinance">
                <?php 

    if( have_rows('co-financed_by', 'options') ):
        while( have_rows('co-financed_by', 'options') ) : the_row();

            $partner_name = get_sub_field('co-financed_by_name');
            $partner_image = get_sub_field('co-financed_by_image');
            $partner_link = get_sub_field('co-financed_by_link');

        ?>
                <img src="<?php echo $partner_image; ?>" alt="<?php echo $partner_name; ?>">
                <?php
        endwhile;
    else :
        // Do something...
    endif;
        ?>
            </div>
        </div>

        <div class="partnersPartofContainer">
            <div class="partnersPartof">
                <?php 

    if( have_rows('produced_by', 'options') ):
        while( have_rows('produced_by', 'options') ) : the_row();

            $partner_name = get_sub_field('produced_by_name');
            $partner_image = get_sub_field('produced_by_image');
            $partner_link = get_sub_field('produced_by_link');

        ?>
                <img src="<?php echo $partner_image; ?>" alt="<?php echo $partner_name; ?>">
                <?php
        endwhile;
    else :
        // Do something...
    endif;
        ?>
            </div>
        </div>
    </div>
</div>


<?php get_footer(); ?>