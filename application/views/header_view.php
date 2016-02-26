<!DOCTYPE html>
<html  dir="ltr" lang="en" xml:lang="en">
    <head>
        <title>Medical2 Institute</title>
        <link rel="shortcut icon" href="http://cnausa.com/lms/theme/image.php/lambda/theme/1451892772/favicon" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="moodle, Medical2 Institute" />
        <link rel="stylesheet" type="text/css" href="http://cnausa.com/lms/theme/yui_combo.php?rollup/3.17.2/yui-moodlesimple-min.css" /><script id="firstthemesheet" type="text/css">/** Required in order to fix style inclusion problems in IE with YUI **/</script><link rel="stylesheet" type="text/css" href="http://cnausa.com/lms/theme/styles.php/lambda/1451892772/all" />
        <link href="http://cnausa.com/assets/css/full-slider.css" rel="stylesheet">
        <script type="text/javascript">
            //<![CDATA[
            var M = {};
            M.yui = {};
            M.pageloadstarttime = new Date();
            M.cfg = {"wwwroot": "http:\/\/cnausa.com\/medical\/lms", "sesskey": "IwtRhLNoqd", "loadingicon": "http:\/\/cnausa.com\/medical\/lms\/theme\/image.php\/lambda\/core\/1451892772\/i\/loading_small", "themerev": "1451892772", "slasharguments": 1, "theme": "lambda", "jsrev": "1451892663", "admin": "admin", "svgicons": true};
            var yui1ConfigFn = function (me) {
                if (/-skin|reset|fonts|grids|base/.test(me.name)) {
                    me.type = 'css';
                    me.path = me.path.replace(/\.js/, '.css');
                    me.path = me.path.replace(/\/yui2-skin/, '/assets/skins/sam/yui2-skin')
                }
            };
            var yui2ConfigFn = function (me) {
                var parts = me.name.replace(/^moodle-/, '').split('-'), component = parts.shift(), module = parts[0], min = '-min';
                if (/-(skin|core)$/.test(me.name)) {
                    parts.pop();
                    me.type = 'css';
                    min = ''
                }
                ;
                if (module) {
                    var filename = parts.join('-');
                    me.path = component + '/' + module + '/' + filename + min + '.' + me.type
                } else
                    me.path = component + '/' + component + '.' + me.type
            };
            YUI_config = {"debug": false, "base": "http:\/\/cnausa.com\/medical\/lms\/lib\/yuilib\/3.17.2\/", "comboBase": "http:\/\/cnausa.com\/medical\/lms\/theme\/yui_combo.php?", "combine": true, "filter": null, "insertBefore": "firstthemesheet", "groups": {"yui2": {"base": "http:\/\/cnausa.com\/medical\/lms\/lib\/yuilib\/2in3\/2.9.0\/build\/", "comboBase": "http:\/\/cnausa.com\/medical\/lms\/theme\/yui_combo.php?", "combine": true, "ext": false, "root": "2in3\/2.9.0\/build\/", "patterns": {"yui2-": {"group": "yui2", "configFn": yui1ConfigFn}}}, "moodle": {"name": "moodle", "base": "http:\/\/cnausa.com\/medical\/lms\/theme\/yui_combo.php?m\/1451892663\/", "combine": true, "comboBase": "http:\/\/cnausa.com\/medical\/lms\/theme\/yui_combo.php?", "ext": false, "root": "m\/1451892663\/", "patterns": {"moodle-": {"group": "moodle", "configFn": yui2ConfigFn}}, "filter": null, "modules": {"moodle-core-actionmenu": {"requires": ["base", "event", "node-event-simulate"]}, "moodle-core-formchangechecker": {"requires": ["base", "event-focus"]}, "moodle-core-checknet": {"requires": ["base-base", "moodle-core-notification-alert", "io-base"]}, "moodle-core-event": {"requires": ["event-custom"]}, "moodle-core-chooserdialogue": {"requires": ["base", "panel", "moodle-core-notification"]}, "moodle-core-lockscroll": {"requires": ["plugin", "base-build"]}, "moodle-core-tooltip": {"requires": ["base", "node", "io-base", "moodle-core-notification-dialogue", "json-parse", "widget-position", "widget-position-align", "event-outside", "cache-base"]}, "moodle-core-languninstallconfirm": {"requires": ["base", "node", "moodle-core-notification-confirm", "moodle-core-notification-alert"]}, "moodle-core-dragdrop": {"requires": ["base", "node", "io", "dom", "dd", "event-key", "event-focus", "moodle-core-notification"]}, "moodle-core-popuphelp": {"requires": ["moodle-core-tooltip"]}, "moodle-core-dock": {"requires": ["base", "node", "event-custom", "event-mouseenter", "event-resize", "escape", "moodle-core-dock-loader"]}, "moodle-core-dock-loader": {"requires": ["escape"]}, "moodle-core-blocks": {"requires": ["base", "node", "io", "dom", "dd", "dd-scroll", "moodle-core-dragdrop", "moodle-core-notification"]}, "moodle-core-formautosubmit": {"requires": ["base", "event-key"]}, "moodle-core-maintenancemodetimer": {"requires": ["base", "node"]}, "moodle-core-notification": {"requires": ["moodle-core-notification-dialogue", "moodle-core-notification-alert", "moodle-core-notification-confirm", "moodle-core-notification-exception", "moodle-core-notification-ajaxexception"]}, "moodle-core-notification-dialogue": {"requires": ["base", "node", "panel", "escape", "event-key", "dd-plugin", "moodle-core-widget-focusafterclose", "moodle-core-lockscroll"]}, "moodle-core-notification-alert": {"requires": ["moodle-core-notification-dialogue"]}, "moodle-core-notification-confirm": {"requires": ["moodle-core-notification-dialogue"]}, "moodle-core-notification-exception": {"requires": ["moodle-core-notification-dialogue"]}, "moodle-core-notification-ajaxexception": {"requires": ["moodle-core-notification-dialogue"]}, "moodle-core-handlebars": {"condition": {"trigger": "handlebars", "when": "after"}}, "moodle-core_availability-form": {"requires": ["base", "node", "event", "panel", "moodle-core-notification-dialogue", "json"]}, "moodle-backup-confirmcancel": {"requires": ["node", "node-event-simulate", "moodle-core-notification-confirm"]}, "moodle-backup-backupselectall": {"requires": ["node", "event", "node-event-simulate", "anim"]}, "moodle-calendar-info": {"requires": ["base", "node", "event-mouseenter", "event-key", "overlay", "moodle-calendar-info-skin"]}, "moodle-course-categoryexpander": {"requires": ["node", "event-key"]}, "moodle-course-modchooser": {"requires": ["moodle-core-chooserdialogue", "moodle-course-coursebase"]}, "moodle-course-dragdrop": {"requires": ["base", "node", "io", "dom", "dd", "dd-scroll", "moodle-core-dragdrop", "moodle-core-notification", "moodle-course-coursebase", "moodle-course-util"]}, "moodle-course-formatchooser": {"requires": ["base", "node", "node-event-simulate"]}, "moodle-course-util": {"requires": ["node"], "use": ["moodle-course-util-base"], "submodules": {"moodle-course-util-base": {}, "moodle-course-util-section": {"requires": ["node", "moodle-course-util-base"]}, "moodle-course-util-cm": {"requires": ["node", "moodle-course-util-base"]}}}, "moodle-course-toolboxes": {"requires": ["node", "base", "event-key", "node", "io", "moodle-course-coursebase", "moodle-course-util"]}, "moodle-course-management": {"requires": ["base", "node", "io-base", "moodle-core-notification-exception", "json-parse", "dd-constrain", "dd-proxy", "dd-drop", "dd-delegate", "node-event-delegate"]}, "moodle-form-shortforms": {"requires": ["node", "base", "selector-css3", "moodle-core-event"]}, "moodle-form-passwordunmask": {"requires": ["node", "base"]}, "moodle-form-showadvanced": {"requires": ["node", "base", "selector-css3"]}, "moodle-form-dateselector": {"requires": ["base", "node", "overlay", "calendar"]}, "moodle-core_message-messenger": {"requires": ["escape", "handlebars", "io-base", "moodle-core-notification-ajaxexception", "moodle-core-notification-alert", "moodle-core-notification-dialogue", "moodle-core-notification-exception"]}, "moodle-core_message-deletemessage": {"requires": ["node", "event"]}, "moodle-question-preview": {"requires": ["base", "dom", "event-delegate", "event-key", "core_question_engine"]}, "moodle-question-searchform": {"requires": ["base", "node"]}, "moodle-question-chooser": {"requires": ["moodle-core-chooserdialogue"]}, "moodle-question-qbankmanager": {"requires": ["node", "selector-css3"]}, "moodle-availability_completion-form": {"requires": ["base", "node", "event", "moodle-core_availability-form"]}, "moodle-availability_date-form": {"requires": ["base", "node", "event", "io", "moodle-core_availability-form"]}, "moodle-availability_grade-form": {"requires": ["base", "node", "event", "moodle-core_availability-form"]}, "moodle-availability_group-form": {"requires": ["base", "node", "event", "moodle-core_availability-form"]}, "moodle-availability_grouping-form": {"requires": ["base", "node", "event", "moodle-core_availability-form"]}, "moodle-availability_profile-form": {"requires": ["base", "node", "event", "moodle-core_availability-form"]}, "moodle-qtype_ddimageortext-form": {"requires": ["moodle-qtype_ddimageortext-dd", "form_filepicker"]}, "moodle-qtype_ddimageortext-dd": {"requires": ["node", "dd", "dd-drop", "dd-constrain"]}, "moodle-qtype_ddmarker-form": {"requires": ["moodle-qtype_ddmarker-dd", "form_filepicker", "graphics", "escape"]}, "moodle-qtype_ddmarker-dd": {"requires": ["node", "event-resize", "dd", "dd-drop", "dd-constrain", "graphics"]}, "moodle-qtype_ddwtos-dd": {"requires": ["node", "dd", "dd-drop", "dd-constrain"]}, "moodle-mod_assign-history": {"requires": ["node", "transition"]}, "moodle-mod_forum-subscriptiontoggle": {"requires": ["base-base", "io-base"]}, "moodle-mod_quiz-quizquestionbank": {"requires": ["base", "event", "node", "io", "io-form", "yui-later", "moodle-question-qbankmanager", "moodle-core-notification-dialogue"]}, "moodle-mod_quiz-modform": {"requires": ["base", "node", "event"]}, "moodle-mod_quiz-quizbase": {"requires": ["base", "node"]}, "moodle-mod_quiz-repaginate": {"requires": ["base", "event", "node", "io", "moodle-core-notification-dialogue"]}, "moodle-mod_quiz-dragdrop": {"requires": ["base", "node", "io", "dom", "dd", "dd-scroll", "moodle-core-dragdrop", "moodle-core-notification", "moodle-mod_quiz-quizbase", "moodle-mod_quiz-util-base", "moodle-mod_quiz-util-page", "moodle-mod_quiz-util-slot", "moodle-course-util"]}, "moodle-mod_quiz-autosave": {"requires": ["base", "node", "event", "event-valuechange", "node-event-delegate", "io-form"]}, "moodle-mod_quiz-util": {"requires": ["node"], "use": ["moodle-mod_quiz-util-base"], "submodules": {"moodle-mod_quiz-util-base": {}, "moodle-mod_quiz-util-slot": {"requires": ["node", "moodle-mod_quiz-util-base"]}, "moodle-mod_quiz-util-page": {"requires": ["node", "moodle-mod_quiz-util-base"]}}}, "moodle-mod_quiz-toolboxes": {"requires": ["base", "node", "event", "event-key", "io", "moodle-mod_quiz-quizbase", "moodle-mod_quiz-util-slot", "moodle-core-notification-ajaxexception"]}, "moodle-mod_quiz-randomquestion": {"requires": ["base", "event", "node", "io", "moodle-core-notification-dialogue"]}, "moodle-mod_quiz-questionchooser": {"requires": ["moodle-core-chooserdialogue", "moodle-mod_quiz-util", "querystring-parse"]}, "moodle-message_airnotifier-toolboxes": {"requires": ["base", "node", "io"]}, "moodle-block_navigation-navigation": {"requires": ["base", "io-base", "node", "event-synthetic", "event-delegate", "json-parse"]}, "moodle-filter_glossary-autolinker": {"requires": ["base", "node", "io-base", "json-parse", "event-delegate", "overlay", "moodle-core-event", "moodle-core-notification-alert", "moodle-core-notification-exception", "moodle-core-notification-ajaxexception"]}, "moodle-filter_mathjaxloader-loader": {"requires": ["moodle-core-event"]}, "moodle-editor_atto-rangy": {"requires": []}, "moodle-editor_atto-editor": {"requires": ["node", "transition", "io", "overlay", "escape", "event", "event-simulate", "event-custom", "node-event-html5", "yui-throttle", "moodle-core-notification-dialogue", "moodle-core-notification-confirm", "moodle-editor_atto-rangy", "handlebars", "timers"]}, "moodle-editor_atto-plugin": {"requires": ["node", "base", "escape", "event", "event-outside", "handlebars", "event-custom", "timers", "moodle-editor_atto-menu"]}, "moodle-editor_atto-menu": {"requires": ["moodle-core-notification-dialogue", "node", "event", "event-custom"]}, "moodle-report_eventlist-eventfilter": {"requires": ["base", "event", "node", "node-event-delegate", "datatable", "autocomplete", "autocomplete-filters"]}, "moodle-report_loglive-fetchlogs": {"requires": ["base", "event", "node", "io", "node-event-delegate"]}, "moodle-gradereport_grader-gradereporttable": {"requires": ["base", "node", "event", "handlebars", "overlay", "event-hover"]}, "moodle-gradereport_history-userselector": {"requires": ["escape", "event-delegate", "event-key", "handlebars", "io-base", "json-parse", "moodle-core-notification-dialogue"]}, "moodle-tool_capability-search": {"requires": ["base", "node"]}, "moodle-tool_monitor-dropdown": {"requires": ["base", "event", "node"]}, "moodle-assignfeedback_editpdf-editor": {"requires": ["base", "event", "node", "io", "graphics", "json", "event-move", "event-resize", "querystring-stringify-simple", "moodle-core-notification-dialog", "moodle-core-notification-exception", "moodle-core-notification-ajaxexception"]}, "moodle-atto_accessibilitychecker-button": {"requires": ["color-base", "moodle-editor_atto-plugin"]}, "moodle-atto_accessibilityhelper-button": {"requires": ["moodle-editor_atto-plugin"]}, "moodle-atto_align-button": {"requires": ["moodle-editor_atto-plugin"]}, "moodle-atto_bold-button": {"requires": ["moodle-editor_atto-plugin"]}, "moodle-atto_charmap-button": {"requires": ["moodle-editor_atto-plugin"]}, "moodle-atto_clear-button": {"requires": ["moodle-editor_atto-plugin"]}, "moodle-atto_collapse-button": {"requires": ["moodle-editor_atto-plugin"]}, "moodle-atto_emoticon-button": {"requires": ["moodle-editor_atto-plugin"]}, "moodle-atto_equation-button": {"requires": ["moodle-editor_atto-plugin", "moodle-core-event", "io", "event-valuechange", "tabview", "array-extras"]}, "moodle-atto_html-button": {"requires": ["moodle-editor_atto-plugin", "event-valuechange"]}, "moodle-atto_image-button": {"requires": ["moodle-editor_atto-plugin"]}, "moodle-atto_indent-button": {"requires": ["moodle-editor_atto-plugin"]}, "moodle-atto_italic-button": {"requires": ["moodle-editor_atto-plugin"]}, "moodle-atto_link-button": {"requires": ["moodle-editor_atto-plugin"]}, "moodle-atto_managefiles-button": {"requires": ["moodle-editor_atto-plugin"]}, "moodle-atto_managefiles-usedfiles": {"requires": ["node", "escape"]}, "moodle-atto_media-button": {"requires": ["moodle-editor_atto-plugin"]}, "moodle-atto_noautolink-button": {"requires": ["moodle-editor_atto-plugin"]}, "moodle-atto_orderedlist-button": {"requires": ["moodle-editor_atto-plugin"]}, "moodle-atto_rtl-button": {"requires": ["moodle-editor_atto-plugin"]}, "moodle-atto_strike-button": {"requires": ["moodle-editor_atto-plugin"]}, "moodle-atto_subscript-button": {"requires": ["moodle-editor_atto-plugin"]}, "moodle-atto_superscript-button": {"requires": ["moodle-editor_atto-plugin"]}, "moodle-atto_table-button": {"requires": ["moodle-editor_atto-plugin", "moodle-editor_atto-menu", "event", "event-valuechange"]}, "moodle-atto_title-button": {"requires": ["moodle-editor_atto-plugin"]}, "moodle-atto_underline-button": {"requires": ["moodle-editor_atto-plugin"]}, "moodle-atto_undo-button": {"requires": ["moodle-editor_atto-plugin"]}, "moodle-atto_unorderedlist-button": {"requires": ["moodle-editor_atto-plugin"]}}}, "gallery": {"name": "gallery", "base": "http:\/\/cnausa.com\/medical\/lms\/lib\/yuilib\/gallery\/", "combine": true, "comboBase": "http:\/\/cnausa.com\/medical\/lms\/theme\/yui_combo.php?", "ext": false, "root": "gallery\/1451892663\/", "patterns": {"gallery-": {"group": "gallery"}}}}, "modules": {"core_filepicker": {"name": "core_filepicker", "fullpath": "http:\/\/cnausa.com\/medical\/lms\/lib\/javascript.php\/1451892663\/repository\/filepicker.js", "requires": ["base", "node", "node-event-simulate", "json", "async-queue", "io-base", "io-upload-iframe", "io-form", "yui2-treeview", "panel", "cookie", "datatable", "datatable-sort", "resize-plugin", "dd-plugin", "escape", "moodle-core_filepicker"]}}};
            M.yui.loader = {modules: {}};

            //]]>
        </script>
        <link rel="stylesheet" type="text/css" href="http://cnausa.com/assets/css/custom.css" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <noscript>
        <link rel="stylesheet" type="text/css" href="http://cnausa.com/lms/theme/lambda/style/nojs.css" />

        </noscript>
        <!-- Google web fonts -->
    </head>

    <body  id="page-site-index" class="format-site course path-site safari dir-ltr lang-en yui-skin-sam yui3-skin-sam mycodebusters-com--medical-lms pagelayout-frontpage course-1 context-2 notloggedin two-column has-region-side-pre used-region-side-pre has-region-side-post used-region-side-post has-region-footer-left empty-region-footer-left has-region-footer-middle empty-region-footer-middle has-region-footer-right empty-region-footer-right has-region-hidden-dock empty-region-hidden-dock layout-option-nonavbar">

        <div class="skiplinks"><a class="skip" href="#maincontent">Skip to main content</a></div>
        <script type="text/javascript" src="http://cnausa.com/lms/theme/yui_combo.php?rollup/3.17.2/yui-moodlesimple-min.js&amp;rollup/1451892663/mcore-min.js"></script><script type="text/javascript" src="http://cnausa.com/lms/theme/jquery.php/core/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="http://cnausa.com/lms/theme/jquery.php/theme_lambda/jquery.easing.1.3.js"></script>
        <script type="text/javascript" src="http://cnausa.com/lms/theme/jquery.php/theme_lambda/camera.min.1.11.js"></script>
        <script type="text/javascript" src="http://cnausa.com/lms/theme/jquery.php/theme_lambda/jquery.bxslider.js"></script>
        <script type='text/javascript' src='http://maps.googleapis.com/maps/api/js?key=AIzaSyA_7yjXzpz9sxQw6Ut0gFa8045N_I4QGXk'></script>
        <script type="text/javascript" src="http://cnausa.com/assets/js/custom.js"></script>
        <script type="text/javascript">
            //<![CDATA[
            document.body.className += ' jsenabled';
            //]]>
        </script>


        <div id="wrapper">
            <header id="page-header" class="clearfix">
                <div class="container-fluid"></div>
            </header>

            <header  class="navbar">
                <nav class="navbar-inner">
                    <div class="container-fluid">
                        <a class="brand" href="http://cnausa.com" ><img src="http://cnausa.com/assets/icons/home2.png" width="20" alt='home' height="20">&nbsp; Medical2 Training Institute</a>
                        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </a>
                        <div class="nav-collapse collapse">
                            <div class="nav-divider-right"></div>
                            <ul class="nav pull-right">
                                <li></li>
                            </ul>

                            <div class="nav-collapse collapse">
                                <ul class="nav"><li class="dropdown"><a title="Programs"  class="dropdown-toggle" href="#">Programs<b class="caret"></b></a>
                                        <ul class="dropdown-menu" data-parent=".nav-collapse" data-toggle="collapse">
                                            <li><a class="dropdown-toggle" href="http://cnausa.com/index.php/programs/program/workshops" id="ws"  title="Workshops">Workshops</a></li>
                                            <li><a href="http://cnausa.com/index.php/programs/program/courses" id="cs" title="Online & CEU’s Courses">Online & CEU’s Courses</a></li>
                                            <li><a href="http://cnausa.com/index.php/programs/program/exams/" id="exam" title="Online Exams">Online Exams</a></li>
                                            <li><a href="http://cnausa.com/index.php/programs/program/school" id="school" title="MS Medical School">Nursing School</a></li>                                     
                                        </ul>
                                    </li>                                    
                                    <li><a href="http://cnausa.com/index.php/register"  id="register_item" title="Register">Register</a></li>
                                    <li><a href="http://cnausa.com/index.php/faq"  id="faq_item" title="FAQ’s">FAQ’s</a></li>

                                    <li id='login_link'><a href="http://cnausa.com/index.php/login" title="Login">Login</a></li>                                    
                                    <li class="dropdown"><a title="More" class="dropdown-toggle" href="#cm_submenu_2">More<b class="caret"></b></a>
                                        <ul class="dropdown-menu">                                            
                                            <li><a href="http://cnausa.com/index.php/certs"  id="cert" title="Verify Certification">Verify Certification</a></li>
                                            <li><a href="http://cnausa.com/index.php/groups"  id="group" title="Private Groups">Private Groups</a></li>
                                            <li><a href="http://cnausa.com/index.php/testimonial"  id="testimonial" title="Testimonial">Testimonial</a></li>
                                            <li><a href="http://cnausa.com/index.php/gallery"  id="gallery" title="Photo Gallery">Photo Gallery</a></li>                                            
                                        </ul>
                                    </li>

                                </ul>
                                <div class="nav-divider-right"></div>                                
                            </div>                      
                            <ul class="nav pull-right">                                
                                <li class="dropdown"><a href="http://cnausa.com/index.php/search"><img src="/lms/theme/image.php/lambda/theme/1453287272/bg/icon-search" style="cursor: pointer;" alt="search" id='search_item' /></a></li>
                                <li class="dropdown"><a href="#">Select Language<span style="color: rgb(155, 155, 155);">▼</span></a></li>    
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>
