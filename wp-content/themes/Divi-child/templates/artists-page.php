<?php /*  Template Name: Artisti  */ ?>
<?php get_header(); ?>

<div class="container-fluid single-page artists-page header-padding">
    <h1 class="text-center sectionTitle" style="font-weight: 600;"><?php echo get_the_title(); ?></h1>
    <section>
        <div class="sectionPadding container">
        <span id="category-filter">
        <?php 
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $key = '/en/';
        if (strpos($url, $key) == false) { 
            echo '<label class="activitiesCheckbox activeCategory" for="all"><input class="allcat" id="all" type="checkbox" name="artistsCheckbox" value="all"><span>Toate</span></label><br>
            <label class="activitiesCheckbox" for="ziua-1"><input class="catCheckbox" id="ziua-1" type="checkbox" name="artistsCheckbox" value="ziua-1"><span>Vineri</span></label>
            <label class="activitiesCheckbox" for="ziua-2"><input class="catCheckbox" id="ziua-2" type="checkbox" name="artistsCheckbox" value="ziua-2"><span>Sâmbătă</span></label>
            <label class="activitiesCheckbox" for="ziua-3"><input class="catCheckbox" id="ziua-3" type="checkbox" name="artistsCheckbox" value="ziua-3"><span>Duminică</span></label><br>
            <label class="activitiesCheckbox" for="scena-1"><input class="catCheckbox" id="scena-1" type="checkbox" name="artistsCheckbox" value="scena-1"><span>SUB SOARE</span></label>
            <label class="activitiesCheckbox" for="scena-2"><input class="catCheckbox" id="scena-2" type="checkbox" name="artistsCheckbox" value="scena-2"><span>LUMEA NOUĂ</span></label>
            <label class="activitiesCheckbox" for="scena-3"><input class="catCheckbox" id="scena-3" type="checkbox" name="artistsCheckbox" value="scena-3"><span>SUB CODRU</span></label>
            <label class="activitiesCheckbox" for="scena-4"><input class="catCheckbox" id="scena-4" type="checkbox" name="artistsCheckbox" value="scena-4"><span>SUB NORI</span></label>
'; 
        } 
        else { 
            echo '<label class="activitiesCheckbox activeCategory" for="all"><input class="allcat" id="all" type="checkbox" name="artistsCheckbox" value="all"><span>All</span></label><br>
            <label class="activitiesCheckbox" for="ziua-1-en"><input class="catCheckbox" id="ziua-1-en" type="checkbox" name="artistsCheckbox" value="ziua-1-en"><span>Friday</span></label>
            <label class="activitiesCheckbox" for="ziua-2-en"><input class="catCheckbox" id="ziua-2-en" type="checkbox" name="artistsCheckbox" value="ziua-2-en"><span>Saturday</span></label>
            <label class="activitiesCheckbox" for="ziua-3-en"><input class="catCheckbox" id="ziua-3-en" type="checkbox" name="artistsCheckbox" value="ziua-3-en"><span>Sunday</span></label><br>
            <label class="activitiesCheckbox" for="scena-1"><input class="catCheckbox" id="scena-1" type="checkbox" name="artistsCheckbox" value="scena-1"><span>SUB SOARE</span></label>
            <label class="activitiesCheckbox" for="scena-2"><input class="catCheckbox" id="scena-2" type="checkbox" name="artistsCheckbox" value="scena-2"><span>LUMEA NOUĂ</span></label>
            <label class="activitiesCheckbox" for="scena-3"><input class="catCheckbox" id="scena-3" type="checkbox" name="artistsCheckbox" value="scena-3"><span>SUB CODRU</span></label>
            <label class="activitiesCheckbox" for="scena-4"><input class="catCheckbox" id="scena-4" type="checkbox" name="artistsCheckbox" value="scena-4"><span>SUB NORI</span></label>
'; 
        };
        ?>

        </span>
            <div class="artistCardContainer">
                <?php
                $args = array('posts_per_page' => -1, 'orderby' => 'date', 'suppress_filters' => false, 'order' => 'ASC', 'post_type' => 'artist');
                $postslist = get_posts($args);
                foreach ($postslist as $post) :
                    $artistName = get_the_title();
                    $artistPage = get_the_permalink();
                    $artistImage = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medium_large');
                    $intervalOrar = get_field('interval_orar', $post->ID);
                    if ($artistImage) {
                        $imageUrl = $artistImage[0];
                    } else {
                        $imageUrl = "/wp-content/themes/Divi-child/images/artist-placeholder.png";
                    };
                    $categories = get_categories($args);
                    foreach ($categories as $cat) :
                        $category = get_the_category($post->ID);
                       
                       ?>
                    <?php endforeach; ?>

                    <a href='<?php echo $artistPage; ?>' class='artistInnerContainer' data-category="<?php if (!empty ($category[0]))  echo $category[0]->slug; echo" "; if (!empty ($category[1]))  echo $category[1]->slug; echo" "; if (!empty ($category[2]))  echo $category[2]->slug; echo" "; if (!empty ($category[3]))  echo $category[3]->slug;  ?> all">
                        <div class='artistImageContainer'>
                            <img class='artistImg' loading='lazy' src='<?php echo $imageUrl; ?>' alt="<?php echo $artistName; ?>">
                            <div class='imageOverlay'></div>
                        </div>
                        <div class='artistContent'>
                            <div class='artistContentBG'></div>
                            <div class='artistContentMeta'>
                            <span class='artistContentName'>
                                <?php echo $artistName; ?>
                            </span>
                                <span class='artistContentDayStage'>
                                    <?php echo $intervalOrar ?>
                                </span>
                            </div>
                        </div>
                        <div class='artistCardHoverOverlay'></div>

                        <div class='artistCardReadMoreBtn'><?php echo get_field('artists_card_button', 'options'); ?></div>

                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <script>

            jQuery(".artistInnerContainer").hover(
                function () {
                    jQuery(this).find('.artistCardReadMoreBtn').addClass('readMoreBtnHover');
                    jQuery(this).find('.artistContent').addClass('artistContentTop');
                    jQuery(this).find('.artistCardHoverOverlay').addClass('onHoverOverlayOpacity');
                    jQuery(this).addClass('borderOnHover');
                },
                function () {
                    jQuery(this).find('.artistCardReadMoreBtn').removeClass('readMoreBtnHover');
                    jQuery(this).find('.artistContent').removeClass('artistContentTop');
                    jQuery(this).find('.artistCardHoverOverlay').removeClass('onHoverOverlayOpacity');
                    jQuery(this).removeClass('borderOnHover');
                }
            );
jQuery(document).on("click", ".allcat", function(){
    jQuery('.allcat').prop('checked', true);
    jQuery('.catCheckbox').prop('checked', false);
});

jQuery(document).on("click", ".catCheckbox", function(){
    jQuery('.allcat').prop('checked', false);
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
  jQuery(".artistInnerContainer").show(); 
  return; 
  
  }
  jQuery(".artistInnerContainer").hide(); 

  jQuery(".artistInnerContainer").each(function(){ 
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
        jQuery(this).closest('label').addClass('activeCategory');
        jQuery('.allcat').closest('label').removeClass('activeCategory');
    } else {
        jQuery(this).closest('label').removeClass('activeCategory');
    }

});


        </script>
    </section>
</div>


<?php get_footer(); ?>
