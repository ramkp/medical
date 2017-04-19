<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

/**
 * Description of Groups
 *
 * @author moyo
 */
class Groups extends Util {

    public $limit = 3;

    function __construct() {
        parent::__construct();
        $this->create_groups_data();
    }

    function create_groups_data() {
        $query = "select * from mdl_groups where courseid>0 order by name";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $has_members = $this->is_has_users($row['id']);
                if ($has_members > 0) {
                    $groupnames[] = mb_convert_encoding($row['name'], 'UTF-8');
                } // end if $has_members > 0
            } // end while 
            file_put_contents('/home/cnausa/public_html/lms/custom/utils/groups.json', json_encode($groupnames));
        } // end if $num > 0
    }

    function is_has_users($groupid) {
        $query = "select * from mdl_groups_members where groupid=$groupid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_groups_page() {
        $list = "";
        $groups = array();
        $query = "select * from mdl_groups where courseid>0 "
                . "order by name limit 0, $this->limit";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $has_members = $this->is_has_users($row['id']);
                if ($has_members > 0) {
                    $g = new stdClass();
                    foreach ($row as $key => $value) {
                        $g->$key = $value;
                    }
                    $groups[] = $g;
                } // end if $has_members > 0
            } // end while
        } // end if $num>0
        $list.=$this->create_groups_page($groups);
        return $list;
    }

    function create_groups_page($groups, $toolbar = true) {
        $list = "";

        if ($toolbar) {
            $list.="<div class='row-fluid'>";
            $list.="<span class='span1'>Search</span>";
            $list.="<span class='span3'><input type='text' id='group_search_text'></span>";
            $list.="<span class='span2'><button class='btn btn-primary' id='search_group_button'>Search</button></span>";
            $list.="<span calss='span2'><button class='btn btn-primary' id='clear_search_group_button'>Clear</button></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='display:none;text-align:center;' id='ajax_loader'>";
            $list.="<span class='span9'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
            $list.="</div>";

            $list.="<div class='row-fluid'>";
            $list.="<span class='span9'><hr/></span>";
            $list.="</div>";

            $list.="<div class='row-fluid' style='font-weight:bold;'>";
            $list.="<span class='span3'>Group Name</span>";
            $list.="<span class='span3'>Course Name</span>";
            $list.="<span class='span3'>Group Users</span>";
            $list.="</div>";
        }
        if (count($groups) > 0) {
            $list.="<div id='groups_container'>";
            foreach ($groups as $g) {
                $users = $this->get_group_users($g->id);
                $total_users = $this->get_group_total_users($g->id);
                $coursename = $this->get_course_name($g->courseid);
                $list.="<div class='row-fluid'>";
                $list.="<span class='span3'>$g->name <br>" . $total_users . " total participants</span>";
                $list.="<span class='span3'>$coursename</span>";
                $list.="<span class='span3'>$users</span>";
                $list.="</div>";
                $list.="<div class='row-fluid'>";
                $list.="<span class='span9'><hr/></span>";
                $list.="</div>";
            } // end foreach
            $list.="</div>";
        } // end if count($groups)>0
        else {
            $list.="<div class='row-fluid'>";
            $list.="<span class='span9'>There are no groups at the course</span>";
            $list.="</div>";
        }
        if ($toolbar) {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span9'  id='pagination'></span>";
            $list.="</div>";
        }

        return $list;
    }

    function get_group_users($groupid) {
        $list = "";
        $users = array();
        $query = "select * from mdl_groups_members where groupid=$groupid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $row['userid'];
        }
        foreach ($users as $userid) {
            $user = $this->get_user_details($userid);
            $list.="<div class='row-fluid'>";
            $list.="<span class='span12'><a href='https://" . $_SERVER['SERVER_NAME'] . "/lms/user/profile.php?id=$userid' target='_blank' style='cursor:pointer;'>$user->firstname $user->lastname</a></span>";
            $list.="</div>";
        }

        return $list;
    }

    function get_group_total_users($groupid) {
        $query = "select * from mdl_groups_members where groupid=$groupid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_total_groups() {
        $groups = array();
        $query = "select * from mdl_groups where courseid>0 order by name";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $has_members = $this->is_has_users($row['id']);
                if ($has_members > 0) {
                    $groups[] = $row['id'];
                } // end if $has_members > 0
            } // end while
            $total = count($groups);
        } // end if $num>0
        else {
            $total = 0;
        }
        return $total;
    }

    function get_group_item($page) {
        $groups = array();
        $rec_limit = $this->limit;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select * from mdl_groups where courseid>0"
                . " order by name LIMIT $offset, $rec_limit";
        //echo "Query: ".$query."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $has_users = $this->is_has_users($row['id']);
            if ($has_users > 0) {
                $g = new stdClass();
                foreach ($row as $key => $value) {
                    $g->$key = $value;
                } // end foreach
                $groups[] = $g;
            }
        } // end while
        $list = $this->create_groups_page($groups, false);
        return $list;
    }

    function search_group_item($item) {
        $list = "";
        $groups = array();
        $query = "select * from mdl_groups where name like '%$item%' "
                . "order by name";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $has_users = $this->is_has_users($row['id']);
                if ($has_users > 0) {
                    $g = new stdClass();
                    foreach ($row as $key => $value) {
                        $g->$key = $value;
                    } // end foreach
                    $groups[] = $g;
                } // end if $has_users > 0
            } // end while
        } // end if $num > 0
        $list.= $this->create_groups_page($groups, false);
        return $list;
    }

}
