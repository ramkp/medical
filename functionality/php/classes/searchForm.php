<?php


/**
 * Description of searchForm
 *
 * @author sirromas
 */
class searchForm {

    function get_search_form() {
        $list="";
        $list.="<div  class='search_container' style='margin: auto;'>
                    <div class='panel-heading'>
                        <h4 class='panel-title'>Search</h4>
                    </div>
                    <div class='panel-body'>
                        <form class='navbar-form navbar-left' >
                            <div class='form-group'>
                            <input type='text' class='input_search_box' id='input_search_box' placeholder='Search' >                            
                            </div>
                            <br/>    <button type='submit' class='btn btn-primary'>Submit</button>
                        </form>
                    </div>
                </div>";
        return $list;
    }
    
}
