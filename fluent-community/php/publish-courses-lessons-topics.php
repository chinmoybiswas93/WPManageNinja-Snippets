<?php
add_action('admin_init', function () {
    if (!current_user_can('manage_options')) {
        return;
    }

    $already_run_key = 'fcom_bulk_publish_lessons_orm_once_2026_06_29';

    if (get_option($already_run_key)) {
        return;
    }

    if (
        !class_exists('\FluentCommunity\Modules\Course\Model\Course') ||
        !class_exists('\FluentCommunity\Modules\Course\Model\CourseTopic') ||
        !class_exists('\FluentCommunity\Modules\Course\Model\CourseLesson')
    ) {
        return;
    }

    // Change these to your specific FluentCommunity course IDs.
    $course_ids = [123, 456];

    $course_ids = array_filter(array_map('absint', $course_ids));

    if (!$course_ids) {
        return;
    }

    $now = current_time('mysql');

    foreach ($course_ids as $course_id) {
        $course = \FluentCommunity\Modules\Course\Model\Course::find($course_id);

        if (!$course) {
            continue;
        }

        // Optional: publish the course itself.
        $previous_course_status = $course->status;

        if ($course->status !== 'published') {
            $course->status = 'published';
            $course->save();

            if ($previous_course_status !== 'published') {
                do_action('fluent_community/course/published', $course);
            }
        }

        // Optional but recommended: publish sections.
        $sections = \FluentCommunity\Modules\Course\Model\CourseTopic::where('space_id', $course_id)
            ->where('status', '!=', 'published')
            ->get();

        foreach ($sections as $section) {
            $section->status = 'published';
            $section->save();
        }

        // Publish lessons.
        $lessons = \FluentCommunity\Modules\Course\Model\CourseLesson::where('space_id', $course_id)
            ->where('status', '!=', 'published')
            ->get();

        foreach ($lessons as $lesson) {
            $previous_status = $lesson->status;

            $lesson->status = 'published';

            if (empty($lesson->scheduled_at)) {
                $lesson->scheduled_at = $now;
            }

            $lesson->save();

            do_action(
                'fluent_community/lesson/updated',
                $lesson,
                [
                    'status' => 'published',
                    'scheduled_at' => $lesson->scheduled_at,
                ],
                $previous_status !== 'published'
            );
        }
    }

    update_option($already_run_key, [
        'course_ids' => $course_ids,
        'ran_at' => $now,
    ], false);
});