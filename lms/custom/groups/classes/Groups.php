<?php

/**
 * Description of Groups
 *
 * @author sirromas
 */
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Mailer.php';

class Groups extends Util {

    function get_requests_list() {
        $requests = array();
        $query = "select * from mdl_private_groups order by request_date desc";
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

    function render_requests_list($requests) {
        $list = "";
        $list.= "<div class='container-fluid'><span class='span9' style='font-weight:strong;'>Private Groups Requests</span></div>";
        if (count($requests) > 0) {
            foreach ($requests as $request) {
                $status = ($request->status == 0) ? "Not replied" : "Replied";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span6'>$request->group_request</span><span class='span2'>$status</span><span class='span2'>" . date('Y-m-d', $request->request_date) . "</span><span class='span1'><a  id='group_$request->id' href='#' onClick='return fasle;'>Detailes</a></span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span10' id='det_$request->id'></span>";
                $list.="</div>";
            } // end foreach
        } // end if count($requests)>0
        else {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span4'>No Private Group Requests found</span>";
            $list.="</div>";
        }
        return $list;
    }

    function get_request_detailed_view($id) {
        $list="";
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

}
