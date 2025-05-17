<?php

namespace mod_ilsccheckmarket\output;

defined('MOODLE_INTERNAL') || die();

use mod_ilsccheckmarket\services\panel_surveys as service_panel_surveys;

class mobile
{
    public static function mobile_course_page($args)
    {
        global $CFG, $OUTPUT, $PAGE, $USER, $DB;

        $courseid = $args['courseid'];
        $cmid = $args['cmid'];
        $userid = $args['userid'];

        $masterkey = get_config('mod_ilsccheckmarket', 'xmasterkey');
        $xkey = get_config('mod_ilsccheckmarket', 'xkey');

        if (!$masterkey || !$xkey) {
            return self::create_return_array("<strong>Masterkey or xkey not set.</strong>");
        }

        $user_is_admin = false;
        $email = null;
        $ilsc_number = $USER->idnumber;

        $service_panel_surveys = new service_panel_surveys($masterkey, $xkey);
        if ($USER->email) {
            $service_panel_surveys->set_email($USER->email);
        }
        if ($ilsc_number) {
            $service_panel_surveys->set_ilscnumber($ilsc_number);
        }

        try {
            $panel_data = $service_panel_surveys->get_panel_data();
        } catch (\Exception $e) {
            return self::create_return_array($e->getMessage());
        }

        // Process panel data
        $respondedCompletelyId = 5;
        $currentTime = time();

        $respondedSurveyData = [];
        $nonRespondedSurveyData = [];
        $expiredSurveyData = [];

        // Process each survey
        foreach ($panel_data as $row) {
            $item = [
                'id' => $row->SurveyId,
                'title' => $row->Title,
                'respondent_status' => $row->ContactStatus,
                'date_invited' => $row->DateInvited ? date('Y-m-d H:i', strtotime($row->DateInvited)) : '',
                'date_responded' => $row->DateResponded ? date('Y-m-d H:i', strtotime($row->DateResponded)) : '',
                'live_url' => $row->LiveUrl,
                'report_url' => $row->PanelistReportUrl,
                'end_date' => $row->EndDate,
                'start_date' => $row->StartDate,
                'contact_status_id' => $row->ContactStatusId
            ];

            // Categorize surveys
            $endTime = strtotime($row->EndDate);
            $startTime = strtotime($row->StartDate);

            if ($row->ContactStatusId === $respondedCompletelyId) {
                $respondedSurveyData[] = $item;
            } else if ($endTime < $currentTime || ($row->DateInvited && strtotime($row->DateInvited) + (14 * 24 * 60 * 60) < $currentTime)) {
                $expiredSurveyData[] = $item;
            } else if ($startTime <= $currentTime && $currentTime <= $endTime) {
                $nonRespondedSurveyData[] = $item;
            }
        }

        // Prepare data for the template
        $templateContext = [
            'ready_to_respond' => [
                'title' => get_string('ready_to_respond', 'mod_ilsccheckmarket'),
                'count' => count($nonRespondedSurveyData),
                'items' => $nonRespondedSurveyData,
                'has_items' => !empty($nonRespondedSurveyData)
            ],
            'completed' => [
                'title' => get_string('completed_surveys', 'mod_ilsccheckmarket'),
                'count' => count($respondedSurveyData),
                'items' => $respondedSurveyData,
                'has_items' => !empty($respondedSurveyData)
            ],
            'expired' => [
                'title' => get_string('expired_surveys', 'mod_ilsccheckmarket'),
                'count' => count($expiredSurveyData),
                'items' => $expiredSurveyData,
                'has_items' => !empty($expiredSurveyData)
            ],
            'cmid' => $cmid,
            'courseid' => $courseid
        ];

        $template = 'mod_ilsccheckmarket/mobile_card_view';

        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => $OUTPUT->render_from_template($template, $templateContext),
                ],
            ],
            'javascript' => '', // No JavaScript needed
            'otherdata' => [],
            'files' => []
        ];
    }

    private static function create_return_array($content)
    {
        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => $content,
                ],
            ],
        ];
    }
}
