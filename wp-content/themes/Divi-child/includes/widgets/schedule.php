<?php 

class eventsSchedule extends WP_Widget {

function __construct(){
    $widget_ops = array( 'description' => esc_html__( 'Displays Schedule Information', 'Divi' ) );
    $control_ops = array( 'width' => 400, 'height' => 300 );
    parent::__construct( false, $name = esc_html__( 'Schedule Widget', 'Divi' ), $widget_ops, $control_ops );
}

/* Displays the Widget in the front-end */
function widget( $args, $instance ){
    extract($args);
    $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? esc_html__( 'About Schedule', 'Divi' ) : esc_html( $instance['title'] ) );
    $imagePath = empty( $instance['imagePath'] ) ? '' : esc_url( $instance['imagePath'] );
    $aboutText = empty( $instance['aboutText'] ) ? '' : $instance['aboutText'];

    echo et_core_intentionally_unescaped( $before_widget, 'html' );
?>
<div class="js" style="width: 100%; overflow:hidden;">
<div class="cd-main-header">
    <div class="filterContainer">
      <button class="filterBtn selected" style="background-image: url('/wp-content/themes/Divi-child/assets/icons/scene/icons-01.svg');" data-value="stage1"></button>
      <button class="filterBtn" style="background-image: url('/wp-content/themes/Divi-child/assets/icons/scene/icons-04.svg');" data-value="stage2"></button>
      <button class="filterBtn" style="background-image: url('/wp-content/themes/Divi-child/assets/icons/scene/icons-03.svg');" data-value="stage3"></button>
      <button class="filterBtn" style="background-image: url('/wp-content/themes/Divi-child/assets/icons/scene/icons-02.svg');" data-value="stage4"></button>
      <button class="filterBtn" style="background-image: url('/wp-content/themes/Divi-child/assets/icons/scene/Teatru.svg');" data-value="stage5"></button>
      <button class="filterBtn" style="background-image: url('/wp-content/themes/Divi-child/assets/icons/scene/Film.svg');" data-value="stage6"></button>
  </div>
</div>
<div class="cd-main-header dayFilter">
    <div class="filterContainer">
      <select id="filterByDays">
        <option selected value="joi">Ziua 1</option>
        <option value="vineri">Ziua 2</option>
        <option value="sambata">Ziua 3</option>
        <option value="duminica">Ziua 4</option>
      </select>
  </div>
</div>

  <div class="cd-schedule cd-schedule--loading margin-top-lg margin-bottom-lg js-cd-schedule">
    <div class="cd-schedule__timeline">
      <ul>
        <li><span>12:00</span></li>
        <!-- <li><span>12:30</span></li> -->
        <li><span>13:00</span></li>
        <!-- <li><span>13:30</span></li> -->
        <li><span>14:00</span></li>
        <!-- <li><span>14:30</span></li> -->
        <li><span>15:00</span></li>
        <!-- <li><span>15:30</span></li> -->
        <li><span>16:00</span></li>
        <!-- <li><span>16:30</span></li> -->
        <li><span>17:00</span></li>
        <!-- <li><span>17:30</span></li> -->
        <li><span>18:00</span></li>
        <!-- <li><span>18:30</span></li> -->
        <li><span>19:00</span></li>
        <!-- <li><span>19:30</span></li> -->
        <li><span>20:00</span></li>
        <!-- <li><span>20:30</span></li> -->
        <li><span>21:00</span></li>
        <!-- <li><span>21:30</span></li> -->
        <li><span>22:00</span></li>
        <!-- <li><span>22:30</span></li> -->
        <li><span>23:00</span></li>
        <!-- <li><span>23:30</span></li> -->
        <li><span>00:00</span></li>
        <!-- <li><span>00:30</span></li> -->
        <li><span>01:00</span></li>
        <!-- <li><span>01:30</span></li> -->
        <li><span>02:00</span></li>
        <!-- <li><span>02:30</span></li> -->
        <li><span>03:00</span></li>
        <!-- <li><span>03:30</span></li> -->
        <li><span>04:00</span></li>
        <!-- <li><span>04:30</span></li> -->
        <li><span>05:00</span></li>
        <!-- <li><span>05:30</span></li> -->
        <li><span>06:00</span></li>
      </ul>
    </div> <!-- .cd-schedule__timeline -->

    <div class="cd-schedule__events">
      <ul> 
        <?php 

        $program = get_fields();

        foreach($program as $zi => $value):
            $data = $value['nume'] . " " . $value['data'];
        ?>

        <li class="cd-schedule__group day-schedule" data-value="<?php echo $zi; ?>">
            <div class="cd-schedule__top-info"><span><?php echo $data; ?></span></div>
            <ul>
                <?php
                if(!empty($value['scena_1']['artisti'])){ 
                    foreach($value['scena_1']['artisti'] as $artist){
                        echo "
                        <li class='cd-schedule__event stage1'>
                            <a data-start='{$artist['ora_inceput']}' data-end='{$artist['ora_final']}'  data-event='event-1' href='#0'>
                                <em class='cd-schedule__name'>{$artist['nume']}</em>
                            </a>
                        </li>
                        ";
                    }
                }

                if(!empty($value['scena_2']['artisti'])){
                    foreach($value['scena_2']['artisti'] as $artist){
                        echo "
                        <li class='cd-schedule__event stage2'>
                            <a data-start='{$artist['ora_inceput']}' data-end='{$artist['ora_final']}'  data-event='event-2' href='#0'>
                                <em class='cd-schedule__name'>{$artist['nume']}</em>
                            </a>
                        </li>
                        ";
                    }
                }
                if(!empty($value['scena_3']['artisti'])){
                    foreach($value['scena_3']['artisti'] as $artist){
                        echo "
                        <li class='cd-schedule__event stage3'>
                            <a data-start='{$artist['ora_inceput']}' data-end='{$artist['ora_final']}'  data-event='event-3' href='#0'>
                                <em class='cd-schedule__name'>{$artist['nume']}</em>
                            </a>
                        </li>
                        ";
                    }
                }
                if(!empty($value['scena_4']['artisti'])){
                    foreach($value['scena_4']['artisti'] as $artist){
                        echo "
                        <li class='cd-schedule__event stage4'>
                            <a data-start='{$artist['ora_inceput']}' data-end='{$artist['ora_final']}'  data-event='event-4' href='#0'>
                                <em class='cd-schedule__name'>{$artist['nume']}</em>
                            </a>
                        </li>
                        ";
                    }
                }
                if(!empty($value['teatru']['artisti'])){
                    foreach($value['teatru']['artisti'] as $artist){
                        echo "
                        <li class='cd-schedule__event stage5'>
                            <a data-start='{$artist['ora_inceput']}' data-end='{$artist['ora_final']}'  data-event='event-5' href='#0'>
                                <em class='cd-schedule__name'>{$artist['nume']}</em>
                            </a>
                        </li>
                        ";
                    }
                }
                if(!empty($value['film']['artisti'])){
                    foreach($value['film']['artisti'] as $artist){
                        echo "
                        <li class='cd-schedule__event stage6'>
                            <a data-start='{$artist['ora_inceput']}' data-end='{$artist['ora_final']}'  data-event='event-6' href='#0'>
                                <em class='cd-schedule__name'>{$artist['nume']}</em>
                            </a>
                        </li>
                        ";
                    }
                }
                ?>
            </ul>
        </li>
        <?php
        endforeach;

        ?>

      </ul>
    </div>
  
    <div class="cd-schedule-modal">
      <header class="cd-schedule-modal__header">
        <div class="cd-schedule-modal__content">
          <span class="cd-schedule-modal__date"></span>
          <h3 class="cd-schedule-modal__name"></h3>
        </div>
  
        <div class="cd-schedule-modal__header-bg"></div>
      </header>
  
      <div class="cd-schedule-modal__body">
        <div class="cd-schedule-modal__event-info"></div>
        <div class="cd-schedule-modal__body-bg"></div>
      </div>
  
      <a href="#0" class="cd-schedule-modal__close text-replace">Close</a>
    </div>
  
    <div class="cd-schedule__cover-layer"></div>
  </div> <!-- .cd-schedule -->
</div>



<?php

    echo et_core_intentionally_unescaped( $after_widget, 'html' );
}

/*Saves the settings. */
function update( $new_instance, $old_instance ){
    $instance = $old_instance;
    $instance['title'] = sanitize_text_field( $new_instance['title'] );
    $instance['imagePath'] = esc_url( $new_instance['imagePath'] );
    $instance['aboutText'] = current_user_can('unfiltered_html') ? $new_instance['aboutText'] : stripslashes( wp_filter_post_kses( addslashes($new_instance['aboutText']) ) );

    return $instance;
}

/*Creates the form for the widget in the back-end. */
function form( $instance ){
    //Defaults
    $instance = wp_parse_args( (array) $instance, array( 'title' => esc_html__( 'About Schedule', 'Divi' ), 'imagePath' => '', 'aboutText' => '' ) );

    # Title
    echo '<p><label for="' . esc_attr( $this->get_field_id('title') ) . '">' . esc_html__( 'Title', 'Divi' ) . ':' . '</label><input class="widefat" id="' . esc_attr( $this->get_field_id('title') ) . '" name="' . esc_attr( $this->get_field_name('title') ) . '" type="text" value="' . esc_attr( $instance['title'] ) . '" /></p>';
    # Image
    echo '<p><label for="' . esc_attr( $this->get_field_id('imagePath') ) . '">' . esc_html__( 'Image', 'Divi' ) . ':' . '</label><textarea cols="20" rows="2" class="widefat" id="' . esc_attr( $this->get_field_id('imagePath') ) . '" name="' . esc_attr( $this->get_field_name('imagePath') ) . '" >' . esc_url( $instance['imagePath'] ) .'</textarea></p>';
    # About Text
    echo '<p><label for="' . esc_attr( $this->get_field_id('aboutText') ) . '">' . esc_html__( 'Text', 'Divi' ) . ':' . '</label><textarea cols="20" rows="5" class="widefat" id="' . esc_attr( $this->get_field_id('aboutText') ) . '" name="' . esc_attr( $this->get_field_name('aboutText') ) . '" >' . esc_textarea( $instance['aboutText'] ) .'</textarea></p>';
}

}

function load_schedule_widget() {
    register_widget('eventsSchedule');
}
add_action('widgets_init', 'load_schedule_widget');