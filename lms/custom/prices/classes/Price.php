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

    function get_state_name_by_id($id) {
        $query = "select * from mdl_states where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $state = $row['state'];
        } // end while
        return $state;
    }

    function get_course_states($courseid) {
        $list = "";
        $states = array();
        $query = "select * from mdl_course_to_state "
                . "where courseid=$courseid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $states[] = $row['stateid'];
            } // end while            
        } // end if $num>0
        $query = "select * from mdl_states";
        $result = $this->db->query($query);
        $list.="<select multiple id='states_$courseid' class='dropdown'>";
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
            $state = $row['state'];
            if (in_array($id, $states)) {
                $list.= "<option value='$id' selected>$state</option>";
            } // end if in_array($id, $states)
            else {
                $list.= "<option value='$id' >$state</option>";
            } // end else
        } // end while
        $list.="</select>";
        return $list;
    }

    function get_items_from_category($id) {
        $price_items = array();
        $query = "select id, "
                . "fullname, "
                . "installment, "
                . "num_payments, "
                . "cost, "
                . "discount_status, "
                . "discount_size, "
                . "taxes, expired "
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
        $list = $this->create_item_block2($price_items, $category_name);
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

    function get_installment_checkbox($courseid, $installment) {
        $list = "";
        if ($installment == 1) {
            $list.="<input type='checkbox' name='installment_$courseid', id='installment_$courseid' value='$installment' checked>";
        } // end if $installment==1
        else {
            $list.="<input type='checkbox' name='installment_$courseid' id='installment_$courseid' value='$installment' >";
        } // end else
        return $list;
    }

    function get_installment_num_payments($courseid, $installment, $num_payments) {
        $list = "";
        if ($installment == 0) {
            $list.="<select id='num_payments_$courseid' class='dropdown' disabled>";
        } // end if $installment==0
        else {
            $list.="<select id='num_payments_$courseid' class='dropdown'>";
        } // end else 
        for ($i = 0; $i <= 10; $i++) {
            if ($i == $num_payments) {
                $list .= "<option value='$i' selected>$i</option>";
            } // end if $i==$num_payments
            else {
                $list .= "<option value='$i' >$i</option>";
            } // end else
        } // end for
        $list.="</select>";
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

    function course_tax_status($id, $taxes) {
        $list = "";
        if ($taxes == 0) {
            $list = "<input type='checkbox' name='taxes_$id' id='taxes_$id' value='$id'>";
        } // end if $taxes_status==0
        else {
            $list = "<input type='checkbox' name='taxes_$id' id='taxes_$id' value='$id' checked>";
        } // end else
        return $list;
    }
    
    function get_expiration_status ($id, $expired) {
    	$list = "";
        if ($expired == 0) {
            $list = "<input type='checkbox' name='expire_$id' id='expire_$id' value='$expired'>";
        } // end if $taxes_status==0
        else {
            $list = "<input type='checkbox' name='expire_$id' id='expire_$id' value='$expired' checked>";
        } // end else
        return $list;
    }

    function create_item_block2($price_items, $category_name) {

        $list = "";
        $list.= "<div class='container-fluid'><span class='span9' style='font-weight:strong;'>Prices - $category_name</span></div>";
        $list.= "<div class='container-fluid style='color:red' id='price_err'></div>";
        if (count($price_items) > 0) {
            foreach ($price_items as $item) {
                $course_discount = $this->get_discount_dropdown('course', $item->id);
                $group_discount = $this->get_discount_dropdown('group', $item->id);
                $states = $this->get_course_states($item->id);
                $installment_checkbox = $this->get_installment_checkbox($item->id, $item->installment);
                $installment_payments = $this->get_installment_num_payments($item->id, $item->installment, $item->num_payments);
                $taxes = $this->course_tax_status($item->id, $item->taxes);
                $expire=$this->get_expiration_status($item->id,$item->expired);
                $list.= "<div class='container-fluid'>";
                $list.="<span class='span6' style='color:red;' id='price_err_$item->id'></span>";
                $list.= "</div>";

                $list.= "<div class='container-fluid'>";
                $list.="<span class='span3'>Item name</span><span class='span6'>$item->fullname</span>";
                $list.= "</div>";

                $list.= "<div class='container-fluid'>";
                $list.="<span class='span3'>Item cost</span><span class='span3'><input type=text class='form-control' id='cost_$item->id' value='$item->cost' style='width:45px;'></span>";
                $list.= "</div>";

                $list.= "<div class='container-fluid'>";
                $list.="<span class='span3'>Item discount %</span><span class='span1'>$course_discount</span>";
                $list.= "</div>";

                $list.= "<div class='container-fluid'>";
                $list.="<span class='span3'>Item group discount %</span><span class='span1'>$group_discount</span>";
                $list.= "</div>";

                /*
                 * 
                $list.= "<div class='container-fluid'>";
                $list.="<span class='span3'>Installment payment</span><span class='span1'>$installment_checkbox</span>";
                $list.= "</div>";

                $list.= "<div class='container-fluid'>";
                $list.="<span class='span3'>Num of payments</span><span class='span1'>$installment_payments</span>";
                $list.= "</div>";
                 * 
                 */

                $list.= "<div class='container-fluid'>";
                $list.="<span class='span3'>Item states</span><span class='span1'>$states</span>";
                $list.= "</div>";

                $list.= "<div class='container-fluid'>";
                $list.="<span class='span3'>Apply state taxes</span><span class='span1'>$taxes</span>";
                $list.= "</div>";
                
                $list.= "<div class='container-fluid'>";
                $list.="<span class='span3'>Program expires</span><span class='span1'>$expire</span>";
                $list.= "</div>";

                $list.= "<div class='container-fluid'>";
                $list.="<span class='span3'><button type='button' id=price_$item->id class='btn btn-primary'>Save</button></span>";
                $list.= "</div>";

                $list.= "<div class='container-fluid'>";
                $list.="<span class='span9'><hr/></span>";
                $list.= "</div>";
            } // end foreach
        } // end if count($price_items) > 0
        else {
            $list.= "<p align='center'>No items found</p>";
        }


        return $list;
    }

    function is_state_exists($courseid, $stateid) {
        $query = "select courseid, stateid "
                . "from mdl_course_to_state "
                . "where courseid=$courseid "
                . "and stateid=$stateid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function update_course_state($course_id, $states) {
        //print_r($states);
        // 1. First delete unchecked states
        $query = "select * from mdl_course_to_state where courseid=$course_id";
        //echo "<br/>Query: " . $query . "<br/>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['id'];
                //echo "Id: ".$id."<br/>";
                $stateid = $row['stateid'];
                if (in_array($stateid, $states) == false) {
                    $query2 = "delete from mdl_course_to_state where id=$id";
                    //echo "<br/>Query: " . $query2 . "<br/>";
                    $this->db->query($query2);
                } // end if in_array($stateid, $states)==false
            } // end while
        } // end if $num>0
        // 2. Insert checked states
        //echo "Count of states: ".count($states)."<br/>";
        if (count($states) > 0) {
            for ($i = 0; $i < count($states); $i++) {
                $is_exists = $this->is_state_exists($course_id, $states[$i]);
                if ($is_exists == 0) {
                    $query = "insert into mdl_course_to_state (courseid, stateid) "
                            . "values ($course_id, $states[$i])";
                    //echo "<br/>Query: " . $query . "<br/>";
                    $this->db->query($query);
                } // end if $is_exists==0
            } // end foreach
        } // end if count($states)>0
    }

    function update_item_price($course_id, $course_cost, $course_discount, $course_group_discount, $installment, $num_payments, $states, $taxes,$expire) {
        // Update mdl_course table        
        $num_payments = ($num_payments=='') ? 0 : $num_payments;
        $query = "update mdl_course "
                . "set cost=$course_cost ,"
                . "discount_size=$course_discount , "
                . "installment=$installment, "
                . "num_payments=$num_payments, expired=$expire, "
                . "taxes=$taxes "
                . "where id=$course_id";
                //echo $query;
        $this->db->query($query);

        // Update mdl_group_discount table
        $query = "update mdl_group_discount "
                . "set group_discount_size=$course_group_discount "
                . "where courseid=$course_id";
        $this->db->query($query);

        // Update course states        
        $this->update_course_state($course_id, $states);
        return "Item successfully updated.";
    }

}
