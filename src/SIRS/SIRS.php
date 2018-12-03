<?php
/**
 * @file
 * Survey derived class for presenting the standard SIRS form for faculty
 */

namespace CL\MSU\SIRS;

use CL\Site\Site;
use CL\Survey\SurveyHTML;
use CL\Survey\SurveyQuestion;
use CL\Survey\SurveyRate;
use CL\Survey\SurveyComment;


/** Survey derived class for presenting the standard SIRS form for faculty */
class SIRS extends \CL\Survey\Survey {
	/**
 * Constructor
 * @param string $tag A tag for this survey
 * @param string $name Name of the faculty for the survey
 */
	public function __construct($tag, $name) {
		parent::__construct($tag, 'SIRS for ' . $name);

		$this->name = $name;
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
		$section = $member !== null ? $course->get_section($member->semester, $member->sectionId) : '';

		$courseName = $site->siteName;
		$name = $this->name;
		$tag = $this->tag;

		$html = <<<HTML
<p class="top">Term: <span>&nbsp;&nbsp;&nbsp;$semester&nbsp;&nbsp;&nbsp;</span> Course: <span>&nbsp;&nbsp;&nbsp;$courseName&nbsp;&nbsp;&nbsp;</span>  Instructor: <span>&nbsp;&nbsp;&nbsp;$name&nbsp;&nbsp;&nbsp;</span></p>
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
<h2>Student Instructional Rating System</h2>
<p>The information reported on the following form is of value to the instructor in assessing and improving his/her teaching effectiveness and to the department and college in evaluating the performance of the instructor.</p>

<input type="hidden" name="type" value="sirs">
<input type="hidden" name="tag" value="$tag">
<input type="hidden" name="name" value="$name">		
HTML;

		$this->add(new SurveyHTML($html));
		$this->add(new SurveyQuestion(1, "The instructor was available and willing to help the student. Explain:", "Availability"));
		$this->add(new SurveyQuestion(2, "The instructor explained course material clearly. Explain:", "Explanation"));
		$this->add(new SurveyQuestion(3, "The instructor was well prepared for classes and other related course activities. Explain:", "Preparation"));
		$this->add(new SurveyQuestion(4, "The instructor organized the course well. Explain:", "Organization"));
		$this->add(new SurveyRate(5, "Rate the instructor on the following scale:"));
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
		$instructor = $this->name;

		$html = <<<HTML
<p class="reporthead">Michigan State University<br>
College of Engineering<br>
Student Instructional Rating System Report<br>
$semester, $year</p>
<p></p>
<div class="stats">
<p><b>Instructor: $instructor &nbsp;&nbsp;&nbsp; $coursename</b></p>

HTML;

		return $html;
	}

	private $name;
}
