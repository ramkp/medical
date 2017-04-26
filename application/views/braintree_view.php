
<?php
echo $form;
?>

<script type="text/javascript">

    $(document).ready(function () {

        var getUrlParameter = function getUrlParameter(sParam) {
            var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                    sURLVariables = sPageURL.split('&'),
                    sParameterName,
                    i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : sParameterName[1];
                }
            }
        };

        var pid = localStorage.getItem("group_renew_payer_id");
        var params = getUrlParameter('cm');
        if (typeof params != 'undefined') {
            var data_arr = params.split('/');
            var userslist = data_arr[2];
            var cert = {pid: pid, userslist: userslist};
            var url = "/lms/custom/usrgroups/send_paypal_group_renew_receipt.php";
            $.post(url, {cert: JSON.stringify(cert)}).done(function (data) {
                console.log('Server response: ' + data);
            }); // end of post
        } // end if typeof params != 'undefined'

    }); // end of document ready

</script>    