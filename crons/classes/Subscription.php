<?php

ini_set('memory_limit', '1024M'); // or you could use 1G
//require_once ('/home/cnausa/public_html/class.pdo.database.php');
require_once ('/home/cnausa/public_html/lms/custom/authorize/Classes/ProcessPayment.php');

class Subscription {

    public $db;

    function __construct() {
        $db = new pdo_db();
        $this->db = $db;
    }

    function check_subs_status() {
        $query = "select * from mdl_installment_users "
                . "where completed=0 and canceled=0";
        $num = $this->db->numrows($query);

        if ($num > 0) {
            $pr = new ProcessPayment();
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $subsID = $row['subscription_id'];
                $status = $pr->getSubscriptionStatus($subsID);
                echo "<p align='center'>Subscription with ID=$subsID has status: $status </p>";
                if ($status == 'expired') {
                    $query = "update mdl_installment_users set completed=1 "
                            . "where subscription_id='$subsID'";
                    $this->db->query($query);
                    echo "<p align='center'>Subscription with ID=$subsID was marked as completed </p>";
                } // end if 
            } // end while
        } // end if $num > 0
        else {
            echo "<p align='center'>There are no active subscriptions found</p>";
        } // end else
    }

}
