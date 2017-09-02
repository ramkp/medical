<?php

class Jobs_model extends CI_Model {

    public $host;

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->host = $_SERVER['SERVER_NAME'];
    }

    public function get_jobs_instructor_page() {
        $list = "";
        $query = "select * from mdl_jobs where id=1";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $content = $row->content;
        }
        $list.="<div class='container-fluid'>";
        $list.="<div class='form_div'><br><span style='text-align:justify;'>$content</span></div>";
        $list.="</div>";
        return $list;
    }

    public function get_jobs_student_page() {
        $list = "";
        $query = "select * from mdl_jobs where id=2";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $content = $row->content;
        }
        $list.="<div class='container-fluid'>";
        $list.="<div class='form_div'><br>$content</div>";
        $list.="</div>";
        return $list;
    }

    function get_jobs_widget() {
        $list = "";

        $list.="<br><br><div  style='width:80%;text-align:center;padding-left:17%;vertical-align: middle;'>";

        $list.="<div class='row-fluid' style='font-weight:bold;color:#966b00;text-align:left;'>";
        $list.="<span class='span4'>what</span>";
        $list.="<span class='span4'>where</span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span4'><input type='text' id='what' style='width:100%;'></span>";
        $list.="<span class='span4'><input type='text' id='where' style='width:100%;'></span>";
        $list.="<span class='span2'><button class='btn btn-primary' id='find_jobs'>Find Jobs</button></span>";
        $list.="</div>";

        $list.="<div class='row-fluid' style='color:#aaa;text-align:left;'>";
        $list.="<span class='span4'>job title, keywords or company</span>";
        $list.="<span class='span4'>city, state, or zip</span>";
        $list.="</div>";

        $list.="<br><div class='row-fluid' style='text-align:center;'>";
        $list.="<span class='span8' id='ajax_loader' style='display:none;'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
        $list.="</div>";

        $list.="</div><br>";

        $list.="<div class='row-fluid' style='text-align:center;'>";
        $list.="<span class='span12' id='search_resutls' style='text-align:left;'></span>";
        $list.="</div>";

        return $list;
    }

    public function get_jobs_student_page2() {
        $list = "";
        $widget = $this->get_jobs_widget();
        $list.="<div class='row-fluid' style='text-align:center;width:96%;margin:auto;'>";
        $list.="<span class='span12'>$widget</span>";
        $list.="</div>";
        return $list;
    }

    public function proces_job_search_results($jobs) {
        $list.="";
        $results = $jobs->results; // array of objects

        $list.="<table id='job_search_results' class='display' cellspacing='0' width='100%'>";

        $list.="<thead>";
        $list.="<tr>";
        $list.="<th>Job title</th>";
        $list.="<th>Company</th>";
        $list.="<th>Location</th>";
        $list.="<th>Job description</th>";
        $list.="<th>Posted</th>";
        $list.="</tr>";
        $list.="</thead>";

        $list.="<tbody>";
        if (count($results) > 0) {
            foreach ($results as $item) {
                $title = $item->jobtitle;
                $company = $item->company;
                $location = $item->formattedLocationFull;
                $preface = $item->snippet;
                $posted = $item->date;
                $ago = $item->formattedRelativeTime;
                $url = $item->url;
                $link = "<a href='$url' target='_blank'>Link</a>";
                $list.="<tr>";
                $list.="<td>$title</td>";
                $list.="<td>$company</td>";
                $list.="<td>$location</td>";
                $list.="<td>$preface<br>$link</td>";
                $list.="<td>$posted<br>$ago</td>";
                $list.="</tr>";
            } // end foreach
        } // end if count($results)>0
        $list.="</tbody>";

        $list.="</table>";

        return $list;
    }

}
