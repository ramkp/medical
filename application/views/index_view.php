<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php');

class slides {

    public $db;

    function __construct() {
        $db = new pdo_db();
        $this->db = $db;
    }

    function get_slides() {
        $list = "";
        $query = "select * from mdl_slides";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            if ($row['active'] == 0) {
                $list.="<div class='item' id='" . $row['id'] . "'>";
            } // end if $row['active']==0
            else {
                $list.="<div class='item active' id='" . $row['id'] . "'>";
            } // end else
            $path = $_SERVER['DOCUMENT_ROOT'] . '/assets/slides/';
            $file = trim(str_replace($path, '', $row['path']));
            $img_path = 'http://' . $_SERVER['SERVER_NAME'] . "/assets/slides/$file";
            $list.="<div class='fill' style='background-image:url($img_path);height:375px;'></div>                        ";
            $list.="</div>";
        } // end while
        return $list;
    }

    function get_active_banner() {
        $query = "select * from mdl_slides where active=1";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $title = $row['title'];
            $slogans = $row['slogan1'] . "<br>" . $row['slogan2'] . "<br>" . $row['slogan3'];
        }
        $banner = array('title' => $title, 'slogans' => $slogans);
        return $banner;
    }

}

$sl = new slides();
$slides = $sl->get_slides();
$banner = $sl->get_active_banner();
?>

<div id="page" class="container-fluid">
    <header id="myCarousel" class="carousel slide">

        <!-- Instruction box -->            
        <div id='instructions'>
            <div id="centered" style="margin: 0 auto; width:275px;">
                <div> 
                    <div>
                        <h3 class="panel-title" 
                            style="background: #e2a500 none repeat scroll 0 0;
                            color: #fff;display: block;font-size: 2.2em;
                            line-height: 1.5em;margin-right: 5%;
                            padding-left: 10px;
                            text-shadow: 0 0 1px rgba(0, 0, 0, 0.5);">
                            <span id='banner_title'><?php echo $banner['title']; ?></span></h3>
                    </div>
                    <div>
                        <span style="background: rgba(0, 0, 0, 0.5) none repeat scroll 0 0;
                              border-left: 4px solid #e2a500;
                              color: #fff;
                              display: block;
                              font-size: 14px;
                              line-height: 24px;
                              margin-left: 5%;
                              padding: 15px 20px;
                              text-shadow: 0 0 1px rgba(0, 0, 0, 0.5)">
                              <span id='banner_slogans'><?php echo $banner['slogans']; ?></span>
                    </div>
                </div>
            </div>
        </div>            
        <!--  -->

        <!-- Wrapper for Slides -->
        <div class="carousel-inner">

            <?php
            echo $slides;
            ?>

        </div>
        <!-- Controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev" id='prev_slide'>
            <span class="icon-prev"></span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next" id='next_slide'>
            <span class="icon-next"></span>
        </a>
    </header>
</div>