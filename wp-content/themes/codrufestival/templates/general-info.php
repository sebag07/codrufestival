<?php /*  Template Name: General Info  */ ?>
<?php get_header(); ?>

<?php
$plecari_pdf = home_url('/docs/linia-46-plecari-codru.pdf');
$orar_xlsx = home_url('/docs/linia-46-orar-zl-zn.xlsx');
?>

<section class="info-wrap container-fluid single-page header-padding">
    <h1 class="info-title pb-4 text-center" style="color: #fff;"><?php echo get_multilingual_text('Informații', 'Information', 'ro'); ?></h1>


    <div class="col-lg-12 col-md-12 col-12 categoriesContainer">
        <span id="category-filter">
            <label class="activitiesCheckbox activeCategory" for="all"><input class="allcat" id="all" type="radio" name="activity-type" value="all" checked><span>Toate</span></label>
            <?php

            $activities = [
                ['id' => 'transport', 'label' => get_multilingual_text('Transport', 'Transport', 'ro')],
            ];

            foreach ($activities as $activity) {
                echo '<label class="activitiesCheckbox" for="' . $activity['id'] . '"><input class="catCheckbox" id="' . $activity['id'] . '" type="radio" name="activity-type" value="' . $activity['id'] . '"><span>' . $activity['label'] . '</span></label>';
            }
            ?>
        </span>
    </div>

    <div data-category="transport">
        <div class="info-content">
            <h2 class="info-title text-white"><?php echo get_multilingual_text('Transport', 'Transport', 'ro'); ?></h2>

            <h3><?php echo get_multilingual_text('Plecări Linia 46', 'Line 46 departures', 'ro'); ?></h3>
            <p><?php echo get_multilingual_text(
                'Plecări de la stațiile Bastion și Denya Forest, 28–30 august 2026.',
                'Departures from Bastion and Denya Forest, 28–30 August 2026.',
                'ro'
            ); ?></p>
            <a class="info-cta" target="_blank" rel="noopener" href="<?php echo esc_url($plecari_pdf); ?>">
                <?php echo get_multilingual_text('Vezi PDF', 'View PDF', 'ro'); ?>
            </a>

            <h3><?php echo get_multilingual_text('Program complet Linia 46', 'Full Line 46 timetable', 'ro'); ?></h3>
            <p><?php echo get_multilingual_text(
                'Program zile lucrătoare și nelucrătoare (Excel).',
                'Weekday and weekend timetable (Excel).',
                'ro'
            ); ?></p>
            <a class="info-cta" download href="<?php echo esc_url($orar_xlsx); ?>">
                <?php echo get_multilingual_text('Descarcă Excel', 'Download Excel', 'ro'); ?>
            </a>
        </div>
    </div>
</section>

<style>
    /* Layout */
    .info-wrap {
        max-width: 1100px;
        margin: 0 auto;
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
        margin: 0 0 1rem;
        color: #fff;
        font-weight: 500;
    }

    .info-content .info-cta {
        margin-bottom: 2rem;
    }

    .info-cta {
        display: inline-block;
        padding: .6rem 1rem;
        border-radius: 999px;
        background: #fff;
        color: #0a1a8a;
        text-decoration: none;
        font-weight: 700;
    }

    .info-cta:hover,
    .info-cta:focus {
        color: #0a1a8a;
        text-decoration: none;
        opacity: .9;
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
            // First check for hash in URL (e.g., #transport)
            const hash = window.location.hash.replace('#', '');
            
            if (hash && ['transport'].includes(hash)) {
                return hash;
            }
            
            // Fallback: check URL path for category keywords
            const path = window.location.pathname;
            
            if (path.includes('/transport/')) {
                return 'transport';
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
