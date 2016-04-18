<?php

$url = "http://" . $_SERVER['SERVER_NAME'] . "/lms/login/index.php";
$list = "";
$list.="<form method='post' action=$url id='login_form'>";
$list.="<div  class='form_div'>"
        . "<table align='center'>"
        . "<tr>"
        . "<th align='left'><h4 class='panel-title'>Login</h4></th>"
        . "</tr>"
        . "<tr>"
        . "<td align='left'><input type='text' class='form-control' id='login_box' name='username' placeholder='Login' style='width:208px;'></td>"
        . "</tr>"
        . "<td align='left'><input type='password' class='form-control' id='password_box' name='password' placeholder='Password' style='width:208px;'></td>"
        . "<tr>"
        . "</tr>"
        . "<td  align='left'><div id='login_err' style='color:red;'></div></td>"
        . "<tr>"
        . "<td align='left'><button class='btn btn-primary' id='login_button'>Submit</button></td>"
        . "</tr>"
        . "<tr><td align='center' colspan='2'>Forgot password? Click<a href='http://".$_SERVER['SERVER_NAME']."/lms/login/forgot_password.php' target='_blank'> here.</a></td></tr>"
        . "<tr><td align='center' colspan='2'>You still don't have an account?? Click<a href='http://".$_SERVER['SERVER_NAME']."/lms/index.php/register' target='_blank'> here.</a></td></tr>"
        . "</table>"
        . "</div></form>";
echo $list;
?>