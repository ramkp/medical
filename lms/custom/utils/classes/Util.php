<?php

/**
 * Description of Util
 *
 * @author sirromas
 */
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/config.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php');

class Util {

    public $db;
    public $user;
    public $course;
    public $student_role = 5;

    function __construct() {
        global $USER, $COURSE;
        $db = new pdo_db();
        $this->db = $db;
        $this->user = $USER;
        $this->course = $COURSE;
    }

    function get_course_context($courseid) {
        $query = "select id from mdl_context
                     where contextlevel=50
                     and instanceid='" . $courseid . "' ";
        $result = $this->db->query($query);
        while ($row = mysql_fetch_assoc($result)) {
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

    function get_course_categories() {
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
            $list.="<select id='course_categories' style='width:75px;'>";
            foreach ($items as $item) {
                $list.="<option vallue='$item->id'>$item->name</option>";
            } // end foreach
            $list.="</select>";
        } // end if count($items)>0 
        return $list;
    }

    function get_course_by_category($id) {
        $list = "";
        $items = array();
        $query = "id, fullname from mdl_course where category=$id";
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
            $list.="<select id='courses' style='width:75px;'>";
            foreach ($items as $item) {
                $list.="<option vallue='$item->id'>$item->fullname</option>";
            } // end foreach
            $list.="</select>";
        } // end if $num>0
        return $list;
    }

    function get_user_details($id) {
        $query = "select firstname, lastname, email from mdl_user where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new stdClass();
            $user->firstname = $row['firstname'];
            $user->lastname = $row['lastname'];
            $user->email = $row['email'];
        } // end while
        return $user;
    }

    function get_course_users($id) {
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
                $user = new stdClass();
                foreach ($row as $key => $value) {
                    $user->$key = $value;
                } // end foreach
                $users[] = $user;
            } // end while
        } // end if $num > 0
        if (count($users) > 0) {
            $list.="<select id='users' style='width:75px;'>";
            foreach ($users as $user) {
                $user_details = $this->get_user_details($user->userid);
                $list.="<option vallue='$user->userid'>$user_details->$user_details->firstname &nbsp; $user_details->lastname </option>";
            } // end foreach            
            $list.="</select>";
        } // end if count($users)>0
        return $list;
    }

}
