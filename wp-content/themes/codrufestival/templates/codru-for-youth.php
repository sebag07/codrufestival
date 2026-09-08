<?php
/**
 * Template Name: CODRU For Youth
 */

$theme_uri = get_template_directory_uri();
$youth_dir = $theme_uri . '/images/youth';
$youth_path = get_template_directory() . '/images/youth';

$youth_numbered_gallery = static function ($url_base, $filenames, $alt) {
    $images = array();

    foreach ($filenames as $filename) {
        $images[] = array(
            'src' => $url_base . '/' . $filename,
            'alt' => $alt,
        );
    }

    return $images;
};

$oxygen_images = array();
$oxygen_files = glob($youth_path . '/oxygen-booth/*.{jpg,jpeg,JPG,JPEG}', GLOB_BRACE) ?: array();
natsort($oxygen_files);

foreach ($oxygen_files as $file) {
    $oxygen_images[] = array(
        'src' => $youth_dir . '/oxygen-booth/' . rawurlencode(basename($file)),
        'alt' => 'Oxygen Booth — vernisaj CODRU for YOUth',
    );
}

$youth_intro = array(
    'kicker' => 'CODRU for YOUth',
    'title' => 'CODRU for YOUth',
    'banner' => $youth_dir . '/take-a-seat-1.jpg',
    'lede' => 'Proiect cultural-educativ care aduce împreună tineri artiști, voluntari, mentori și comunitatea, oferindu-le oportunitatea de a crea intervenții artistice pentru spațiul public și de a transforma orașul într-un loc al dialogului și al creativității.',
    'body' => array(
        'Pe parcursul proiectului, peste 100 de tineri își dezvoltă competențe prin ateliere de creație, mentorat și voluntariat cultural, iar rezultatele muncii lor devin parte din oraș.',
        'Prin CODRU for YOUth, Asociația Balvia își propune să creeze un cadru în care tinerii nu sunt doar spectatori ai vieții culturale, ci devin creatori, voluntari și ambasadori ai schimbării în propriul oraș. Proiectul aduce împreună educația non-formală, arta contemporană și implicarea civică, demonstrând că dezvoltarea unei comunități începe prin oferirea unor experiențe reale de participare.',
    ),
    'coordinators_label' => 'Coordonatori',
    'coordinators' => array(
        'lector univ. dr. Elena Aronoaie-Adorian',
        'lector univ. dr. Mihai Zgondoiu',
    ),
    'funding' => 'CODRU for YOUth este un proiect finanțat de Municipiul Timișoara, prin Centrul de Proiecte, în cadrul programului „Tineret în acțiune 2026”.',
);

$youth_installations = array(
    array(
        'id' => 'take-a-seat',
        'label' => 'Take a Seat',
        'title' => 'Take a Seat',
        'subtitle' => 'Instalație urbană',
        'description' => '<p>Instalația „Take a Seat”, un obiect urban supradimensionat, realizat printr-un proces colaborativ de către tineri artiști mentorați de lector univ. dr. Elena Aronoaie-Adorian și lector univ. dr. Mihai Zgondoiu, pornind de la întrebarea care stă la baza întregului proiect: „Ce se schimbă și ce rămâne atunci când tehnologia avansează?” Instalația explorează întâlnirea dintre tehnicile artistice tradiționale și noile forme de expresie contemporană.</p><p>Coordonat artistic de lector univ. dr. Elena Aronoaie-Adorian și lector univ. dr. Mihai Zgondoiu, atelierul a oferit participanților oportunitatea de a experimenta tehnici artistice tradiționale și contemporane, de a lucra colaborativ și de a transforma o idee într-o intervenție artistică destinată spațiului public. Instalația Take a Seat propune o reflecție asupra felului în care urbanul impactează natura, iar tehnologia schimbă lumea din jurul nostru, invitând publicul să redescopere spațiul urban ca loc al dialogului și al întâlnirii.</p>',
        'video' => array(
            'src' => $youth_dir . '/take-a-seat-video-720.mov',
            'poster' => $youth_dir . '/take-a-seat-1.jpg',
            'label' => 'Take a Seat — video',
        ),
        'images' => $youth_numbered_gallery(
            $youth_dir,
            array(
                'take-a-seat-1.jpg',
                'take-a-seat-2.jpg',
                'take-a-seat-3.jpg',
                'take-a-seat-4.jpg',
                'take-a-seat-5.jpg',
            ),
            'Take a Seat — instalație urbană CODRU for YOUth'
        ),
    ),
    array(
        'id' => 'formare-voluntari',
        'label' => 'Formare voluntari',
        'title' => 'Formare voluntari',
        'subtitle' => 'Laborator de competențe în voluntariat',
        'description' => '<p>Laboratorul de competențe în voluntariat cultural, unde aproape 100 de tineri au participat la sesiuni de formare bazate pe metode experiențiale și participative. În grupuri mici, participanții au explorat teme precum organizarea evenimentelor pe departamente, voluntariatul ca atitudine, lucrul în echipă și valorile personale în contextul creației culturale. La finalul programului, aceștia au devenit voluntarii care vor contribui la desfășurarea activităților proiectului și vor media interacțiunea publicului cu instalațiile artistice realizate în cadrul CODRU for YOUth.</p>',
        'images' => $youth_numbered_gallery(
            $youth_dir,
            array(
                'formare-1.jpg',
                'formare-2.jpg',
                'formare-3.jpg',
                'formare-4.jpg',
                'formare-5.jpg',
            ),
            'Formare voluntari — laborator de competențe CODRU for YOUth'
        ),
    ),
    array(
        'id' => 'oxygen-booth',
        'label' => 'Oxygen Booth',
        'title' => 'Oxygen Booth',
        'subtitle' => 'A Place to Breathe',
        'description' => '<p>CODRU Oxygen Booth - A Place to Breathe aduce un petec de pădure în mijlocul orașului și creează un spațiu în care natura, arta și oamenii se întâlnesc.</p><p>Construită în jurul unei nevoi simple și esențiale, aceea de a respira, instalația recreează un fragment de natură prin vegetație vie, mușchi, pietre și scoarță de copac, completate de lumină și de o coloană sonoră inspirată de sunetele pădurii.</p><p>Mai mult decât o instalație artistică, Oxygen Booth este o invitație la reconectare cu natura și la conștientizarea relației de interdependență dintre noi și mediul în care trăim. Un loc în care, pentru câteva minute, orașul rămâne afară și îți dai voie pur și simplu să respiri.</p>',
        'images' => $oxygen_images,
    ),
);

get_header();
?>

<div class="codru-youth-page">
    <div class="codru-youth-page__wrap">
        <header class="codru-youth-page__intro">
            <?php if (!empty($youth_intro['kicker'])) : ?>
                <p class="codru-youth-page__kicker"><?php echo esc_html($youth_intro['kicker']); ?></p>
            <?php endif; ?>
            <h1 class="codru-youth-page__title"><?php echo esc_html($youth_intro['title']); ?></h1>
            <?php if (!empty($youth_intro['banner'])) : ?>
                <figure class="codru-youth-page__banner">
                    <img src="<?php echo esc_url($youth_intro['banner']); ?>" alt="<?php echo esc_attr($youth_intro['title']); ?>">
                </figure>
            <?php endif; ?>
            <?php if (!empty($youth_intro['lede'])) : ?>
                <p class="codru-youth-page__lede"><?php echo esc_html($youth_intro['lede']); ?></p>
            <?php endif; ?>
            <?php if (!empty($youth_intro['body'])) : ?>
                <div class="codru-youth-page__body">
                    <?php foreach ($youth_intro['body'] as $paragraph) : ?>
                        <p><?php echo esc_html($paragraph); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($youth_intro['coordinators'])) : ?>
                <div class="codru-youth-page__coordinators">
                    <p class="codru-youth-page__coordinators-label"><?php echo esc_html($youth_intro['coordinators_label']); ?></p>
                    <ul>
                        <?php foreach ($youth_intro['coordinators'] as $coordinator) : ?>
                            <li><?php echo esc_html($coordinator); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </header>

        <?php
        codrufestival_react_island('YouthInstallations', array(
            'defaultId' => 'take-a-seat',
            'installations' => $youth_installations,
        ));
        ?>

        <?php if (!empty($youth_intro['funding'])) : ?>
            <p class="codru-youth-page__funding"><?php echo esc_html($youth_intro['funding']); ?></p>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
