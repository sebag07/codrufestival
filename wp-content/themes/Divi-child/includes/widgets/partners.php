<?php 

class partners extends WP_Widget {

function __construct(){
    $widget_ops = array( 'description' => esc_html__( 'Displays all partners', 'Divi' ) );
    $control_ops = array( 'width' => 400, 'height' => 300 );
    parent::__construct( false, $name = esc_html__( 'Partners Widget', 'Divi' ), $widget_ops, $control_ops );
}

/* Displays the Widget in the front-end */
function widget( $args, $instance ){
    extract($args);
    $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? esc_html__( 'Partners Widget', 'Divi' ) : esc_html( $instance['title'] ) );
    $imagePath = empty( $instance['imagePath'] ) ? '' : esc_url( $instance['imagePath'] );
    $aboutText = empty( $instance['aboutText'] ) ? '' : $instance['aboutText'];

    echo et_core_intentionally_unescaped( $before_widget, 'html' );
?>

<?php
$level_1 = array(
    "Kaufland"      => array( "link"    => 'https://www.kaufland.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Kaufland.png")
);
$level_2 = array(
    "Atos"          => array( "link"    => 'https://atos.net/ro/romania',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Atos.png"),
    "CocaCola"      => array( "link"    => 'https://www.coca-cola.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/CocaCola.png"),
    "DSSmith"       => array( "link"    => 'https://www.dssmith.com/ro',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/DS-Smith.png"),
    "ForviaHella"   => array( "link"    => 'https://www.hella.com/hella-ro/ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Forvia.png"),
    "Ness"          => array( "link"    => 'https://www.ness.com/ness-romania/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Ness.png"   )
);
$level_3 = array(
    "Fornetti"      => array( "link"    => 'https://www.fornetti.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Fornetti.png"),
    "IuliusTown"    => array( "link"    => 'https://iuliustown.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Iulius-Town.png"),
    "Sobranie"      => array( "link"    => 'https://www.yourfreedom.ro',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Sobranie.png"),
    "Stanca"        => array( "link"    => 'https://www.stancalaw.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/StancaLawOffice.png"),
    "Ursus"         => array( "link"    => 'https://ursus.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Ursus.png"),
    "Winston"       => array( "link"    => 'https://www.yourfreedom.ro',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Winston.png"),
    "ZF"            => array( "link"    => 'https://www.zf.com/romania/ro/company/company.html',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/ZF.png")
);
$level_4 = array(
    "Access"        => array( "link"    => 'https://www.theaccessgroup.com/en-gb/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/acces.png"),        
    "Aeroport"      => array( "link"    => 'https://aerotim.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/AeroportulTraianVuia.png"),
    "Agache"        => array( "link"    => 'https://agache.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Agache.png"),
    "Agrocomert"    => array( "link"    => 'https://agrocomert.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Agrocomert.png"),
    "Aquatim"       => array( "link"    => 'https://www.aquatim.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/aquatim.png"),
    "ArtEncounters" => array( "link"    => 'https://artencounters.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/ArtEncounters.png"),
    "BriteClean"    => array( "link"    => 'https://www.briteclean.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/BriteClean.png"),
    "Carturesti"    => array( "link"    => 'https://carturesti.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Carturesti.png"),
    "CityLimo"      => array( "link"    => 'https://citylimo.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/citylimo.png"),                              
    "CityMedia"     => array( "link"    => 'https://citymedia-tm.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/CityMedia.png"),
    "DFG"           => array( "link"    => 'https://dfgradical.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/DFG.png"),
    "Decathlon"     => array( "link"    => 'https://dfgradical.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Decathlon.png"),
    "Ducodan"       => array( "link"    => 'https://www.decathlon.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Ducodan.png"),
    "EPS Global"    => array( "link"    => 'https://www.epsprogramming.com/contact/timisoara',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/epsglobal.png"),
    "ExitMasters"   => array( "link"    => 'https://exitmasters.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/ExitMasters.png"),
    "FacArteDesign" => array( "link"    => 'https://arte.uvt.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Arte.png"),
    "FarmTech"      => array( "link"    => "https://www.farmtech.ro/",
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Schaffer.png"),
    "Ghem"          => array( "link"    => 'https://ghem.app/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Ghem.png"),
    "HaufeGroup"    => array( "link"    => 'https://www.haufegroup.com/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Haufe.png"),
    "Hemofarm"      => array( "link"    => 'https://www.hemofarm.com/eng',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Hemofarm.png"),
    "Horticultura"  => array( "link"    => 'https://horticultura.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Horticultura.png"),
    "Hotel Ibis"    => array( "link"    => 'https://all.accor.com/hotel/B3H6/index.en.shtml',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Ibis.png"),
    "Hotel Tm"      => array( "link"    => 'https://hoteltimisoara.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/HotelTimisoara.png"),
    "Hydromatic"    => array( "link"    => 'http://www.hydromaticsistem.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/hydromatic.png"),
    "Kenosis"       => array( "link"    => 'https://kenosis.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/kenosis.png"),
    "LAPT"          => array( "link"    => 'http://www.arteplasticetm.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/LAPT.png"),
    "MuraliStore"   => array( "link"    => 'https://muralistore.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Murali.png"),
    "MusicService"  => array( "link"    => 'http://www.musicservice.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Music-Service.png"),
    "Nokia"         => array( "link"    => 'https://www.nokia.com/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Nokia.png"),
    "Pasmatex"      => array( "link"    => 'http://www.pasmatex.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Pasmatex.png"),
    "PizzaLand"     => array( "link"    => 'https://www.pizzaland.com.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/PizzaLand.png"),
    "PrintInkHub"   => array( "link"    => 'http://printinkhub.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/PrintInkHub.png"),
    "Prospero"      => array( "link"    => 'https://prospero.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Prospero.png"),
    "Pur Clothing"  => array( "link"    => 'https://www.pur.clothing/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Pur.png"),
    "Rolling"       => array( "link"    => 'https://www.rollingsystem.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Rolling.png"),
    "Silver Motors" => array( "link"    => 'https://www.silvermotors.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/silvermotors.png"),
    "SoundCreation" => array( "link"    => 'https://www.soundcreation.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/SoundCreation.png"),
    "SPOT"          => array( "link"    => 'https://spotevents.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Spot.png"),
    "Timisoara CC"  => array( "link"    => 'https://tmcc.ro/en/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/TimisoaraConvenstionCenter.png"),
    "UPT"           => array( "link"    => 'https://www.upt.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/UPT.png"),
    "USVT"          => array( "link"    => 'https://www.usab-tm.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/USVT.png"),
    "UVT"           => array( "link"    => 'https://www.uvt.ro/ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/UVT.png"),
    "VestTract"     => array( "link"    => "https://www.vest-tract.ro/",
                              "image"   => "/wp-content/themes/Divi-child/images/partners/VestTract.png"),
    "Wet"           => array( "link"    => "https://wetseltzer.com/",
                              "image"   => "/wp-content/themes/Divi-child/images/partners/wet.png")
);
$level_5 = array(
    "AugustPasta"   => array( "link"    => 'https://www.facebook.com/augustpastabar/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/AugustPastaBar.png"),
    "Cofferize"     => array( "link"    => 'https://www.facebook.com/cofferize.van',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Cofferize.png"),
    "Darc"          => array( "link"    => 'https://www.facebook.com/club.darc.timisoara',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Darc.png"),
    "Garage"        => array( "link"    => 'https://www.facebook.com/GarageCafeTM',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Garage.png"),
    "HypeCulture"   => array( "link"    => 'https://www.facebook.com/hypeculture.ro',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Hype.png"),
    "Mononom"       => array( "link"    => 'https://www.facebook.com/Mononom.TM',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Mononom.png"),
    "Neata"         => array( "link"    => 'https://www.facebook.com/NeataOmeletteBistro',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/neatza.png"),
    "Origins"       => array( "link"    => 'https://www.facebook.com/origins.coffeeshop',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Origins.png"),
    "Senneville"    => array( "link"    => 'https://www.facebook.com/senneville.ro',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Senneville.png"),
    "Sidebar"       => array( "link"    => 'https://www.facebook.com/sidebartm',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/SideBar.png"),
    "Storia"        => array( "link"    => 'https://www.facebook.com/Storia.theplacetobe',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Storia.png"),
    "80sPub"        => array( "link"    => 'https://www.facebook.com/The80sPub',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/80pub.png"),
    "Viniloteca"    => array( "link"    => 'https://www.facebook.com/viniloteca',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Viniloteca.png"),
    "Zai"           => array( "link"    => 'https://www.facebook.com/zaiaprescafe',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Zai.png"),
    "Zaza"          => array( "link"    => 'https://www.facebook.com/zazarestopub/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/zaza.png")
);
$media_partner = array(
    "Guerilla"      => array( "link"    => 'https://www.guerrillaradio.ro/', 
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Guerilla.png")
);
$cofinance_partner = array(
    "CJT"           => array( "link"    => 'https://www.cjtimis.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/CJT.png"),
    "Ghiroda"       => array( "link"    => 'https://www.primariaghiroda.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Ghiroda.png"),
    "MosnitaNoua"   => array( "link"    => 'https://mosnita.ro/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/MosnitaNoua.png"),
    "PadureaBistra" => array( "link"    => 'https://www.facebook.com/ADI-P%C4%83durea-Bistra-329124070763327/',
                              "image"   => "/wp-content/themes/Divi-child/images/partners/Adi.png")
);
$partof_partner = array(
    "TEG"           => array( "link"    => "https://teg.ro/",
                              "image"   => "/wp-content/themes/Divi-child/images/partners/TEG.png")
);

?>

<div class="sectionTitle"><h2>Parteneri</h2></div>
<div class="partnersContainer">
    <div class="partnersLevel1">
    <?php 
    foreach($level_1 as $value){
        echo "<a href=" . $value['link'] . "><img src=" . $value['image'] . "></a>";
    }
        ?>
    </div>
</div>
<div class="partnersContainer">
    <div class="partnersLevel2">
    <?php 
    foreach($level_2 as $value){
        echo "<a href=" . $value['link'] . "><img src=" . $value['image'] . "></a>";
    }
        ?>
    </div>
</div>
<div class="partnersContainer">
    <div class="partnersLevel3">
    <?php 
    foreach($level_3 as $value){
        echo "<a href=" . $value['link'] . "><img src=" . $value['image'] . "></a>";
    }
        ?>
    </div>
</div>
<div class="partnersContainer">
    <div class="partnersLevel4">
    <?php 
    foreach($level_4 as $value){
        echo "<a href=" . $value['link'] . "><img src=" . $value['image'] . "></a>";
    }
        ?>
    </div>
</div>
<div class="partnersContainer">
    <div class="partnersLevel5">
    <?php 
    foreach($level_5 as $value){
        echo "<a href=" . $value['link'] . "><img src=" . $value['image'] . "></a>";
    }
        ?>
    </div>
</div>

<div class="sectionTitle"><h2>Partener Media</h2></div>
<div class="partnersMediaContainer">
    <div class="partnersMedia">
    <?php 
    foreach($media_partner as $key => $value){
        echo "<a href=" . $value['link'] . "><img src=" . $value['image'] . "></a>";
    }
        ?>
    </div>
</div>

<div class="sectionTitle"><h2>Eveniment cofinanțat de</h2></div>
<div class="partnersCofinanceContainer">
    <div class="partnersCofinance">
    <?php 
    foreach($cofinance_partner as $value){
        echo "<a href=" . $value['link'] . "><img src=" . $value['image'] . "></a>";
    }
        ?>
    </div>
</div>

<div class="sectionTitle"><h2>O producție parte din</h2></div>
<div class="partnersPartofContainer">
    <div class="partnersPartof">
    <?php 
    foreach($partof_partner as $value){
        echo "<a href=" . $value['link'] . "><img src=" . $value['image'] . "></a>";
    }
        ?>
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
    $instance = wp_parse_args( (array) $instance, array( 'title' => esc_html__( 'About partners', 'Divi' ), 'imagePath' => '', 'aboutText' => '' ) );

    # Title
    echo '<p><label for="' . esc_attr( $this->get_field_id('title') ) . '">' . esc_html__( 'Title', 'Divi' ) . ':' . '</label><input class="widefat" id="' . esc_attr( $this->get_field_id('title') ) . '" name="' . esc_attr( $this->get_field_name('title') ) . '" type="text" value="' . esc_attr( $instance['title'] ) . '" /></p>';
    # Image
    echo '<p><label for="' . esc_attr( $this->get_field_id('imagePath') ) . '">' . esc_html__( 'Image', 'Divi' ) . ':' . '</label><textarea cols="20" rows="2" class="widefat" id="' . esc_attr( $this->get_field_id('imagePath') ) . '" name="' . esc_attr( $this->get_field_name('imagePath') ) . '" >' . esc_url( $instance['imagePath'] ) .'</textarea></p>';
    # About Text
    echo '<p><label for="' . esc_attr( $this->get_field_id('aboutText') ) . '">' . esc_html__( 'Text', 'Divi' ) . ':' . '</label><textarea cols="20" rows="5" class="widefat" id="' . esc_attr( $this->get_field_id('aboutText') ) . '" name="' . esc_attr( $this->get_field_name('aboutText') ) . '" >' . esc_textarea( $instance['aboutText'] ) .'</textarea></p>';
}

}

function wpb_load_widget() {
register_widget('partners');
}
add_action('widgets_init', 'wpb_load_widget');