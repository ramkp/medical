<?php

/**
 * Description of Programs
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';

class Programs {

    public $db;

    function __construct() {
        $this->db = new pdo_db();
    }

    function get_category_id($cat_name) {
        $query = "select id, name "
                . "from mdl_course_categories "
                . "where name like '%$cat_name%'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
        }
        return $id;
    }

    function get_category_items($cat_name) {
        $items = array();
        $cat_id = $this->get_category_id($cat_name);
        $query = "select id,fullname,summary,startdate,cost,discount_size "
                . "from mdl_course where category=$cat_id and cost>0";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $item = new stdClass();
                foreach ($row as $key => $value) {
                    $item->$key = $value;
                }
                $items[] = $item;
            } // end while
        } // end if $num>0
        $list = $this->create_items_block($items, $cat_name);
        return $list;
    }

    function get_group_discount($id) {
        $query = "select group_discount_size "
                . "from mdl_group_discount "
                . "where courseid=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $discount = $row['group_discount_size'];
        }
        return $discount;
    }

    function calculate_item_cost($id, $item_cost, $item_discount) {
        $group_discount = $this->get_group_discount($id);
        $clean_item_cost = $item_cost - round(($item_cost * $item_discount) / 100, 2);
        $group_cost = $item_cost - round(($item_cost * $group_discount) / 100, 2);
        $cost = array('item_cost' => $clean_item_cost, 'group_cost' => $group_cost);
        return $cost;
    }

    function get_item_cost_blocks($item) {
        $cost_block = "";
        $cost_group_block = "";
        $item_costs = $this->calculate_item_cost($item->id, $item->cost, $item->discount_size);
        if ($item->discount_size > 0) {
            $cost_block.="Regular cost <strong>$$item->cost .</strong> Your personal cost <strong>$" . $item_costs['item_cost'] . "</strong>";
        } // end if $item->discount_size > 0
        else {
            $cost_block.="Cost <strong>$$item->cost</strong>";
        }
        $group_discount = $this->get_group_discount($item->id);
        if ($group_discount > 0) {
            $cost_group_block.="Want to register group? Get additional discount of <strong>$group_discount%</strong>";
        }  // end if $group_discount > 0        
        $blocks = array('item_cost' => $cost_block, 'item_group_cost' => $cost_group_block);
        return $blocks;
    }

    function get_school_page($cat_name) {
        $list = "";

        
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.= "<span class='span9'>A <strong>nursing school</strong> is a type of educational "
                . "institution, or part thereof, providing education and "
                . "training to become a fully qualified nurse. The nature of "
                . "nursing education and nursing qualifications varies "
                . "considerably across the world. Please select on the map your closest location.</span>";
        $list.="</div>";        

        $list."<div style='text-align:center;'>";
        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.= "<span class='span9'><hr></span>";
        $list.="</div>";
        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.= "<span class='span9' id='map' style='position: relative;height:675px;'></span>";
        $list.="</div></div>";        
        

        return $list;
    }

    function get_category_preface($cat_name) {
        $list = "";
        switch ($cat_name) {
            case 'Workshops':
                $list.="A training workshop is a type of interactive "
                        . "training where participants carry out a number "
                        . "of training activities rather than passively "
                        . "listen to a lecture or presentation. Broadly, "
                        . "two types of workshops exist: A general workshop "
                        . "is put on for a mixed audience, and a closed workshop "
                        . "is tailored towards meeting the training needs "
                        . "of a specific group";
                break;
            case 'Courses':
                $list.="Continuing education (similar to further education in "
                        . "the United Kingdom and Ireland) is an all-encompassing "
                        . "term within a broad list of post-secondary learning "
                        . "activities and programs. The term is used mainly in"
                        . " the United States and parts of Canada. Recognized forms "
                        . "of post-secondary learning activities within the domain "
                        . "include: degree credit courses by non-traditional students, "
                        . "non-degree career training, workforce training, formal "
                        . "personal enrichment courses (both on-campus and online) "
                        . "self-directed learning (such as through Internet interest"
                        . " groups, clubs or personal research activities) and "
                        . "experiential learning as applied to problem solving.";
                break;
            case 'Exams':
                $list.="Online assessment is the process used to measure certain"
                        . " aspects of information for a set purpose where the "
                        . "assessment is delivered via a computer connected to a "
                        . "network. Most often the assessment is some type of "
                        . "educational test. Different types of online assessments "
                        . "contain elements of one or more of the following "
                        . "components, depending on the assessment's purpose: "
                        . "formative, diagnostic, or summative. Instant and detailed"
                        . " feedback, as well as flexibility of location and time, "
                        . "are just two of the many benefits associated with online "
                        . "assessments. There are many resources available that "
                        . "provide online assessments, some free of charge and "
                        . "others that charge fees or require a membership.";
                break;
            case 'School':
                $list.="A nursing school is a type of educational institution, "
                        . "or part thereof, providing education and training to "
                        . "become a fully qualified nurse. The nature of nursing "
                        . "education and nursing qualifications varies considerably "
                        . "across the world.";
                break;
        }
        return $list;
    }

    function create_items_block($items, $cat_name) {
        $list = "";
        $cat_preface = $this->get_category_preface($cat_name);
        $list.="<br/><div  class='form_div'>";

        if (count($items) > 0) {
            foreach ($items as $item) {
                $blocks = $this->get_item_cost_blocks($item);

                $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
                $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>$item->fullname</h5></div>";
                $list.="<div class='panel-body'>";

                //$list.="<div class='container-fluid' style='text-align:left;'>";
                //$list.="<span class='span2'>$cat_name</span><span class='span2'><a href='#' id=program_$item->id onClick='return false;'>Register</a></span>";
                //$list.="<span class='span2'>$cat_name</span><span class='span2'><button id='program_$item->id' class='btn btn-primary'>Register</button></span>";
                //$list.="</div>";

                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.= "<span class='span6'>$item->summary</span>";
                $list.="</div>";

                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.= "<span class='span4'>Start date <strong>" . date('Y-m-d', $item->startdate) . "</strong></span>";
                $list.="</div>";

                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.= "<span class='span6'>" . $blocks['item_cost'] . "</span>";
                $list.="</div>";

                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.= "<span class='span6'>" . $blocks['item_group_cost'] . "</span>";
                $list.="</div>";

                $list.="<br/><div class='container-fluid' style='text-align:left;'>";
                $list.= "<span class='span2'><button id='program_$item->id' class='btn btn-primary'>Register</button></span>";
                $list.="</div>";

                $list.="</div>"; // end of panel-body
                $list.="</div>"; // end of panel panel-default


                /*
                  $list.="<class='container-fluid'>";
                  $list.="<span class='span6'><h5>$item->fullname</h5>;<a href='#' id=program_$item->id onClick='return false;'>Register</a></span>";
                  $list.="</div>";
                  $list.="<div class='container-fluid'>";
                  $list.= "<span class='span6'>$item->summary</span>";
                  $list.="</div>";
                  $list.="<div class='container-fluid'>";
                  $list.= "<span class='span4'>Start date <strong>" . date('Y-m-d', $item->startdate) . "</strong></span>";
                  $list.="</div>";
                  $list.="<div class='container-fluid'>";
                  $list.= "<span class='span6'>" . $blocks['item_cost'] . "</span>";
                  $list.="</div>";
                  $list.="<div class='container-fluid'>";
                  $list.= "<span class='span6'>" . $blocks['item_group_cost'] . "</span>";
                  $list.="</div>";
                  $list.="<div class='container-fluid'>";
                  $list.= "<span class='span6'><hr></span>";
                  $list.="</div>";
                 */

                //<button type='button' id=price_$item->id class='btn btn-primary'>Save</button>
            }
        } // end if count($items)>0
        else {
            $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
            $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>$cat_name</h5></div>";
            $list.="<div class='panel-body'>";

            $list.="<class='container-fluid'>";
            $list.="<span class='span6'>No items found</span>";
            $list.="</div>"; // end of container-fluid

            $list.="</div>"; // end of panel-body
            $list.="</div>"; // end of panel panel-default
        }

        $list.= "</div>"; // end of form div
        return $list;
    }

}
