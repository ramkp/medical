<footer id="page-footer" class="container-fluid">

                <div class="footerlinks">
                    <div class="row-fluid">
                        <p class="helplink"></p><br/>
                        <div class="footnote"  style="display:inline-block;">
                            <p>(c) 2016 | <a href="#" target="_blank">
                                    <img src="http://cnausa.com/assets/icons/heart.gif" alt="heart" width="15" height="15">
                                </a> Powered  By Z&S &nbsp;&nbsp;                                                                
                                <a href="#">About Us</a>&nbsp;
                                <a href="#">Chat with US</a>&nbsp;
                                <a href="#">Contact US</a>&nbsp;
                                <!--<i class="fa fa-mobile fa-lg"></i>&nbsp; Phone: (007) 123-456    
                                <i class="fa fa-envelope-o"></i>&nbsp; E-mail:
                                <a href="mailto:info@medical-training.com">info@medical-training.com</a>
                                -->                
                            </p>
                        </div>
                        <div class="social_icons pull-right">                            
                            <!--
                            <a href="#" target="_blank" style="color: #bdc3c7;cursor:none;">Connect with us &nbsp;</a>                            
                            <a href="#" target="_blank" ><img src="http://cnausa.com/assets/icons/fb.png" alt="fb"> </a>
                            <a href="#" target="_blank" ><img src="http://cnausa.com/assets/icons/twt.png" alt="twt"></a>
                            <a href="#" target="_blank" ><img src="http://cnausa.com/assets/icons/in.png" alt="in">  </a> -->
                            <a href=""><div class='shareaholic-canvas' data-app='share_buttons' data-app-id='24115344'></div></a>

                        </div>
                    </div>
                </div>
            </footer>
        </div>

        <script type="text/javascript">
            //<![CDATA[
            var require = {
                baseUrl: 'http://cnausa.com/lms/lib/requirejs.php/1451892663/',
                // We only support AMD modules with an explicit define() statement.
                enforceDefine: true,
                skipDataMain: true,
                paths: {
                    jquery: 'http://cnausa.com/lms/lib/javascript.php/1451892663/lib/jquery/jquery-1.11.3.min',
                    jqueryui: 'http://cnausa.com/lms/lib/javascript.php/1451892663/lib/jquery/ui-1.11.4/jquery-ui.min',
                    jqueryprivate: 'http://cnausa.com/lms/lib/javascript.php/1451892663/lib/requirejs/jquery-private'
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
        <script type="text/javascript" src="http://cnausa.com/lms/lib/javascript.php/1451892663/lib/requirejs/require.min.js"></script>
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
        <script type="text/javascript" src="http://cnausa.com/lms/theme/javascript.php/lambda/1451892772/footer"></script>
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
        <script src="http://cnausa.com/lms/theme/lambda/javascript/ie/iefix.js"></script>
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