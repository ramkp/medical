<?php

/**
 * Description of navClass
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/certificates/classes/Certificates.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/certificates/classes/Renew.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Invoice.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Mailer.php';

class navClass extends Util {

    function get_navigation_items($userid) {
        $top_menu = "";
        $roleid = $this->get_user_role($userid);
        $username = $this->user->username;
        if ($userid == 2) {
            // This is Admin
            $top_menu = $this->get_admin_menu_items($userid);
        }// end if $userid==2
        else {
            if ($roleid != '') {
                //echo "Role ID: ".$roleid."<br>";                
                //echo "Username: ".$username."<br>";            
                if ($roleid == 3 || $roleid == 4) {
                    // Tutors
                    $top_menu = $this->get_tutors_menu_items();
                } // end if $roleid == 3 || $roleid == 4
                if ($roleid == 5) {
                    // Students
                    $top_menu = $this->get_students_menu_items();
                } // end if $roleid == 5
                if ($roleid == 1 || $username == 'manager') {
                    // Manager
                    $top_menu = $this->get_manager_menu();
                } // end if $roleid==1
            } // end if $roleid!=''
            else {
                // This is user w/o enrolled courses            
                $top_menu = $this->get_user_menu();
            }
        } // end else when it is not admin user
        return $top_menu;
    }

    function get_user_menu() {
        $userid = $this->user->id;
        $list = "";
        $list = $list . "<header role='banner' class='navbar'>
        <nav role='navigation' class='navbar-inner'>
            <div class='container-fluid'>
                <a class='brand' href='#'><img src='../../../../../assets/icons/home2.png' width='20' height='20'>&nbsp; Medical2</a>
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

    function get_manager_menu() {
        $userid = $this->user->id;
        $list = "";
        $price_items = $this->get_price_items();
        $permission = $this->check_module_permission('invoice');
        $list = $list . "<header role='banner' class='navbar'>
        <nav role='navigation' class='navbar-inner'>
            <div class='container-fluid'>
                <a class='brand' href='#'><img src='../../../../../assets/icons/home2.png' width='20' height='20'>&nbsp; Medical2 Career College</a>
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
                            <li class='dropdown'><a title='Programs' class='dropdown-toggle' href='#'>Courses<b class='caret'></b></a>                                
                            $price_items
                            </li>";
        //if ($permission == 1) {
        $list.="<li class='dropdown'><a title='Invoices' class='dropdown-toggle' href='#'>Invoices<b class='caret'></b></a>
                                <ul class='dropdown-menu'>
                                    <li><a href='#' title='Invoice' id='data_inv'>Invoice</a></li>
                                    <li><a href='#' title='Open invoices' id='opn_inv'>Open invoices</a></li>
                                    <li><a href='#' title='Paid invoices' id='paid_inv'>Paid invoices</a></li>
                                    <li><a href='#' title='Send invoice' id='send_inv'>Send invoice</a></li>                                    
                                </ul>
                            </li>";
        //}
        $list.="<li class='dropdown'><a title='Payments' class='dropdown-toggle' href='#'>Payments<b class='caret'></b></a>
                                <ul class='dropdown-menu'>
                                    <li><a href='#' id='cards' title='Cards'>Credit cards payments</a></li>
                                    <li><a href='#' id='refund' title='Refund'>Refund payments</a></li>                                                                      
                                </ul>
                            </li>                            
                            <li class='dropdown'><a title='More' class='dropdown-toggle' href='#' id='more'>Tools<b class='caret'></b></a>
                                <ul class='dropdown-menu'>                                   
                                    <li><a href='#' title='Promotions' id='promote'>Bulk Messaging</a></li>
                                    <li><a href='#' title='Promotions' id='register_user'>Register User</a></li>
                                    <li><a href='#' title='User credentials' id='user_cred'>View User</a></li>
                                    <li><a href='#' title='Partial Payments' id='partial'>Partial Payments</a></li>
                                    <li><a href='#' title='Installment Users' id='installment'>Installment Users</a></li>
                                    <li><a href='#' title='Google Map' id='late_fee'>Late Fee</a></li>                                    
                                    <li><a href='#' title='Certificates' id='Certificates'>Certificates</a></li>                                    
                                    <li><a href='#' title='Taxes' id='taxes'>State Taxes</a></li> 
                                </ul>
                            </li>
                            <li class='dropdown'><a title='Invoices' class='dropdown-toggle' href='#'>Manage Site Pages<b class='caret'></b></a>
                                <ul class='dropdown-menu'>
                                    <li><a href='#' title='About' id='about'>About page</a></li>
                                     <li><a href='#' title='Contact Page' id='contact_page'>Contact Page</a></li>
                                    <li><a href='#' title='Testimonial' id='Testimonial'>Clients</a></li> 
                                    <li><a href='#' title='FAQ' id='faq'>FAQ</a></li>
                                    <li><a href='#' title='Photo Gallery' id='Photo_Gallery'>Photo Gallery</a></li>
                                    <li><a href='#' title='Terms' id='terms'>Terms & Conditions</a></li> 
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
                <a class='brand' href='#'><img src='../../../../../assets/icons/home2.png' width='20' height='20'>&nbsp; Medical2 Career College</a>
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
                            <li class='dropdown'><a title='Programs' class='dropdown-toggle' href='#'>Courses<b class='caret'></b></a>                                
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
                                    <!--
                                    <li><a href='#' id='cash' title='Cash'>Cash payments</a></li>
                                    <li><a href='#' id='cheque' title='Cheque'>Cheque payments</a></li>
                                    -->
                                    <li><a href='#' id='cards' title='Cards'>Credit cards payments</a></li>
                                    <li><a href='#' id='refund' title='Refund'>Refund payments</a></li>
                                    <li><a href='#' id='refund_pwd_link' title='Refund password'>Refund password</a></li>
                                    <li><a href='#' id='free' title='Free'>Free</a></li>                                                                        
                                </ul>
                            </li>
                            <li class='dropdown'><a title='Reports' class='dropdown-toggle' href='#'>Reports<b class='caret'></b></a>
                                <ul class='dropdown-menu'>
                                    <li><a href='#' id='user_report' title='Users stats'>Users stats</a></li>
                                    <li><a href='#' id='payments_report' title='Payments log'>Payments log</a></li>                                    
                                    <li><a href='#' id='revenue_reports' title='Revenue report'>Revenue report</a></li>
                                    
                                </ul>
                            </li>                            
                            <li class='dropdown'><a title='More' class='dropdown-toggle' href='#' id='more'>Tools<b class='caret'></b></a>
                                <ul class='dropdown-menu'>
                                    <li><a href='#' title='Permissions' id='permissions'>Permissions</a></li>
                                    <li><a href='#' title='Promotions' id='promote'>Bulk Messaging</a></li>
                                    <li><a href='#' title='Promotions' id='register_user'>Register User</a></li>
                                    <li><a href='#' title='User credentials' id='user_cred'>View User</a></li>
                                    <li><a href='#' title='Renew fee' id='renew_fee'>Renew Fee</a></li>
                                    <li><a href='#' title='Partial Payments' id='partial'>Partial Payments</a></li>
                                    <li><a href='#' title='Installment Users' id='installment'>Installment Users</a></li>
                                    <li><a href='#' title='Late Fee' id='late_fee'>Late Fee</a></li>                                    
                                    <li><a href='#' title='Certificates' id='Certificates'>Certificates</a></li>                                    
                                    <li><a href='#' title='Taxes' id='taxes'>State Taxes</a></li> 
                                </ul>
                            </li>
                            <li class='dropdown'><a title='Invoices' class='dropdown-toggle' href='#'>Manage Site Pages<b class='caret'></b></a>
                                <ul class='dropdown-menu'>
                                    <li><a href='#' title='About' id='about'>About page</a></li>
                                     <li><a href='#' title='Contact Page' id='contact_page'>Contact Page</a></li>
                                    <li><a href='#' title='Testimonial' id='Testimonial'>Clients</a></li> 
                                    <li><a href='#' title='FAQ' id='faq'>FAQ</a></li>
                                    <li><a href='#' title='Index page' id='index'>Index page</a></li>
                                    <li><a href='#' title='Photo Gallery' id='Photo_Gallery'>Photo Gallery</a></li>
                                    <li><a href='#' title='Terms' id='terms'>Terms & Conditions</a></li> 
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

    function get_user_courses($userid) {
        $contexts = array();
        $courses = array();
        $query = "select * from mdl_role_assignments "
                . "where roleid=5 and userid=$userid ";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $contexts[] = $row['contextid'];
        } // end while
        if (count($contexts) > 0) {
            foreach ($contexts as $contextid) {
                $query = "select * from mdl_context where id=$contextid";
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $courses[] = $row['instanceid'];
                } // end while
            } // end foreach
        } // end if count($contexts)>0
        return $courses;
    }

    function get_students_menu_items() {
        $userid = $this->user->id;
        $courses = $this->get_user_courses($userid);
        $list = "";
        $list = $list . "<header role='banner' class='navbar'>
        <nav role='navigation' class='navbar-inner'>
            <div class='container-fluid'>
                <a class='brand' href='#'><img src='../../../../../assets/icons/home2.png' width='20' height='20'>&nbsp; Medical2</a>
                <a class='btn btn-navbar' data-toggle='collapse' data-target='.nav-collapse'>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                </a>
                <div class='nav-collapse collapse'>
                    <div class='nav-divider-right'></div>
                    <ul class='nav pull-left'>
                        <li></li>                        
                        <li class='dropdown'><a href='#cm_submenu_2' class='dropdown-toggle' title='Certificate'>Certificate<b class='caret'></b></a>                        
                        <ul class='dropdown-menu' style='display: none;'>";
        if (count($courses) > 0) {
            foreach ($courses as $courseid) {
                if ($this->user->id == 12937) {
                    $completion_status = 1;
                } // end if
                else {
                    $completion_status = $this->is_course_completed($courseid, $this->user->id);
                } // end else
                if ($completion_status > 0) {
                    $coursename = $this->get_course_name($courseid);
                    $cert = new Certificates();
                    $exists = $cert->is_certificate_exists($courseid, $this->user->id);
                    if ($exists == 0) {
                        // We need to create new certificate
                        $start = $this->get_course_completion_date($courseid, $this->user->id);
                        $year_expiration_sec = 31104000; // 12 month expiration in secs
                        $end = $start + $year_expiration_sec;
                        $cert->create_certificate2($courseid, $this->user->id, $start, $end);
                    } // end if $exists==0                    
                    $userid = $this->user->id;
                    $list.="<li><a title='Print Certificate' a href='http://" . $_SERVER['SERVER_NAME'] . "/lms/custom/certificates/$userid/$courseid/certificate.pdf' target='_blank'>$coursename - Print Certificate</a></li>";
                    if ($courseid != 41) {
                        $list.="<li><a title='Renew Certificate' class='ren_cert' data-courseid='$courseid' data-userid='$userid' href='#'>$coursename - Renew Certificate</a></li>";
                    }
                } // end if $compleation_status!=0        
            } // end foreach
        } // end if count($courses)>0
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
                <a class='brand' href='#'><img src='../../../../../assets/icons/home2.png' width='20' height='20'>&nbsp; Medical2</a>
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
            $completed = $row['timecompleted'];
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

    function get_renew_fee($courseid) {
        $renew = new Renew();
        $amount = $renew->get_renew_amount($courseid);
        return $amount;
    }

    function check_user_balance($courseid, $userid) {
        // Payment should be done not later then 360 days ago        
        $year = 31104000; // 360 days in secs
        $now = time();
        $exp = $now + $year;
        $fee = $this->get_renew_fee($courseid);
        $query = "select * from mdl_card_payments "
                . "where courseid=$courseid"
                . " and userid=$userid "
                . "and psum='$fee' "
                . "and pdate<$exp";
        //echo "Query: ".$query."<br>";
        $num1 = $this->db->numrows($query);

        $query = "select * from mdl_partial_payments "
                . "where courseid=$courseid"
                . " and userid=$userid "
                . "and psum='$fee' "
                . "and pdate<$exp";
        $num2 = $this->db->numrows($query);
        if ($num1 > 0 || $num2 > 0) {
            $num = 1;
        } // end if $num1>0 || $num2>0
        else {
            $num = 0;
        } // end else       

        return $num;
    }

    function update_user_balance($courseid, $userid) {
        $query = "update mdl_user_balance "
                . "set balance_sum='0' "
                . "where courseid=$courseid and userid=$userid";
        //echo $query;
        $this->db->query($query);
    }

    function make_student_course_completed($courseid, $userid) {
        $now = time();
        $query = "insert into mdl_course_completions "
                . "(userid,"
                . "course,"
                . "timeenrolled,"
                . "timestarted,"
                . "timecompleted,"
                . "reaggregate) "
                . "values($userid,"
                . "$courseid,"
                . "$now,"
                . "0,"
                . "$now,"
                . "0)";
        $this->db->query($query);
    }

    function get_course_completion($courseid, $userid) {
        $timecompleted = '';
        $query = "select * from mdl_course_completions "
                . "where course=$courseid "
                . "and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $timecompleted = $row['timecompleted'];
            } // end while
        } // end if $num>0
        return $timecompleted;
    }

    function get_certificate_renew_fee($courseid, $userid) {
        $renew_fee = $this->get_renew_fee($courseid);
        $query = "select * from mdl_certificates "
                . "where courseid=$courseid and userid=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $expiration_date = $row['expiration_date']; // Unix timestap
        }
        $renew = new Renew();
        $late_fee = $renew->get_renew_late_fee($courseid, $expiration_date);
        $total_fee = $renew_fee + $late_fee;
        return $total_fee;
    }

    function renew_certificate($cert) {

        $courseid = $cert->courseid;
        $userid = $cert->userid;

        $query = "select * from mdl_certificates "
                . "where courseid=$courseid "
                . "and userid=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $expire = $row['expiration_date'];
        }

        $renew = new Renew();
        $renew_amount = $renew->get_renew_amount($courseid);
        $late_fee = $renew->get_renew_late_fee($courseid, $expire);

        //echo "Renew amount: " . $renew_amount . "<br>";
        //echo "Late fee: " . $late_fee . "<brr>";


        $one_year_payment = $renew_amount + $late_fee;
        $two_year_payment = $renew_amount * 2 + $late_fee;
        $three_year_payment = $renew_amount * 3 + $late_fee;

        $list.="<div class='container-fluid'>";
        $list.="<span class='span9'>Certificate renew is a paid service (late fee could be applied) .  Please select option: </span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span9'>One year prolongation - <a href='https://" . $_SERVER['SERVER_NAME'] . "/index.php/payments/index/$userid/$courseid/0/$one_year_payment/1' target='_blank'>$$one_year_payment</a></span></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span9'>Two years prolongation - <a href='https://" . $_SERVER['SERVER_NAME'] . "/index.php/payments/index/$userid/$courseid/0/$two_year_payment/2' target='_blank'>$$two_year_payment</a></span></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span9'>Three years prolongation - <a href='https://" . $_SERVER['SERVER_NAME'] . "/index.php/payments/index/$userid/$courseid/0/$three_year_payment/3' target='_blank'>$$three_year_payment</a></span></span>";

        $list.="</div>";

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
        $list = "";
        $query = "select * from mdl_course_categories";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $list.="<li><a href=http://" . $_SERVER['SERVER_NAME'] . "/index.php/programs/program/" . $row['id'] . "/ id='' title='Online Exams'>" . $row['name'] . "</a></li>";
        } // end while
        return $list;
    }

}
