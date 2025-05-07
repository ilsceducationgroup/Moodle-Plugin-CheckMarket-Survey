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
            return self::create_return_array("<bold>Masterkey or xkey not set.</bold>");
            die();
        }

        // $user_is_admin = self::user_is_admin($userid);
        $user_is_admin = false;

        if (!$user_is_admin) {
            $email = null;
            $ilsc_number = $USER->idnumber;
        } else {
            $email = trim(optional_param('email', '', PARAM_TEXT));
            $ilsc_number = trim(optional_param('ilscnumber', '', PARAM_TEXT));
        }

        $service_panel_surveys = new service_panel_surveys($masterkey, $xkey);
        $email = $USER->email;
        if ($email) {
            $service_panel_surveys->set_email($email);
        }

        if ($ilsc_number) {
            $service_panel_surveys->set_ilscnumber($ilsc_number);
        }

        $panel_data = null;

        try {
            $panel_data = $service_panel_surveys->get_panel_data();
        } catch (\Exception $e) {
            return self::create_return_array($e->getMessage());
        }

        // In mobile.php, after processing panel_data
        $respondedCompletelyId = 5;
        $currentTime = time();

        $respondedSurveyData = [];
        $nonRespondedSurveyData = [];
        $expiredSurveyData = [];

        // First transform panel_data into the format needed
        $data = array_map(function ($row) {
            $dateInvited = $row->DateInvited ? date('Y-m-d H:i', strtotime($row->DateInvited)) : '';
            $dateResponded = $row->DateResponded ? date('Y-m-d H:i', strtotime($row->DateResponded)) : '';
            return [
                'id' => $row->SurveyId,
                'title' => $row->Title,
                'status' => $row->SurveyStatus,
                'respondent_status' => $row->ContactStatusId,
                'date_invited' => $dateInvited,
                'date_responded' => $dateResponded,
                'link' => $row->LiveUrl,
                'end_date' => $row->EndDate,
                'start_date' => $row->StartDate
            ];
        }, $panel_data);

        foreach ($data as $row) {
            // Check for response status 
            if ($row['respondent_status'] === $respondedCompletelyId) {
                $respondedSurveyData[] = $row;
            }
            // Check for expiration - assuming survey end date or 14 days after invitation
            else {
                $endTime = isset($row['end_date']) ? strtotime($row['end_date']) : PHP_INT_MAX;
                $invitedTime = !empty($row['date_invited']) ? strtotime($row['date_invited']) : 0;
                $twoWeeksAfterInvite = $invitedTime + (14 * 24 * 60 * 60);

                if ($endTime < $currentTime || ($invitedTime && $twoWeeksAfterInvite < $currentTime)) {
                    $expiredSurveyData[] = $row;
                } else {
                    $nonRespondedSurveyData[] = $row;
                }
            }
        }

        $dataToTemplate = [];

        if ($nonRespondedSurveyData) {
            $dataToTemplate[] = [
                'title' => get_string('ready_to_respond', 'mod_ilsccheckmarket'),
                'count' => count($nonRespondedSurveyData),
                'items' => $nonRespondedSurveyData,
            ];
        }

        if ($respondedSurveyData) {
            $dataToTemplate[] = [
                'title' => get_string('completed_surveys', 'mod_ilsccheckmarket'),
                'count' => count($respondedSurveyData),
                'items' => $respondedSurveyData,
            ];
        }

        if ($expiredSurveyData) {
            $dataToTemplate[] = [
                'title' => get_string('expired_surveys', 'mod_ilsccheckmarket'),
                'count' => count($expiredSurveyData),
                'items' => $expiredSurveyData,
            ];
        }

        $template = 'mod_ilsccheckmarket/mobile';

        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => $OUTPUT->render_from_template($template, [
                        'title' => get_string('moduletitle', 'mod_ilscaddressform'),
                        'user' => $USER,
                        'data' => $dataToTemplate,
                    ]),
                ],
            ],
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

    private static function user_is_admin($userid)
    {
        $admins = get_admins();
        foreach ($admins as $admin) {
            if ($userid == $admin->id) {
                return true;
            }
        }
        return false;
    }
}
