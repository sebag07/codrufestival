/**
 * Festivawl Calendar Public JavaScript
 * Handles day switching and interactive functionality
 */

(function($) {
    'use strict';

    /**
     * Initialize a Festivawl Calendar instance
     * @param {string} calendarId - The unique ID of the calendar container
     */
    function initFestivawalCalendar(calendarId) {
        const $container = $('#' + calendarId);
        
        if (!$container.length) {
            console.warn('Festivawl Calendar: Container not found for ID:', calendarId);
            return;
        }

        // Initialize day tab switching
        initDayTabSwitching($container);
        
        // Initialize event interactions
        initEventInteractions($container);
        
        // Initialize responsive behavior
        initResponsiveBehavior($container);
        
        // Initialize drag-to-scroll functionality
        initDragToScroll($container);
    }

    /**
     * Initialize day tab switching functionality
     * @param {jQuery} $container - Calendar container
     */
    function initDayTabSwitching($container) {
        const $dayTabs = $container.find('.festivawl-day-tab');
        const $dayContents = $container.find('.festivawl-day-content');

        $dayTabs.on('click', function(e) {
            e.preventDefault();
            
            const $tab = $(this);
            const dayIndex = $tab.data('day');
            
            // Remove active class from all tabs and contents
            $dayTabs.removeClass('active');
            $dayContents.removeClass('active');
            
            // Add active class to clicked tab
            $tab.addClass('active');
            
            // Show corresponding day content
            const $targetContent = $container.find(`.festivawl-day-content[data-day="${dayIndex}"]`);
            $targetContent.addClass('active');
            
            // Trigger custom event for potential third-party integrations
            $container.trigger('festivawl:dayChanged', {
                dayIndex: dayIndex,
                tab: $tab[0],
                content: $targetContent[0]
            });
        });

        // Keyboard navigation for tabs
        $dayTabs.on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $(this).click();
            }
            
            if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
                e.preventDefault();
                const direction = e.key === 'ArrowLeft' ? -1 : 1;
                const currentIndex = $dayTabs.index(this);
                const nextIndex = currentIndex + direction;
                
                if (nextIndex >= 0 && nextIndex < $dayTabs.length) {
                    $dayTabs.eq(nextIndex).focus().click();
                }
            }
        });
    }

    /**
     * Initialize event block interactions
     * @param {jQuery} $container - Calendar container
     */
    function initEventInteractions($container) {
        const $eventBlocks = $container.find('.festivawl-event-block');

        // Event hover effects (already handled by CSS, but we can add custom logic here)
        $eventBlocks.on('mouseenter', function() {
            const $event = $(this);
            const eventId = $event.data('event-id');
            
            // Could highlight related events or show additional info
            $container.trigger('festivawl:eventHover', {
                eventId: eventId,
                element: this
            });
        });

        // Event click interactions
        $eventBlocks.on('click', function(e) {
            e.preventDefault();
            
            const $event = $(this);
            const eventId = $event.data('event-id');
            const artist = $event.find('.event-artist').text();
            const time = $event.find('.event-time').text();
            const stage = $event.find('.event-stage').text();
            
            // Trigger custom event for potential modal/popup integrations
            $container.trigger('festivawl:eventClicked', {
                eventId: eventId,
                artist: artist,
                time: time,
                stage: stage,
                element: this
            });
            
            // Basic click feedback
            $event.addClass('clicked');
            setTimeout(() => {
                $event.removeClass('clicked');
            }, 200);
        });

        // Keyboard accessibility for events
        $eventBlocks.on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $(this).click();
            }
        });
    }

    /**
     * Initialize responsive behavior
     * @param {jQuery} $container - Calendar container
     */
    function initResponsiveBehavior($container) {
        let resizeTimeout;
        
        $(window).on('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                adjustEventPositions($container);
            }, 150);
        });
    }



    /**
     * Adjust event positions on resize (if needed)
     * @param {jQuery} $container - Calendar container
     */
    function adjustEventPositions($container) {
        // Event positions are handled by PHP calculations and CSS
        // This function is available for future enhancements
        $container.trigger('festivawl:positionsAdjusted');
    }

    /**
     * Initialize drag-to-scroll functionality for horizontal scrolling
     * @param {jQuery} $container - Calendar container
     */
    function initDragToScroll($container) {
        const scrollableWrappers = $container.find('.festivawl-scrollable-area');
        
        scrollableWrappers.each(function() {
            const wrapper = this;
            let isDown = false;
            let startX;
            let scrollLeft;

            const handleMouseDown = (e) => {
                // Don't interfere with button clicks or other interactive elements
                if (e.target.closest('.festivawl-day-tab, .festivawl-event-block')) {
                    return;
                }
                
                isDown = true;
                wrapper.classList.add('festivawl-dragging');
                startX = e.pageX - wrapper.offsetLeft;
                scrollLeft = wrapper.scrollLeft;
                document.body.style.cursor = 'grabbing';
                document.body.style.userSelect = 'none'; // Prevent text selection
            };

            const handleMouseUp = () => {
                isDown = false;
                wrapper.classList.remove('festivawl-dragging');
                document.body.style.cursor = 'default';
                document.body.style.userSelect = '';
            };

            const handleMouseMove = (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - wrapper.offsetLeft;
                const walk = (x - startX) * 1.2; // Slightly faster scrolling for better UX
                requestAnimationFrame(() => {
                    wrapper.scrollLeft = scrollLeft - walk;
                });
            };

            const handleMouseLeave = () => {
                if (isDown) {
                    isDown = false;
                    wrapper.classList.remove('festivawl-dragging');
                    document.body.style.cursor = 'default';
                    document.body.style.userSelect = '';
                }
            };

            const updateCursor = () => {
                if (wrapper.scrollWidth > wrapper.clientWidth) {
                    wrapper.style.cursor = 'grab';
                } else {
                    wrapper.style.cursor = 'default';
                }
            };

            // Add event listeners
            wrapper.addEventListener('mousedown', handleMouseDown);
            wrapper.addEventListener('mouseleave', handleMouseLeave);
            window.addEventListener('mouseup', handleMouseUp);
            window.addEventListener('mousemove', handleMouseMove);

            // Set initial cursor
            updateCursor();

            // Update cursor on resize
            window.addEventListener('resize', updateCursor);

            // Cleanup on destroy (for dynamic content)
            $(wrapper).data('festivawl-drag-cleanup', () => {
                wrapper.removeEventListener('mousedown', handleMouseDown);
                wrapper.removeEventListener('mouseleave', handleMouseLeave);
                window.removeEventListener('mouseup', handleMouseUp);
                window.removeEventListener('mousemove', handleMouseMove);
                window.removeEventListener('resize', updateCursor);
            });
        });
    }

    /**
     * Add loading state
     * @param {jQuery} $container - Calendar container
     */
    function showLoading($container) {
        const loadingHtml = `
            <div class="festivawl-loading">
                <div class="loading-spinner"></div>
                <span>${festivawl_calendar_ajax.strings.loading}</span>
            </div>
        `;
        $container.html(loadingHtml);
    }

    /**
     * Show error state
     * @param {jQuery} $container - Calendar container
     * @param {string} message - Error message
     */
    function showError($container, message) {
        const errorHtml = `
            <div class="festivawl-error">
                ${message || festivawl_calendar_ajax.strings.error}
            </div>
        `;
        $container.html(errorHtml);
    }

    /**
     * Utility function to get stage colors
     * @param {number} priority - Stage priority
     * @returns {object} Color object with bg and border
     */
    function getStageColors(priority) {
        const colors = {
            1: { bg: '#6B1954', border: '#ED1D1D' },
            2: { bg: '#46317D', border: '#6C9FDC' },
            3: { bg: '#563D69', border: '#6CDC71' },
            4: { bg: '#644067', border: '#DCBD6C' },
            5: { bg: '#4F1C70', border: '#DC6CD7' },
            6: { bg: '#2E134E', border: '#000000' },
            7: { bg: '#6E528E', border: '#FFFFFF' },
            8: { bg: '#36294B', border: '#266400' },
            9: { bg: '#363570', border: '#1ABC9C' },
            10: { bg: '#5B1E6F', border: '#E84393' },
            11: { bg: '#373566', border: '#2ECC71' },
            12: { bg: '#532B7D', border: '#C792EA' },
            13: { bg: '#5A3354', border: '#F1C40F' },
            14: { bg: '#371C81', border: '#2D42FF' },
            15: { bg: '#3E3B81', border: '#52EAFF' }
        };
        
        return colors[priority] || colors[1];
    }

    // Make functions available globally
    window.initFestivawalCalendar = initFestivawalCalendar;
    window.FestivawalCalendar = {
        init: initFestivawalCalendar,
        showLoading: showLoading,
        showError: showError,
        getStageColors: getStageColors,
        initDragToScroll: initDragToScroll
    };

    // Auto-initialize any calendars on page load
    $(document).ready(function() {
        $('.festivawl-calendar-container').each(function() {
            const calendarId = $(this).attr('id');
            if (calendarId) {
                initFestivawalCalendar(calendarId);
            }
        });
    });

})(jQuery); 