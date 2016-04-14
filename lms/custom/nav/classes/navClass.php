<?php

/**
 * Description of navClass
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/certificates/classes/Certificates.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Invoice.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Mailer.php';

class navClass extends Util {

    function get_navigation_items($userid) {
        $top_menu = "";
        if ($userid == 2) {
            // This is Admin
            $top_menu = $this->get_admin_menu_items($userid);
        }// end if $userid==2
        else {
            $roleid = $this->get_user_role($userid);
            //echo "Role ID: ".$roleid."<br>";
            if ($roleid == 1) {
                // Manager
                $top_menu = $this->get_manager_menu();
            } // end if $roleid==1
            if ($roleid == 3 || $roleid == 4) {
                // Tutors
                $top_menu = $this->get_tutors_menu_items();
            } // end if $roleid == 3 || $roleid == 4
            if ($roleid == 5) {
                // Students
                $top_menu = $this->get_students_menu_items();
            } // end if $roleid == 5
        }
        return $top_menu;
    }

    function get_manager_menu() {
        $userid = $this->user->id;
        $list = "";
        $price_items = $this->get_price_items();
        $list = $list . "<header role='banner' class='navbar'>
        <nav role='navigation' class='navbar-inner'>
            <div class='container-fluid'>
                <a class='brand' href='#'><img src='../../../../../assets/icons/home2.png' width='20' height='20'>&nbsp; Medical2 Training Institute</a>
                <a class='btn btn-navbar' data-toggle='collapse' data-target='.nav-collapse'>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                </a>
                <div class='nav-collapse collapse'>
                    <div class='nav-divider-right'></div>
                    <ul class='nav pull-right'>
                        <li></li>
                    </ul>
                    <div class='nav-collapse collapse'>
                        <ul class='nav'>
                            <li class='dropdown'><a title='Programs' class='dropdown-toggle' href='#'>Programs<b class='caret'></b></a>                                
                            $price_items
                            </li>                            
                            <li class='dropdown'><a title='Invoices' class='dropdown-toggle' href='#'>Invoices<b class='caret'></b></a>
                                <ul class='dropdown-menu'>
                                    <li><a href='#' title='Invoice' id='data_inv'>Invoice</a></li>
                                    <li><a href='#' title='Open invoices' id='opn_inv'>Open invoices</a></li>
                                    <li><a href='#' title='Paid invoices' id='paid_inv'>Paid invoices</a></li>
                                    <li><a href='#' title='Send invoice' id='send_inv'>Send invoice</a></li>                                    
                                </ul>
                            </li>                            
                            <li class='dropdown'><a title='Payments' class='dropdown-toggle' href='#'>Payments<b class='caret'></b></a>
                                <ul class='dropdown-menu'>
                                    <li><a href='#' id='cash' title='Cash'>Cash payments</a></li>
                                    <li><a href='#' id='cheque' title='Cheque'>Cheque payments</a></li>
                                    <li><a href='#' id='cards' title='Cards'>Credit cards payments</a></li>
                                    <li><a href='#' id='free' title='Free'>Free</a></li>                                    
                                    <li><a href='#' id='refund' title='Refund'>Refund</a></li>                            
                                </ul>
                            </li>                            
                            <li class='dropdown'><a title='More' class='dropdown-toggle' href='#' id='more'>More<b class='caret'></b></a>
                                <ul class='dropdown-menu'>
                                    <li><a href='#' title='About' id='about'>About page</a></li>
                                    <li><a href='#' title='Users Feedback' id='feedback'>Users Feedback</a></li>
                                    <li><a href='#' title='Installment Users' id='installment'>Installment Users</a></li>
                                    <li><a href='#' title='FAQ' id='FAQ'>FAQ’s</a></li>
                                    <li><a href='#' title='Groups' id='Groups'>Private Groups</a></li>                                    
                                    <li><a href='#' title='Google Map' id='contact_page'>Contact Page</a></li>
                                    <li><a href='#' title='Google Map' id='late_fee'>Late Fee</a></li>
                                    <li><a href='#' title='Certificates' id='Certificates'>Certificates</a></li>                                    
                                    <li><a href='#' title='Testimonial' id='Testimonial'>Testimonial</a></li> 
                                    <li><a href='#' title='Taxes' id='taxes'>State Taxes</a></li> 
                                    <li><a href='#' title='Photo Gallery' id='Photo_Gallery'>Photo Gallery</a></li>                                     
                                </ul>
                            </li>
                            <li class='dropdown'><a title='Account' class='dropdown-toggle' href='#cm_submenu_2'>Account<b class='caret'></b></a>
                                <ul class='dropdown-menu'>                                
                                    <li><a href='/lms/user/profile.php?id=$userid' title='Profile'>Profile</a></li>                                    
                                    <li><a href='/lms/user/preferences.php' title='Preferences'>Preferences</a></li>
                                    <li><a href='/lms/message/index.php' title='Preferences'>Messages</a></li>
                                    <li><a href='/lms/login/logout.php?seskey='gqe32fe3' title='Logout'>Logout</a></li>                                            
                                </ul>
                            </li>
                        </ul>
                        <div class='nav-divider-right'></div>
                        <ul class='nav pull-right'>
                            <li></li>
                        </ul>
                    </div>
                    <div class='nav-divider-left'></div>
                </div>
            </div>
        </nav>
    </header>";
        return $list;
    }

    function get_admin_menu_items() {
        $userid = $this->user->id;
        $list = "";
        $price_items = $this->get_price_items();
        $list = $list . "<header role='banner' class='navbar'>
        <nav role='navigation' class='navbar-inner'>
            <div class='container-fluid'>
                <a class='brand' href='#'><img src='../../../../../assets/icons/home2.png' width='20' height='20'>&nbsp; Medical2 Training Institute</a>
                <a class='btn btn-navbar' data-toggle='collapse' data-target='.nav-collapse'>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                </a>
                <div class='nav-collapse collapse'>
                    <div class='nav-divider-right'></div>
                    <ul class='nav pull-right'>
                        <li></li>
                    </ul>
                    <div class='nav-collapse collapse'>
                        <ul class='nav'>
                            <li class='dropdown'><a title='Programs' class='dropdown-toggle' href='#'>Programs<b class='caret'></b></a>                                
                            $price_items
                            </li>                            
                            <li class='dropdown'><a title='Invoices' class='dropdown-toggle' href='#'>Invoices<b class='caret'></b></a>
                                <ul class='dropdown-menu'>
                                    <li><a href='#' title='Invoice' id='data_inv'>Invoice</a></li>
                                    <li><a href='#' title='Open invoices' id='opn_inv'>Open invoices</a></li>
                                    <li><a href='#' title='Paid invoices' id='paid_inv'>Paid invoices</a></li>
                                    <li><a href='#' title='Send invoice' id='send_inv'>Send invoice</a></li>                                    
                                </ul>
                            </li>                            
                            <li class='dropdown'><a title='Payments' class='dropdown-toggle' href='#'>Payments<b class='caret'></b></a>
                                <ul class='dropdown-menu'>
                                    <li><a href='#' id='cash' title='Cash'>Cash payments</a></li>
                                    <li><a href='#' id='cheque' title='Cheque'>Cheque payments</a></li>
                                    <li><a href='#' id='cards' title='Cards'>Credit cards payments</a></li>
                                    <li><a href='#' id='free' title='Free'>Free</a></li>                                    
                                    <li><a href='#' id='refund' title='Refund'>Refund</a></li>                            
                                </ul>
                            </li>
                            <li class='dropdown'><a title='Reports' class='dropdown-toggle' href='#'>Reports<b class='caret'></b></a>
                                <ul class='dropdown-menu'>
                                    <li><a href='#' id='user_report' title='Users stats'>Users stats</a></li>
                                    <li><a href='#' id='payments_report' title='Payments log'>Payments log</a></li>                                    
                                    <li><a href='#' id='program_reports' title='Program reports'>Program reports</a></li>
                                    <li><a href='#' id='revenue_reports' title='Revenue reports'>Revenue reports</a></li>
                                    <li><a href='#' id='workshop_reports' title='Workshop reports'>Workshop reports</a></li>
                                </ul>
                            </li>                            
                            <li class='dropdown'><a title='More' class='dropdown-toggle' href='#' id='more'>More<b class='caret'></b></a>
                                <ul class='dropdown-menu'>
                                    <li><a href='#' title='About' id='about'>About page</a></li>
                                    <li><a href='#' title='Users Feedback' id='feedback'>Users Feedback</a></li>
                                    <li><a href='#' title='Renew fee' id='renew_fee'>Renew Fee</a></li>
                                    <li><a href='#' title='Installment Users' id='installment'>Installment Users</a></li>
                                    <li><a href='#' title='FAQ' id='FAQ'>FAQ’s</a></li>
                                    <li><a href='#' title='Groups' id='Groups'>Private Groups</a></li>
                                    <li><a href='#' title='Google Map' id='contact_page'>Contact Page</a></li>
                                    <li><a href='#' title='Google Map' id='late_fee'>Late Fee</a></li>                                   
                                    <li><a href='#' title='Certificates' id='Certificates'>Certificates</a></li>                                    
                                    <li><a href='#' title='Testimonial' id='Testimonial'>Testimonial</a></li> 
                                    <li><a href='#' title='Taxes' id='taxes'>State Taxes</a></li> 
                                    <li><a href='#' title='Photo Gallery' id='Photo_Gallery'>Photo Gallery</a></li>                                     
                                </ul>
                            </li>
                            <li class='dropdown'><a title='Account' class='dropdown-toggle' href='#cm_submenu_2'>Account<b class='caret'></b></a>
                                <ul class='dropdown-menu'>                                
                                    <li><a href='/lms/user/profile.php?id=$userid' title='Profile'>Profile</a></li>                                    
                                    <li><a href='/lms/user/preferences.php' title='Preferences'>Preferences</a></li>
                                    <li><a href='/lms/message/index.php' title='Preferences'>Messages</a></li>
                                    <li><a href='/lms/login/logout.php?seskey='gqe32fe3' title='Logout'>Logout</a></li>                                            
                                </ul>
                            </li>
                        </ul>
                        <div class='nav-divider-right'></div>
                        <ul class='nav pull-right'>
                            <li></li>
                        </ul>
                    </div>
                    <div class='nav-divider-left'></div>
                </div>
            </div>
        </nav>
    </header>";
        return $list;
    }

    function get_price_items() {
        $list = "";
        $list = $list . "<ul class='dropdown-menu' id='prices'>";
        $query = "select id,name from mdl_course_categories order by id ";
        $num = $this->db->numrows($query);
        if ($num) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $list = $list . "<li><a href='' title='" . $row['name'] . "' id='" . $row['id'] . "' onClick='return false;'>" . $row['name'] . "</a></li>";
            } // end while
        } // end if $num)
        else {
            $list = $list . "<li><a href='' title='There are no price items' >There are no price items</a></li>";
        }
        $list = $list . "</ul>";
        return $list;
    }

    function is_course_expired($courseid) {
        $query = "select expired from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $expired = $row['expired'];
        }
        return $expired;
    }

    function get_students_menu_items() {
        $userid = $this->user->id;
        $courseid = $this->get_user_course($userid);
        $expired = $this->is_course_expired($courseid);
        $list = "";
        $list = $list . "<header role='banner' class='navbar'>
        <nav role='navigation' class='navbar-inner'>
            <div class='container-fluid'>
                <a class='brand' href='#'><img src='../../../../../assets/icons/home2.png' width='20' height='20'>&nbsp; Medical2 Training Institute</a>
                <a class='btn btn-navbar' data-toggle='collapse' data-target='.nav-collapse'>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                </a>
                <div class='nav-collapse collapse'>
                    <div class='nav-divider-right'></div>
                    <ul class='nav pull-left'>
                        <li></li>                        
                        <li class='dropdown'><a href='#cm_submenu_2' class='dropdown-toggle' title='Cerrtificate'>Certtificate<b class='caret'></b></a>                        
                        <ul class='dropdown-menu' style='display: none;'>                                            
                        <li><a title='Get Certificate' id='get_cert' href='#'>Get Certificate</a></li>";
        if ($expired != 0) {
            $list.="<li><a title='Renew Certificate' id='ren_cert' href='#'>Renew Certificate</a></li>";
        }
        $list.="</ul>
                        </li>                        
                    </ul>                            
                    <div class='nav-collapse collapse'>
                        <ul class='nav pull-right'>                   
                            <li class='dropdown'><a title='Account' class='dropdown-toggle' href='#cm_submenu_2'>Account<b class='caret'></b></a>
                                <ul class='dropdown-menu'>                                
                                    <li><a href='/lms/user/profile.php?id=$userid' title='Profile'>Profile</a></li>                                    
                                    <li><a href='/lms/user/preferences.php' title='Preferences'>Preferences</a></li>
                                    <li><a href='/lms/message/index.php' title='Preferences'>Messages</a></li>
                                    <li><a href='/lms/login/logout.php?seskey='gqe32fe3' title='Logout'>Logout</a></li>                                            
                                </ul>
                            </li>
                        </ul>
                        <div class='nav-divider-right'></div>
                        <ul class='nav pull-right'>
                            <li></li>
                        </ul>
                    </div>
                    <div class='nav-divider-left'></div>
                </div>
            </div>
        </nav>
    </header>";
        return $list;
    }

    function get_tutors_menu_items() {
        $userid = $this->user->id;
        $list = "";
        $list = $list . "<header role='banner' class='navbar'>
        <nav role='navigation' class='navbar-inner'>
            <div class='container-fluid'>
                <a class='brand' href='#'><img src='../../../../../assets/icons/home2.png' width='20' height='20'>&nbsp; Medical2 Training Institute</a>
                <a class='btn btn-navbar' data-toggle='collapse' data-target='.nav-collapse'>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                </a>
                <div class='nav-collapse collapse'>
                    <div class='nav-divider-right'></div>
                    <ul class='nav pull-right'>
                        <li></li>
                    </ul>
                    <div class='nav-collapse collapse'>
                        <ul class='nav pull-right'>                   
                            <li class='dropdown'><a title='Account' class='dropdown-toggle' href='#cm_submenu_2'>Account<b class='caret'></b></a>
                                <ul class='dropdown-menu'>                                
                                    <li><a href='/lms/user/profile.php?id=$userid' title='Profile'>Profile</a></li>                                    
                                    <li><a href='/lms/user/preferences.php' title='Preferences'>Preferences</a></li>
                                    <li><a href='/lms/message/index.php' title='Preferences'>Messages</a></li>
                                    <li><a href='/lms/login/logout.php?seskey='gqe32fe3' title='Logout'>Logout</a></li>                                            
                                </ul>
                            </li>
                        </ul>
                        <div class='nav-divider-right'></div>
                        <ul class='nav pull-right'>
                            <li></li>
                        </ul>
                    </div>
                    <div class='nav-divider-left'></div>
                </div>
            </div>
        </nav>
    </header>";
        return $list;
    }

    function get_user_creation_time($userid) {
        $query = "select timecreated from mdl_user where id=$userid";
        //echo "Query: ".$query."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $timecreated = $row['timecreated'];
        }
        return $timecreated;
    }

    function get_user_course($userid) {
        $timecreated = $this->get_user_creation_time($userid);
        $query = "select * from mdl_role_assignments "
                . "where roleid=5 and userid=$userid "
                . "and timemodified='$timecreated'";
        //echo "Query: ".$query."<br>";
        $num = $this->db->numrows($query);
        if ($num == 0) {
            $query = "select * from mdl_role_assignments "
                    . "where roleid=5 and userid=$userid ";
        }
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $contextid = $row['contextid'];
        }

        $query = "select * from mdl_context where id=$contextid";
        //echo "Query: ".$query."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['instanceid'];
        }
        return $courseid;
    }

    function is_course_completed($courseid, $userid) {
        $query = "select * from mdl_course_completions "
                . "where course=$courseid and userid=$userid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_course_completion_date($courseid, $userid) {
        $query = "select * from mdl_course_completions "
                . "where course=$courseid and userid=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $completed = $row['timestarted'];
        }
        return $completed;
    }

    function get_certificate() {
        $list = "";
        $courseid = $this->get_user_course($this->user->id);
        $completion_status = $this->is_course_completed($courseid, $this->user->id);
        if ($completion_status == 0) {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span9'>You still did not completed the course, certificate is not available.</span>";
            $list.="</div>";
        } // end if $compleation_status!=0
        else {
            $date = $this->get_course_completion_date($courseid, $this->user->id);
            $now = time(); // secs
            $year_expiration_sec = 28512000; // 11 month expiration in secs
            $diff = $now - $date;
            if ($diff < $year_expiration_sec) {
                $user = $this->get_user_details($this->user->id);
                $cert = new Certificates();
                $cert->send_certificate($courseid, $this->user->id, $date);
                $list.="<div class='container-fluid'>";
                $list.="<span class='span9'>Certificate has been sent to $user->email.</span>";
                $list.="</div>";
            } // end if $diff<$year_expiration_sec
            else {
                $list.="<div class='container-fluid'>";
                $list.="<span class='span9'>Your Certificate almost expire, please use renew option</span>";
                $list.="</div>";
            } // end else
        }
        return $list;
    }

    function check_user_balance($courseid, $userid) {
        $query = "select * from mdl_user_balance "
                . "where courseid=$courseid and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $sum = $row['balance_sum'];
            } // end while
        } // end if $num>0
        else {
            $sum = 'n/a';
        }
        return $sum;
    }

    function update_user_balance($courseid, $userid) {
        $query = "update mdl_user_balance "
                . "set balance_sum='0' "
                . "where courseid=$courseid and userid=$userid";
        //echo $query;
        $this->db->query($query);
    }

    function renew_certificate() {
        $courseid = $this->get_user_course($this->user->id);
        $sum = $this->check_user_balance($courseid, $this->user->id);
        if ($sum > 0) {
            $user = $this->get_user_details($this->user->id);
            $cert = new Certificates();
            $date = $cert->get_course_completion($courseid, $this->user->id, TRUE);
            $new_date = $date + 31536000;
            $cert->send_certificate($courseid, $this->user->id, $new_date);
            $this->update_user_balance($courseid, $this->user->id);
            $list.="<div class='container-fluid'>";
            $list.="<span class='span9'>New Certificate has been sent to $user->email.</span>";
            $list.="</div>";
        } // end if $sum>0
        else {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span9'>Your balance does not contain enough funds to renew certificate.</span>";
            $list.="</div>";
            $list.="<div class='container-fluid'>";
            $list.="<span class='span9'>Certificate renew is a paid service. Please click <a href='#' onClick='return false;' id='send_invoice_renew'>here</a> to get Certificate Renew invoice. Once invoice will be paid you will be able to Renew Certificate</span>";
            $list.="</div>";
            return $list;
        }
        return $list;
    }

    function send_invoice_for_renew() {
        $courseid = $this->get_user_course($this->user->id);
        $invoice = new Invoice();
        $path = $invoice->send_renew_invoice($courseid, $this->user->id);
        //echo "Invoice path: ".$path."<br>";
        $userdata = $this->get_user_details($this->user->id);
        $user = new stdClass();
        $user->first_name = $userdata->firstname;
        $user->last_name = $userdata->lastname;
        $user->email = $userdata->email;
        $user->invoice = $path;

        $mailer = new Mailer();
        $mailer->send_invoice($user);
        $list.="<div class='container-fluid'>";
        $list.="<span class='span9'>Invoice has been sent to $user->email.</span>";
        $list.="</div>";
        return $list;
    }

    function get_categories_menu() {
        $list="";
        $query = "select * from mdl_course_categories";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $list.="<li><a href=http://".$_SERVER['SERVER_NAME']."/index.php/programs/program/".$row['id']."/ id='' title='Online Exams'>".$row['name']."</a></li>";
        } // end while
        return $list;
    }

}
