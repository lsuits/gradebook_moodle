<?php

class quick_edit_user extends quick_edit_tablelike {

    private $categories = array();

    private $structure;

    public function init() {
        global $DB;

        $this->user = $DB->get_record('user', array('id' => $this->itemid));

        $params = array('courseid' => $this->courseid);

        $filter_items = grade_report_quick_edit::only_items();

        $this->items= array_filter(grade_item::fetch_all($params), $filter_items);

        $this->structure = new grade_structure();
        $this->structure->modinfo = get_fast_modinfo(
            $DB->get_record('course', array('id' => $this->courseid))
        );
    }

    public function headers() {
        return array(
            '',
            get_string('assessmentname', 'gradereport_quick_edit'),
            get_string('gradecategory', 'grades'),
            get_string('range', 'grades'),
            get_string('grade', 'grades'),
            get_string('feedback', 'grades'),
            $this->make_toggle_links('override'),
            $this->make_toggle_links('exclude')
        );
    }

    public function format_line($item) {
        global $OUTPUT;

        $grade = $this->fetch_grade_or_default($item, $this->user);

        return array(
            $this->format_icon($item),
            $this->format_link('grade', $item->id, $item->itemname),
            $this->category($item)->get_name(),
            $this->factory()->create('range')->format($item),
            $this->factory()->create('finalgrade')->format($grade) .
            $this->structure->get_grade_analysis_icon($grade),
            $this->factory()->create('feedback')->format($grade),
            $this->factory()->create('override')->format($grade),
            $this->factory()->create('exclude')->format($grade)
        );
    }

    private function format_icon($item) {
        $element = array('type' => 'item', 'object' => $item);

        return $this->structure->get_element_icon($element);
    }

    private function category($item) {
        if (!isset($this->categories[$item->categoryid])) {
            $category = $item->get_parent_category();

            $this->categories[$category->id] = $category;
        }

        return $this->categories[$item->categoryid];
    }

    public function heading() {
        return fullname($this->user);
    }
}