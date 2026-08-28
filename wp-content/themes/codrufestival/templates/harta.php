<?php /*  Template Name: Map  */ ?>
<?php get_header(); ?>

<?php
$map_src = get_stylesheet_directory_uri() . '/images/map/codru-festival-map.webp';
?>

<div class="container termsPage pt-5 pb-5">
    <h1 class="pt-5 pb-4 text-center" style="font-weight: 600;"><?php echo get_the_title(); ?></h1>
    <div class="newsContainer row">
    <script src="https://unpkg.com/panzoom@9.4.0/dist/panzoom.min.js"></script>
    <script>
    jQuery(document).ready(function(){
        jQuery("#map img").removeAttr("srcset");
        var element = document.querySelector('#map');

        panzoom(element, {
            initialZoom: 1,
            minZoom: 1,
            maxZoom: 8
        });
    });
    </script>
    <div class="festival-map-viewport">
        <div id="map">
            <img src="<?php echo esc_url($map_src); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" draggable="false">
        </div>
    </div>
    </div>
</div>


<?php get_footer(); ?>
