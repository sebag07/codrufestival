<?php /*  Template Name: Program  */ ?>
<?php get_header(); ?>

<div class="container programPage termsPage pt-5 pb-5">
    <h1 class="pt-5 pb-4 text-center"><?php echo get_the_title(); ?></h1>
    <div>
        <div class="js" style="width: 100%; overflow:hidden;">
            <div class="cd-main-header">
                <div class="filterContainer">
                    <button class="filterBtn selected" data-value="ziua1">30 August</button>
                    <button class="filterBtn" data-value="ziua2">31 August</button>
                    <button class="filterBtn" data-value="ziua3">1 September</button>
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

                    $scena_1 = get_field('scena_1', 'option');
                    $scena_2 = get_field('scena_2', 'option');
                    $scena_3 = get_field('scena_3', 'option');
                    $scena_4 = get_field('scena_4', 'option');

                    $new_program = [$scena_1, $scena_2, $scena_3, $scena_4];
                    $index = 1;

                    $hours = array(
                        "24:00" => "00:00",
                        "24:30" => "00:30",
                        "25:00" => "01:00",
                        "25:30" => "01:30",
                        "26:00" => "02:00",
                        "26:30" => "02:30",
                        "27:00" => "03:00",
                        "27:30" => "03:30",
                        "28:00" => "04:00",
                        "28:30" => "04:30",
                        "29:00" => "05:00",
                        "29:30" => "05:30",
                        "30:00" => "06:00"
                    );

                    foreach($new_program as $scena => $value):
                        ?>
                        <li class="cd-schedule__group day-schedule" data-value="<?php echo $value['nume_scena']; ?>">
                            <div class="cd-schedule__top-info scena<?php echo $index; ?>"><span><?php echo $value['nume_scena']; ?></span></div>
                            <ul class="scena<?php echo $index; ?>">
                                <?php 
                            if(!empty($value['ziua_1']['artisti'])){ 
                                foreach($value['ziua_1']['artisti'] as $artist){
                                    $start = isset($hours[$artist['ora_inceput']]) ? $hours[$artist['ora_inceput']] : $artist['ora_inceput'];
                                    $end  = isset($hours[$artist['ora_final']]) ? $hours[$artist['ora_final']] : $artist['ora_final'];
                                    echo "
                                    <li class='cd-schedule__event ziua1'>
                                        <a data-start='{$artist['ora_inceput']}' data-end='{$artist['ora_final']}'  data-event='event-1' href='#0'>
                                            <em class='cd-schedule__name'>{$artist['nume']}</em>
                                            <span>$start - $end</span>
                                        </a>
                                    </li>
                                    ";
                                }
                            }

                            if(!empty($value['ziua_2']['artisti'])){ 
                                foreach($value['ziua_2']['artisti'] as $artist){
                                    $start = isset($hours[$artist['ora_inceput']]) ? $hours[$artist['ora_inceput']] : $artist['ora_inceput'];
                                    $end  = isset($hours[$artist['ora_final']]) ? $hours[$artist['ora_final']] : $artist['ora_final'];
                                    echo "
                                    <li class='cd-schedule__event ziua2'>
                                        <a data-start='{$artist['ora_inceput']}' data-end='{$artist['ora_final']}'  data-event='event-2' href='#0'>
                                            <span>$start - $end</span>
                                            <em class='cd-schedule__name'>{$artist['nume']}</em>
                                        </a>
                                    </li>
                                    ";
                                }
                            }

                            if(!empty($value['ziua_3']['artisti'])){ 
                                foreach($value['ziua_3']['artisti'] as $artist){
                                    $start = isset($hours[$artist['ora_inceput']]) ? $hours[$artist['ora_inceput']] : $artist['ora_inceput'];
                                    $end  = isset($hours[$artist['ora_final']]) ? $hours[$artist['ora_final']] : $artist['ora_final'];
                                    echo "
                                    <li class='cd-schedule__event ziua3'>
                                        <a data-start='{$artist['ora_inceput']}' data-end='{$artist['ora_final']}'  data-event='event-3' href='#0'>
                                            <span>$start - $end</span>
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
                        $index++;
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
    </div>
</div>

<?php get_footer(); ?>