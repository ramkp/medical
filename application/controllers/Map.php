<?php

class Map extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $list = '';
        $list.="<br/><div  class='form_div'>";
        $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
        $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Site Map/h5></div>";
        $list.="<div class='panel-body'>";


        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span12'>
        <ul>
            <li><a href='http://medical2.com/'>medical2</a>
                <ul>
                    <ul>
                        <li><a href='http://medical2.com/index.php/faq'>FAQ</a>
                        <li><a href='http://medical2.com/index.php/gallery'>Gallery</a>
                            <ul>
                                <ul>
                                    <li><a href='http://medical2.com/index.php/programs/detailes/41'>Certified Nurse Assistant  Program of Study</a>
                                    <li><a href='http://medical2.com/index.php/programs/detailes/44'>Phlebotomy Certification Workshop</a>
                                    <li><a href='http://medical2.com/index.php/programs/detailes/45'>Phlebotomy With EKG Certification Workshop</a>
                                    <li><a href='http://medical2.com/index.php/programs/detailes/46'>Picc Line Certification Workshop</a>
                                    <li><a href='http://medical2.com/index.php/programs/detailes/47'>IV Therapy Certifican Online Exam</a>
                                    <li><a href='http://medical2.com/index.php/programs/detailes/48'>IV Therapy Exam + Study Guide</a>
                                    <li><a href='http://medical2.com/index.php/programs/detailes/49'>OB Tech Certification Exam</a>
                                    <li><a href='http://medical2.com/index.php/programs/detailes/50'>Online Phlebotomy Technician Certification Exam</a>
                                    <li><a href='http://medical2.com/index.php/programs/detailes/51'>Phlebotomy Technician Exam + Phlebotomy Book</a>
                                    <li><a href='http://medical2.com/index.php/programs/detailes/52'>IV Therapy Home Study Program</a>
                                    <li><a href='http://medical2.com/index.php/programs/detailes/53'>OB Technician Complete Program</a>
                                    <li><a href='http://medical2.com/index.php/programs/detailes/54'>IV Therapy Certification Workshop</a>
                                    <li><a href='http://medical2.com/index.php/programs/detailes/55'>Medical Assistant</a>
                                    <li><a href='http://medical2.com/index.php/programs/detailes/56'>Medical Administrator Assistant</a>
                                    <li><a href='http://medical2.com/index.php/programs/program/2'>Hands-On Certification Workshops</a>
                                    <li><a href='http://medical2.com/index.php/programs/program/3/'>CEUs & Online Courses</a>
                                    <li><a href='http://medical2.com/index.php/programs/program/4/'>Online Certification Exams</a>
                                    <li><a href='http://medical2.com/index.php/programs/program/5'>Healthcare Career Courses </a>
                                    <li><a href='http://medical2.com/index.php/programs/schedule/41'>Schedule Certified Nurse Assistant  Program of Study</a>
                                    <li><a href='http://medical2.com/index.php/programs/schedule/44'>Schedule Phlebotomy Certification Workshop</a>
                                    <li><a href='http://medical2.com/index.php/programs/schedule/45'>Phlebotomy With EKG Certification Workshop </a>
                                    <li><a href='http://medical2.com/index.php/programs/schedule/47'>Schedule IV Therapy Certifican Online Exam</a>
                                    <li><a href='http://medical2.com/index.php/programs/schedule/49'>Schedule OB Tech Certification Exam</a>
                                    <li><a href='http://medical2.com/index.php/programs/schedule/50'>Schedule Online Phlebotomy Technician Certification Exam</a>
                                    <li><a href='http://medical2.com/index.php/programs/schedule/54'>Schedule IV Therapy Certification Workshop</a>
                                    <li><a href='http://medical2.com/index.php/testimonial'>Testimonials</a>
                                </ul>
                            </ul>
                    </ul>
                </ul>
        </ul></span>";
        $list.="</div>"; // end of container-fluid

        $list.="</div>";
        $list.="</div>";
        $list.="</div>";
        

        $page = $list;
        $data = array('page' => $page);
        $this->load->view('header_view');
        $this->load->view('about_view', $data);
        $this->load->view('footer_view');
    }

}
