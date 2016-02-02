<?php

/**
 * Description of Map
 *
 * @author sirromas
 */
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

class Map extends Util {

    public $category_id;

    function __construct($id) {
        parent::__construct();
        $this->category_id = $id;
    }

    function is_location_exists($courseid) {
        $query = "select id, courseid "
                . "from mdl_nursing_school_map "
                . "where courseid=$courseid";
        return $num = $this->db->numrows($query);
    }

    function create_new_location($course_object) {
        $query = "insert into mdl_nursing_school_map "
                . "(courseid,school_title) "
                . "values($course_object->id, '" . $course_object->fullname . "')";
        $result = $this->db->query($query);
    }

    function get_courses_list() {
        $course_objects = array();
        $query = "select id, fullname "
                . "from mdl_course "
                . "where category=$this->category_id and visible=1";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $course_obj = new stdClass();
                foreach ($row as $key => $value) {
                    $course_obj->$key = $value;
                }
                $course_objects[] = $course_obj;
            }
        } // end if $num>0
        return $course_objects;
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

    function get_school_detailes($courseid) {
        $list = "";
        $query = "select id,fullname,summary,startdate,cost,discount_size "
                . "from mdl_course where id=$courseid and cost>0";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $item = new stdClass();
            foreach ($row as $key => $value) {
                $item->$key = $value;
            } // end foreach
        } // end while
        $blocks = $this->get_item_cost_blocks($item);
        $list.="<div class='container-fluid'>";
        $list.="<span class='span4'><h5>$item->fullname</h5>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.= "<span class='span4'><a href='#' id=program_$item->id onClick='return false;'>Register</a></span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.= "<span class='span3'>Start date <strong>" . date('Y-m-d', $item->startdate) . "</strong></span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.= "<span class='span3'>" . $blocks['item_cost'] . "</span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.= "<span class='span4'>" . $blocks['item_group_cost'] . "</span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.= "<span class='span4'><hr></span>";
        $list.="</div>";
        return $list;
    }

    function get_locations_list() {
        $location_objects = array();
        $query = "select * from mdl_nursing_school_map";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $detailes = $this->get_school_detailes($row['courseid']);
                $location_object = new stdClass();
                foreach ($row as $key => $value) {
                    $location_object->$key = $value;
                }
                $location_object->info = $detailes;
                $location_objects[] = $location_object;
            } // end while
        } // end if $num > 0
        return $location_objects;
    }

    function get_index_page() {
        $map_objects = array();
        $course_list = $this->get_courses_list();
        // Synchronize courses & map objects
        foreach ($course_list as $course) {
            if ($this->is_location_exists($course->id) == 0) {
                $this->create_new_location($course);
            } // end if $this->is_location_exists($course->id) == 0
        } // end foreach
        $query = "select map.*, c.id, c.visible "
                . "from mdl_nursing_school_map map, mdl_course c "
                . "where map.courseid=c.id and c.visible=1";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $map_object = new stdClass();
                foreach ($row as $key => $value) {
                    $map_object->$key = $value;
                }
                $map_objects[] = $map_object;
            } // end while
        } // end if $num > 0
        $list = $this->render_index_page($map_objects);
        return $list;
    }

    function render_index_page($map_objects) {
        $list = "";
        $list.= "<div class='container-fluid'><span class='span9' style='font-weight:strong;'>Nursing School - Map Locations</span></div>";
        $list.= "<div class='container-fluid style='color:red' id='map_err'></div>";
        if (count($map_objects) == 0) {
            $list = $list . "<p align='center'>No items found</p>";
        } // end if ount($map_objects)==0
        else {
            $useragent = $_SERVER['HTTP_USER_AGENT'];
            if (!preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
                $list.= "<div class='container-fluid'><span class='span3'>Item name</span><span class='span2'>Latitude</span><span class='span2'>Longitude</span><span class='span3'>Item marker</span><span class='span2'>Actions</span></div>";
            }
            foreach ($map_objects as $map_object) {
                $list.="<div class='container-fluid'>";
                $list.="<span class='span3'>$map_object->school_title</span><span class='span2'><input type='text' value='$map_object->lat' id='lat_$map_object->courseid' style='width:45px;'></span><span class='span2'><input type='text' id='lng_$map_object->courseid' value='$map_object->lng' style='width:45px;'></span><span class='span3'><input type='text' value='$map_object->marker_text' id='marker_$map_object->courseid' style='width:75%' ></span><span class='span2'><button type='button' id=map_$map_object->courseid class='btn btn-primary'>Save</button></span>";
                $list.="</div>";
            } // end foreach
            $list.="<br/><div class='container-fluid'><span class='span12' id='map' style='position: relative;height:375px;'></span></div><br/>";
        } // end else
        return $list;
    }

    function update_map($lat, $lng, $courseid, $marker_text) {
        $query = "update mdl_nursing_school_map "
                . "set lat='$lat', lng='$lng', marker_text='$marker_text' "
                . "where courseid=$courseid";
        $result = $this->db->query($query);
    }

    function refresh_google_map() {
        $markers = $this->get_locations_list();
        return json_encode($markers);
    }

}
