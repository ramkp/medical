/* -------------------- Check Browser --------------------- */

function browser() {

    var isOpera = !!(window.opera && window.opera.version);  // Opera 8.0+
    var isFirefox = testCSS('MozBoxSizing');                 // FF 0.8+
    var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
    // At least Safari 3+: "[object HTMLElementConstructor]"
    var isChrome = !isSafari && testCSS('WebkitTransform');  // Chrome 1+
    //var isIE = /*@cc_on!@*/false || testCSS('msTransform');  // At least IE6

    function testCSS(prop) {
        return prop in document.documentElement.style;
    }

    if (isOpera) {

        return false;

    } else if (isSafari || isChrome) {

        return true;

    } else {

        return false;

    }

}


$(document).ready(function () {


    /* ---------- Add class .active to current link  ---------- */
    $('ul.main-menu li a').each(function () {

        if ($($(this))[0].href == String(window.location)) {

            $(this).parent().addClass('active');

        }

    });

    $('ul.main-menu li ul li a').each(function () {

        if ($($(this))[0].href == String(window.location)) {

            $(this).parent().addClass('active');
            $(this).parent().parent().show();

        }

    });

    /* ---------- Submenu  ---------- */

    $('.dropmenu').click(function (e) {

        e.preventDefault();

        $(this).parent().find('ul').slideToggle();

    });

    /************************************************************************
     * 
     *                Show login form after click
     * 
     ************************************************************************/

    $("#login_link").click(function () {
        console.log('Login link clicked ...');
        $.post("functionality/php/get_login_form.php", function (data) {
            $('#instructions').hide();
            $("#page").html(data);
        });
    });

    /************************************************************************
     * 
     *                Login form verification
     * 
     ************************************************************************/
    function check_login_form() {
        var login = $('#login_box').val();
        var password = $('#passsword_box').val();
        if (login == '' || password == '') {
            $('#login_err').html('*all fields required');
        }
        if (login != '' && password != '') {
            $('#login_form').submit();
        }
    }

    /************************************************************************
     * 
     *                   Show program items
     * 
     ************************************************************************/
    function show_program_items(cat_name) {
        $.post("functionality/php/get_programs_list.php", {cat_name: cat_name})
                .done(function (data) {
                    $('#instructions').hide();
                    $("#page").html(data);
                });

    }

    /************************************************************************
     * 
     *                Show register form after click
     * 
     ************************************************************************/

    $('#register_item').click(function () {
        $("#search_div").hide();
        $("#login_div").hide();
        //$('#register_div').html('This register form ....');
        $('#register_div').show('slow');
    });

    /************************************************************************
     * 
     *                      Show search form
     * 
     ************************************************************************/

    $('#search_item').click(function () {
        console.log('Search clicked ...');
        $.post("functionality/php/get_search_form.php", function (data) {
            $('#instructions').hide();
            $("#page").html(data);
        });
    });

    /************************************************************************
     * 
     *                      Show workshops list after click
     * 
     ************************************************************************/
    $('#ws').click(function () {
        console.log('Workshops clicked ...');
        show_program_items('Workshops');
    });

    /************************************************************************
     * 
     *                      Show courses list after click
     * 
     ************************************************************************/

    $('#cs').click(function () {
        console.log('Courses clicked ...');
        show_program_items('Courses');

    });

    /************************************************************************
     * 
     *                      Show exams list after click
     * 
     ************************************************************************/

    $('#exam').click(function () {
        console.log('Exams clicked ...');
        show_program_items('Exams');

    });

    /************************************************************************
     * 
     *                      Show school's list after click
     * 
     ************************************************************************/

    $('#school').click(function () {
        console.log('Schools clicked ...');
        show_program_items('School');

    });

    /************************************************************************
     * 
     *                Events processing block
     * 
     ************************************************************************/

    $('#page').on('click', 'button', function (event) {
        //alert(event.target.id);
        if (event.target.id == 'login_button') {
            event.preventDefault();
            check_login_form();
        }



    });


}); // end of (document).ready(function ()