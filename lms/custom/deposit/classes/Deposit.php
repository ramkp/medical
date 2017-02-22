<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';

/**
 * Description of Deposit
 *
 * @author moyo
 */
class Deposit extends Util {

    public $limit = 3;

    function __construct() {
        parent::__construct();
    }

    function get_deposit_page() {
        $list = "";
        $deposits = array();
        $query = "select * from mdl_deposit order by added desc limit 0, $this->limit";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $d = new stdClass();
                foreach ($row as $key => $value) {
                    $d->$key = $value;
                }
                $deposits[] = $d;
            }
        } // end if $num>0
        $list.=$this->create_deposit_page($deposits);
        return $list;
    }

    function create_deposit_page($deposits, $toolbar = true, $search = false) {
        $list = "";

        if ($toolbar) {
            $list.="<div class='row-fluid' style='font-weight:bold;'>";
            $list.="<span class='span2'>Search by date</span>";
            $list.="<span class='span2'><input type='text' id='dep_date1' style='width:75px;'></span>";
            $list.="<span class='span2'><input type='text' id='dep_date2' style='width:75px;'></span>";
            $list.="<span class='span1'><button id='search_dep_btn' class='btn btn-primary'>Go</button></span>";
            $list.="<span class='span2'><button id='clear_search_btn' class='btn btn-primary'>Clear</button></span>";
            $list.="<span class='span1'><button id='add_deposit_btn' class='btn btn-primary'>Add</button></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='display:none;text-align:center;' id='ajax_loader'>";
            $list.="<span class='span9'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
            $list.="</div><br>";
        }

        if (count($deposits) > 0) {

            $total = $this->get_deposits_grand_total($deposits, $search);

            $list.="<div id='deposit_container'>";

            $list.="<div class='row-fluid' style='font-weight:bold;text-align:center;'>";
            $list.="<span class='span8'>Total Deposits: $$total</span>";
            $list.="</div>";

            $list.="<div class='row-fluid' style='font-weight:bold;'>";
            $list.="<span class='span3'>Bank cheque num</span>";
            $list.="<span class='span1'>Amount</span>";
            $list.="<span class='span2'>Manager name</span>";
            $list.="<span class='span2'>Date</span>";
            $list.="</div>";

            $list.="<div class='row-fluid'>";
            $list.="<span class='span9'><hr/></span>";
            $list.="</div>";

            foreach ($deposits as $d) {
                $date = date('m-d-Y', $d->added);
                $user = $this->get_user_details($d->userid);
                $list.="<div class='row-fluid'>";
                $list.="<span class='span3'>$d->banknum</span>";
                $list.="<span class='span1'>$$d->amount</span>";
                $list.="<span class='span2'>$user->firstname $user->lastname</span>";
                $list.="<span class='span2'>$date</span>";
                $list.="</div>";
                $list.="<div class='row-fluid'>";
                $list.="<span class='span9'><hr/></span>";
                $list.="</div>";
            } // end foreach
            $list.="</div>";
            if ($toolbar) {
                $list.="<div class='container-fluid'>";
                $list.="<span class='span9'  id='pagination'></span>";
                $list.="</div>";
            }
        } // end if count($deposits)>0
        else {
            $list.="<br><div class='row-fluid'>";
            $list.="<span class='span3'>There are no any deposits added</span>";
            $list.="</div>";
        }

        return $list;
    }

    function get_deposits_grand_total($deposits, $search) {
        $total = 0;
        if ($search) {
            foreach ($deposits as $d) {
                $total = $total + $d->amount;
            }
        } // end if $search
        else {
            $query = "select sum(amount) as total from mdl_deposit";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $total = $row['total'];
            }
        } // end else
        return $total;
    }

    function search_deposit($d) {
        $list = "";
        $unix_date1 = strtotime($d->date1);
        $unix_date2 = strtotime($d->date2);
        $deposits = array();
        $query = "select * from mdl_deposit "
                . "where added between $unix_date1 and $unix_date2 "
                . "order by added desc";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $d = new stdClass();
                foreach ($row as $key => $value) {
                    $d->$key = $value;
                }
                $deposits[] = $d;
            } // end while
        } // end if $num>0
        $list.=$this->create_deposit_page($deposits, false, true);
        return $list;
    }

    function get_add_deposit_dialilog() {
        $list = "";
        $userid = $this->user->id;
        $user = $this->get_user_details($userid);
        $list.="<div id='myModal' class='modal fade' style='width:675px;'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Add new deposit</h4>
                </div>
                <div class='modal-body' style='width:650px;'>
                   
                <div class='container-fluid'>
                <span class='span2'>Bank cheque num</span>
                <span class='span2'><input type='text' id='banknum' style=''></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span2'>Amount ($)*</span>
                <span class='span2'><input type='text' id='amount' style=''></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span2'>Manager</span>
                <input type='hidden' id='userid' value='$userid'>
                <span class='span2'><input type='text' id='name'  value='$user->firstname $user->lastname' disabled></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span2'>Date*</span>
                <span class='span2'><input type='text' id='pdate' style=''></span>
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span6' style='color:red;' id='dep_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='add_new_deposit_done'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function add_deposit($d) {
        $date = strtotime($d->date);
        $query = "insert into mdl_deposit "
                . "(banknum,"
                . "amount,"
                . "userid,"
                . "added) "
                . "values ('$d->banknum',"
                . "'$d->amount',"
                . "'$d->userid', "
                . "'$date')";
        $this->db->query($query);
    }

    function get_deposit_item($page) {
        $deposits = array();
        $rec_limit = $this->limit;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select * from mdl_deposit "
                . "order by added desc LIMIT $offset, $rec_limit";

        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $d = new stdClass();
            foreach ($row as $key => $value) {
                $d->$key = $value;
            } // end foreach
            $deposits[] = $d;
        } // end while
        $list = $this->create_deposit_page($deposits, false);
        return $list;
    }

    function get_deposit_total() {
        $query = "select * from mdl_deposit";
        $num = $this->db->numrows($query);
        return $num;
    }

}
