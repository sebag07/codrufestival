<?php /*  Template Name: Gallery  */ ?>
<?php get_header(); ?>

    <div class="container programPage termsPage pt-5 pb-5">
        <h1 class="pt-5 pb-4 text-center"><?php echo get_the_title(); ?></h1>
        <div>
            <div class="js" style="width: 100%; overflow:hidden;">
                <div class="cd-main-header">
                    <div class="filterContainer">
                        <button class="filterBtn selected" data-value="*">Toate zilele</button>
                        <?php
                                if (have_rows('days_gallery')) :
                                    while (have_rows('days_gallery')) : the_row();
                                        $day = get_sub_field('day');
                                        $gallery = get_sub_field('gallery');
                                        ?>
                                        <button class="filterBtn" data-value=".<?php echo esc_attr(strtolower(str_replace(' ', '-', $day))); ?>"><?php echo esc_html($day); ?></button>
                                    <?php
                                    endwhile;
                                endif;
                        ?>
                    </div>
                </div>
                <div class="galleryContainer grid">
                    <?php
                            if (have_rows('days_gallery')) :
                                while (have_rows('days_gallery')) : the_row();
                                    $day = get_sub_field('day');
                                    $gallery = get_sub_field('gallery');
                                    if ($gallery) :
                                        foreach ($gallery as $image) :
                                            ?>
                                            <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="element-item <?php echo esc_attr(strtolower(str_replace(' ', '-', $day))); ?>" data-category="<?php echo esc_attr(strtolower(str_replace(' ', '-', $day))); ?>">
                                        <?php
                                        endforeach;
                                    endif;
                                endwhile;
                            endif;
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.js"></script>

    <script>
        jQuery(document).ready(function($) {

            const grid = jQuery('.grid');

            grid.isotope({
                // options
                itemSelector: '.element-item',
                layoutMode: 'masonry',
                masonry: {
                    columnWidth: '.grid-sizer'
                    gutter: 10
                }
            });
            jQuery('.filterBtn').on('click', function() {
                jQuery('.filterBtn').removeClass('selected');
                jQuery(this).addClass('selected');

                var filterValue = $( this ).attr('data-value');
                grid.isotope({ filter: filterValue });
            });

        });
    </script>

<?php get_footer(); ?>