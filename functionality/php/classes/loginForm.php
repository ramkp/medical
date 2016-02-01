<?php


/**
 * Description of loginForm
 *
 * @author sirromas
 */
class loginForm {

    function get_login_form () {
        $list="";
        $list.="<div id='login_div' style='z-index: 675;margin: auto;'>     
                        <div class='panel-heading'>
                            <h4 class='panel-title'>Login</h4>
                        </div>
                        <div class='panel-body' style=''>
                            <form  id='login_form' method='post' name='login_form' action='http://cnausa.com/lms/login/index.php'>
                                <div class='form-group'>
                                    <input type='text' class='form-control' id='login_box' name='username' placeholder='Login' ><br/>
                                    <input type='password' class='form-control' id='password_box' name='password' placeholder='Password' ><br/>
                                </div>
                                <div id='login_err' style='color:red;'></div>
                                <br/><button class='btn btn-primary' id='login_button'>Submit</button>
                            </form>
                        </div>
                    </div>";
        return $list;
    }
    
}
