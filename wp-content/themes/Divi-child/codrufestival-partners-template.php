<?php /*  Template Name: Parteneri  */ ?>
<?php get_header('codru2023live'); ?>


<div class="container-fluid partnersPage sectionPaddingHero m-auto">
    <div class="row valuesContainer">
        <div class="col-lg-12 col-md-12 col-12 text-center partnersTitle">
            <h2><?php echo get_field('above_repeater_title')?></h2>
        </div>
        <div class="col-lg-6 col-md-8 col-12 pt-5">
            <?php if ( have_rows( 'value_repeater' ) ): ?>

            <?php while( have_rows( 'value_repeater' ) ) : the_row(); ?>

            <?php if( $repeaterImg = get_sub_field('value_number') ) { 

                        echo "<div class='valueRepeater'>
                            <p class='value-step'>$repeaterImg</p>";
                            echo "<div class='value-text'>";
                            the_sub_field('value_text');
                            echo "</div>";
                            echo "</div>";            
                    } ?>

            <?php endwhile; ?>

            <?php endif; ?>
        </div>
        <div class="col-lg-6 col-md-4 col-12 partnerImage">
            <img src="/wp-content/themes/Divi-child/images/rightimg.png" alt="">
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12 text-center partnersTitle pb-3">
            <h2>Fii frate cu CODRU!</h2>
        </div>
            <div class="col-lg-6 col-md-12 col-12 order-2 order-md-2 order-lg-1 valuesImage text-center pt-3">
                <img src="/wp-content/themes/Divi-child/images/values-page/value_1.jpg" alt="">
            </div>
            <div class="col-lg-6 col-md-12 order-1 order-md-1 order-lg-2 col-12 valueTextContainer">
                <p>Acționăm cu grijă față de natură. Suntem o echipă dedicată protejării mediului înconjurător și suntem
                    convinși că putem face o diferență semnificativă împreună.</p>
                <p>Faci parte dintr-o companie…</p>
                <p>… care pune mare preț pe sustenabilitate?</p>
                <p>… căreia îi pasă de protejarea mediului înconjurător?</p>
                <p>… care face eforturi pentru a fi cât mai eco-friendly?</p>
                <p>… care susține proiecte ale comunității?</p>
            </div>
            <div class="col-lg-6 col-md-12 col-12 order-3 order-md-3 order-lg-3 valueTextContainer">
                <p>Dacă răspunsul este DA, cel puțin o dată, atunci te invităm să te alături MISIUNII NOASTRE.</p>
                <p>CODRU Festival este dedicat inițierii și susținerii proiectelor care au un impact pozitiv asupra mediului
                    și evenimentelor culturale. Ne dorim să aducem schimbări reale și durabile în aceste domenii, promovând
                    sustenabilitatea și diversitatea culturală. Încurajăm diferite forme de expresie artistică care aduc
                    bucurie și inspirație comunității noastre.</p>
            </div>
            <div class="col-lg-6 col-md-12 col-12 order-4 order-md-4 order-lg-4 valuesImage text-center pt-3">
                <img src="/wp-content/themes/Divi-child/images/values-page/value_3.jpeg" alt="">
            </div>
            <div class="col-lg-6 col-md-12 col-12 order-6 order-md-6 order-lg-5 valuesImage text-center pt-3">
                <img src="/wp-content/themes/Divi-child/images/values-page/value_4.jpeg" alt="">
            </div>
            <div class="col-lg-6 col-md-12 col-12 order-5 order-md-5 order-lg-6 pt-3 valueTextContainer">
                <p>La CODRU...</p>
                <p>… ne străduim să construim o comunitate puternică, în care se creează legături durabile între oameni și natură</p>
                <p>… inițiem multiple sesiuni de plantare și ecologizare, an de an, alături de sute de voluntari</p>
                <p>… suntem siguri că proiectele noastre vor aduce o serie de beneficii pentru compania ta precum și
                    expunere și vizibilitate în comunitatea locală și în rândul participanților la evenimentele noastre.</p>
            </div>
            <div class="col-lg-6 col-md-12 col-12 order-7 order-md-7 order-lg-7 valueTextContainer">
                <p>Ne-am propus să aducem în comunitatea noastră, precum și în lumea afacerilor, o schimbare pozitivă.</p>
                <p>Dacă te alături misiunii noastre, compania ta va beneficia de o expunere semnificativă și vizibilitate în
                    comunitatea locală. Mii de participanți s-au alăturat deja acțiunilor și evenimentelor noastre,
                    ceea ce ne oferă oportunitatea de a face cunoscute valorile companiei din care faci parte.</p>
                <p>Suntem nerăbdători să aflăm cum poate compania din care faci parte să se alăture misiunii CODRU.</p>
            </div>
            <div class="col-lg-6 col-md-12 col-12 order-8 order-md-8 order-lg-8 valuesImage text-center pt-3">
                <img src="/wp-content/themes/Divi-child/images/values-page/value_5.jpeg" alt="">
            </div>
    </div>
</div>


<?php get_footer('codru2023live'); ?>