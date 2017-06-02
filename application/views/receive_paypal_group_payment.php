<?php
echo $form;
?>


<script type="text/javascript">

    $(document).ready(function () {

        var token = $('#token').val();
        console.log('Token: '+token);
        var transaction = localStorage.getItem(token);
        var url = "/lms/custom/paypal/send_paypal_group_payment_receipt.php";
        $.post(url, {t: JSON.stringify(transaction)}).done(function (data) {
            console.log(data);
        });

    });

</script>    
