<?php /*  Template Name: General Info  */ ?>
<?php get_header(); ?>

<section class="info-wrap container-fluid single-page header-padding">
    <h1 class="info-title pb-4 text-center" style="color: #fff;">Informații</h1>


    <div class="col-lg-12 col-md-12 col-12 categoriesContainer">
        <span id="category-filter">
            <label class="activitiesCheckbox activeCategory" for="all"><input class="allcat" id="all" type="radio" name="activity-type" value="all" checked><span>Toate</span></label>
            <?php

            $activities = [
                ['id' => 'schedule', 'label' => get_multilingual_text('Orar Festival', 'Festival Schedule', 'ro')],
                ['id' => 'transport', 'label' => get_multilingual_text('Transport', 'Transport', 'ro')],
                ['id' => 'payment', 'label' => get_multilingual_text('Top Up/Payment', 'Top Up/Payment', 'ro')],
                ['id' => 'rules', 'label' => get_multilingual_text('Reguli Festival', 'Festival Rules', 'ro')],
            ];

            foreach ($activities as $activity) {
                echo '<label class="activitiesCheckbox" for="' . $activity['id'] . '"><input class="catCheckbox" id="' . $activity['id'] . '" type="radio" name="activity-type" value="' . $activity['id'] . '"><span>' . $activity['label'] . '</span></label>';
            }
            ?>
        </span>
    </div>

    <div data-category="schedule">
        <div class="info-content">
            <h2 class="info-title text-white"><?php echo get_multilingual_text('Orar Festival', 'Festival Schedule', 'ro'); ?></h2>
        </div>
        
        <div class="info-item">
            <div class="info-media">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/info/schedule/orar 4x5.jpg" alt="Everything you need to know about Top Up/Payment" />
            </div>
        </div>
    </div>

    <div data-category="transport">
        <div class="info-content">
            <h2 class="info-title text-white"><?php echo get_multilingual_text('Transport', 'Transport', 'ro'); ?></h2>
            <h3><?php echo get_multilingual_text('Am pregatit pentru tine orarul autobuzului aici:', 'We have prepared the bus timetable for you here:', 'ro'); ?></h3>
            <a class="info-cta" href="https://docs.google.com/spreadsheets/d/1A8q-TmBD9n__ZRQZ3czzVE8obhfBfSswXH76ybyLPbc/edit?gid=0#gid=0"><?php echo get_multilingual_text('ORAR', 'TIMETABLE', 'ro'); ?></a>
        </div>

        <!-- Item 1 (image left, text right) -->
        <div class="info-item">
            <div class="info-media">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/info/transport/1.jpg" alt="Transport Bicicleta" />
            </div>
            <div class="info-media">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/info/transport/2.jpg" alt="Stație de autobuz pentru linia 46" />
            </div>
            <div class="info-media">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/info/transport/3.jpg" alt="Transport Uber Taxi Bolt" />
            </div>
            <div class="info-media">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/info/transport/5.jpg" alt="Transport Masina" />
            </div>
        </div>
    </div>

    <div data-category="payment">
        <div class="info-content">
            <h2 class="info-title text-white"><?php echo get_multilingual_text('Top Up/Payment', 'Top Up/Payment', 'ro'); ?></h2>
        </div>
        
        <div class="info-item">
            <div class="info-media">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/info/payment/1 ing.jpg" alt="Everything you need to know about Top Up/Payment" />
            </div>
            <div class="info-media">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/info/payment/2 ing.jpg" alt="Everything you need to know about Top Up/Payment" />
            </div>
            <div class="info-media">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/info/payment/3 ing.jpg" alt="Everything you need to know about Top Up/Payment" />
            </div>
            <div class="info-media">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/info/payment/4 ing.jpg" alt="Everything you need to know about Top Up/Payment" />
            </div>
            <div class="info-media">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/info/payment/5 ing.jpg" alt="Everything you need to know about Top Up/Payment" />
            </div>
        </div>
    </div>

    <div data-category="rules">
        <div class="info-content">
            <h2 class="info-title text-white"><?php echo get_multilingual_text('Reguli Festival', 'Festival Rules', 'ro'); ?></h2>
        </div>
        
        <div class="info-item">
            <div class="info-media">
                <img 
                src="<?php echo get_stylesheet_directory_uri() . get_multilingual_text('/images/info/rules/permis-ro.jpg', '/images/info/rules/permis-en.jpg', 'ro'); ?>" 
                alt="<?php echo get_multilingual_text('Obiecte permise', 'Permitted objects', 'ro'); ?>" 
                />
            </div>
            <div class="info-media">
            <img 
                src="<?php echo get_stylesheet_directory_uri() . get_multilingual_text('/images/info/rules/interzis-ro.jpg', '/images/info/rules/interzis-en.jpg', 'ro'); ?>" 
                alt="<?php echo get_multilingual_text('Obiecte interzise', 'Prohibited objects', 'ro'); ?>" 
                />
            </div>
            <div class="info-media">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/info/rules/reguli-01.jpg" alt="Everything you need to know about Top Up/Payment" />
            </div>
            <div class="info-media">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/info/rules/reguli-02.jpg" alt="Everything you need to know about Top Up/Payment" />
            </div>
            <div class="info-media">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/info/rules/reguli-03.jpg" alt="Everything you need to know about Top Up/Payment" />
            </div>
            <div class="info-media">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/info/rules/reguli-10.jpg" alt="Everything you need to know about Top Up/Payment" />
            </div>
            <div class="info-media">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/info/rules/reguli-13.jpg" alt="Everything you need to know about Top Up/Payment" />
            </div>
        </div>
    </div>

    <!-- Add more items; alternate by toggling the .info-item--reverse class -->
</section>

<style>
    /* Layout */
    .info-wrap {
        max-width: 1100px;
        margin: 0 auto;
    }

    .info-item {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.25rem;
        margin: 1rem 0;
    }

    .info-media img {
        width: 100%;
        object-fit: cover;
        border-radius: 14px;
    }

    .info-content {
        color: #fff;
        padding-top: 10px;
    }

    .info-content h3 {
        font-size: clamp(1.25rem, 1vw + 1rem, 2rem);
        margin: .25rem 0 .5rem;
        color: #fff;
    }

    .info-content p {
        padding: 0;
        margin: 0;
        color: #fff;
        font-weight: 500;
    }

    .info-bullets {
        margin: 0 0 .75rem 1.1rem;
    }

    .info-cta {
        display: inline-block;
        padding: .6rem 1rem;
        border-radius: 999px;
        background: #fff;
        color: #0a1a8a;
        /* brand blue text */
        text-decoration: none;
        font-weight: 700;
    }

    /* Desktop: split 50/50, alternate by reversing order */
    @media (min-width: 576px) {
        .info-item {
            grid-template-columns: 1fr 1fr;
            align-items: center;
        }

        .info-item--reverse .info-media {
            order: 2;
        }

        .info-item--reverse .info-content {
            order: 1;
        }
    }

    /* Optional: sticky left nav on wide screens */
    @media (min-width: 1100px) {
        .left-nav {
            position: sticky;
            top: 1.5rem;
        }
    }
</style>

<script>
    jQuery(document).ready(function() {
        function filterContent() {
            let selectedCategory = jQuery("input[name='activity-type']:checked").val();

            // Hide all content sections first
            jQuery("[data-category]").hide();

            if (selectedCategory === 'all') {
                // Show all content sections
                jQuery("[data-category]").show();
            } else {
                // Show only the selected category
                jQuery("[data-category='" + selectedCategory + "']").show();
            }
        }

        function setCheckedAttributes() {
            jQuery("input[name='activity-type']").each(function() {
                if (jQuery(this).is(":checked")) {
                    jQuery(this).prop("checked", true);
                    jQuery(this).parent().addClass('activeCategory');
                } else {
                    jQuery(this).prop("checked", false);
                    jQuery(this).parent().removeClass('activeCategory');
                }
            });
        }

        function getUrlParameter() {
            // First check for hash in URL (e.g., #transport, #schedule)
            const hash = window.location.hash.replace('#', '');
            
            if (hash && ['transport', 'schedule', 'payment', 'rules'].includes(hash)) {
                return hash;
            }
            
            // Fallback: check URL path for category keywords
            const path = window.location.pathname;
            
            if (path.includes('/transport/')) {
                return 'transport';
            } else if (path.includes('/schedule/')) {
                return 'schedule';
            } else if (path.includes('/payment/')) {
                return 'payment';
            } else if (path.includes('/rules/')) {
                return 'rules';
            }
            
            return 'all'; // Default to 'all' if no specific category found
        }

        function initializeWithUrlParameter() {
            const urlCategory = getUrlParameter();
            
            // Find and select the appropriate radio button
            const targetRadio = jQuery("input[name='activity-type'][value='" + urlCategory + "']");
            if (targetRadio.length) {
                targetRadio.prop('checked', true);
                setCheckedAttributes();
                filterContent();
            }
        }

        // Attach change event listener to radio buttons
        jQuery("input[name='activity-type']").on('change', function() {
            setCheckedAttributes();
            filterContent();
            
            // Update URL hash
            const selectedValue = jQuery(this).val();
            if (selectedValue === 'all') {
                // Remove hash for 'all' selection
                if (window.history && window.history.replaceState) {
                    window.history.replaceState(null, null, window.location.pathname);
                }
            } else {
                // Add hash for specific category
                if (window.history && window.history.replaceState) {
                    window.history.replaceState(null, null, window.location.pathname + '#' + selectedValue);
                }
            }
        });

        // Attach click event listener to labels for better UX
        jQuery("label.activitiesCheckbox").on('click', function() {
            const radioId = jQuery(this).find('input[type="radio"]').attr('id');
            jQuery("#" + radioId).prop('checked', true).trigger('change');
        });

        // Initialize with URL parameter detection
        initializeWithUrlParameter();
    });
</script>


<?php get_footer(); ?>