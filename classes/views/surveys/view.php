<?php

namespace mod_ilsccheckmarket\views\surveys;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/blocks/moodleblock.class.php');

class view extends \block_base
{
    public ?array $respondedSurveyData = null;
    public ?array $nonRespondedSurveyData = null;
    public $email = '';
    public $ilsc_number = '';
    public ?int $courseid = null;
    public bool $user_is_admin = false;
    public ?array $expiredSurveyData = null;

    public function __construct(?int $courseid, bool $user_is_admin)
    {
        $this->courseid = $courseid;
        $this->user_is_admin = $user_is_admin;
    }

    public function set_page($page)
    {
        $this->page = $page;
    }

    public function get_content()
    {
        $this->content = new \stdClass();

        $this->content->text = '';

        if ($this->user_is_admin) {
            $this->content->text .= $this->search_form();
        }

        $this->content->text .= $this->get_tabs_content();

        return $this->content;
    }

    public function search_form()
    {
        $content = \html_writer::start_div('mt-3 mb-5 border-bottom pb-3');
        $content .= \html_writer::tag('h2', 'Surveys');

        $content .= \html_writer::span('Search for a student by email or ILSC number', 'text-muted');


        $content .= \html_writer::start_tag(
            'form',
            [
                'id' => 'search-form',
                'class' => 'mt-5',
                'method' => 'post',
                'action' => $this->page->url,
            ]
        );

        $content .= \html_writer::tag(
            'input',
            '',
            [
                'type' => 'hidden',
                'name' => 'id',
                'value' => $this->courseid,
                'id' => 'courseid',
            ],
        );

        $content .= \html_writer::start_div('row');

        $content .= \html_writer::start_div('col-md-12 col-lg-6 col-xl-4');
        $content .= \html_writer::tag(
            'input',
            '',
            [
                'type' => 'email',
                'id' => 'Student email',
                'placeholder' => 'Student email...',
                'value' => $this->email,
                'class' => 'form-control mb-3',
                'name' => 'email',
                'title' => 'Student email'
            ]
        );
        $content .= \html_writer::end_div();
        $content .= \html_writer::end_div();

        $content .= \html_writer::start_div('row');
        $content .= \html_writer::start_div('col-md-12 col-lg-6 col-xl-4');
        $content .= \html_writer::tag(
            'input',
            '',
            [
                'type' => 'text',
                'id' => 'ilscnumber',
                'placeholder' => 'Student number...',
                'value' => $this->ilsc_number,
                'class' => 'form-control mb-3',
                'name' => 'ilscnumber',
                'title' => 'Student email'
            ]
        );

        $content .= \html_writer::end_div();
        $content .= \html_writer::end_div();

        $content .= \html_writer::start_div('row');

        $content .= \html_writer::start_div('col');

        $content .= \html_writer::tag('button', 'Search', [
            'id' => 'search',
            'class' => 'btn btn-primary',
            'type' => 'submit',
            'aria-label' => 'Search',
        ]);


        $content .= \html_writer::end_div();
        $content .= \html_writer::end_div();
        $content .= \html_writer::end_div();
        $content .= \html_writer::end_tag('form');

        return $content;
    }

    public function get_tabs_content()
    {
        $content = \html_writer::start_tag('nav');
        $content .= \html_writer::start_div('nav nav-tabs', ['id' => 'nav-tab', 'role' => 'tablist']);

        // First tab - Ready to Respond
        $content .= \html_writer::tag('button', 'Ready to Respond', [
            'class' => 'nav-link active',
            'id' => 'nav-non-responded-tab',
            'data-bs-toggle' => 'tab',
            'data-bs-target' => '#nav-home',
            'type' => 'button',
            'role' => 'tab',
            'aria-controls' => 'nav-home',
            'aria-selected' => 'true',
        ]);

        // Second tab - Completed Surveys
        $content .= \html_writer::tag('button', 'Completed Surveys', [
            'class' => 'nav-link',
            'id' => 'nav-responded-tab',
            'data-bs-toggle' => 'tab',
            'data-bs-target' => '#nav-profile',
            'type' => 'button',
            'role' => 'tab',
            'aria-controls' => 'nav-profile',
            'aria-selected' => 'false',
        ]);

        // New third tab - Expired Surveys
        $content .= \html_writer::tag('button', 'Expired Surveys', [
            'class' => 'nav-link',
            'id' => 'nav-expired-tab',
            'data-bs-toggle' => 'tab',
            'data-bs-target' => '#nav-expired',
            'type' => 'button',
            'role' => 'tab',
            'aria-controls' => 'nav-expired',
            'aria-selected' => 'false',
        ]);

        $content .= \html_writer::end_div();
        $content .= \html_writer::end_tag('nav');

        $content .= \html_writer::start_div('tab-content', ['id' => 'nav-tabContent']);

        // Content for first tab - Ready to Respond
        $content .= \html_writer::start_div('tab-pane fade show active', ['id' => 'nav-home', 'role' => 'tabpanel', 'aria-labelledby' => 'nav-non-responded-tab', 'tabindex' => '0']);
        $nonRespondedTable = new table($this->nonRespondedSurveyData ?? [], 'ready_to_respond');
        $content .= $nonRespondedTable->get_content()->text;
        $content .= \html_writer::end_div();

        // Content for second tab - Completed Surveys
        $content .= \html_writer::start_div('tab-pane fade', ['id' => 'nav-profile', 'role' => 'tabpanel', 'aria-labelledby' => 'nav-responded-tab', 'tabindex' => '0']);
        $respondedTable = new table($this->respondedSurveyData ?? [], 'completed');
        $content .= $respondedTable->get_content()->text;
        $content .= \html_writer::end_div();

        // Content for third tab - Expired Surveys
        $content .= \html_writer::start_div('tab-pane fade', ['id' => 'nav-expired', 'role' => 'tabpanel', 'aria-labelledby' => 'nav-expired-tab', 'tabindex' => '0']);
        $expiredTable = new table($this->expiredSurveyData ?? [], 'expired');
        $content .= $expiredTable->get_content()->text;
        $content .= \html_writer::end_div();

        $content .= \html_writer::end_div();
        return $content;
    }
}
