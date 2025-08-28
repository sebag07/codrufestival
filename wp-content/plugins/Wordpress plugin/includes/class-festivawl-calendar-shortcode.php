<?php

/**
 * Shortcode handler for Festivawl Calendar.
 *
 * Handles the [festivawl_calendar] shortcode and renders the festival calendar.
 */
class Festivawl_Calendar_Shortcode {

    /**
     * Initialize the shortcode.
     */
    public function init() {
        add_shortcode('festivawl_calendar', array($this, 'render_calendar'));
    }

    /**
     * Render the festival calendar shortcode.
     *
     * @param array $atts Shortcode attributes
     * @return string HTML output
     */
    public function render_calendar($atts) {
        try {
            // Parse shortcode attributes
            $atts = shortcode_atts(array(
                'id' => get_option('festivawl_calendar_default_festival_id', ''),
                'theme' => get_option('festivawl_calendar_theme_style', 'default'),
                'mobile' => get_option('festivawl_calendar_enable_mobile_view', true)
            ), $atts, 'festivawl_calendar');

            // Sanitize attributes
            $festival_id = absint($atts['id']);
            $theme = sanitize_text_field($atts['theme']);
            $mobile = filter_var($atts['mobile'], FILTER_VALIDATE_BOOLEAN);

            if (empty($festival_id)) {
                return '<div class="festivawl-error" style="background: orange; color: black; padding: 20px; border: 2px solid red;">FESTIVAL ID IS EMPTY! Please provide a valid festival ID.</div>';
            }

            // Fetch festival data
            $festival_data = Festivawl_Calendar_API::get_festival_data($festival_id);

            if (is_wp_error($festival_data)) {
                $error_msg = $festival_data->get_error_message();
                return '<div class="festivawl-error" style="background: red; color: white; padding: 20px; border: 2px solid black;">API ERROR: ' . esc_html($error_msg) . '</div>';
            }

            // Generate unique ID for this calendar instance
            $calendar_id = 'festivawl-calendar-' . $festival_id . '-' . wp_rand(1000, 9999);

            // Start output buffering
            ob_start();

            // Render the calendar
            $this->render_calendar_html($calendar_id, $festival_data, $theme, $mobile);

            $output = ob_get_clean();
            
            if (empty($output)) {
                return '<div style="background: purple; color: white; padding: 20px; border: 2px solid yellow;">HTML GENERATION FAILED! Check debug log.</div>';
            }

            return $output;
            
        } catch (Exception $e) {
            return '<div style="background: red; color: white; padding: 20px; border: 2px solid black;">EXCEPTION: ' . esc_html($e->getMessage()) . '</div>';
        }
    }

    /**
     * Render the complete calendar HTML.
     *
     * @param string $calendar_id Unique calendar ID
     * @param array $festival_data Festival data from API
     * @param string $theme Theme style
     * @param bool $mobile Enable mobile view
     */
    private function render_calendar_html($calendar_id, $festival_data, $theme, $mobile) {
        if (!is_array($festival_data)) {
            echo '<div style="background: red; color: white; padding: 20px;">FESTIVAL DATA IS NOT AN ARRAY!</div>';
            return;
        }
        
        // Extract processed data
        $stages = $festival_data['stages'];
        $days = $festival_data['days'];
        $schedule = $festival_data['schedule'];
        $time_slots = $festival_data['time_slots'];
        
        // Set transparency class based on admin settings
        $is_transparent = get_option('festivawl_calendar_transparent_bg', false);
        $transparency_class = $is_transparent ? ' transparent' : '';
        
        // Dynamic width based on stage count for optimal UX
        // 1-3 stages: Compact width (centered, sized to content)
        // 4+ stages: Full width (keeps current behavior)
        $stage_count = count($stages);
        $compact_width_style = '';
        
        if ($stage_count <= 3) {
            // Calculate actual needed width for compact layout
            $time_column_width = 80;
            $stage_width = 220;
            $stage_gap = 20;
            $container_padding = 32; // 16px each side 
            $extra_space = 50; // Extra breathing room
            
            $total_needed_width = $time_column_width + ($stage_count * ($stage_width + $stage_gap)) + $container_padding + $extra_space;
            $compact_width_style = ' style="max-width: ' . $total_needed_width . 'px; margin: 0 auto;"';
        }
        // For 4+ stages: No max-width, uses full container width (current behavior)
        
        ?>
        <div id="<?php echo esc_attr($calendar_id); ?>" class="festivawl-calendar-container<?php echo esc_attr($transparency_class); ?>"<?php echo $compact_width_style; ?>>
            
            <!-- Day Navigation Tabs -->
            <div class="festivawl-day-tabs">
                <?php foreach ($days as $day_index => $day_info): ?>
                    <button class="festivawl-day-tab <?php echo $day_index === 0 ? 'active' : ''; ?>" 
                            data-day="<?php echo esc_attr($day_index); ?>">
                        <h2 class="day-name"><?php echo esc_html($day_info['name']); ?></h2>
                        <span class="day-date"><?php echo esc_html($day_info['date']); ?></span>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Calendar Content -->
            <div class="festivawl-calendar-content">
                
                <?php foreach ($days as $day_index => $day_info): ?>
                    <div class="festivawl-day-content <?php echo $day_index === 0 ? 'active' : ''; ?>" 
                         data-day="<?php echo esc_attr($day_index); ?>">
                        
                        <!-- Single Scrollable Container -->
                        <div class="festivawl-scrollable-area">
                            
                            <!-- Stage Headers -->
                            <div class="festivawl-stage-headers">
                                <div class="festivawl-time-header"></div>
                                <?php foreach ($stages as $stage): ?>
                                    <div class="festivawl-stage-header" data-stage-priority="<?php echo esc_attr($stage['priority']); ?>">
                                        <h3 class="stage-name"><?php echo esc_html($stage['sceneName']); ?></h3>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Calendar Grid -->
                            <div class="festivawl-calendar-grid">
                            
                            <!-- Time Column -->
                            <div class="festivawl-time-column">
                                <?php if (isset($time_slots[$day_index])): ?>
                                    <?php foreach ($time_slots[$day_index] as $time_slot): ?>
                                        <div class="festivawl-time-slot">
                                            <span class="time-label"><?php 
                                                // Handle both old string format and new array format
                                                if (is_array($time_slot) && isset($time_slot['time'])) {
                                                    echo esc_html($time_slot['time']);
                                                } else {
                                                    echo esc_html($time_slot);
                                                }
                                            ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <!-- Main Grid Area - EXACTLY like React component -->
                            <?php 
                            $container_height = count($time_slots[$day_index]) * 80 + 100; // Add 100px buffer for proper spacing
                            $container_width = count($stages) * (220 + 20); // stages.length * (stageWidth + gap)
                            ?>
                            <div class="festivawl-stages-container" style="min-height: <?php echo $container_height; ?>px; min-width: <?php echo $container_width; ?>px;">
                                
                                <!-- Horizontal Grid Lines spanning full width - EXACTLY like React -->
                                <?php if (isset($time_slots[$day_index])): ?>
                                    <?php foreach ($time_slots[$day_index] as $index => $time_slot): ?>
                                        <div class="festivawl-grid-line" style="top: <?php echo ($index * 80) + 40; ?>px;"></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <!-- Stage Columns - EXACTLY like React component -->
                                <?php foreach ($stages as $stage_index => $stage): ?>
                                    <?php 
                                    $stage_left = $stage_index * (220 + 20); // stageIndex * (stageWidth + 20)
                                    $stage_height = count($time_slots[$day_index]) * 80 + 100; // timeSlots.length * HOUR_HEIGHT + buffer
                                    ?>
                                    <div class="festivawl-stage-column" 
                                         data-stage-priority="<?php echo esc_attr($stage['priority']); ?>"
                                         style="left: <?php echo $stage_left; ?>px; height: <?php echo $stage_height; ?>px;">

                                        <!-- Events -->
                                        <?php 
                                        $stage_events = isset($schedule[$day_index][$stage['sceneName']]) 
                                            ? $schedule[$day_index][$stage['sceneName']] 
                                            : array();
                                        
                                        if (!empty($stage_events)) {
                                            foreach ($stage_events as $event_index => $event) {
                                                $this->render_event_block($event, $stage, $time_slots[$day_index]);
                                            }
                                        }
                                        ?>

                                    </div>
                                <?php endforeach; ?>

                            </div>
                            
                        </div>
                        
                        </div>
                        
                    </div>
                <?php endforeach; ?>
                
            </div>
            
        </div>
        <?php
    }

    /**
     * Render an individual event block.
     *
     * @param array $event Event data
     * @param array $stage Stage data  
     * @param array $time_slots Time slots for the day
     */
    private function render_event_block($event, $stage, $time_slots) {
        // Calculate position and height
        $position_data = $this->calculate_event_position($event, $time_slots);
        
        if (!$position_data) {
            echo '<div style="background: yellow; color: black; padding: 5px; margin: 2px; border: 1px solid red;">POSITION FAILED: ' . esc_html($event['artist']) . ' (' . esc_html($event['formatted_time']) . ')</div>';
            return; // Skip if we can't calculate position
        }

        $top_pixels = $position_data['top_pixels'];
        $height_pixels = $position_data['height_pixels'];
        
        // Add class for short events (where stage name should be hidden)
        $short_event_class = '';
        if ($height_pixels <= 30) {
            $short_event_class = ' short-event';
        }
        
        ?>
        <div class="festivawl-event-block<?php echo $short_event_class; ?>" 
             data-event-id="<?php echo esc_attr($event['id']); ?>"
             data-stage-priority="<?php echo esc_attr($stage['priority']); ?>"
             style="position: absolute; top: <?php echo esc_attr($top_pixels); ?>px; left: 20px; right: 12px; width: 220px; height: <?php echo esc_attr($height_pixels); ?>px; z-index: 3;">
            
            <div class="event-header">
                <span class="event-stage"><?php echo esc_html($stage['sceneName']); ?></span>
                <span class="event-time"><?php echo esc_html($event['formatted_time']); ?></span>
            </div>
            
            <div class="event-content">
                <p class="event-artist"><?php echo esc_html($event['artist']); ?></p>
            </div>
            
        </div>
        <?php
    }

    /**
     * Calculate event position and height in pixels.
     *
     * @param array $event Event data
     * @param array $time_slots Time slots array for the day
     * @return array|false Position data or false if calculation fails
     */
    private function calculate_event_position($event, $time_slots) {
        if (empty($time_slots)) {
            return false;
        }

        // Check if formatted_time is set
        if (!isset($event['formatted_time']) || empty($event['formatted_time'])) {
            return false;
        }

        // Extract start and end times from formatted_time (e.g., "20:30 - 22:00")
        if (!preg_match('/^(\d{1,2}:\d{2})\s*-\s*(\d{1,2}:\d{2})$/', $event['formatted_time'], $matches)) {
            return false;
        }

        $start_time = $matches[1];
        $end_time = $matches[2];

        // Get first time slot for reference
        $first_slot_data = reset($time_slots);
        if (!$first_slot_data) {
            return false;
        }

        // Handle both array and string format for time slots
        if (is_array($first_slot_data) && isset($first_slot_data['time'])) {
            $first_time_slot = $first_slot_data['time'];
        } else {
            $first_time_slot = $first_slot_data;
        }

        // Calculate positions using React's exact logic
        $top_position = $this->get_time_position_pixels($start_time, $first_time_slot);
        
        if ($top_position === false) {
            return false;
        }
        
        $end_position = $this->get_time_position_pixels($end_time, $first_time_slot);
        
        if ($end_position === false) {
            return false;
        }
        
        $height = $end_position - $top_position;

        // Match React positioning exactly: top: topPosition + 3, height: height - 5
        $final_top = $top_position + 3;
        $final_height = $height - 5;
        
        // Ensure minimum height
        if ($final_height < 10) {
            $final_height = 10;
        }

        $result = array(
            'top_pixels' => $final_top,
            'height_pixels' => $final_height
        );

        return $result;
    }

    /**
     * Convert time string to pixel position using React's exact getTimePosition logic.
     *
     * @param string $time_string Time in H:i format (e.g., "20:30")
     * @param string $first_time_slot First time slot as reference (e.g., "16:00")
     * @return float Pixel position
     */
    private function get_time_position_pixels($time_string, $first_time_slot) {
        // Parse the time string
        $time_parts = explode(':', $time_string);
        if (count($time_parts) !== 2) {
            return false;
        }
        
        $hours = (int) $time_parts[0];
        $minutes = (int) $time_parts[1];
        $total_minutes = $hours * 60 + $minutes;
        
        // Parse first time slot
        $start_parts = explode(':', $first_time_slot);
        if (count($start_parts) !== 2) {
            return false;
        }
        
        $start_hour = (int) $start_parts[0];
        $start_minutes = $start_hour * 60;
        
        // Handle next-day wraparound (exactly like React)
        $adjusted_minutes = $total_minutes < $start_minutes ? $total_minutes + 24 * 60 : $total_minutes;
        
        // React logic: return ((adjustedMinutes - startMinutes) / 60) * 80 + (HOUR_HEIGHT / 2);
        $HOUR_HEIGHT = 80; // From React component
        $minutes_diff = $adjusted_minutes - $start_minutes;
        $hours_diff = $minutes_diff / 60;
        $base_position = $hours_diff * 80;
        $center_offset = $HOUR_HEIGHT / 2;
        $position = $base_position + $center_offset;
        
        return $position;
    }

    /**
     * Get stage color based on priority.
     *
     * @param int $priority Stage priority
     * @return array Color scheme
     */
    public static function get_stage_colors($priority) {
        $colors = array(
            1 => array('bg' => '#8B5A7C', 'border' => '#FF4757'), // Mainstage - pink/red
            2 => array('bg' => '#4A6FA5', 'border' => '#3742FA'), // Secondary - blue
            3 => array('bg' => '#6C5CE7', 'border' => '#A055FF'), // Galaxy - purple  
            4 => array('bg' => '#FDCB6E', 'border' => '#F39C12'), // Daydreaming - yellow
            5 => array('bg' => '#6C5CE7', 'border' => '#8B5CF6'), // Fortune - purple
            6 => array('bg' => '#2D3436', 'border' => '#6C5CE7')   // Time - dark purple
        );

        return isset($colors[$priority]) ? $colors[$priority] : $colors[1];
    }
} 