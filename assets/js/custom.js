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
     *                Show login after click
     * 
     ************************************************************************/


    $("#login_link").click(function () {

        /*
         $("#search_div").hide();
         $('#register_div').hide();
         */
        console.log('Login link clicked ...');
        $.post("functionality/php/get_login_form.php", function (data) {
            $('#instructions').hide();
            $("#page").html(data);
        });

        /*
         if ($("#login_div").is(":visible")) {
         $("#login_div").hide('slow');
         } // end if $("#login_div").is(":visible") == true
         else {
         $("#login_div").show('slow');
         } // end else 
         */


    });

    /************************************************************************
     * 
     *                Show search form after click
     * 
     ************************************************************************/

    $('#search').on('submit', function () {
        //$('#home-carousel').hide();
        $("#login_div").hide();
        $('#register_div').hide();
        console.log('Search clicked ...');
        if ($("#search_div").is(":visible")) {
            $("#search_div").hide('slow');
            return false;
        } // end if $("#login_div").is(":visible") == true
        else {
            $("#search_div").show('slow');
            return false;
        } // end else 
        return false;

    }); // end '#search_box').on('submit', function ()

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