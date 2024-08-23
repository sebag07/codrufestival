<?php /*  Template Name: Video template  */ ?>
<?php get_header(); ?>

<?php 

$videoTemplateTtitle = get_field('video_template_title');
$videoTemplateLeftTitle = get_field('video_template_left_title');
$videoTemplateLeftText = get_field('video_template_left_text');
$videoTemplateEmbeddedSrc = get_field('video_template_youtube_embedded_src');

?>


<div class="container-fluid partnersPage pb-3 m-auto">
    <div class="row valuesContainer">
        <div class="col-lg-12 col-md-12 col-12 text-center pb-5 partnersTitle">
            <h2><?php echo $videoTemplateTtitle?></h2>
        </div>
        <div class="col-lg-6 col-md-10 col-11 videoTemplateTextContainer">
            <h2><?php echo $videoTemplateLeftTitle ?></h2>
            <?php echo $videoTemplateLeftText ?>
        </div>
        <div class="col-lg-6 col-md-10 col-11 video-container">
                <iframe
    src="<?php echo $videoTemplateEmbeddedSrc ?>">
                </iframe>        
        </div>
    </div>
</div>

<?php get_footer(); ?>