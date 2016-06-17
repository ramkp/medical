<footer id="page-footer" class="container-fluid">

    <div class="footerlinks">
        <div class="row-fluid">
            <p class="helplink"></p><br/>
            <div class="footnote"  style="display:inline-block;vertical-align: middle;">
                <p>(c) 2016 | <a href="#" target="_blank">
                        <img src="https://<?php echo $_SERVER['SERVER_NAME']; ?>/assets/icons/heart.gif" alt="heart" width="15" height="15">
                    </a> Powered  By Z&S &nbsp;&nbsp;                                                                
                    <a href="https://<?php echo $_SERVER['SERVER_NAME']; ?>/index.php/about">About Us</a>&nbsp;
                    <a href="https://<?php echo $_SERVER['SERVER_NAME']; ?>/index.php/map">Site Map</a>&nbsp;
                    <a href="https://<?php echo $_SERVER['SERVER_NAME']; ?>/index.php/contact">Contact US</a>&nbsp;
                    
                    <!-- Social sharing buttons -->
                    <span class='st_facebook_large' displayText='Facebook'></span>
                    <span class='st_twitter_large' displayText='Tweet'></span>
                    <span class='st_linkedin_large' displayText='LinkedIn'></span>
                    <span class='st_googleplus_large' displayText='Google +'></span>
                    <span class='st_instagram_large' displayText='Instagram Badge'></span>
                    <span class='st__large' displayText=''></span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://www.bbb.org/mississippi/business-reviews/schools-business-and-vocational/medical-2-training-institute-in-tupelo-ms-235827408/#bbbonlineclick" target="_blank" rel="nofollow"><img src="http://seal-ms.bbb.org/seals/blue-seal-200-65-bbb-235827408.png" style="border: 0;" alt="Medical 2 Training Institute BBB Business Review" /></a>
                </p>
                
            </div>

            <script type="text/javascript">
                var LHCChatOptions = {};
                LHCChatOptions.opt = {widget_height: 340, widget_width: 300, popup_height: 520, popup_width: 500};
                (function () {
                    var po = document.createElement('script');
                    po.type = 'text/javascript';
                    po.async = true;
                    var referrer = (document.referrer) ? encodeURIComponent(document.referrer.substr(document.referrer.indexOf('://') + 1)) : '';
                    var location = (document.location) ? encodeURIComponent(window.location.href.substring(window.location.protocol.length)) : '';
                    po.src = '//medical2.com/chat/index.php/chat/getstatus/(click)/internal/(position)/bottom_right/(ma)/br/(top)/350/(units)/pixels/(leaveamessage)/true?r=' + referrer + '&l=' + location;
                    var s = document.getElementsByTagName('script')[0];
                    s.parentNode.insertBefore(po, s);
                })();
            </script>
        </div>
    </div>
</footer>
</div>

<script type="text/javascript">
    //<![CDATA[
    var require = {
        baseUrl: 'https://<?php echo $_SERVER['SERVER_NAME']; ?>/lms/lib/requirejs.php/1451892663/',
        // We only support AMD modules with an explicit define() statement.
        enforceDefine: true,
        skipDataMain: true,
        paths: {
            jquery: 'https://<?php echo $_SERVER['SERVER_NAME']; ?>/lms/lib/javascript.php/1451892663/lib/jquery/jquery-1.11.3.min',
            jqueryui: 'https://<?php echo $_SERVER['SERVER_NAME']; ?>/lms/lib/javascript.php/1451892663/lib/jquery/ui-1.11.4/jquery-ui.min',
            jqueryprivate: 'https://<?php echo $_SERVER['SERVER_NAME']; ?>/lms/lib/javascript.php/1451892663/lib/requirejs/jquery-private'
        },
        // Custom jquery config map.
        map: {
            // '*' means all modules will get 'jqueryprivate'
            // for their 'jquery' dependency.
            '*': {jquery: 'jqueryprivate'},
            // 'jquery-private' wants the real jQuery module
            // though. If this line was not here, there would
            // be an unresolvable cyclic dependency.
            jqueryprivate: {jquery: 'jquery'}
        }
    };

    //]]>
</script>
<script type="text/javascript" src="https://<?php echo $_SERVER['SERVER_NAME']; ?>/lms/lib/javascript.php/1451892663/lib/requirejs/require.min.js"></script>
<script type="text/javascript">
    //<![CDATA[
    require(['core/first'], function () {
        ;
        require(["core/log"], function (amd) {
            amd.setConfig({"level": "warn"});
        });
    });
    //]]>
</script>
<script type="text/javascript" src="https://<?php echo $_SERVER['SERVER_NAME']; ?>/lms/theme/javascript.php/lambda/1451892772/footer"></script>
<script type="text/javascript">
    //<![CDATA[
    M.str = {"moodle": {"lastmodified": "Last modified", "name": "Name", "error": "Error", "info": "Information", "viewallcourses": "View all courses", "morehelp": "More help", "loadinghelp": "Loading...", "cancel": "Cancel", "yes": "Yes", "confirm": "Confirm", "no": "No", "areyousure": "Are you sure?", "closebuttontitle": "Close", "unknownerror": "Unknown error"}, "repository": {"type": "Type", "size": "Size", "invalidjson": "Invalid JSON string", "nofilesattached": "No files attached", "filepicker": "File picker", "logout": "Logout", "nofilesavailable": "No files available", "norepositoriesavailable": "Sorry, none of your current repositories can return files in the required format.", "fileexistsdialogheader": "File exists", "fileexistsdialog_editor": "A file with that name has already been attached to the text you are editing.", "fileexistsdialog_filemanager": "A file with that name has already been attached", "renameto": "Rename to \"{$a}\"", "referencesexist": "There are {$a} alias\/shortcut files that use this file as their source", "select": "Select"}, "block": {"addtodock": "Move this to the dock", "undockitem": "Undock this item", "dockblock": "Dock {$a} block", "undockblock": "Undock {$a} block", "undockall": "Undock all", "hidedockpanel": "Hide the dock panel", "hidepanel": "Hide panel"}, "langconfig": {"thisdirectionvertical": "btt"}, "admin": {"confirmation": "Confirmation"}};
    //]]>
</script>
<script type="text/javascript">
    //<![CDATA[
    var navtreeexpansions4 = [{"id": "expandable_branch_0_courses", "key": "courses", "type": 0}];
    //]]>
</script>
<script type="text/javascript">
    //<![CDATA[
    //(function () {

    //Y.use("moodle-block_navigation-navigation", function () {
    //M.block_navigation.init_add_tree({"id": "4", "instance": "4", "candock": true, "courselimit": "20", "expansionlimit": 0});
    //});
    //Y.use("moodle-block_navigation-navigation", function () {
    //M.block_navigation.init_add_tree({"id": "5", "instance": "5", "candock": true});
    //});
    //Y.use("moodle-calendar-info", function () {
    //  Y.M.core_calendar.info.init();
    //});


    //Y.on('domready', function () {

    //});
    //})();
    //]]>
</script>

<!--[if lte IE 9]>
<script src="https://cnausa.com/lms/theme/lambda/javascript/ie/iefix.js"></script>
<![endif]-->


<script>
    jQuery(document).ready(function ($) {
        $('.navbar .dropdown').hover(function () {
            $(this).addClass('extra-nav-class').find('.dropdown-menu').first().stop(true, true).delay(250).slideDown();
        }, function () {
            var na = $(this)
            na.find('.dropdown-menu').first().stop(true, true).delay(100).slideUp('fast', function () {
                na.removeClass('extra-nav-class')
            })
        });

    });

    jQuery(document).ready(function () {
        var offset = 220;
        var duration = 500;
        jQuery(window).scroll(function () {
            if (jQuery(this).scrollTop() > offset) {
                jQuery('.back-to-top').fadeIn(duration);
            } else {
                jQuery('.back-to-top').fadeOut(duration);
            }
        });

        jQuery('.back-to-top').click(function (event) {
            event.preventDefault();
            jQuery('html, body').animate({scrollTop: 0}, duration);
            return false;
        })
    });



</script>

</body>
</html>