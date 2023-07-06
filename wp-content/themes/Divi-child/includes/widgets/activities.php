<?php 

class activities extends WP_Widget {

function __construct(){
    $widget_ops = array( 'description' => esc_html__( 'Displays Activities', 'Divi' ) );
    $control_ops = array( 'width' => 400, 'height' => 300 );
    parent::__construct( false, $name = esc_html__( 'Activities Widget', 'Divi' ), $widget_ops, $control_ops );
}

/* Displays the Widget in the front-end */
function widget( $args, $instance ){
    extract($args);
    $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? esc_html__( 'About Activities', 'Divi' ) : esc_html( $instance['title'] ) );
    $imagePath = empty( $instance['imagePath'] ) ? '' : esc_url( $instance['imagePath'] );
    $aboutText = empty( $instance['aboutText'] ) ? '' : $instance['aboutText'];

    echo et_core_intentionally_unescaped( $before_widget, 'html' );
?>

        <div class="activities-container">
            <div class="activities">
                <div class="activity active">
                    <div class="activity-info">
                        <p>CODRU Festival 2022 are loc la doar 4km de Timișoara. Simte libertatea, înconjurat de tot VERDELE pe care Pădurea BISTRA ți-l poate oferi.</p>
                    </div>
                    <div class="label">
                        <div class="icon">
                            <img src="<?php echo get_template_directory_uri() . "-child/assets/icons/activitati/"; ?>icons-01.svg">
                        </div>
                        <div class="info">
                            <div class="main">Natură</div>
                        </div>  
                    </div>
                </div>
                <div class="activity">
                    <div class="activity-info">
                        <p>CINCI zone destinate copiilor de toate vârstele: AMALGAM, SUB CERUL LIBER, MAREA BĂLĂCEALĂ, E, COMEDIE!, LA PITICNIC.</p>
                    </div>
                    <div class="label">
                        <div class="icon">
                            <img src="<?php echo get_template_directory_uri() . "-child/assets/icons/activitati/"; ?>icons-02.svg">
                        </div>
                        <div class="info">
                            <div class="main">Kids</div>
                        </div>
                   </div>
                </div>
                <div class="activity" >
                    <div class="activity-info">
                        <p>Experiența culinară din CODRU te va iniția într-o călătorie prin toate colțurile lumii.</p>
                    </div>
                    <div class="label">
                        <div class="icon">
                            <img src="<?php echo get_template_directory_uri() . "-child/assets/icons/activitati/"; ?>icons-03.svg">
                        </div>
                        <div class="info">
                            <div class="main">Gastronomie</div>
                        </div>
                    </div>
                </div>
                <div class="activity" >
                    <div class="activity-info">
                        <p>Arta meșteșugului, designul de produs și emoția obiectelor lucrate manual, ne inspiră în CODRU. Găsești aici obiecte de decor și accesorii handmade, creații ale designerilor locali, cosmetice naturale și jucării ecologice realizate din diverse materiale.</p>
                    </div>
                    <div class="label">
                        <div class="icon">
                            <img src="<?php echo get_template_directory_uri() . "-child/assets/icons/activitati/"; ?>icons-04.svg">
                        </div>
                        <div class="info">
                            <div class="main">Market</div>
                        </div>
                    </div>
                </div>
                <div class="activity" >
                    <div class="activity-info">
                        <p>Pentru că ARTA este o imitație a naturii, iar în CODRU artele vizuale sunt parte integrantă. Oriunde privești în jurul tău, vei descoperi piesele unui puzzle perfect, compus din puterea ARTELOR VIZUALE și blândețea CODRULUI.</p>
                    </div>
                   <div class="label">
                      <div class="icon">
                            <img src="<?php echo get_template_directory_uri() . "-child/assets/icons/activitati/"; ?>icons-05.svg">
                      </div>
                      <div class="info">
                         <div class="main">Arte Vizuale</div>
                      </div>
                   </div>
                </div>
                <div class="activity" >
                    <div class="activity-info">
                        <p>Atunci când filmul de comedie îmbrățișează natura. Bucharest Best Comedy Festival aduce în CODRU o serie de opt filme de comedie.</p>
                    </div>
                    <div class="label">
                       <div class="icon">
                            <img src="<?php echo get_template_directory_uri() . "-child/assets/icons/activitati/"; ?>icons-06.svg">
                       </div>
                       <div class="info">
                          <div class="main">Film</div>
                       </div>
                    </div>
                 </div>
                 <div class="activity" >
                    <div class="activity-info">
                        <p>La CODRU, teatrul se joacă în mijlocul naturii! Spectacole de teatru, pentru adulți și copii vor fi puse în scenă în toate cele patru zile ale festivalului.</p>
                    </div>
                    <div class="label">
                       <div class="icon">
                            <img src="<?php echo get_template_directory_uri() . "-child/assets/icons/activitati/"; ?>icons-07.svg">
                       </div>
                       <div class="info">
                          <div class="main">Teatru</div>
                       </div>
                    </div>
                 </div>
                 <div class="activity" >
                     <div class="activity-info">
                         <p>CODRU este despre deconectare de la viața cotidiană. Atelierele noastre practice si teoretice, ședințele de Yoga și masajul de relaxare vin în speranța unei reconectări cu natura și găsirea echilibrului necesar pentru o stare de bine.</p>
                     </div>
                    <div class="label">
                       <div class="icon">
                            <img src="<?php echo get_template_directory_uri() . "-child/assets/icons/activitati/"; ?>icons-08.svg">
                       </div>
                       <div class="info">
                          <div class="main">Wellness</div>
                       </div>
                    </div>
                 </div>
             </div>
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
    $instance = wp_parse_args( (array) $instance, array( 'title' => esc_html__( 'About Activities', 'Divi' ), 'imagePath' => '', 'aboutText' => '' ) );

    # Title
    echo '<p><label for="' . esc_attr( $this->get_field_id('title') ) . '">' . esc_html__( 'Title', 'Divi' ) . ':' . '</label><input class="widefat" id="' . esc_attr( $this->get_field_id('title') ) . '" name="' . esc_attr( $this->get_field_name('title') ) . '" type="text" value="' . esc_attr( $instance['title'] ) . '" /></p>';
    # Image
    echo '<p><label for="' . esc_attr( $this->get_field_id('imagePath') ) . '">' . esc_html__( 'Image', 'Divi' ) . ':' . '</label><textarea cols="20" rows="2" class="widefat" id="' . esc_attr( $this->get_field_id('imagePath') ) . '" name="' . esc_attr( $this->get_field_name('imagePath') ) . '" >' . esc_url( $instance['imagePath'] ) .'</textarea></p>';
    # About Text
    echo '<p><label for="' . esc_attr( $this->get_field_id('aboutText') ) . '">' . esc_html__( 'Text', 'Divi' ) . ':' . '</label><textarea cols="20" rows="5" class="widefat" id="' . esc_attr( $this->get_field_id('aboutText') ) . '" name="' . esc_attr( $this->get_field_name('aboutText') ) . '" >' . esc_textarea( $instance['aboutText'] ) .'</textarea></p>';
}

}

function load_activities_widget() {
    register_widget('activities');
}
add_action('widgets_init', 'load_activities_widget');