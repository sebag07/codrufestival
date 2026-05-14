<?php

/**
 * API handler for Festivawl Calendar.
 *
 * Handles communication with the Festivawl API and data processing.
 */
class Festivawl_Calendar_API {

    /**
     * Base URL for Festivawl API.
     */
    const API_BASE_URL = 'https://api.festivawl.com/app';

    /**
     * Get festival data from both metadata and performance APIs.
     *
     * @param int $festival_id Festival ID
     * @return array|WP_Error Combined festival data or error
     */
    public static function get_festival_data($festival_id) {
        // Fetch festival metadata first
        $metadata = self::get_festival_metadata($festival_id);

        if (is_wp_error($metadata)) {
            return $metadata;
        }

        // Note: We no longer check calendarReady - display all festivals regardless of readiness status

        // Fetch performance data
        $performance_data = self::get_festival_performance_data($festival_id);

        if (is_wp_error($performance_data)) {
            return $performance_data;
        }

        // Combine metadata and performance data
        $combined_data = array_merge($performance_data, array(
            'festival_name' => isset($metadata['name']) ? $metadata['name'] : 'Unknown Festival',
            'festival_timezone' => isset($metadata['timezone']) ? $metadata['timezone'] : 'UTC',
            'festival_metadata' => $metadata
        ));

        // Process the combined data for calendar display
        $processed_data = self::process_festival_data($combined_data);

        return $processed_data;
    }

    /**
     * Get festival metadata from the /festival/{id} endpoint.
     *
     * @param int $festival_id Festival ID
     * @return array|WP_Error Festival metadata or error
     */
    private static function get_festival_metadata($festival_id) {
        $api_url = self::API_BASE_URL . '/festival/' . $festival_id;

        $response = wp_remote_get($api_url, array(
            'timeout' => 30,
            'headers' => array(
                'Accept' => 'application/json',
                'User-Agent' => 'Festivawl Calendar WordPress Plugin'
            )
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        $response_code = wp_remote_retrieve_response_code($response);

        if ($response_code !== 200) {
            return new WP_Error('api_error', 'API request failed with code: ' . $response_code);
        }

        $body = wp_remote_retrieve_body($response);

        if (empty($body)) {
            return new WP_Error('api_error', 'Empty response from API');
        }

        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new WP_Error('api_error', 'Invalid JSON response: ' . json_last_error_msg());
        }

        return $data;
    }

    /**
     * Get festival performance data from the /performance/{id} endpoint.
     *
     * @param int $festival_id Festival ID
     * @return array|WP_Error Festival performance data or error
     */
    private static function get_festival_performance_data($festival_id) {
        $api_url = self::API_BASE_URL . '/performance/' . $festival_id;

        $response = wp_remote_get($api_url, array(
            'timeout' => 30,
            'headers' => array(
                'Accept' => 'application/json',
                'User-Agent' => 'Festivawl Calendar WordPress Plugin'
            )
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        $response_code = wp_remote_retrieve_response_code($response);

        if ($response_code !== 200) {
            return new WP_Error('api_error', 'API request failed with code: ' . $response_code);
        }

        $body = wp_remote_retrieve_body($response);

        if (empty($body)) {
            return new WP_Error('api_error', 'Empty response from API');
        }

        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new WP_Error('api_error', 'Invalid JSON response: ' . json_last_error_msg());
        }

        // Validate required fields
        if (!isset($data['completeEventSchedule']) || !isset($data['daysStartEndSchedule'])) {
            return new WP_Error('api_error', 'Missing required fields in API response');
        }

        return $data;
    }

    /**
     * Process raw festival data into calendar-ready format.
     *
     * @param array $raw_data Raw festival data from API
     * @return array Processed festival data
     */
    private static function process_festival_data($raw_data) {
        // Extract stages from the first event's stage data
        $stages = self::extract_stages($raw_data);

        // Generate day information
        $days = self::generate_days_info($raw_data);

        // Determine timezone
        if (isset($raw_data['festival_timezone'])) {
            $timezone = $raw_data['festival_timezone'];
        } elseif (isset($raw_data['timezone'])) {
            $timezone = $raw_data['timezone'];
        } else {
            $timezone = 'UTC';
        }

        // Process events by stage and day
        $schedule = self::process_events_by_stage($raw_data, $stages, $timezone);

        // Generate time slots for each day
        $time_slots = self::generate_time_slots($raw_data, $timezone);

        return array(
            'stages' => $stages,
            'days' => $days,
            'schedule' => $schedule,
            'time_slots' => $time_slots,
            'timezone' => $timezone,
            'festival_name' => isset($raw_data['festival_name']) ? $raw_data['festival_name'] : 'Unknown Festival'
        );
    }

    /**
     * Extract unique stages from event data.
     *
     * @param array $festival_data Festival data
     * @return array Array of stages
     */
    private static function extract_stages($festival_data) {
        $stages = array();
        $stage_ids = array();

        if (!isset($festival_data['completeEventSchedule'])) {
            return $stages;
        }

        foreach ($festival_data['completeEventSchedule'] as $day_events) {
            foreach ($day_events as $event) {
                if (isset($event['stage']) && !in_array($event['stage']['id'], $stage_ids)) {
                    $stages[] = $event['stage'];
                    $stage_ids[] = $event['stage']['id'];
                }
            }
        }

        // Sort stages by priority
        usort($stages, function($a, $b) {
            return $a['priority'] - $b['priority'];
        });

        return $stages;
    }

    /**
     * Generate day information from the schedule.
     *
     * @param array $festival_data Festival data
     * @return array Array of day information
     */
    private static function generate_days_info($festival_data) {
        $days = array();

        if (!isset($festival_data['daysStartEndSchedule'])) {
            return $days;
        }

        foreach ($festival_data['daysStartEndSchedule'] as $day_index => $day_data) {
            if (isset($day_data['startTime'])) {
                $start_time = new DateTime($day_data['startTime']);
                $days[$day_index] = array(
                    'name' => 'Day ' . ($day_index + 1),
                    'date' => $start_time->format('M j'),
                    'full_date' => $start_time->format('Y-m-d'),
                    'start_time' => $day_data['startTime'],
                    'end_time' => isset($day_data['endTime']) ? $day_data['endTime'] : null
                );
            }
        }

        return $days;
    }

    /**
     * Process events and organize them by stage and day.
     *
     * @param array $festival_data Festival data
     * @param array $stages Available stages
     * @param string $timezone Festival timezone
     * @return array Organized schedule data
     */
    private static function process_events_by_stage($festival_data, $stages, $timezone) {
        $schedule = array();

        if (!isset($festival_data['completeEventSchedule'])) {
            return $schedule;
        }

        try {
            $tz = new DateTimeZone($timezone);
        } catch (Exception $e) {
            $tz = new DateTimeZone('UTC');
        }

        foreach ($festival_data['completeEventSchedule'] as $day_index => $day_events) {
            $schedule[$day_index] = array();

            // Initialize empty arrays for each stage
            foreach ($stages as $stage) {
                $schedule[$day_index][$stage['sceneName']] = array();
            }

            foreach ($day_events as $event) {
                if (!isset($event['stage']['sceneName'])) {
                    continue;
                }

                $stage_name = $event['stage']['sceneName'];

                // Convert times to the festival timezone
                try {
                    $start_time = new DateTime($event['startTime']);
                    $end_time = new DateTime($event['endTime']);
                    
                    $start_time->setTimezone($tz);
                    $end_time->setTimezone($tz);

                    $formatted_time = $start_time->format('H:i') . ' - ' . $end_time->format('H:i');

                    $processed_event = array(
                        'id' => $event['id'],
                        'artist' => isset($event['event']['eventName']) ? $event['event']['eventName'] : $event['event'],
                        'start_time' => $start_time->format('c'),
                        'end_time' => $end_time->format('c'),
                        'formatted_time' => $formatted_time,
                        'day' => $day_index,
                        'stage' => $event['stage']
                    );

                    if (!isset($schedule[$day_index][$stage_name])) {
                        $schedule[$day_index][$stage_name] = array();
                    }

                    $schedule[$day_index][$stage_name][] = $processed_event;

                } catch (Exception $e) {
                    // Skip events with invalid dates
                    continue;
                }
            }

            // Sort events within each stage by start time
            foreach ($schedule[$day_index] as &$stage_events) {
                usort($stage_events, function($a, $b) {
                    return strcmp($a['start_time'], $b['start_time']);
                });
            }
        }

        return $schedule;
    }

    /**
     * Generate time slots for each day.
     *
     * @param array $festival_data Festival data
     * @param string $timezone Festival timezone
     * @return array Time slots for each day
     */
    private static function generate_time_slots($festival_data, $timezone) {
        $time_slots = array();

        if (!isset($festival_data['daysStartEndSchedule'])) {
            return $time_slots;
        }

        try {
            $tz = new DateTimeZone($timezone);
        } catch (Exception $e) {
            $tz = new DateTimeZone('UTC');
        }

        foreach ($festival_data['daysStartEndSchedule'] as $day_index => $day_data) {
            if (!isset($day_data['startTime']) || !isset($day_data['endTime'])) {
                continue;
            }

            try {
                $start_time = new DateTime($day_data['startTime']);
                $end_time = new DateTime($day_data['endTime']);
                
                $start_time->setTimezone($tz);
                $end_time->setTimezone($tz);

                $time_slots[$day_index] = array();

                // Generate hourly time slots
                $current_time = clone $start_time;
                $current_time->setTime($current_time->format('H'), 0, 0); // Round down to the hour

                while ($current_time <= $end_time) {
                    $time_slots[$day_index][] = $current_time->format('H:i');
                    $current_time->add(new DateInterval('PT1H'));
                }

            } catch (Exception $e) {
                // Skip days with invalid dates
                continue;
            }
        }

        return $time_slots;
    }
} 