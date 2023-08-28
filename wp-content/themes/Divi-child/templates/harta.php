<?php /*  Template Name: Map  */ ?>
<?php get_header('codru2023live'); ?>

<div class="container termsPage pt-5 pb-5">
    <h1 class="pt-5 pb-4 text-center" style="font-weight: 600;"><?php echo get_the_title(); ?></h1>
    <div class="newsContainer row">
    <script src='https://unpkg.com/panzoom@9.4.0/dist/panzoom.min.js'></script>
    <script>
    
    jQuery(document).ready(function(){
        jQuery("#map img").removeAttr("srcset");
        // just grab a DOM element
            var element = document.querySelector('#map');

            // And pass it to panzoom
            panzoom(element, {
            initialZoom: 1
        });
    });
    </script>
    <div id="map">
        <img src="<?php echo get_field('festival_map') ?>">
    </div>
    </div>
</div>


<?php get_footer('codru2023live'); ?>
