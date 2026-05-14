/**
 * Festivawl Calendar Admin JavaScript
 * Handles admin interface interactions and AJAX requests
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        console.log('Festivawl Admin JS loaded - Progressive Color Interface');
        
        try {
            initCacheClearButton();
            initFormValidation();
            initPreviewFunctionality();
            initPickrColorPickers();
            
            // Force sync all PCR buttons after a short delay to ensure Pickr is fully loaded
            setTimeout(function() {
                try {
                    syncAllPcrButtons();
                } catch (error) {
                    console.error('Error syncing PCR buttons:', error);
                }
            }, 500);
        } catch (error) {
            console.error('Error during admin JS initialization:', error);
            showNotice('error', 'JavaScript initialization error: ' + error.message);
        }
        
        // Add progressive stage button handler (delegated event)
        $(document).on('click', '.add-progressive-stage', function() {
            var stageNum = $(this).data('stage');
            console.log('Add progressive stage clicked for stage:', stageNum);
            addProgressiveStage(stageNum);
        });
        
        // Remove progressive stage button handlers (delegated event)
        $(document).on('click', '.remove-progressive-stage', function() {
            var $row = $(this).closest('.progressive-stage-row');
            var stageNum = $row.data('stage');
            
            console.log('Remove progressive stage clicked for stage:', stageNum);
            
            if (confirm('Are you sure you want to remove Stage ' + stageNum + ' colors?')) {
                removeProgressiveStage(stageNum, $row);
            }
        });
        
        // Reset all stage colors button handler
        $('#reset-all-stage-colors').on('click', function(e) {
            e.preventDefault();
            console.log('Reset all stage colors clicked');
            
            try {
                if (confirm('⚠️ WARNING: This will remove ALL custom stage colors and any unsaved changes!\n\nMake sure to save your settings first if you want to keep any current work.\n\nAre you sure you want to reset all stage colors?')) {
                    resetAllStageColors();
                }
            } catch (error) {
                console.error('Error in reset button handler:', error);
                showNotice('error', 'An error occurred: ' + error.message);
            }
        });
    });

    /**
     * Initialize cache clear button functionality
     */
    function initCacheClearButton() {
        $('#clear-cache-btn').on('click', function() {
            var $button = $(this);
            var originalText = $button.text();
            
            // Safety check for AJAX variables
            if (typeof festivawl_admin_ajax === 'undefined') {
                showNotice('error', 'AJAX configuration error. Please reload the page and try again.');
                return;
            }
            
            $button.prop('disabled', true).text('Clearing...');

            $.post(festivawl_admin_ajax.ajax_url, {
                action: 'festivawl_clear_cache',
                nonce: festivawl_admin_ajax.nonce
            }, function(response) {
                if (response.success) {
                    $button.text('Cache Cleared!');
                    showNotice('success', response.data.message);
                    setTimeout(function() {
                        $button.prop('disabled', false).text(originalText);
                    }, 2000);
                } else {
                    $button.text('Error!');
                    showNotice('error', 'Failed to clear cache: ' + (response.data || 'Unknown error'));
                    setTimeout(function() {
                        $button.prop('disabled', false).text(originalText);
                    }, 2000);
                }
            }).fail(function() {
                $button.text('Error!');
                showNotice('error', 'AJAX error occurred while clearing cache.');
                setTimeout(function() {
                    $button.prop('disabled', false).text(originalText);
                }, 2000);
            });
        });
    }

    /**
     * Initialize form validation
     */
    function initFormValidation() {
        // Add basic form validation here if needed
        $('form').on('submit', function() {
            // Basic validation can go here
        });
    }

    /**
     * Initialize preview functionality
     */
    function initPreviewFunctionality() {
        // API testing functionality
        $('.test-api-btn').on('click', function() {
            var festivalId = $('#festival_id_input').val();
            if (festivalId) {
                testFestivalAPI(festivalId);
            } else {
                showNotice('error', 'Please enter a festival ID first.');
            }
        });
    }

    // Store Pickr instances for cleanup
    var pickrInstances = [];
    
    /**
     * Sync ALL PCR buttons with their input values - global function
     */
    function syncAllPcrButtons() {
        $('.color-input-group').each(function() {
            var $group = $(this);
            var $input = $group.find('.color-hex-input');
            var $pcrButton = $group.find('.pcr-button');
            var currentHex = $input.val();
            
            if (currentHex && currentHex.startsWith('#') && $pcrButton.length > 0) {
                // ONLY set CSS custom property - let CSS rules handle the rest
                $pcrButton[0].style.setProperty('--pcr-color', currentHex, 'important');
                
                // Try to find and update Pickr instance directly - this is the most reliable method
                var pickrElement = $group.find('.pickr-color-trigger')[0];
                if (pickrElement && pickrElement._pickr) {
                    try {
                        pickrElement._pickr.setColor(currentHex, false); // false = don't trigger events
                        console.log('✅ Updated Pickr instance directly:', currentHex);
                    } catch (e) {
                        console.log('❌ Failed to update Pickr instance:', e.message);
                    }
                } else {
                    console.log('⚠️ No Pickr instance found for this color group');
                }
                
                console.log('Global synced PCR button (all methods) to:', currentHex);
            }
        });
    }

    /**
     * Initialize Pickr color pickers with compact Figma-like layout
     */
    function initPickrColorPickers() {
        $('.pickr-color-trigger').each(function() {
            var $trigger = $(this);
            var stage = $trigger.data('stage');
            var target = $trigger.data('target');
            var currentColor = $trigger.css('background-color');
            var $input = $trigger.siblings('.color-hex-input');
            
            // Convert rgb to hex if needed
            var hexColor = $input.val() || '#6B1954';
            
            // Create Pickr instance
            var pickr = Pickr.create({
                el: $trigger[0],
                theme: 'nano', // 'classic', 'monolith', or 'nano'
                position: 'bottom-middle', // Position relative to trigger
                closeOnScroll: true,
                closeWithKey: 'Escape',
                appClass: 'festivawl-color-picker',
                swatches: [
                    '#6B1954', '#ED1D1D', '#2E86AB', '#A23B72', 
                    '#F18F01', '#C73E1D', '#2E7D32', '#7B1FA2',
                    '#FF5722', '#4CAF50', '#2196F3', '#9C27B0'
                ],
                components: {
                    // Main components
                    preview: true,
                    opacity: false,
                    hue: true,
                    
                    // Input / output Options
                    interaction: {
                        hex: true,
                        rgba: false,
                        hsla: false,
                        hsva: false,
                        cmyk: false,
                        input: true,
                        clear: true,
                        save: false  // Remove save button - auto-save on change
                    }
                },
                strings: {
                    clear: 'Reset'
                },
                default: hexColor
            });
            
            // Simple function to sync button color with input value
            function syncButtonColor() {
                syncAllPcrButtons(); // Just sync all of them
            }
            
            // Handle color changes - auto-save without save button
            pickr.on('change', function(color) {
                var hexValue = color.toHEXA().toString();
                $input.val(hexValue);
                syncButtonColor(); // Sync button to input value
                console.log('Color auto-saved:', hexValue);
            });
            
            pickr.on('clear', function() {
                var defaultColor = target === 'bg' ? '#6B1954' : '#ED1D1D';
                $input.val(defaultColor);
                syncButtonColor(); // Sync button to input value
                pickr.setColor(defaultColor);
                console.log('Color cleared to default:', defaultColor);
            });
            
            // Also sync on picker init
            syncButtonColor();
            
            // Store instance for cleanup and later access
            pickrInstances.push(pickr);
            
            // Store pickr instance on the trigger element for easy access
            $trigger[0]._pickr = pickr;
        });
    }
    
    /**
     * Re-initialize color pickers for dynamically added content
     */
    function reinitColorPickers() {
        // Destroy existing Pickr instances
        pickrInstances.forEach(function(pickr) {
            if (pickr && typeof pickr.destroy === 'function') {
                pickr.destroy();
            }
        });
        pickrInstances = [];
        
        // Re-initialize all color pickers
        initPickrColorPickers();
    }
    
    /**
     * Create HTML for "Add Stage X" button
     */
    function createAddStageButton(stageNum) {
        return '<div class="progressive-stage-row inactive" data-stage="' + stageNum + '" style="background: #f9f9f9; border: 2px dashed #ccc; padding: 20px; margin-bottom: 15px; border-radius: 6px; text-align: center;">' +
               '<button type="button" class="add-progressive-stage" data-stage="' + stageNum + '" ' +
               'style="background: #0073aa; color: white; border: none; padding: 12px 20px; border-radius: 4px; font-size: 14px; cursor: pointer; font-weight: 500;">' +
               '+ Add Stage ' + stageNum + ' Colors' +
               '</button>' +
               '<p style="margin: 8px 0 0 0; color: #666; font-size: 13px;">Customize colors for stage ' + stageNum + '</p>' +
               '</div>';
    }


    /**
     * Add a progressive stage
     */
    function addProgressiveStage(stageNum) {
        console.log('Adding progressive stage:', stageNum);
        
        // Safety check for AJAX variables
        if (typeof festivawl_admin_ajax === 'undefined') {
            showNotice('error', 'AJAX configuration error. Please reload the page and try again.');
            return;
        }
        
        showLoadingOverlay('Adding Stage ' + stageNum + '...');
        
        $.post(festivawl_admin_ajax.ajax_url, {
            action: 'festivawl_add_stage_color',
            stage_num: stageNum,
            nonce: festivawl_admin_ajax.nonce
        }, function(response) {
            console.log('AJAX response for add progressive stage:', response);
            hideLoadingOverlay();
            
            if (response.success) {
                // Replace the inactive row with the active stage HTML
                var $inactiveRow = $('.progressive-stage-row.inactive[data-stage="' + stageNum + '"]');
                $inactiveRow.replaceWith(response.data.html);
                
                // Initialize Pickr color pickers for the new stage only
                var $newStage = $('.progressive-stage-row.active[data-stage="' + stageNum + '"]');
                $newStage.find('.pickr-color-trigger').each(function() {
                    var $trigger = $(this);
                    var stage = $trigger.data('stage');
                    var target = $trigger.data('target');
                    var $input = $trigger.siblings('.color-hex-input');
                    var hexColor = $input.val() || (target === 'bg' ? '#6B1954' : '#ED1D1D');
                    
                    var pickr = Pickr.create({
                        el: $trigger[0],
                        theme: 'nano',
                        position: 'bottom-middle',
                        closeOnScroll: true,
                        closeWithKey: 'Escape',
                        appClass: 'festivawl-color-picker',
                        swatches: [
                            '#6B1954', '#ED1D1D', '#2E86AB', '#A23B72', 
                            '#F18F01', '#C73E1D', '#2E7D32', '#7B1FA2',
                            '#FF5722', '#4CAF50', '#2196F3', '#9C27B0'
                        ],
                        components: {
                            preview: true,
                            opacity: false,
                            hue: true,
                            interaction: {
                                hex: true,
                                rgba: false,
                                hsla: false,
                                hsva: false,
                                cmyk: false,
                                input: true,
                                clear: true,
                                save: false  // Remove save button - auto-save on change
                            }
                        },
                        strings: {
                            clear: 'Reset'
                        },
                        default: hexColor
                    });
                    
                    // Simple function to sync button color with input value
                    function syncButtonColor() {
                        // Just sync all PCR buttons on the page
                        $('.color-input-group').each(function() {
                            var $group = $(this);
                            var $input = $group.find('.color-hex-input');
                            var $pcrButton = $group.find('.pcr-button');
                            var currentHex = $input.val();
                            
                            if (currentHex && currentHex.startsWith('#') && $pcrButton.length > 0) {
                                // Use CSS custom property to avoid !important conflicts
                                $pcrButton[0].style.setProperty('--pcr-color', currentHex, 'important');
                                console.log('Dynamic synced PCR button to:', currentHex);
                            }
                        });
                    }
                    
                    pickr.on('change', function(color) {
                        var hexValue = color.toHEXA().toString();
                        $input.val(hexValue);
                        syncButtonColor(); // Sync button to input value
                        console.log('Dynamic color auto-saved:', hexValue);
                    });
                    
                    pickr.on('clear', function() {
                        var defaultColor = target === 'bg' ? '#6B1954' : '#ED1D1D';
                        $input.val(defaultColor);
                        syncButtonColor(); // Sync button to input value
                        pickr.setColor(defaultColor);
                        console.log('Dynamic color cleared to default:', defaultColor);
                    });
                    
                    // Also sync on picker init
                    syncButtonColor();
                    
                    pickrInstances.push(pickr);
                    
                    // Store pickr instance on the trigger element for easy access
                    $trigger[0]._pickr = pickr;
                    
                    // Force sync all buttons after a short delay
                    setTimeout(function() {
                        syncAllPcrButtons();
                    }, 200);
                });
                
                // Add the next "Add stage" button if it doesn't exist
                var nextStageNum = stageNum + 1;
                if ($('.progressive-stage-row[data-stage="' + nextStageNum + '"]').length === 0) {
                    var nextStageHtml = createAddStageButton(nextStageNum);
                    $('#festivawl-stage-colors-container').append(nextStageHtml);
                }
                
                showNotice('success', 'Stage ' + stageNum + ' colors added successfully! Remember to save your settings to keep all changes.');
            } else {
                console.error('Failed to add progressive stage:', response.data);
                showNotice('error', 'Error adding stage: ' + (response.data || 'Unknown error'));
            }
        }).fail(function(xhr, status, error) {
            console.error('AJAX error adding progressive stage:', status, error);
            hideLoadingOverlay();
            showNotice('error', 'AJAX error occurred while adding stage: ' + error);
        });
    }
    
    /**
     * Remove a progressive stage
     */
    function removeProgressiveStage(stageNum, $row) {
        // Safety check for AJAX variables
        if (typeof festivawl_admin_ajax === 'undefined') {
            showNotice('error', 'AJAX configuration error. Please reload the page and try again.');
            return;
        }
        
        showLoadingOverlay('Removing Stage ' + stageNum + '...');
        
        $.post(festivawl_admin_ajax.ajax_url, {
            action: 'festivawl_remove_stage_color',
            stage_num: stageNum,
            nonce: festivawl_admin_ajax.nonce
        }, function(response) {
            hideLoadingOverlay();
            
            if (response.success) {
                // Remove the stage row
                $row.remove();
                
                // Check if we need to add back the "Add next stage" button
                var allStages = $('.progressive-stage-row.active').map(function() {
                    return parseInt($(this).data('stage'));
                }).get();
                
                if (allStages.length > 0) {
                    // Find the highest stage number and add the next "Add" button
                    var maxStage = Math.max.apply(Math, allStages);
                    var nextStageNum = maxStage + 1;
                    
                    // Only add if it doesn't already exist
                    if ($('.progressive-stage-row[data-stage="' + nextStageNum + '"]').length === 0) {
                        var nextStageHtml = createAddStageButton(nextStageNum);
                        $('#festivawl-stage-colors-container').append(nextStageHtml);
                    }
                } else {
                    // No stages left, add Stage 1 button
                    if ($('.progressive-stage-row[data-stage="1"]').length === 0) {
                        var stage1Html = createAddStageButton(1);
                        $('#festivawl-stage-colors-container').append(stage1Html);
                    }
                }
                
                showNotice('success', 'Stage ' + stageNum + ' colors removed successfully! Remember to save your settings to keep all changes.');
            } else {
                showNotice('error', 'Error removing stage: ' + (response.data || 'Unknown error'));
            }
        }).fail(function() {
            hideLoadingOverlay();
            showNotice('error', 'AJAX error occurred while removing stage.');
        });
    }
    
    /**
     * Reset all stage colors
     */
    function resetAllStageColors() {
        // Safety check for AJAX variables
        if (typeof festivawl_admin_ajax === 'undefined') {
            showNotice('error', 'AJAX configuration error. Please reload the page and try again.');
            return;
        }
        
        showLoadingOverlay('Resetting all colors...');
        
        $.post(festivawl_admin_ajax.ajax_url, {
            action: 'festivawl_reset_stage_colors',
            nonce: festivawl_admin_ajax.nonce
        }, function(response) {
            hideLoadingOverlay();
            
            if (response.success) {
                showNotice('success', 'All stage colors have been reset successfully!');
                // Reload after a short delay to show the success message
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                showNotice('error', 'Error resetting stage colors: ' + (response.data || 'Unknown error'));
            }
        }).fail(function(xhr, status, error) {
            hideLoadingOverlay();
            showNotice('error', 'AJAX error occurred while resetting stage colors: ' + error);
            console.error('Reset AJAX Error:', xhr, status, error);
        });
    }

    /**
     * Test festival API connection
     */
    function testFestivalAPI(festivalId) {
        showLoadingOverlay('Testing API connection...');

        $.post(festivawl_admin_ajax.ajax_url, {
            action: 'festivawl_test_api',
            festival_id: festivalId,
            nonce: festivawl_admin_ajax.nonce
        }, function(response) {
            hideLoadingOverlay();

            if (response.success) {
                var data = response.data;
                var message = 'API Test Successful!\n\n' +
                             'Festival ID: ' + data.festival_id + '\n' +
                             'Events: ' + data.event_count + '\n' +
                             'Stages: ' + data.stage_count + '\n' +
                             'Days: ' + data.day_count;
                
                showNotice('success', message);
            } else {
                showNotice('error', 'API Test Failed: ' + (response.data || 'Unknown error'));
            }
        }).fail(function() {
            hideLoadingOverlay();
            showNotice('error', 'AJAX error occurred during API test.');
        });
    }

    /**
     * Show admin notice
     */
    function showNotice(type, message) {
        try {
            var $notice = $('<div class="notice notice-' + type + ' is-dismissible"><p>' + message + '</p></div>');
            var $target = $('.wrap h1');
            if ($target.length === 0) {
                $target = $('.wrap').first();
            }
            if ($target.length === 0) {
                $target = $('body');
            }
            $target.after($notice);
            
            // Auto-dismiss after 5 seconds
            setTimeout(function() {
                if ($notice && $notice.fadeOut) {
                    $notice.fadeOut();
                }
            }, 5000);
        } catch (error) {
            console.error('Error showing notice:', error);
            // Fallback: use browser alert
            alert(type.toUpperCase() + ': ' + message);
        }
    }

    /**
     * Show loading overlay
     */
    function showLoadingOverlay(message) {
        try {
            message = message || 'Loading...';
            
            // Remove any existing overlays first
            hideLoadingOverlay();
            
            var $overlay = $('<div class="festivawl-loading-overlay">' +
                            '<div class="festivawl-loading-content">' +
                            '<div class="festivawl-spinner"></div>' +
                            '<p>' + message + '</p>' +
                            '</div>' +
                            '</div>');
            
            $('body').append($overlay);
        } catch (error) {
            console.error('Error showing loading overlay:', error);
        }
    }

    /**
     * Hide loading overlay
     */
    function hideLoadingOverlay() {
        try {
            $('.festivawl-loading-overlay').remove();
        } catch (error) {
            console.error('Error hiding loading overlay:', error);
        }
    }

    // Expose functions for global use
    window.FestivawalAdmin = {
        showNotice: showNotice,
        showLoadingOverlay: showLoadingOverlay,
        hideLoadingOverlay: hideLoadingOverlay,
        testFestivalAPI: testFestivalAPI
    };

})(jQuery); 