<?php /*  Template Name: Standard Text Template  */ ?>
<?php get_header(); ?>

<div class="container termsPage pt-5 pb-5">
    <h1 class="pt-5 pb-4 text-center"><?php echo get_the_title(); ?></h1>
    <div>
        <?php echo the_content(); ?>
    </div>
</div>

<?php get_footer(); ?>
