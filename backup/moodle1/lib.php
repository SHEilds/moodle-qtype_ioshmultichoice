<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    qtype
 * @subpackage ioshmultichoice
 * @copyright  2011 David Mudrak <david@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * ioshmultichoice question type conversion handler
 */
class moodle1_qtype_ioshmultichoice_handler extends moodle1_qtype_handler {

    /**
     * @return array
     */
    public function get_question_subpaths() {
        return array(
            'ANSWERS/ANSWER',
            'ioshmultichoice',
        );
    }

    /**
     * Appends the ioshmultichoice specific information to the question
     */
    public function process_question(array $data, array $raw) {

        // Convert and write the answers first.
        if (isset($data['answers'])) {
            $this->write_answers($data['answers'], $this->pluginname);
        }

        // Convert and write the ioshmultichoice.
        if (!isset($data['ioshmultichoice'])) {
            // This should never happen, but it can do if the 1.9 site contained
            // corrupt data.
            $data['ioshmultichoice'] = array(array(
                'single'                         => 1,
                'shuffleanswers'                 => 1,
                'correctfeedback'                => '',
                'correctfeedbackformat'          => FORMAT_HTML,
                'partiallycorrectfeedback'       => '',
                'partiallycorrectfeedbackformat' => FORMAT_HTML,
                'incorrectfeedback'              => '',
                'incorrectfeedbackformat'        => FORMAT_HTML,
                'answernumbering'                => 'abc',
                'showstandardinstruction'        => 0
            ));
        }
        $this->write_ioshmultichoice($data['ioshmultichoice'], $data['oldquestiontextformat'], $data['id']);
    }

    /**
     * Converts the ioshmultichoice info and writes it into the question.xml
     *
     * @param array $ioshmultichoices the grouped structure
     * @param int $oldquestiontextformat - {@see moodle1_question_bank_handler::process_question()}
     * @param int $questionid question id
     */
    protected function write_ioshmultichoice(array $ioshmultichoices, $oldquestiontextformat, $questionid) {
        global $CFG;

        // The grouped array is supposed to have just one element - let us use foreach anyway
        // just to be sure we do not loose anything.
        foreach ($ioshmultichoices as $ioshmultichoice) {
            // Append an artificial 'id' attribute (is not included in moodle.xml).
            $ioshmultichoice['id'] = $this->converter->get_nextid();

            // Replay the upgrade step 2009021801.
            $ioshmultichoice['correctfeedbackformat']               = 0;
            $ioshmultichoice['partiallycorrectfeedbackformat']      = 0;
            $ioshmultichoice['incorrectfeedbackformat']             = 0;

            if ($CFG->texteditors !== 'textarea' and $oldquestiontextformat == FORMAT_MOODLE) {
                $ioshmultichoice['correctfeedback']                 = text_to_html($ioshmultichoice['correctfeedback'], false, false, true);
                $ioshmultichoice['correctfeedbackformat']           = FORMAT_HTML;
                $ioshmultichoice['partiallycorrectfeedback']        = text_to_html($ioshmultichoice['partiallycorrectfeedback'], false, false, true);
                $ioshmultichoice['partiallycorrectfeedbackformat']  = FORMAT_HTML;
                $ioshmultichoice['incorrectfeedback']               = text_to_html($ioshmultichoice['incorrectfeedback'], false, false, true);
                $ioshmultichoice['incorrectfeedbackformat']         = FORMAT_HTML;
            } else {
                $ioshmultichoice['correctfeedbackformat']           = $oldquestiontextformat;
                $ioshmultichoice['partiallycorrectfeedbackformat']  = $oldquestiontextformat;
                $ioshmultichoice['incorrectfeedbackformat']         = $oldquestiontextformat;
            }

            $ioshmultichoice['correctfeedback'] = $this->migrate_files(
                    $ioshmultichoice['correctfeedback'], 'question', 'correctfeedback', $questionid);
            $ioshmultichoice['partiallycorrectfeedback'] = $this->migrate_files(
                    $ioshmultichoice['partiallycorrectfeedback'], 'question', 'partiallycorrectfeedback', $questionid);
            $ioshmultichoice['incorrectfeedback'] = $this->migrate_files(
                    $ioshmultichoice['incorrectfeedback'], 'question', 'incorrectfeedback', $questionid);

            $this->write_xml('ioshmultichoice', $ioshmultichoice, array('/ioshmultichoice/id'));
        }
    }
}
