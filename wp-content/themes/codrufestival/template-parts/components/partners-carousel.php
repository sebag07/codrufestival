<?php
// Fetch all partners from ACF options fields (as in partners.php)
$partner_groups = [
    [
        'acf_key' => 'partners_level_1',
        'name_field' => 'partner_name',
        'image_field' => 'partner_image',
    ],
    [
        'acf_key' => 'partners_level_2',
        'name_field' => 'partner_name',
        'image_field' => 'partner_image',
    ],
    [
        'acf_key' => 'partners_level_3',
        'name_field' => 'partner_name',
        'image_field' => 'partner_image',
    ],
    [
        'acf_key' => 'partners_level_4',
        'name_field' => 'partner_name',
        'image_field' => 'partner_image',
    ],
    [
        'acf_key' => 'partners_level_5',
        'name_field' => 'partner_name',
        'image_field' => 'partner_image',
    ],
    [
        'acf_key' => 'produced_by',
        'name_field' => 'producer_name',
        'image_field' => 'producer_image',
    ],
];

$all_partners = [];
foreach ($partner_groups as $group) {
    if (have_rows($group['acf_key'], 'options')) {
        while (have_rows($group['acf_key'], 'options')) {
            the_row();
            $name = get_sub_field($group['name_field']);
            $image = get_sub_field($group['image_field']);
            if ($image) {
                $all_partners[] = [
                    'name' => $name,
                    'image' => $image,
                ];
            }
        }
    }
}

// Group partners into chunks of 4 for 2x2 grid
$partner_chunks = array_chunk($all_partners, 4);
?>

<div class="partners-carousel-container">
    <h2 class="sectionTitle"><?php echo get_multilingual_text('Parteneri', 'Partners', 'en'); ?></h2>
    <!-- Owl Carousel -->
    <div class="owl-carousel partners-owl">
        <?php foreach ($partner_chunks as $chunk): ?>
            <div class="item">
                <div class="partners-grid">
                    <?php foreach ($chunk as $partner): ?>
                        <div class="partner-item">
                            <img src="<?php echo esc_url($partner['image']); ?>" alt="<?php echo esc_attr($partner['name']); ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.owl-carousel {
    display: block;
}
@media (max-width: 991px) {
    .owl-carousel {
        display: block;
    }
}

.partners-carousel-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.partners-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-template-rows: 1fr 1fr;
    gap: 20px;
    width: 300px;
    height: 300px;
    margin: 0 auto;
}

@media (max-width: 1200px) {
    .partners-grid {
        width: auto;
        height: auto;
    }
}

.partner-item {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 15px;
    border-radius: 8px;
}

.partner-item img {
    max-width: 120px;
    max-height: 120px;
    width: auto;
    height: auto;
    object-fit: contain;
}

/* Owl Carousel custom styles */
.owl-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 100%;
    pointer-events: none;
}

.owl-prev, .owl-next {
    position: absolute;
    background: rgba(0,0,0,0.5) !important;
    color: white !important;
    padding: 10px 15px !important;
    border-radius: 50% !important;
    pointer-events: auto;
}

.owl-prev {
    left: -50px;
}

.owl-next {
    right: -50px;
}

.owl-dots {
    text-align: center;
    margin-top: 20px;
}

.owl-dot {
    display: inline-block;
    margin: 0 5px;
}

.owl-dot span {
    width: 10px;
    height: 10px;
    background: #ccc;
    border-radius: 50%;
    display: block;
}

.owl-dot.active span {
    background: var(--button-color);
}
</style>