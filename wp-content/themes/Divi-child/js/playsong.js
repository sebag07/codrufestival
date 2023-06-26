
let playing = false;
let selfPlaying = false;
let playingArtist = "";


jQuery(document).on("click", ".play-image", function() {
  
    jQuery(".is-playing").trigger("pause");
    jQuery(".is-playing").closest(".play-image").attr("src", "wp-content/themes/Divi-child/images/playwhite.png");
    jQuery(this).attr("src", "wp-content/themes/Divi-child/images/playwhite.png");
    jQuery(".is-playing").removeClass("is-playing");
    jQuery(".play-image").removeClass("is-playing");

    var artistToPlay = jQuery(this).attr("data-artist");
    var songToPlay = jQuery("#" + artistToPlay);

    if(artistToPlay == playingArtist){
        playingArtist = "";
        return false;
    }

    console.log(songToPlay);
    console.log(artistToPlay);

    songToPlay.trigger("play");
    songToPlay.addClass("is-playing");
    jQuery(".play-image").addClass("is-playing");

    if (jQuery(".is-playing").paused) {
        jQuery(this).attr("src", "wp-content/themes/Divi-child/images/playwhite.png");
    } else {
        jQuery(this).attr("src", "wp-content/themes/Divi-child/images/pausewhite.png");
    }
    playingArtist = artistToPlay;

    songToPlay.on("ended", function(){
        jQuery(".play-image").attr("src", "wp-content/themes/Divi-child/images/playwhite.png");
        jQuery(".is-playing").removeClass("is-playing");
        return false;
    })


    return false;
});



