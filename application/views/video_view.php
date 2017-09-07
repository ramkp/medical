<?php
echo $video;
?>


<div class='form_div'></div>

<script type="text/javascript">

    $(document).ready(function () {
        
        /*
        var labelurl = '/lms/custom/flowplayer/url.php';
        $.post(labelurl, {id: <?php echo $video; ?>}).done(function (url) {
            var containerid = '.form_div';
            console.log('Container id: ' + containerid);
            var container = $(containerid);
            container.empty();
            container.bind("contextmenu", function (e) {
                e.preventDefault();
            });
            flowplayer(container, {
                share: false,
                key: "$599424236128582",
                clip: {
                    sources: [{type: "video/mp4", src: url, engine: "html5"}]
                } // end of clip
            }); // end of player ...
        }); // end of post 
        */

        /*
         $(".flowplayer").flowplayer({
         share: false,
         key: "$599424236128582",
         });
         */
    }); // end of document ready

</script>