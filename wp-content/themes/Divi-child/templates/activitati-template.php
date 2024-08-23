<?php /*  Template Name: Activitati  */ ?>
<?php get_header(); ?>

<div class="container activitiesContainer">
<h2 class="sectionTitle mb-5"><?php echo get_the_title(); ?></h2>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12 categoriesContainer">
                <span id="category-filter">
            <label class="activitiesCheckbox activeCategory" for="all"><input class="allcat" id="all" type="checkbox" name="teamRadio" value="all"><span>Toate</span></label>

                        <?php

                        $args = array(
                            'post_type' => 'activitati',
                            'post_status' => 'publish',
                            'posts_per_page' => '-1'
                        );
                        
                        $query = new WP_Query($args);
                        if ($query->have_posts()) :
                            while ($query->have_posts()) : $query->the_post();

                    
                            $terms = wp_get_post_terms( $post->ID, 'activitati_category');

                                // $categories = get_the_category($post->ID);

                                foreach ($terms as $cat) : ?>

                                <label class="activitiesCheckbox" for="<?php echo $cat->slug; ?>"><input
                                    class="catCheckbox" id="<?php echo $cat->slug; ?>" type="checkbox" name="teamRadio"
                                    value="<?php echo $cat->slug; ?>"><span><?php echo $cat->name; ?></span>
                                </label>

                                <?php
                                endforeach;
                            endwhile;
                            wp_reset_postdata();
                        endif; ?>
            </span>
            </div>
            <div class="activitiesPosts">
                <div class="row">
                    <?php
                    $postslist = get_posts($args);
                    foreach ($postslist as $post) : {
                        $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                        if ($image) {
                            $imageUrl = $image[0];
                        } else {
                            $imageUrl = "/wp-content/themes/Divi-child/images/logo.png";
                        }
                        $title = get_the_title();
                        // $categories = get_the_category($post->ID);
                        $shortDescription = get_field('short_description', $post->ID);
                        $date = get_field('date', $post->ID);
                        $type = get_field('type', $post->ID);
                        $terms = wp_get_post_terms( $post->ID, 'activitati_category');
                        $postURL = get_permalink($post->ID);
                        foreach ($terms as $cat) : {
                                echo "  <div class='col-lg-4 col-md-6 col-12 activitiesBlurb' data-category='$cat->slug all'>
                                    <div class='activitiesPost'>
                                        <div class='imageContainer'><img src='$imageUrl'>
                                        <div class='details'>
                                        <span class='type'>$type</span>
                                        <span class='date'>$date</span>
                                        </div>
                                        </div>
                                        <div class='postInfo'>
                                        <h4 class='mb-1'>$title</h4>
                                        <p >$shortDescription</p>
                                        <a class='readMore' href='$postURL'><span>Citește mai mult</span></a>
                                        </div>
                                    </div>
                                </div>
                            ";
                        }
                        endforeach;
                    }
                    endforeach;
                    ?>
                </div>
            </div>
        </div>
    </div>

<script>

jQuery(document).on("click", ".allcat", function(){
    jQuery('.allcat').prop('checked', true);
    jQuery('.catCheckbox').prop('checked', false);
});

jQuery(document).on("click", ".catCheckbox", function(){
    jQuery('.allcat').prop('checked', false);
    jQuery('.catCheckbox').prop('checked', false);
    jQuery(this).prop('checked', true);
});

jQuery(document).ready(function(){
    jQuery('.allcat').prop('checked', true);

});

jQuery(function(){ 

jQuery('#category-filter label').find("input").on('change',function(){
  let selected = []; 
  jQuery('#category-filter label').find("input").each(function(){
    if(jQuery(this).is(":checked")){ 
      selected.push(jQuery(this).val());
    }
  })
  if(!selected.length){
  jQuery(".activitiesBlurb").show(); 
  return; 
  
  }
  jQuery(".activitiesBlurb").hide(); 

  jQuery(".activitiesBlurb").each(function(){ 
    const category = jQuery(this).attr('data-category');
    const categorySplitted = category.split(' ');
    categorySplitted.forEach((cat)=>{
      if(selected.indexOf(cat) !== -1){
      jQuery(this).show();
      }
    });

    
  });
});

});

jQuery(".allcat").change(function() {
    if(this.checked) {
        jQuery(this).closest('label').addClass('activeCategory');
        jQuery('.catCheckbox').closest('label').removeClass('activeCategory');
    } else {
        jQuery(this).closest('label').removeClass('activeCategory');
    }
});

jQuery(".catCheckbox").change(function() {
    if(this.checked) {
        jQuery('.allcat').closest('label').removeClass('activeCategory');
        jQuery('.catCheckbox').closest('label').removeClass('activeCategory');
        jQuery(this).closest('label').addClass('activeCategory');
    } else {
        jQuery(this).closest('label').removeClass('activeCategory');
    }

});


    
    
</script>

<?php get_footer(); ?>
