<?php

/**
 * Description of Register
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';

class Register {

    public $host;
    public $db;

    function __construct() {
        $this->db = new pdo_db();
        $this->host = $_SERVER['SERVER_NAME'];
    }

    function get_participants_dropbox() {
        $drop_down = "";
        $drop_down.="<div class='dropdown'>
        <a href='#' id='participants' data-toggle='dropdown' 
        class='dropdown-toggle'>Participants 
        <b class='caret'></b></a>
        <ul class='dropdown-menu'>";
        for ($i = 1; $i <= 10; $i++) {
            $drop_down.="<li><a href='#' id='tot_" . $i . "'>" . $i . "</a></li>";
        }
        $drop_down.="</ul></div>";
        return $drop_down;
    }

    function get_coure_name_by_id($courseid) {
        $query = "select id, fullname "
                . "from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['fullname'];
        }
        return $name;
    }

    function get_selected_program($courseid) {
        $list = "";
        $name = $this->get_coure_name_by_id($courseid);
        $list.="<a id='courses' class='dropdown-toggle' 
                onclick='return false;' data-toggle='dropdown' 
            href='#'>$name</a>";
        return $list;
    }

    function get_states_list($group = false) {
        $drop_down = "";
        if ($group == false) {
            //$drop_down.="<div class='dropdown'>
            //<a href='#' id='state' data-toggle='dropdown' class='dropdown-toggle' onClick='return false;'>State <b class='caret'></b></a>
            //<ul class='dropdown-menu'>";
            $drop_down.="<select id='state' style='width:120px;'>";
        } // end if $group==false
        else {
            //$drop_down.="<div class='dropdown'>
            //<a href='#' id='group_state' data-toggle='dropdown' class='dropdown-toggle' onClick='return false;'>State <b class='caret'></b></a>
            //<ul class='dropdown-menu'>";
            $drop_down.="<select id='group_state' style='width:120px;'>";
        }

        $query = "select * from mdl_states";
        $result = $this->db->query($query);
        $drop_down.="<option value='0' selected>State</option>";
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $drop_down.="<option value='" . $row['id'] . "'>" . $row['state'] . "</option>";
        } // end while
        $drop_down.="</select>";
        return $drop_down;
    }

    function get_register_page($courseid = null) {
        $list = "";
        $cats = $this->get_course_categories();
        $courses = $this->get_courses_by_category();
        $participants = $this->get_participants_dropbox();

        // ****************** Program information **************************

        if ($courseid == null) {
            $list.="<br/><div  class='form_div'>";
            $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
            $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Program information</h5></div>";
            $list.="<div class='panel-body'>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Program type*</span>";
            $list.="<span class='span2'>$cats</span>";
            $list.="<span class='span2' id='cat_course'>$courses</span>";
            $list.="<span class='span2' id='program_err' style='color:red;'></span>";
            $list.="</div>"; // end of container-fluid
            $list.="</div>"; // end of panel-body
            $list.="</div>"; // end of panel panel-default
        } // end if $courseid==null
        else {
            $selected_program = $this->get_selected_program($courseid);
            $list.="<br/><div  class='form_div'>";
            $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
            $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Program information</h5></div>";
            $list.="<div class='panel-body'>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Selected program:</span>";
            $list.="<span class='span2'>$selected_program</span>";

            $list.="</div>"; // end of container-fluid
            $list.="</div>"; // end of panel-body
            $list.="</div>"; // end of panel panel-default
        }
        //
        //
        // ********************  Registration type **************************        
        $list.="<div class='panel panel-default' id='type_section'>";
        $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Registration type</h5></div>";
        $list.="<div class='panel-body'>";
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'><input type='radio' name='type' id='me' value='me' checked>I want to register myself </span>";
        $list.="<span class='span2'><input type='radio' name='type' id='group' value='group' >I want to register group </span>";
        $list.="<span class='span2' id='gr_num'>$participants</span>";
        $list.="<span class='span2' id='type_err' style='color:red;'></span>";
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="</div>"; // end of container-fluid
        $list.="</div>"; // end of panel-body
        $list.="</div>"; // end of panel panel-default
        //
        // ********************  Individual registration form **************************        
        $list.="<div class='panel panel-default' id='personal_section'>";
        $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>User details </h5></div>";
        $list.="<div class='panel-body'>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2' >First name*</span>";
        $list.="<span class='span2' ><input type='text' id='first_name' name='first_name' ></span>";
        $list.="<span class='span2' >Last name*</span>";
        $list.="<span class='span2' ><input type='text' id='last_name' name='last_name'  ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Email*</span>";
        $list.="<span class='span2'><input type='text' id='email' name='email' ></span>";
        $list.="<span class='span2'>Phone*</span>";
        $list.="<span class='span2'><input type='text' id='phone' name='phone'  ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Address*</span>";
        $list.="<span class='span2'><input type='text' id='addr' name='addr' ></span>";
        $list.="<span class='span2'>Business Or Institution</span>";
        $list.="<span class='span2'><input type='text' id='inst' name='inst' ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>ZIP Code*</span>";
        $list.="<span class='span2'><input type='text' id='zip' name='zip' ></span>";
        $list.="<span class='span2'>City*</span>";
        $list.="<span class='span2'><input type='text' id='city' name='city' ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>State*</span>";
        $list.="<span class='span2'><input type='text' id='state' name='state' ></span>";
        $list.="<span class='span2'>Country*</span>";
        $list.="<span class='span2'><input type='text' id='country' name='country' ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'><a href='#' id='proceed_to_personal_payment' onClick='return false;'>Proceed to payment</a></span>&nbsp;<span style='color:red;' id='personal_err'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span8' style='text-align:center;display:none;' id='ajax_loading_personal'><img src='https://$this->host/assets/img/ajax.gif' /></span";
        $list.="</div>";

        $list.="</div>";
        $list.="</div>";

        $list.= "</div>"; // end of form div

        return $list;
    }

    function get_course_categories() {
        $drop_down = "";
        $drop_down.="<div class='dropdown'>
        <a href='#' id='categories' data-toggle='dropdown' class='dropdown-toggle' onClick='return false;'>Program type<b class='caret'></b></a>
        <ul class='dropdown-menu'>";
        $query = "select id,name from mdl_course_categories";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $drop_down.="<li><a href='#' id='cat_" . $row['id'] . "'>" . $row['name'] . "</a></li>";
        }
        $drop_down.="</ul></div>";
        return $drop_down;
    }

    function get_courses_by_category($cat_id = null) {
        $drop_down = "";
        if ($cat_id != null) {
            //$drop_down.="<div class='dropdown'>
            //<a href='#' id='courses' data-toggle='dropdown' class='dropdown-toggle' onClick='return false;'>Program <b class='caret'></b></a>
            //<ul class='dropdown-menu'>";

            $drop_down.="<select id='register_courses' style='width:120px;'>";
            $drop_down.="<option value='0' selected>Program</option>";
            $query = "select id, fullname from mdl_course where category=$cat_id and cost>0";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $drop_down.="<option value='" . $row['id'] . "'>" . $row['fullname'] . "</option>";
                } // end while
            } // end if $num > 0
            $drop_down.="</select>";
        } // end if $num > 0
        else {
            $drop_down.="<select id='register_courses' style='width:120px;'></select>";
        }
        return $drop_down;
    }

    public function come_from() {
        $drop_down = "";
        $drop_down.= "<select id='come_from_group' style='width:120px;'>";
        $drop_down.="<option value='0' selected>Select</option>";
        $drop_down.="<option value='Newspaper'>Newspaper</option>";
        $drop_down.="<option value='Magazine' >Magazine</option>";
        $drop_down.="<option value='Radio' >Radio</option>";
        $drop_down.="<option value='TV'>TV</option>";
        $drop_down.="<option value='Google' >Google</option>";
        $drop_down.="<option value='Microsoft' >Microsoft</option>";
        $drop_down.="<option value='Yahoo' >Yahoo</option>";
        $drop_down.="<option value='Twitter' >Twitter</option>";
        $drop_down.="<option value='Instagram' >Instagram</option>";
        $drop_down.="<option value='Other'>Other</option>";
        $drop_down.="</select>";
        return $drop_down;
    }

    function get_group_registration_form($tot_participants) {

        $states = $this->get_states_list(true);
        $list = "";
        $list.="<div class='panel panel-default' id='group_common_section'>";
        $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Group Registration </h5></div>";
        $list.="<div class='panel-body'>";
        $come_from = $this->come_from();

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Address*</span>";
        $list.="<span class='span2'><input type='text' id='group_addr' name='group_addr' ></span>";
        $list.="<span class='span2'>Business Or Institution</span>";
        $list.="<span class='span2'><input type='text' id='group_inst' name='group_inst' ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>ZIP Code*</span>";
        $list.="<span class='span2'><input type='text' id='group_zip' name='group_zip' ></span>";
        $list.="<span class='span2'>City*</span>";
        $list.="<span class='span2'><input type='text' id='group_city' name='group_city' ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>State*</span>";
        $list.="<span class='span2'>$states</span>";
        $list.="<span class='span2'>Group name*</span>";
        $list.="<span class='span2'><input type='text' id='group_name' name='group_name' ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>How did you hear about us?*</span>";
        $list.="<span class='span2'>$come_from</span>";
        //$list.="<span class='span4'><input type='checkbox' id='gr_policy'> I have read and agree to Terms and Conditions</span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'><a href='#' id='manual_group_registration' onClick='return false;'>Next</a></span>";
        $list.="<span class='span4'>Have a lot of group participants? <a href='#' id='upload_group_file' onClick='return false;'>Upload users file</a></span><span class='span2' style='color:red;' id='group_common_errors'></span>";
        $list.="</div>";

        $list.="</div>";
        $list.="</div>";
        return $list;
    }

    function get_group_manual_registration_form($tot_participants) {
        $list = "";
        $list.="<div class='panel panel-default' id='participants_details'>";
        $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Participants Detailes</h5></div>";
        $list.="<div class='panel-body'>";
        for ($i = 1; $i <= $tot_participants; $i++) {

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>First name*</span>";
            $list.="<span class='span2'><input type='text' id='first_name_$i' name='first_name_$i' ></span>";
            $list.="<span class='span2'>Last name*</span>";
            $list.="<span class='span2'><input type='text' id='last_name_$i' name='last_name_$i'  ></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Email*</span>";
            $list.="<span class='span2'><input type='text' id='email_$i' name='email_$i' ></span>";
            $list.="<span class='span2'>Phone*</span>";
            $list.="<span class='span2'><input type='text' id='phone_$i' name='phone_$i'  ></span>";
            $list.="</div>";
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span8'><hr/></span>";
            $list.="</div>";
        } // end for

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.= "<span style='color:red;' id='group_manual_form_err'></span>";
        $list.="</div>";

        $list.= "<div class='container-fluid' style='text-align:center;'";
        $list.= "<span class='span2'><a href='#' id='proceed_to_group_payment' onClick='return false;'>Next</a></span>";
        $list.= "</div>";



        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span8' style='text-align:center;display:none;' id='ajax_loading_group'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
        $list.="</div>";

        $list.="</div>";
        $list.="</div>";
        return $list;
    }

    function is_email_exists($email) {
        $query = "select username, deleted from mdl_user "
                . "where username='$email' and deleted=0";
        return $this->db->numrows($query);
    }

    function get_course_id($course_name) {
        $query = "select id, fullname from mdl_course "
                . "where fullname='$course_name'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
        }
        return $id;
    }

    function get_category_state_items($categoryname, $statename) {
        $list = "";
        $courses = array();

        //1. Get state id
        //$query = "select id from mdl_states where state='$statename'";
        //$result = $this->db->query($query);
        //while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        //  $stateid = $row['id'];
        //}
        //2. Get courses list for selected state
        $stateid = $statename;
        $query = "select courseid from mdl_course_to_state where stateid=$stateid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $courseid[] = $row['courseid'];
            }
            //echo "<br>-------------------<br>";
            //print_r($courseid);
            //echo "<br>-------------------<br>";
            //3. Get category id
            //$query = "select id from mdl_course_categories where name='$categoryname'";
            //$result = $this->db->query($query);
            //while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            //  $category_id = $row['id'];
            //}
            //4. Get courses withing selected state and category
            $category_id = $categoryname;
            $query = "select id, fullname from mdl_course "
                    . "where category=$category_id and cost>0";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    //echo "Course id: " . $row['id'] . "<br>";
                    if (in_array($row['id'], $courseid)) {
                        $course = new stdClass();
                        $course->id = $row['id'];
                        $course->name = $row['fullname'];
                        $courses[] = $course;
                    } // end if in_array($row['id'], $courseid)
                } // end while
                //$list.="<div class='dropdown'>
                //<a href='#' id='courses' data-toggle='dropdown' class='dropdown-toggle' onClick='return false;'>Program <b class='caret'></b></a>
                //<ul class='dropdown-menu'>";
                $list.="<select id='courses' style='width:120px;'>";
                foreach ($courses as $course) {
                    $list.="<option value='$course->id'>$course->name</option>";
                } // end foreach
                $list.="</select>";
            } // end if $num>0
            else {
                $list.="<select id='courses' style='width:120px;'>";
                $list.="</select>";
            } // end else            
        }  // end if $num>0    
        else {
            $list.="<select id='courses' style='width:120px;'>";
            $list.="</select>";
        }
        return $list;
    }

    function get_policy_dialog() {
        $list = "";
        $list.="<div id='myModal' class='modal fade'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title'>Terms and Conditions</h4>
            </div>
            <div class='modal-body'>
                <p style='font-weight:bold;'>Medical2 Inc.  ADVANCE REGISTRATION / REFUND / PREREQUISITE POLICY:</p>
                
                <p align='justify'>Participants who wish to cancel/transfer their registration must provide official written notification. Medical2 does not accept cancellations/transfers by phone. Cancellations must be received by Medical2 no later than 2 weeks (14Days) prior to your original workshop date. Cancellations/transfers must include the participant's name, and workshop name and date. A 100% refund can be issued if the deadline has been met. Failure to notify will result in the forfeit of any obligation by Medical2. Failure to attend your Workshop results in the forfeit of the entire Workshop Fee. Late Registrations are not entitled to any refund. Transfers forfeit the right to a refund if they voluntarily cancel their enrollment after transferring. Transfers must attend a Workshop within 6 months of their original Workshop date. After 6 months, your transfer is null and void, and you will be required to re-register and pay in full again.
                All books are non-refundable and non-returnable. Medical2 Medical Certification Agency reserves the right to cancel or limit the time of the workshops without any prior notice, if minimum registration requirements are not met. Medical2 Medical Certification Agency is not responsible for any travel related expenses incurred, including but not limited to: Airfare, Hotel, Taxi, Parking, Rental Car, etc…by registering for and/or attending our workshops. The OB Tech Program is entirely non-refundable. All Online Exam fees are non-refundable. Your certification is non-refundable, non-transferable. Medical2 Medical Certification Agency is not a Job Placement Agency. We neither provide jobs, nor guarantee jobs. Medical2 simply provides a training, certification and continuing education service. It is your sole responsibility to check with your state and/or employer for acceptance of our certification.
                Should the workshop for which you are registered be cancelled or rescheduled by Medical2 Medical Certification Agency, you are subject to a full refund or transfer. All refund requests and transfers must be made in writing. If you transfer, you forfeit your right to any refund, and any future cancellation refunds, no exceptions.<br> 
                <span style='color:red;'>
                Notice:There will be $25 late registration fee within 7 days prior to the workshop.
                Medical2 Medical Certification Agencys Phlebotomy Workshop & Online Exam shall not be used to obtain a California Phlebotomy State License.</span><br>
                
                By submitting this form I confirm that I have read and meet all of the medical background pre-requisites, am at least 18 years of age, and completely understand and agree with the Medical2 Medical Certification Agency policies stated on this form. Noncompliance of any these polices will forfeit any obligation by Medical2 Medical Certification Agency.<br>
                <span style='color:red;'>Refund / Pre-Requisite Policy for On-line Exams</span> (online phlebotomy certification exams are not part of phlebotomy workshop)
                In order to be eligible for a Madical2 On-line Exam, you are required to send Medical2 proof of eligibility as outlined in the eligibility requirements for the exam for which you are registering.
                The exam fee is non-refundable, non-transferable. Medical2,Inc will not be held responsible for any registrant who fails to read and/or comply with these policies. It is your sole responsibility to check with your state and/or employer for acceptance of our certification.
                Your test will be activated only after we receive proof of eligibility from you.
                You have 90 days from the activation date to finish the exam. You acknowledge that in the event that you fail the exam on your first attempt you are only eligible to attempt this exam 4 times in a 12 month period. You must register and pay the fee each time you attempt the exam.You must use a computer with a stable, high-speed Internet connection. Medical2 is not responsible for any technical difficulties you may have due to your computer and/or poor Internet connection. Once you have started your exam, you cannot log out, as it is timed. Medical2 is not responsible if you log out of your timed exam, and lose time. In the event of a technical problem you can retry 2 times in a 1 hour period from the same computer at the discretion of Medical2 technical support staff. It is your responsibility to disable call waiting, answering machines, and any other devise that may cause interference of your on-line exam. This exam must be passed with an 75% or better in order to become certified. You may request a hand score of the exam for an additional fee of $25.
                Medical2,Inc reserves the right to update our Terms and Conditions at any time, without prior notice. Updates are effective immediately.
                By submitting this form: I confirm that I meet all of the medical background pre-requisites for the program for which I am registering, I am at least 18 years of age, and I fully understand and agree with the Medical2,Inc policies stated on this form. I understand noncompliance of any of these policies will forfeit any obligation by Medical2,Inc.
                Refund / Pre-Requisite Policy for: Online Exams.
                In order to be eligible for the 'Medical2 Online Exams', you will need to send us your proof of eligibility as outlined in the eligibility requirements for this exam (Please see FAQs).
                The exam fee is non-refundable, non-transferable. Medical2 will not be held responsible for any registrant who fails to read and/or comply with these policies. It is your sole responsibility to check with your state and/or employer for acceptance our certification. Your test will be activated only after we receive proof of eligibility from you.
                You have 90 days from the registration date to finish the exam. You acknowledge that in the event that you fail the exam on your first attempt you are only eligible to attempt this exam 4 times in a 12 month period. The fee is the same each time. In the event of a technical problem you can retry 2 times in a 1 hour period from the same computer at the discretion of Medical2 Medical Certification Agency technical support staff. It is your responsibility to disable call waiting, answering machines, and any other devise that may cause interference of your online exam. This exam must be passed with an 75% or better in order to become certified. You may request a hand score of the exam for an additional fee of $25.'
                </p>
            </div>
            <div class='modal-footer' style='text-align:center;'>
                <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>OK</button></span>
            </div>
        </div>
    </div>
</div>";
        return $list;
    }

    function get_schedulerid($courseid) {
        $schedulerid = 0;
        $query = "select id from mdl_scheduler where course=$courseid";
        $result = $this->db->query($query);
        $num = $this->db->numrows($query);
        if ($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $schedulerid = $row['id'];
            } // end while
        } // end if $num > 0
        return $schedulerid;
    }

    function get_course_slots($courseid) {
        $slots = array();
        $schedulerid = $this->get_schedulerid($courseid);
        $now = time() - 86400;
        if ($schedulerid > 0) {
            $query = "select DISTINCT id from mdl_scheduler_slots "
                    . "where schedulerid=$schedulerid "
                    . "and starttime>" . $now . " order by starttime";
            //echo "Query: ".$query."<br>";
            $result = $this->db->query($query);
            $num = $this->db->numrows($query);
            if ($num > 0) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $slots[] = trim($row['id']);
                } // end while
            } // end if $num > 0
        } // $schedulerid>0        
        array_unique($slots);

        return $slots;
    }

    function get_slot_data($id) {
        $query = "select * from mdl_scheduler_slots where id=$id";
        $result = $this->db->query($query);
        $num = $this->db->numrows($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $slot = new stdClass();
            foreach ($row as $key => $value) {
                $slot->$key = $value;
            } // end foreach
        } // end while
        //array_unique($slot);
        return $slot;
    }

    function get_register_course_states($courseid) {
        date_default_timezone_set('Pacific/Wallis');
        $register_states = array();
        $drop_down = "";
        $drop_down.="<select id='register_state' style='width:120px;'>";
        $drop_down.="<option value='0' selected>State</option>";
        $slots = $this->get_course_slots($courseid);
        if (count($slots) > 0) {
            foreach ($slots as $slot) {
                $slot_data = $this->get_slot_data($slot);
                $locations = explode("/", $slot_data->appointmentlocation);
                //$state = $locations[0] . "  " . $locations[1]. "- ".$date;
                $state = $locations[0];
                $register_states[$slot] = $state;
            } // end foreach
            $sorted = array_unique($register_states);
            asort($sorted);
            foreach ($sorted as $key => $value) {
                $drop_down.="<option value='$key'>$value</option>";
            } // end foeach 
        } // end if count($slots)>0
        $drop_down.="</select>";
        return $drop_down;
    }

    function get_state_name($stateid) {
        $query = "select * from mdl_states where id=$stateid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $state = $row['state'];
        }
        return $state;
    }

    function get_register_course_cities($courseid, $slotid, $future = true) {
        date_default_timezone_set('Pacific/Wallis');
        $drop_down = "";
        $drop_down.="<select id='register_cities' style='width:120px;'>";
        $drop_down.="<option value='0' selected>City</option>";
        $schedulerid = $this->get_schedulerid($courseid);
        if ($schedulerid > 0) {
            $slot_data = $this->get_slot_data($slotid);
            $locations = explode("/", $slot_data->appointmentlocation);
            $statename = $locations[0];
            $now = time() + 86400;
            if ($future == true) {
                $query = "select * from mdl_scheduler_slots "
                        . "where schedulerid=$schedulerid "
                        . "and appointmentlocation like '%$statename%' "
                        . "and starttime>$now order by appointmentlocation";
            } // end if $future==true
            else {
                $query = "select * from mdl_scheduler_slots "
                        . "where schedulerid=$schedulerid "
                        . "and appointmentlocation like '%$statename%' "
                        . "order by appointmentlocation";
            } // end else
            $result = $this->db->query($query);
            $num = $this->db->numrows($query);
            if ($num > 0) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $hdate = date('m-d-Y', $row['starttime']);
                    $locations2 = explode("/", $row['appointmentlocation']);
                    if (count($locations2) == 0) {
                        $locations2 = explode(",", $row['appointmentlocation']);
                    }
                    $cityname = $locations2[1];
                    $drop_down.="<option value='" . $row['id'] . "'>$cityname - $hdate</option>";
                } // end while
            } // end if $num > 0
        } // end if $schedulerid>0
        $drop_down.="</select>";
        return $drop_down;
    }

}
