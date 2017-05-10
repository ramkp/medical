<?php

require_once ('/home/cnausa/public_html/lms/class.pdo.database.php');

/**
 * Description of Balance
 *
 * @author moyo
 */
class Balance {

    public $db;
    public $renew_payments;
    public $career_courses;

    function __construct() {
        $db = new pdo_db();
        $this->db = $db;
        $this->renew_payments = array(50, 75, 100, 125, 150, 225);
        $this->career_courses = $this->get_career_courses();
    }

    function has_certificate($courseid, $userid) {
        $date = null;
        $query = "select * from mdl_certificates "
                . "where courseid=$courseid and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $date = $row['issue_date'];
            } // end while
        } // end if $num > 0
        return $date;
    }

    function get_career_courses() {
        $query = "SELECT * FROM `mdl_course` WHERE category BETWEEN 5 and 8";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courses[] = $row['id'];
        }
        return $courses;
    }

    function get_course_cost($courseid) {
        $query = "select * from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cost = $row['cost'];
        }
        return $cost;
    }

    function get_workshop_cost($slotid) {
        $query = "select * from mdl_scheduler_slots where id=$slotid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cost = $row['cost'];
        }
        return $cost;
    }

    function get_course_late_fee($courseid) {
        $query = "select * from mdl_late_fee where courseid=$courseid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $fee = $row['fee_amount'];
            } // end while
        } // end if $num > 0
        else {
            $fee = 25;
        }
        return $fee;
    }

    function get_user_promo_code_discount($courseid, $userid) {
        $list = "";
        $query = "select * from mdl_card_payments "
                . "where userid=$userid and courseid=$courseid "
                . "and refunded=0 "
                . "and promo_code<>''";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $code = $row['promo_code'];
            } // end while
            $query = "select * from mdl_code where code='$code'";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $type = $row['type'];
                $amount = $row['amount'];
            } // end while
            $list.="<div class='row-fluid'>";
            if ($type == 'amount') {
                $list.="<span class='span6'>$$amount off</span>";
            } // end if $type=='amount
            else {
                $list.="<span class='span6'>$amount% off</span>";
            }
            $list.="</div>";
        } // end if $num > 0
        return $list;
    }

    function is_renew_payment($sum, $courseid, $userid, $pdate) {
        $cert_issue_date = $this->has_certificate($courseid, $userid);
        if ($cert_issue_date != null) {
            if (in_array($courseid, $this->career_courses)) {
                // carrer courses do not have renew payments - it is part of balance
                $payment = $sum;
            } /// end if in_array($courseid, $this->career_courses
            if (in_array($sum, $this->renew_payments) && $pdate > $cert_issue_date) {
                $payment = 0; // it is renew payment and should not be part of balance
            } // end if in_array($sum, $this->renew_payments
            else {
                $payment = $sum;
            } // end else
        } // end if $cert_issue_date!=null
        else {
            // Certificate is not yet issued, so payment is part of balance
            $payment = $sum;
        } // end else
        return $payment;
    }

    function get_student_payments_for_balance($courseid, $userid) {

        // 1. Check credit card payments
        // 2. Check brain card payments
        // 3. Check PayPal payments 
        // 4. Check cash payments
        // 5. Check free payments
        // 6. Check invoice payments

        $card_payments = 0;
        $cash_payments = 0;
        $free_payments = 0;
        $invoice_payments = 0;

        $query = "select * from mdl_card_payments "
                . "where courseid=$courseid "
                . "and userid=$userid "
                . "and refunded=0";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $card_payments = $card_payments + $this->is_renew_payment($row['psum'], $courseid, $userid, $row['pdate']);
            }
        }

        // ------------------------------------------------------------- //

        $query = "select * from mdl_card_payments2 "
                . "where courseid=$courseid "
                . "and userid=$userid "
                . "and refunded=0";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $card_payments = $card_payments + $row['psum'];
            }
        }

        // ------------------------------------------------------------- //

        $query = "select * from mdl_paypal_payments "
                . "where courseid=$courseid "
                . "and userid=$userid "
                . "and refunded=0";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $paypal_payments = $paypal_payments + $row['psum'];
            }
        }

        // ------------------------------------------------------------- //

        $query = "select * from mdl_partial_payments "
                . "where courseid=$courseid "
                . "and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $cash_payments = $cash_payments + $this->is_renew_payment($row['psum'], $courseid, $userid, $row['pdate']);
            }
        }

        // ------------------------------------------------------------- //

        $query = "select * from mdl_free "
                . "where courseid=$courseid "
                . "and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $free_payments = $free_payments + $this->is_renew_payment($row['psum'], $courseid, $userid, $row['pdate']);
            }
        }

        // ------------------------------------------------------------- //

        $query = "select * from mdl_invoice "
                . "where courseid=$courseid "
                . "and userid=$userid and i_status=1";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $invoice_payments = $invoice_payments + $this->is_renew_payment($row['i_sum'], $courseid, $userid, $row['i_pdate']);
            }
        }

        $totalpaid = $card_payments + $cash_payments + $free_payments + $invoice_payments;

        return $totalpaid;
    }

    function get_student_payments($courseid, $userid) {

        // 1. Check credit card payments
        // 2. Check brain credit card payments
        // 3. Check PayPal payments
        // 4. Check cash payments
        // 5. Check free payments
        // 6. Check invoice payments

        $card_payments = 0;
        $paypal_payments = 0;
        $cash_payments = 0;
        $free_payments = 0;
        $invoice_payments = 0;

        $query = "select * from mdl_card_payments "
                . "where courseid=$courseid "
                . "and userid=$userid "
                . "and refunded=0";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $card_payments = $card_payments + $row['psum'];
            }
        }

        // ------------------------------------------------------------- //

        $query = "select * from mdl_card_payments2 "
                . "where courseid=$courseid "
                . "and userid=$userid "
                . "and refunded=0";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $card_payments = $card_payments + $row['psum'];
            }
        }

        // ------------------------------------------------------------- //

        $query = "select * from mdl_paypal_payments "
                . "where courseid=$courseid "
                . "and userid=$userid "
                . "and refunded=0";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $paypal_payments = $paypal_payments + $row['psum'];
            }
        }

        // ------------------------------------------------------------- //

        $query = "select * from mdl_partial_payments "
                . "where courseid=$courseid "
                . "and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $cash_payments = $cash_payments + $row['psum'];
            }
        }

        // ------------------------------------------------------------- //

        $query = "select * from mdl_free "
                . "where courseid=$courseid "
                . "and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $free_payments = $free_payments + $row['psum'];
            }
        }

        // ------------------------------------------------------------- //

        $query = "select * from mdl_invoice "
                . "where courseid=$courseid "
                . "and userid=$userid and i_status=1";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $invoice_payments = $invoice_payments + $row['i_sum'];
            }
        }

        $totalpaid = $card_payments + $paypal_payments + $cash_payments + $free_payments + $invoice_payments;
        return $totalpaid;
    }

    function get_item_cost($courseid, $userid, $slotid) {
        $promo_courses = $this->get_user_promo_paid_courses($userid);
        if (!in_array($courseid, $promo_courses)) {
            if ($slotid == null) {
                $itemcost = $this->get_course_cost($courseid);
            } // end if
            else {
                $coursecost = $this->get_course_cost($courseid);
                $wscost = $this->get_workshop_cost($slotid);
                $itemcost = ($wscost > 0) ? $wscost : $coursecost;
            } // end else
        } // end if !in_array($courseid, $promo_courses)
        else {
            $itemcost = $this->get_promo_course_cost($courseid, $userid);
        } // end else
        return $itemcost;
    }

    function get_promo_course_cost($courseid, $userid) {
        $query = "select * from mdl_card_payments "
                . "where userid=$userid and courseid=$courseid "
                . "and refunded=0 "
                . "and promo_code<>''";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $code = $row['promo_code'];
        } // end while

        $query = "select * from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $oldprice = $row['cost'];
        }

        $query = "select * from mdl_code where code='$code'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $type = $row['type'];
            $amount = $row['amount'];
        } // end while

        if ($type == 'amount') {
            $newprice = $oldprice - $amount;
        } // end if
        else {
            $newprice = $oldprice - ($oldprice * $amount) / 100;
        } // end else
        return $newprice;
    }

    function get_user_balance($courseid, $userid, $slotid = null) {
        $promo_courses = $this->get_user_promo_paid_courses($userid);
        if (!in_array($courseid, $promo_courses)) {
            $itemcost = $this->get_item_cost($courseid, $slotid);
        } // end if 
        else {
            $itemcost = $this->get_promo_course_cost($courseid, $userid);
        } // end else
        $totalpaid = $this->get_student_payments($courseid, $userid);
        $balance = $itemcost - $totalpaid;
        // Apply workaround in case if we have discount in CNA program
        if ($courseid == 41 && $balance == 20) {
            $balance_due = 0;
        } // end if $courseid == 41 && $balance == 20 
        else {
            $balance_due = ($balance > 0) ? $balance : 0;
        }
        return $balance_due;
    }

    function get_user_promo_paid_courses($userid) {
        $courses = array();
        $query = "select * from mdl_card_payments "
                . "where userid=$userid "
                . "and refunded=0 "
                . "and promo_code<>''";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $courses[] = $row['courseid'];
            } // enw while
        } // end if $num > 0

        $query = "select * from mdl_card_payments2 "
                . "where userid=$userid "
                . "and refunded=0 "
                . "and promo_code<>''";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $courses[] = $row['courseid'];
            } // enw while
        } // end if $num > 0

        return $courses;
    }

}
