<?php

/**
 * Description of searchForm
 *
 * @author sirromas
 */
class searchForm {

    function get_search_form() {
        $list = "";
        $list.="<div  class='form_div'>"
                . "<table align='center'>"
                . "<tr>"
                . "<th align='left'><h4 class='panel-title'>Search</h4></th>"
                . "</tr>"
                . "<tr>"
                . "<td><input type='text' class='input_search_box' id='input_search_box' placeholder='Search' style='width:208px;'></td>"
                . "</tr>"
                . "<tr>"
                . "<td align='left'><button type='submit' class='btn btn-primary'>Submit</button></td>"
                . "</tr>"
                . "</div>";
        return $list;
    }

}
