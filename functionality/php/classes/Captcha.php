<?php

/**
 * Description of Captcha
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';

class Captcha {

    public $db;

    function __construct() {
        $this->db = new pdo_db();
    }

    function verify_captcha($word) {
        // Delete old captcha
        $expiration = time() - 7200; // Two hours limit
        $ip = $_SERVER['REMOTE_ADDR'];
        $query = "DELETE FROM mdl_captcha WHERE captcha_time < " . $expiration;
        $this->db->query($query);          
        
        $query = "SELECT COUNT(*) AS count FROM mdl_captcha "
                . "WHERE word = '$word' "
                . "AND ip_address = '$ip' "
                . "AND captcha_time >$expiration";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $count = $row['count'];
        }
        return $count;
    }

}
