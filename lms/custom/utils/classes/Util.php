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

    function __construct() {
        global $USER, $COURSE;
        $db = new pdo_db();
        $this->db = $db;
        $this->user = $USER;
        $this->course = $COURSE;
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

}
