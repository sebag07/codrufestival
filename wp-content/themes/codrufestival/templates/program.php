<?php /*  Template Name: Program  */ ?>
<?php get_header(); ?>

<div class="container programPage termsPage pt-5 pb-5">
    <h1 class="pt-5 pb-4 text-center"><?php echo get_the_title(); ?></h1>
    <div>
        <?php echo do_shortcode('[festivawl_calendar id="116"]'); ?>
    </div>
</div>

<?php get_footer(); ?>