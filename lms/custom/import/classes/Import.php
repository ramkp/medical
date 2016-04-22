<?php

/**
 * Description of Import
 *
 * @author sirromas
 */
set_time_limit(0);
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/config.php';
require_once($CFG->dirroot . '/user/editlib.php');

class Import extends Util {

    function get_password($length = 8) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    function valid_email($email) {
        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    function read_data_from_file($filepath) {
        $users = array();
        $handle = @fopen($filepath, "r");
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                $clean_buffer = str_replace(',,', ',', $buffer);
                $user = $this->check_file_data($clean_buffer);
                if ($user != null) {
                    $users[] = $user;
                } // end if $user!=null
            } // end while            
            fclose($handle);
        } // end if $handle
        else {
            die("Error: can't open file <br>");
        } // end else
        return $users;
    }

    function check_file_data($buffer) {
        global $CFG;
        $user = null;
        $line_arr = explode(",", $buffer);
        $firstname = $line_arr[0];
        $lastname = $line_arr[1];
        $email = strtolower($line_arr[2]);
        $phone = $line_arr[3];
        $pwd = $this->get_password();

        $address = $line_arr[5] . " " . $line_arr[6];
        $city = $line_arr[6];
        $zip = $line_arr[7];

        if ($firstname != '' && $lastname != '' && $email != '' && $phone != '') {
            //if ($this->valid_email($email)) {
            $user = new stdClass();
            $user->confirmed = 1;
            $user->username = $email;
            $user->password = $pwd;
            $user->purepwd = $pwd;
            $user->email = $email;
            $user->email1 = $email;
            $user->email2 = $email;
            $user->phone = $phone;
            $user->firstname = $firstname;
            $user->lastname = $lastname;
            $user->address = $address;
            $user->institution = '---';
            $user->zip = $zip;
            $user->city = $city;
            $user->state = '';
            $user->country = 'US';
            $user->lang = current_language();
            $user->firstaccess = 0;
            $user->timecreated = time();
            $user->mnethostid = $CFG->mnet_localhost_id;
            $user->secret = random_string(15);
            $user->auth = $CFG->registerauth;
            return $user;
            //} // end if valid_email($email)            
        } // end if $firstname != '' && $lastname != '' ...        
        else {
            return $user;
        }
    }

    function is_user_exists($username) {
        $query = "select id from mdl_user where username='$username'";
        $num = $this->db->numrows($query);
        return $num;
    }

    function signup_user($user) {
        global $CFG;
        $authplugin = get_auth_plugin($CFG->registerauth);
        $namefields = array_diff(get_all_user_name_fields(), useredit_get_required_name_fields());
        foreach ($namefields as $namefield) {
            $user->$namefield = '';
        }

        try {
            $authplugin->user_signup($user, false);
            $this->update_users_data($user);
            echo "User $user->username has been imported";
            echo "<br>-------------------------------------------------------<br>";
        } // end try        
        catch (Exception $e) {
            echo 'Error occured: ', $e->getMessage(), "\n";
        }
    }

    function update_users_data($user) {
        $query = "update mdl_user "
                . "set purepwd='$user->purepwd' , "
                . "phone1='$user->phone' "
                . "where username='$user->username'";
        $this->db->query($query);
    }

    function process_user_data($filepath) {
        $users = $this->read_data_from_file($filepath);
        if (count($users) > 0) {
            $counter = 0;
            $already_imported_counter = 0;
            foreach ($users as $user) {
                if ($this->is_user_exists($user->username) == 0) {
                    /*
                     * 
                      echo "<pre>----------------------------------------------<br>";
                      print_r($user);
                      echo "<pre>----------------------------------------------<br>";
                     * 
                     */
                    $this->signup_user($user);
                    echo "User $user->username will be imported ...<br>";
                    $counter++;
                } // end if $this->is_user_exists($user->username)==0
                else {
                    echo "User already exists <br>";
                    $already_imported_counter++;
                }
            } // end foreach
            //echo "<p align='center'>Total already imported (simulation): " . $already_imported_counter . "</p>";
            //echo "<p align='center'>Total users to be imported (simulation): " . $counter . "</p>";

            echo "<p align='center'>Total already imported (real operation): " . $already_imported_counter . "</p>";
            echo "<p align='center'>Total users to be imported (real operation): " . $counter . "</p>";
        } // end if count($users) > 0
        else {
            echo "There are no users selected from the file ... <br>";
        }
    }

    function get_user_data($username) {
        $user = null;
        $query = "select uid,username from mdl_user where username='$username'";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user = new stdClass();
                $user->username = $row['username'];
                $user->uid = $row['uid'];
            }
        } // end if $num>0        
        return $user;
    }

    function update_users_uid($filepath) {
        $handle = @fopen($filepath, "r");
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                $clean_buffer = str_replace(',,', ',', $buffer);
                $data = explode(",", $clean_buffer);
                $uid = $data[0];
                $username = trim(strtolower($data[1]));
                $user = $this->get_user_data($username);
                if ($user) {
                    echo "File username: " . $username . "<br>";
                    echo "Object username: " . $user->username . "<br>";
                    if ($user->username == $username) {
                        $query = "update mdl_user set uid='$uid' where username='$username'";
                        $this->db->query($query);
                        echo "User ($username) has been updated with uid $uid ...<br>";
                    } // end if $user->username=='$username' && $user->uid==''
                    else {
                        echo "User ($username) already has uid ... <br>";
                    }
                    echo "<br>--------------------------------------<br>";
                } // end if $user!=null
            } // end while            
            fclose($handle);
        } // end if $handle
        else {
            die("Error: can't open file <br>");
        } // end else
    }

}
