<?php /*  Template Name: FAQ  */ ?>
<?php get_header(); ?>
<style>
    html {
        scroll-padding-top: 100px;
    }
</style>
<div class="container-fluid single-page header-padding">

    <h1 id="faqTitle" class="text-center faqTitle" style="font-weight: 600;"><?php echo get_the_title(); ?></h1>
    <div class="container-fluid faq p-relative sectionPadding">
        <div class="container">
            <div class="row">
            <div class="sidebarContainer col-lg-4">
                <div class="sidebar"> 

                </div>
            </div>
            <div class="accordion col-lg-8" id="accordionExample">
                <?php
                $index = 1;

                if( have_rows('faq_repeater_parent', 'options') ):
                    while( have_rows('faq_repeater_parent', 'options') ) : the_row();
                
                        $parent_title = get_sub_field('faq_parent_title', 'options');

                        $questions = get_sub_field('faq_repeater', 'options');        
                        ?>
                        <span class="accordionTitleContainer">
                            <h2 class="accordionTitle"><?php echo $parent_title; ?></h2>
                            <a href="#faqTitle" class="backToTopMobile"><?php echo get_field('back_to_top_text', 'options') ?></a>
                        </span>
                    <?php
                        foreach ($questions as $question): ?>
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="<?php echo "heading" . $index; ?>">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="<?php echo "#collapse" . $index; ?>" aria-expanded="false"
                                            aria-controls="<?php echo "collapse" . $index; ?>">
                                        <?php echo $question['question']; ?>
                                    </button>
                                </h3>
                                <div id="<?php echo "collapse" . $index; ?>" class="accordion-collapse collapse"
                                     aria-labelledby="<?php echo "heading" . $index; ?>" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <?php echo $question['answer']; ?>
                                    </div>
                                </div>
                            </div>
                            <?php $index++; ?>
                        <?php endforeach; ?>
                        
                     <?php   
                    endwhile;
                endif;?>
            </div>
            </div>
        </div>
    </div>
</div>
<script>
$('.accordion .accordionTitleContainer > h2').each(function() {
    $sidebarItem = (this).innerHTML;
    $(this).text(function (index, oldText) {
    $noSpacesText = oldText.replace(/\s+/g, '');
    $noSpacesTextLowercase = $noSpacesText.toLowerCase();
    $(this).attr('id', $noSpacesTextLowercase);
    console.log($sidebarItem);
});
})

$('.accordion').each(function() {
    $('h2', this).each(function() {
    $sidebarItem = (this).innerHTML;
    $(this).text(function (index, oldText) {
    $noSpacesText = oldText.replace(/\s+/g, '');
    $noSpacesTextLowercase = $noSpacesText.toLowerCase();
    $tagName = $(this).prop('tagName').toLowerCase();
    $('.sidebar').append('<a href="#' + $noSpacesTextLowercase + '"><' + $tagName + '>' + $sidebarItem + '</' + $tagName + '></a>');
    // $(this).parent().find('.mobileAnchors').append('<a href="#' + $noSpacesTextLowercase + '"><' + $tagName + '>' + $sidebarItem + '</' + $tagName + '></a>');
});
})
})

$('.sidebar a').click(function(){
    $('.activeSidebarItem').removeClass('activeSidebarItem')
    $(this).find('h2').addClass('activeSidebarItem');
});
</script>

<?php get_footer(); ?>
