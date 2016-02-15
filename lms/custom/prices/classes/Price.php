<?php

/**
 * Description of Price
 *
 * @author sirromas
 */
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

class Price extends Util {

    function create_item_group_discount($id) {
        $query = "insert into mdl_group_discount "
                . "(courseid) values($id)";
        $result = $this->db->query($query);
    }

    function get_item_group_discount($id) {
        $query = "select * from mdl_group_discount where courseid=$id";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $item = new stdClass();
                foreach ($row as $key => $value) {
                    if ($key != 'id') {
                        $item->$key = $value;
                    } // end if $key != 'id'
                } // end foreach
            } // end while
        } // end if $num>0
        else {
            $this->create_item_group_discount($id);
            $item = new stdClass();
            $item->courseid = $id;
            $item->groupid = 0;
            $item->tot_participants = 0;
            $item->group_discount_status = 0;
            $item->group_discount_size = 0;
        }
        return $item;
    }

    function get_category_name($id) {
        $query = "select id, name from mdl_course_categories where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['name'];
        }
        return $name;
    }

    function get_items_from_category($id) {
        $price_items = array();
        $query = "select id, fullname, cost, discount_status, discount_size "
                . "from mdl_course "
                . "where category=$id";
        $num = $this->db->numrows($query);
        if ($num) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $course_item = new stdClass();
                foreach ($row as $key => $value) {
                    $course_item->$key = $value;
                }
                $group_item = $this->get_item_group_discount($row['id']);
                $price_item = (object) array_merge((array) $course_item, (array) $group_item);
                $price_items[] = $price_item;
            } // end while
        } // end if $num                
        $category_name = $this->get_category_name($id);
        $list = $this->create_price_item_block($price_items, $category_name);
        $course_price_item_block = json_encode(array('item_title' => $category_name, 'item_data' => $list));
        return $course_price_item_block;
    }

    function get_existing_course_discounts($scope, $id) {
        if ($scope == 'group') {
            $query = "select id, group_discount_size "
                    . "from mdl_group_discount "
                    . "where courseid=$id";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $discount = $row['group_discount_size'];
            }
        } // end if $scope == 'group'        
        else {
            $query = "select id, discount_size "
                    . "from mdl_course "
                    . "where id=$id";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $discount = $row['discount_size'];
            }
        } // end else 
        return $discount;
    }

    function get_discount_dropdown($scope, $id) {
        $list = "";
        $drop_id = ($scope == 'group') ? "group_" . $id : "item_" . $id;
        $list = $list . "<select id=$drop_id class='dropdown'>";
        for ($i = 0; $i <= 75; $i++) {
            $selected_item = $this->get_existing_course_discounts($scope, $id);
            if ($i == $selected_item) {
                $list = $list . "<option value='$i' selected>$i</option>";
            } // end if $i==$selected_item
            else {
                $list = $list . "<option value='$i'>$i</option>";
            }
        } // end for
        $list = $list . "</select>";
        return $list;
    }    

    function create_price_item_block($price_items, $category_name) {
        $list = "";
        $list.= "<div class='container-fluid'><span class='span9' style='font-weight:strong;'>Prices - $category_name</span></div>";
        $list.= "<div class='container-fluid style='color:red' id='price_err'></div>";
        if (count($price_items) > 0) {
            $useragent = $_SERVER['HTTP_USER_AGENT'];
            if (!preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
                $list.= "<div class='container-fluid'><span class='span3'>Item name</span><span class='span2'>Item cost</span><span class='span2'>Item discount</span><span class='span2'>Group discount</span><span class='span2'>Actions</span></div>";
            }
            foreach ($price_items as $item) {
                $course_discount = $this->get_discount_dropdown('course', $item->id);
                $group_discount = $this->get_discount_dropdown('group', $item->id);
                $list.="<div class='container-fluid'>";
                $list.="<span class='span3'>$item->fullname</span><span class='span2'><input type=text class='form-control' id='cost_$item->id' value='$item->cost' style='width:45px;'></span><span class='span2'>$course_discount</span><span class='span2'>$group_discount</span><span class='span2'><button type='button' id=price_$item->id class='btn btn-primary'>Save</button></span>";
                $list.="</div>";
            } // end foreach
        } // end if count($price_items) > 0
        else {
            $list.= "<p align='center'>No items found</p>";
        }
        return $list;
    }

    function update_item_price($course_id, $course_cost, $course_discount, $course_group_discount) {
        // Update mdl_course table
        $query = "update mdl_course "
                . "set cost=$course_cost ,"
                . "discount_size=$course_discount "
                . "where id=$course_id";
        $result = $this->db->query($query);

        // Update mdl_group_discount table
        $query = "update mdl_group_discount "
                . "set group_discount_size=$course_group_discount "
                . "where courseid=$course_id";
        //echo "Query: ".$query;
        $result = $this->db->query($query);
        return "Item successfully updated.";
    }

}
