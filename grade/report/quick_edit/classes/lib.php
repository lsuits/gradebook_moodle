<?php

require_once $CFG->dirroot . '/grade/report/quick_edit/classes/uilib.php';
require_once $CFG->dirroot . '/grade/report/quick_edit/classes/datalib.php';

abstract class quick_edit_screen {
    var $courseid;

    var $itemid;

    var $groupid;

    var $context;

    function __construct($courseid, $itemid, $groupid = null) {
        $this->courseid = $courseid;
        $this->itemid = $itemid;
        $this->groupid = $groupid;

        $this->context = get_context_instance(CONTEXT_COURSE, $this->courseid);

        $this->init();
    }

    public function format_link($screen, $itemid, $display = null) {
        $url = new moodle_url('/grade/report/quick_edit/index.php', array(
            'id' => $this->courseid,
            'item' => $screen,
            'itemid' => $itemid,
            'group' => $this->groupid
        ));

        if ($display) {
            return html_writer::link($url, $display);
        } else {
            return $url;
        }
    }

    public function fetch_grade_or_default($item, $user) {
        $grade = grade_grade::fetch(array(
            'itemid' => $item->id, 'userid' => $user->id
        ));

        if (!$grade) {
            $default = new stdClass;

            $default->userid = $user->id;
            $default->itemid = $item->id;
            $default->feedback = '';

            $grade = new grade_grade($default, false);
        }

        $grade->grade_item = $item;

        return $grade;
    }

    public function make_toggle($key) {
        $attrs = array('href' => '#');

        $all = html_writer::tag('a', get_string('all'), $attrs + array(
            'class' => 'include_all ' . $key
        ));

        $none = html_writer::tag('a', get_string('none'), $attrs + array(
            'class' => 'include_none ' . $key
        ));

        return html_writer::tag('span', "$all / $none", array(
            'class' => 'inclusion_links'
        ));
    }

    public function make_toggle_links($key) {
        return get_string($key, 'gradereport_quick_edit') . ' ' .
            $this->make_toggle($key);
    }

    public function heading() {
        return get_string('pluginname', 'gradereport_quick_edit');
    }

    public abstract function init();

    public abstract function html();

    public function factory() {
        if (empty($this->__factory)) {
            $this->__factory = new quick_edit_grade_ui_factory();
        }

        return $this->__factory;
    }

    public function processor() {
        return new quick_edit_grade_processor($this->courseid);
    }
}

abstract class quick_edit_tablelike extends quick_edit_screen {
    var $items;

    public abstract function headers();

    public abstract function format_line($item);

    public function html() {
        $table = new html_table();

        $table->head = $this->headers();

        $table->data = array();

        foreach ($this->items as $item) {
            $table->data[] = $this->format_line($item);
        }

        return html_writer::table($table);
    }
}