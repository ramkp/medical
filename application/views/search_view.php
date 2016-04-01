<?php

$list = "";
/*
 * 
  $list.="<div  class='form_div'>"
  . "<table align='center'>"
  . "<tr>"
  . "<th align='left'><h4 class='panel-title'>Search</h4></th>"
  . "</tr>"

  . "<tr>"
  . "<td><input type='text' class='input_search_box' id='input_search_box' placeholder='Search' style='width:208px;'>&nbsp;&nbsp;<span id='search_err' ></span></td>"
  . "</tr>"

  . "<tr>"
  . "<td align='left'><button type='submit' class='btn btn-primary' id='search_button'>Submit</button></td>"
  . "<tr>"

  . "<tr>"
  . "<td><span id='search_result'></span></td>"
  . "</tr>"

  . "</table>"
  . "</div>";
 * 
 */




$list.="<div class='container-fluid' >";
$list.="<div class='text-center'>";
$list.= "<br/><br/><input type='text' class='input_search_box' id='input_search_box' placeholder='Search' style='width:208px;'><button type='submit' class='btn btn-primary' id='search_button'>Submit</button><br/><br/>";
$list.="</div>";
$list.="</div>";

$list.="<div class='container-fluid'>";
$list.="<div class='text-center'>";
$list.= "<span id='search_result' style='text-align:center;'></span>";
$list.="</div>";
$list.="</div>";

echo $list;
