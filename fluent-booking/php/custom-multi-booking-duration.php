<?php
add_filter('fluent_booking/meeting_multi_durations_schema', function (array $durations) {
    $out   = [];
    $added = false;
    foreach ($durations as $row) {
        $out[] = $row;
        if (! empty($row['value']) && (string) $row['value'] === '60') {
            $out[] = [
                'value' => '75',
                'label' => __('75 Minutes', 'fluent-booking'),
            ];
            $added = true;
        }
    }
    // Fallback: append if 60 was not found (future-proof).
    if (! $added) {
        $out[] = [
            'value' => '75',
            'label' => __('75 Minutes', 'fluent-booking'),
        ];
    }
    return $out;
}, 20);
