<?php

namespace mod_ilsccheckmarket\views\surveys;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/blocks/moodleblock.class.php');

class table extends \block_base
{
    public array $data;

    public function __construct(array $data, string $table_type = 'ready_to_respond')
    {
        $this->data = $data;
        $this->table_type = $table_type;
    }

    public function get_content()
    {
        $this->content = new \stdClass();
        $data = $this->data ?? [];

        if (count($data) === 0) {
            $this->content->text = \html_writer::tag('p', 'No data found', ['class' => 'text-center mt-5 mb-5']);
            return $this->content;
        }

        $table = new \html_table();

        // Define columns based on table_type
        if ($this->table_type === 'expired') {
            // For expired surveys, exclude Date Responded and Link columns
            $table->head = array(
                'Id',
                'Title',
                'Survey Status',
                'Respondent Status',
                'Date Invited',
            );
            $table->size = ['0', '40%', '20%', '20%', '20%'];
        } else {
            // For ready_to_respond and completed surveys, include all columns
            $table->head = array(
                'Id',
                'Title',
                'Survey Status',
                'Respondent Status',
                'Date Invited',
                'Date Responded',
                'Link',
            );
            $table->size = ['0', '25%', '15%', '20%', '15%', '15%', '10%'];
        }

        $table->attributes['class'] = 'generaltable table-sm';
        $table->attributes['style'] = 'table-layout: fixed;';
        $table->colclasses = ['hidden', null, null, null, null, 'text-center'];

        foreach ($data as $row) {
            $table->data[] = $this->create_row($row);
        }

        $this->content->text = \html_writer::table($table);
        return $this->content;
    }

    private function create_row(object $rowData): ?\html_table_row
    {
        $tr = new \html_table_row();

        $id_cell = new \html_table_cell($rowData->SurveyId ?? '');
        $id_cell->attributes['class'] = 'hidden';

        $tr->cells[] = $id_cell;
        $tr->cells[] = new \html_table_cell($rowData->Title ?? '');
        $tr->cells[] = new \html_table_cell($rowData->SurveyStatus ?? '');
        $tr->cells[] = new \html_table_cell($rowData->ContactStatus ?? '');

        $dateInput = function ($value) {
            $value = $value ? date('Y-m-d\TH:i:s', strtotime($value)) : '';
            return \html_writer::tag(
                'input',
                '',
                [
                    'type' => 'datetime-local',
                    'class' => 'form-control form-control-sm ',
                    'readonly' => 'readonly',
                    'value' => $value,
                    'style' => empty($value) ? 'color: rgba(0,0,0,0);' : '',
                ]
            );
        };

        $tr->cells[] = new \html_table_cell($dateInput($rowData->DateInvited ?? ''));


        // Only add the Link cell for non-expired tabs
        if ($this->table_type !== 'expired') {
            $tr->cells[] = new \html_table_cell($dateInput($rowData->DateResponded ?? ''));

            $linkLiveUrl = '';
            $startDate = $rowData->StartDate ?? '2100-01-01';
            $endDate = $rowData->EndDate ?? '2100-01-01';
            $still_available = strtotime($startDate) <= time() && time() <= strtotime($endDate);

            // Check if this is a completed survey (ContactStatusId = 5)
            if ($rowData->ContactStatusId === 5 && !empty($rowData->PanelistReportUrl)) {
                // For completed surveys, show the report link
                $linkLiveUrl = \html_writer::link(
                    new \moodle_url($rowData->PanelistReportUrl),
                    'View response',
                    [
                        'class' => 'btn btn-sm btn-outline-primary',
                        'target' => '_blank',
                    ]
                );
            }
            // For ready_to_respond tab, show the survey link if available
            else if ($this->table_type === 'ready_to_respond' && $still_available && !empty($rowData->LiveUrl)) {
                $linkLiveUrl = \html_writer::link(
                    new \moodle_url($rowData->LiveUrl),
                    'Open survey',
                    [
                        'class' => 'btn btn-sm btn-outline-primary',
                        'target' => '_blank',
                    ]
                );
            }

            $tr->cells[] = new \html_table_cell($linkLiveUrl);
        }

        return $tr;
    }
}
