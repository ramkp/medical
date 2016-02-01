<?php

/**
 * Description of navClass
 *
 * @author sirromas
 */
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

class navClass extends Util {

    function get_navigation_items($userid) {
        $top_menu = "";
        if ($userid == 2 || $userid == 3) {
            // This is Admin or Manager - do not calculate role
            $top_menu = $this->get_admin_menu_items($userid);
        }// end if $userid==2 || $USER->id == 3
        else {
            $roleid = $this->get_user_role($userid);
        }
        return $top_menu;
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
                            <li class='dropdown'><a title='Reports' class='dropdown-toggle' href='#'>Reports<b class='caret'></b></a>
                                <ul class='dropdown-menu'>
                                    <li><a href='#' title='Report'>Report#1</a></li>
                                    <li><a href='#' title='Report'>Report#2</a></li>
                                    <li><a href='#' title='Report'>Report#3</a></li>
                                    <li><a href='#' title=''>Report#4</a></li>                                     
                                </ul>
                            </li>                            
                            <li class='dropdown'><a title='More' class='dropdown-toggle' href='#' id='more'>More<b class='caret'></b></a>
                                <ul class='dropdown-menu'>
                                    <li><a href='#' title='Report' id='FAQ'>FAQ’s</a></li>                                    
                                    <li><a href='#' title='Report' id='Google_Map'>Google Map</a></li>                                   
                                    <li><a href='#' title='Report' id='Certificates'>Certificates</a></li>                                     
                                    <li><a href='#' title='Report' id='Testimonial'>Testimonial</a></li>                                     
                                    <li><a href='#' title='Report' id='Photo_Gallery'>Photo Gallery</a></li>                                     
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

}

