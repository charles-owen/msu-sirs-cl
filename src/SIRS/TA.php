<?php
/** @file
 * Survey derived class for presenting the standard TA evaluation form
 */

namespace CL\MSU\SIRS;

use CL\Site\Site;
use CL\Survey\SurveyHTML;
use CL\Survey\SurveyQuestion;
use CL\Survey\SurveyRate;
use CL\Survey\SurveyComment;

/** Survey derived class for presenting the standard TA evaluation form */
class TA extends \CL\Survey\Survey {
	/**
	 * Constructor
	 * @param string $tag A tag for this survey
	 * @param string $name Name of the teaching assistant for the survey
	 * @param string $lead The lead instructor for the course.
	 */
	public function __construct($tag, $name, $lead) {
		parent::__construct($tag, 'TA Evaluation for ' . $name);

		$this->name = $name;
		$this->lead = $lead;
		$this->css = 'vendor/cl/msu-sirs/sirs.css';
	}


	/**
	 * Build the survey.
	 * @param Site $site The Site object
	 */
	public function build(Site $site) {
		$user = $site->users->user;
		$member = $user->member;
		$course = $site->course;
		$semester = $member->semester;
		$section = $member !== null ? $course->get_section($member->semester, $member->sectionId)->id : '';

		$courseName = $site->siteName;
		$name = $this->name;
		$tag = $this->tag;
		$lead = $this->lead;

		$html = <<<HTML
<p class="top">Term: (semester or session and year): <span>&nbsp;&nbsp;&nbsp;$semester&nbsp;&nbsp;&nbsp;</span><br/>
Course: <span>&nbsp;&nbsp;&nbsp;$courseName&nbsp;&nbsp;&nbsp;</span>  Section: <span>&nbsp;&nbsp;&nbsp;$section&nbsp;&nbsp;&nbsp;</span><br />
Lead Instructor: <span>&nbsp;&nbsp;&nbsp;$lead&nbsp;&nbsp;&nbsp;</span> Teaching Assistant: <span>&nbsp;&nbsp;&nbsp;$name&nbsp;&nbsp;&nbsp;</span></p>
<div class="keybox"><ul>
<li>SA-Strongly Agree</li>
<li>&nbsp;&nbsp;A-Agree</li>
<li>&nbsp;&nbsp;N-Neither Disagree Nor Agree</li>
<li>&nbsp;&nbsp;D-Disagree</li>
<li>SD-Strongly Disagree</li>
</ul>
</div>
<h1>MICHIGAN STATE UNIVERSITY<br>
COLLEGE OF ENGINEERING</h1>
<h2>Student Instructional Rating System<br />
Teaching Assistant Rating Form*</h2>
<p>The information reporated on the following form is of value to the teaching assistant responsibilities
for this section in assessing and improving his/her teaching effectiveness, to the lead instructor--instructor
responsible for the course--in evaluating the performance of the teaching assistant and in assessing and 
improving their joint effectiveness, and the department and college in evaluating the performance of the instructional
tream.</p>

<input type="hidden" name="type" value="ta">
<input type="hidden" name="tag" value="$tag">
<input type="hidden" name="name" value="$name">
<input type="hidden" name="lead" value="$lead">
HTML;

		$this->add(new SurveyHTML($html));
		$this->add(new SurveyQuestion(1, "The teaching assistant was available and willing to help the student. Explain:", "Available"));
		$this->add(new SurveyQuestion(2, "The teaching assistant was prepared for class sessions and enthusiastic about teaching this course section. Explain:", "Prepared"));
		$this->add(new SurveyQuestion(3, "The teaching assistant organized and explained the materials for this section well and, generally, displayed a high-level of competence in the subject matter of this course. Explain:", "Organized and Explained"));
		$this->add(new SurveyQuestion(4, "The teaching assistant communicated, both in written and oral modes, well and with ease. Explain:", "Communication"));
		$this->add(new SurveyQuestion(5, "The teaching assistant was fair in the grading of assignments and tests. Explain:", "Grading"));
		$this->add(new SurveyRate(6, "Rate the teaching assistant on the following scale:"));
		$this->add(new SurveyComment("comment", "Comments: Please provide any additional information and constructive comments in the space below."));
	}

	/**
	 * Create the statistics report heading. Override for custom headings.
	 * @param Site $site
	 * @return string HTML
	 */
	public function reportHeading(Site $site) {
		$user = $site->users->user;
		$member = $user->member;
		$section = $site->course->get_section_for($user);

		$semester = $section->season;
		$year = $section->year;
		$coursename = $site->siteName;
		$name = $this->name;
		$instructor = $this->lead;

		$html = <<<HTML
<p class="reporthead">Michigan State University<br>
College of Engineering<br>
Teaching Assistant Evaluation<br>
$semester, $year</p>
<p></p>
<div class="stats">
<p><b>Teaching Assistant: $name &nbsp;&nbsp;&nbsp;Instructor: $instructor &nbsp;&nbsp;&nbsp; $coursename</b></p>

HTML;

		return $html;
	}


	private $name;
	private $lead;
}
