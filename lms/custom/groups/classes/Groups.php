<?php

/**
 * Description of Groups
 *
 * @author sirromas
 */
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Mailer.php';

class Groups extends Util {

    public $limit = 3;

    function get_requests_list() {
        $requests = array();
        $query = "select * from mdl_private_groups order by request_date desc limit 0, $this->limit";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $request = new stdClass();
                foreach ($row as $key => $value) {
                    $request->$key = $value;
                }
                $requests[] = $request;
            } // end while 
        } // end if $num > 0
        $list = $this->render_requests_list($requests);
        return $list;
    }

    function render_requests_list($requests, $wrap = 1) {
        $list = "";        
        if (count($requests) > 0) {
            $list.="<div id='group_items' >";
            foreach ($requests as $request) {
                if ($request->group_request != '') {
                    $group_detailes = $this->get_request_detailed_view($request->id);
                    $list.="<div class='container-fluid'>";
                    $list.="<span class='span3'>$request->group_request</span><span class='span3'>" . date('Y-m-d', $request->request_date) . "</span><span class='span3'><a  id='group_$request->id' href='#' onClick='return false;'>Detailes</a></span>";
                    $list.="</div>";
                    $list.="<div class='container-fluid' style='text-align:center;'>";
                    $list.="<span class='span12' id='det_$request->id' style='text-align:center;display:none;'>$group_detailes</span>";
                    $list.="</div>";
                    $list.= "<div class='container-fluid'>";
                    $list.="<span class='span9'><hr/></span>";
                    $list.= "</div>";
                } // end if $request->group_request != ''
            } // end foreach
            $list.="</div>";
            if ($wrap == 1) {
                $list.="<div class='container-fluid'>";
                $list.="<span class='span9'  id='pagination'></span>";
                $list.="</div>";
            } // end if $wrap == 1
        } // end if count($requests)>0
        else {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span4'>No Private Group Requests found</span>";
            $list.="</div>";
        }
        return $list;
    }

    function get_request_detailes($id) {
        $query = "select * from mdl_private_groups where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $request = new stdClass();
            foreach ($row as $key => $value) {
                $request->$key = $value;
            } // end foreach
        } // end while 
        return $request;
    }

    function get_course_name_by_id($id) {
        $query = "select id, fullname from mdl_course where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['fullname'];
        }
        return $name;
    }

    function get_request_detailed_view($id) {
        $request = $this->get_request_detailes($id);
        $coursename = $this->get_course_name_by_id($request->courseid);
        $list = "";
        $list.="<div class='container-fluid' style='text-align:center;font-weight:bold;'>";
        $list.="<span class='span1'>Contact</span>";
        $list.="<span class='span2'>Course</span>";
        $list.="<span class='span2'>Phone</span>";
        $list.="<span class='span2'>Email</span>";
        $list.="<span class='span2'>Company</span>";
        $list.="<span class='span2'>City</span>";
        $list.="<span class='span1'>Budget</span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span1'>$request->group_fio</span>";
        $list.="<span class='span2'>$coursename</span>";
        $list.="<span class='span2'>$request->group_phone</span>";
        $list.="<span class='span2'>$request->group_email</span>";
        $list.="<span class='span2'>$request->group_company</span>";
        $list.="<span class='span2'>$request->group_city</span>";
        $list.="<span class='span1'>$$request->group_budget</span>";
        $list.="</div>";
        return $list;
    }

    function get_group_recipient($id) {
        $query = "select id, group_email from mdl_private_groups where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $email = $row['group_email'];
        }
        return $email;
    }

    function reply_to_request($id, $reply_text) {
        $mailer = new Mailer();
        $recipient = $this->get_group_recipient($id);
        $query = "update mdl_private_groups "
                . "set group_reply='$reply_text', status=1 where id=$id";
        $this->db->query($query);
        $mailer->send_group_reply_message($reply_text, $recipient);
    }

    function get_group_item($page) {
        $requests = array();
        $rec_limit = $this->limit;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select * from mdl_private_groups order by request_date desc LIMIT $offset, $rec_limit";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $request = new stdClass();
            foreach ($row as $key => $value) {
                $request->$key = $value;
            } // end foreach      
            $requests[] = $request;
        } // end while
        $list = $this->render_requests_list($requests, 0);
        return $list;
    }
    
    function get_total_group_items () {
        $query="select * from mdl_private_groups order by request_date desc";
        $num = $this->db->numrows($query);
        return $num;
    }

}
