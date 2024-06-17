<?php /*  Template Name: FAQ  */ ?>
<?php get_header(); ?>

<div class="container-fluid single-page header-padding">

    <h1 class="text-center" style="font-weight: 600;"><?php echo get_the_title(); ?></h1>
    <div class="container-fluid faq p-relative sectionPadding">
        <div class="container">
            <div class="accordion" id="accordionExample">
                <?php
                $questions = get_field("faq_repeater", "options");
                $index = 1;
                foreach ($questions as $question): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="<?php echo "heading" . $index; ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="<?php echo "#collapse" . $index; ?>" aria-expanded="false"
                                    aria-controls="<?php echo "collapse" . $index; ?>">
                                <?php echo $question['question']; ?>
                            </button>
                        </h2>
                        <div id="<?php echo "collapse" . $index; ?>" class="accordion-collapse collapse"
                             aria-labelledby="<?php echo "heading" . $index; ?>" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <?php echo $question['answer']; ?>
                            </div>
                        </div>
                    </div>
                    <?php $index++; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>


<?php get_footer(); ?>
