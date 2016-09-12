<?php

/**
 * Description of Util
 *
 * @author sirromas
 */
//session_start();
require_once ('/home/cnausa/public_html/lms/config.php');
require_once ('/home/cnausa/public_html/lms/class.pdo.database.php');

class Util {

    public $host;
    public $db;
    public $user;
    public $course;
    public $student_role = 5;
    public $editor_path;
    public $json_path;

    function __construct() {
        global $USER, $COURSE;
        $db = new pdo_db();
        $this->db = $db;
        $this->user = $USER;
        $this->course = $COURSE;
        $this->host = $_SERVER['SERVER_NAME'];
        $this->editor_path = 'https://' . $_SERVER['SERVER_NAME'] . "/lms/editor/";
        $this->json_path=$_SERVER['DOCUMENT_ROOT'].'/lms/custom/utils/data.json';
        //$this->create_typehead_data();
    }

    function create_typehead_data() {
        $courses = array();
        $firstname = array();
        $lastname = array();
        $emails = array();
		$users=array();
        
        $query = "select * from mdl_course where visible=1";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courses[] = mb_convert_encoding($row['fullname'], 'UTF-8');
        }

        $query = "select * from mdl_user where deleted=0";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $lastname[] = mb_convert_encoding($row['lastname'], 'UTF-8');
            $firstname[] = mb_convert_encoding($row['firstname'], 'UTF-8');
            $users[]=mb_convert_encoding($row['lastname'], 'UTF-8')." ".mb_convert_encoding($row['firstname'], 'UTF-8');
            $emails[] = mb_convert_encoding($row['email'],'UTF-8');
        }

        $data = array_merge($users, $emails, $courses);
        file_put_contents($this->json_path, json_encode($data));
    }

    function get_screen_resolution() {
        if (isset($_SESSION['screen_width']) AND isset($_SESSION['screen_height'])) {
            
        }  // end if isset($_SESSION['screen_width']) AND isset($_SESSION['screen_height'])
        else if (isset($_REQUEST['width']) AND isset($_REQUEST['height'])) {
            //  $_SESSION['screen_width'] = $_REQUEST['width'];
            //  $_SESSION['screen_height'] = $_REQUEST['height'];
            //  header('Location: ' . $_SERVER['PHP_SELF']);
        } // end else if        
        else {
            //echo '<script type="text/javascript">window.location = "' . $_SERVER['PHP_SELF'] . '?width="+$(window).width()+"&height="+$(window).height();</script>';
        }
    }

    function get_course_context($courseid) {
        $query = "select id from mdl_context
                     where contextlevel=50
                     and instanceid='" . $courseid . "' ";
        //echo "Query: ".$query."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $contextid = $row['id'];
        }
        return $contextid;
    }

    function get_user_role($userid) {
        $query = "select * from mdl_role_assignments"
                . "   where userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $roleid = $row['roleid'];
            }
            return $roleid;
        } // end if $num > 0
    }

    function prepare_editor_data($vTexte) {
        $aTexte = explode("\n", $vTexte);
        for ($i = 0; $i < count($aTexte) - 1; $i++) {
            $aTexte[$i] .= '\\';
        }
        return implode("\n", $aTexte);
    }

    function get_course_categories($send = null) {
        $list = "";
        $items = array();
        $query = "select id, name from mdl_course_categories order by name";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $item = new stdClass();
            foreach ($row as $key => $value) {
                $item->$key = $value;
            } // end foreach
            $items[] = $item;
        } // end while
        if (count($items) > 0) {
            $list.="<span class='span3'>Program type</span><span class='span4'>";
            if ($send == null) {
                $list.="<select id='course_categories' style='width:275px;'>";
            } // end if $send==null
            else {
                $list.="<select id='send_course_categories' style='width:275px;'>";
            } // end else
            $list.="<option value='0' selected>Program type</option>";
            foreach ($items as $item) {
                $list.="<option value='$item->id'>$item->name</option>";
            } // end foreach
            $list.="</select>";
        } // end if count($items)>0 
        return $list;
    }
    
    function get_invoice_course_categories () {
    	$list = "";
    	$items = array();
    	$query = "select id, name from mdl_course_categories order by name";
    	$result = $this->db->query($query);
    	while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    		$item = new stdClass();
    		foreach ($row as $key => $value) {
    			$item->$key = $value;
    		} // end foreach
    		$items[] = $item;
    	} // end while
    	if (count($items) > 0) {
    		$list.="<span class='span3'>Program type</span><span class='span4'>";
    		$list.="<select id='invoice_categories' style='width:275px;'>";
    		$list.="<option value='0' selected>Program type</option>";
    		foreach ($items as $item) {
    			$list.="<option value='$item->id'>$item->name</option>";
    		} // end foreach
    		$list.="</select></span>";
    	} // end if count($items)>0
    	return $list;
    }
    
    function get_invoice_course_by_category($id) {
    	$list = "";
    	$items = array();
    	$query = "select id, fullname from mdl_course where category=$id 
    					 and cost>0 and visible=1";
    	$num = $this->db->numrows($query);
    	if ($num > 0) {
    		$result = $this->db->query($query);
    		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    			$item = new stdClass();
    			foreach ($row as $key => $value) {
    				$item->$key = $value;
    			} // end foreach
    			$items[] = $item;
    		} // end while
    		$list.="<span class='span3'>Programs:</span><span class='span4'>
    				<select id='invoice_courses' style='width:275px;'>";
    		$list.="<option value='0' selected>Program</option>";
    		foreach ($items as $item) {
    			$list.="<option value='$item->id'>$item->fullname</option>";
    		} // end foreach
    		$list.="</select></span>";
    	} // end if $num>0
    	else {
    		$list.="<span class='span3'>Programs:</span><span class='span4'>n/a</span>";
    	}
    	return $list;
    }

    function get_course_by_category($id) {
        $list = "";
        $items = array();
        $query = "select id, fullname from mdl_course where category=$id and cost>0 and visible=1";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $item = new stdClass();
                foreach ($row as $key => $value) {
                    $item->$key = $value;
                } // end foreach
                $items[] = $item;
            } // end while
            $list.="<span class='span3'>Programs:</span><span class='span4'><select id='courses' style='width:275px;'>";
            $list.="<option value='0' selected>Program</option>";
            foreach ($items as $item) {
                $list.="<option value='$item->id'>$item->fullname</option>";
            } // end foreach
            $list.="</select></span>";
        } // end if $num>0
        else {
            $list.="<span class='span3'>Programs:</span><span class='span4'>n/a</span>";
        }
        return $list;
    }

    function get_course_by_category2($id) {
        $list = "";
        $items = array();
        $query = "select id, fullname from mdl_course where category=$id and cost>0 and visible=1";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $item = new stdClass();
                foreach ($row as $key => $value) {
                    $item->$key = $value;
                } // end foreach
                $items[] = $item;
            } // end while
            $list.="<span class='span3'>Programs:</span><span class='span4'>";
            $list.="<select id='send_courses' style='width:275px;'>";
            $list.="<option value='0' selected>Program</option>";
            foreach ($items as $item) {
                $list.="<option value='$item->id'>$item->fullname</option>";
            } // end foreach
            $list.="</select></span>";
        } // end if $num>0
        else {
            $list.="<span class='span3'>Programs:</span><span class='span4'>n/a</span>";
        }
        return $list;
    }

    function get_user_details($id) {
        $query = "select * from mdl_user where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new stdClass();
            foreach ($row as $key => $value) {
                $user->$key = $value;
            } // end if $row['firstname'] != '' && $row['lastname'] != ''
        } // end while
        return $user;
    }

    function is_user_deleted($id) {
        $query = "select deleted from mdl_user where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $status = $row['deleted'];
        }
        return $status;
    }

    function get_course_users($id, $output = true, $mutliple = false) {
        //echo "Course id: ".$id."<br>";
        //$mutliple=true; // temp workaround
        $list = "";
        $users = array();
        //1. Get course context
        $instanceid = $this->get_course_context($id);

        //2. Get course users
        $query = "select id, roleid, contextid, userid "
                . "from mdl_role_assignments "
                . "where roleid=$this->student_role and contextid=$instanceid";
        //echo "Query: ".$query."<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $status = $this->is_user_deleted($row['userid']);
                if ($status == 0) {
                    $user = new stdClass();
                    foreach ($row as $key => $value) {
                        $user->$key = $value;
                    } // end foreach
                    $user_detailes = $this->get_user_details($user->userid);

                    if ($user_detailes->lastname != '') {

                        //echo "<br><pre>";
                        //print_r($user_detailes);
                        //echo "</pre><br>";

                        $users[strtolower(trim($user_detailes->lastname)) . "." . strtolower(trim($user_detailes->firstname))] = $user;
                    } // end if $user_details->firstname != '' && $user_details->lastname != ''
                    //$users[] = $user;
                } // end if $status==0
            } // end while
        } // end if $num > 0
        //echo "<br>Users array before sort<pre>";
        // print_r($users);
        // echo "</pre><br>";

        ksort($users);

        //echo "<br>Users array after sort<pre>";
        //print_r($users);
        //echo "</pre><br>";


        if (count($users) > 0) {
            $list.="<span class='span3'>Enrolled users:</span><span class='span4'>";
            if ($mutliple == true) {
                $list.="<select id='users' multiple style='width:275px;'>";
            } // end if $mutliple==true
            else {
                $list.="<select id='users' style='width:275px;'>";
            }

            $list.="<option value='0' selected>Select user</option>";
            foreach ($users as $user) {
                $user_details = $this->get_user_details($user->userid);
                $list.="<option value='$user->userid'>" . ucfirst(strtolower(trim($user_details->lastname))) . " &nbsp;" . ucfirst(strtolower(trim($user_details->firstname))) . "</option>";
            } // end foreach            
            $list.="</select></span>";
        } // end if count($users)>0
        else {
            $list.="<span class='span3'>Enrolled users:</span><span class='span4'>n/a</span>";
        }
        if ($output == true) {
            return $list;
        } // end if $output == true
        else {
            return $users;
        }
    }

    function get_course_users2($id, $output = true, $mutliple = false) {
        $list = "";
        $users = array();
        //1. Get course context
        $instanceid = $this->get_course_context($id);

        //2. Get course users
        $query = "select id, roleid, contextid, userid "
                . "from mdl_role_assignments "
                . "where roleid=$this->student_role and contextid=$instanceid";

        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $status = $this->is_user_deleted($row['userid']);
                if ($status == 0) {
                    $user = new stdClass();
                    foreach ($row as $key => $value) {
                        $user->$key = $value;
                    } // end foreach
                    $user_detailes = $this->get_user_details($user->userid);
                    if ($user_detailes->lastname != '') {
                        $users[strtolower(trim($user_detailes->lastname)) . "." . strtolower(trim($user_detailes->firstname))] = $user;
                    } // end if $user_details->firstname != '' && $user_details->lastname != ''
                    //$users[] = $user;
                } // end if $status==0
            } // end while
        } // end if $num > 0

        ksort($users);

        if (count($users) > 0) {
            $list.="<span class='span3'>Enrolled users:</span><span class='span4'>";
            $list.="<select id='send_users' style='width:275px;'>";
            $list.="<option value='0' selected>Select user</option>";
            foreach ($users as $user) {
                $user_details = $this->get_user_details($user->userid);
                $list.="<option value='$user->userid'>" . ucfirst(strtolower(trim($user_details->lastname))) . " &nbsp;" . ucfirst(strtolower(trim($user_details->firstname))) . "</option>";
            } // end foreach            
            $list.="</select></span>";
        } // end if count($users)>0
        else {
            $list.="<span class='span3'>Enrolled users:</span><span class='span4'>n/a</span>";
        }
        if ($output == true) {
            return $list;
        } // end if $output == true
        else {
            return $users;
        }
    }

    function get_course_promotion_users($id, $output = true) {
        $list = "";
        $users = array();
        //1. Get course context
        $instanceid = $this->get_course_context($id);

        //2. Get course users
        $query = "select id, roleid, contextid, userid "
                . "from mdl_role_assignments "
                . "where roleid=$this->student_role and contextid=$instanceid";
        //echo "Query: ".$query."<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $status = $this->is_user_deleted($row['userid']);
                if ($status == 0) {
                    $user = new stdClass();
                    foreach ($row as $key => $value) {
                        $user->$key = $value;
                    } // end foreach
                    $user_detailes = $this->get_user_details($user->userid);
                    if ($user_detailes->firstname != '' && $user_detailes->lastname != '') {
                        $users[strtolower(trim($user_detailes->lastname))] = $user;
                    } // end if $user_details->firstname != '' && $user_details->lastname != ''                   
                } // end if $status==0
            } // end while
        } // end if $num > 0
        ksort($users);
        if (count($users) > 0) {
            $list.="<span class='span3'>Enrolled users:</span><span class='span4'>";
            $list.="<select id='users' name[]='users'  multiple style='width:275px;'>";
            $list.="<option value='0' selected>Select user</option>";
            foreach ($users as $user) {
                $user_details = $this->get_user_details($user->userid);
                if ($user_details->firstname != '' && $user_details->lastname != '') {
                    $list.="<option value='$user->userid'>" . ucfirst(strtolower(trim($user_details->lastname))) . " &nbsp;" . ucfirst(strtolower(trim($user_details->firstname))) . "</option>";
                }
            } // end foreach            
            $list.="</select></span>";
        } // end if count($users)>0
        else {
            $list.="<span class='span3'>Enrolled users:</span><span class='span4'>n/a</span>";
        }
        if ($output == true) {
            return $list;
        } // end if $output == true
        else {
            return $users;
        }
    }

    function get_course_name($courseid) {
        $query = "select fullname from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['fullname'];
        }
        return $name;
    }

    function get_user_address_block($userid, $invoice_id=null) {
        $list = "";
        $user_detailes = $this->get_user_details($userid);
		$list.="$user_detailes->firstname $user_detailes->lastname<br>";
		$list.="Phone: $user_detailes->phone1<br>";
		$list.="Email: $user_detailes->email<br>";
		$list.="$user_detailes->address<br>";
		$list.="$user_detailes->city, $user_detailes->state, $user_detailes->zip";
        
        return $list;
    }

    function get_course_scheduler($id) {
        $schedulerid = 0;
        $query = "select * from mdl_scheduler where course=$id";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $schedulerid = $row['id'];
            } // end while
        } // end if $num > 0
        return $schedulerid;
    }

    function get_course_workshops($id) {
        $slots = array();
        date_default_timezone_set('Pacific/Wallis');
        $schedulerid = $this->get_course_scheduler($id);
        $list = "";
        $list.="<span class='span3'>Workshops:</span><span class='span4'>";
        $list.="<select id='workshops' style='width:275px;'>";
        $list.="<option value='0' selected>Classes/Workshops</option>";
        if ($schedulerid > 0) {
            $query = "select * from mdl_scheduler_slots "
                    . "where schedulerid=$schedulerid";
            $num = $this->db->numrows($query);
            //echo "Num: ".$num."<br>";
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    if ($row['starttime']) {
                        $slotobj = new stdClass();
                        $slotobj->id = $row['id'];
                        $slotobj->starttime = $row['starttime'];
                        $slotobj->location = $row['appointmentlocation'];
                        $slots[$row['starttime']] = $slotobj;
                    } // end if $location_arr[0] && $location_arr[0] && $row['starttime']
                } // end while
                ksort($slots);
                foreach ($slots as $slot) {
                    $date = date('m-d-Y', $slot->starttime);
                    $list.="<option value='" . $slot->id . "' >" . $slot->location . " - " . $date . "</option>";
                } // end foreach
            } // end if $num > 0
        } // end if $schedulerid>0
        $list.="</select>";
        return $list;
    }

    function get_workshop_users($id) {
        $users = array();
        $list = "";
        $list.="<span class='span3'>Users:</span><span class='span4'>";
        $list.="<select id='ws_users' name[]='ws_users' multiple style='width:275px;'>";
        $list.="<option value='0' selected>Select user</option>";
        $query = "select * from mdl_scheduler_appointment where slotid=$id";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user = $this->get_user_details($row['studentid']);
                $users[ucfirst(strtolower($user->lastname))] = $user;
            } // end while
            ksort($users);
            foreach ($users as $user) {
                $data = ucfirst($user->lastname) . " " . ucfirst($user->firstname);
                $list.="<option value='" . $user->id . "'>$data</option>";
            } // end foreach
        } // end if $num > 0
        $list.="</select>";
        return $list;
    }

    function check_module_permission($mname) {
        $query = "select * from mdl_permissions where module_name='$mname'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $enabled = $row['enabled'];
        }
        return $enabled;
    }

}
