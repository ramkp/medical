YUI.add("moodle-mod_scheduler-studentlist",function(e,t){var n={EXPANDED:"expanded",COLLAPSED:"collapsed"};M.mod_scheduler=M.mod_scheduler||{},MOD=M.mod_scheduler.studentlist={},MOD.setState=function(t,r){image=e.one("#"+t),content=e.one("#list"+t),r?(content.removeClass(n.COLLAPSED),content.addClass(n.EXPANDED),image.set("src",M.util.image_url("t/expanded"))):(content.removeClass(n.EXPANDED),content.addClass(n.COLLAPSED),image.set("src",M.util.image_url("t/collapsed")))},MOD.toggleState=function(t){content=e.one("#list"+t),isVisible=content.hasClass(n.EXPANDED),this.setState(t,!isVisible)},MOD.init=function(t,n){this.setState(t,n),e.one("#"+t).on("click",function(e){M.mod_scheduler.studentlist.toggleState(t)})}},"@VERSION@",{requires:["base","node","event","io"]});
