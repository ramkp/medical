<!DOCTYPE html>

<html>
    <head>
        <title>Calendar</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src='//code.jquery.com/ui/1.12.1/jquery-ui.js'></script>
        <script src="//momentjs.com/downloads/moment.js"></script>
        <link rel='stylesheet' href='//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css'>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    </head>
    <body>

        <br><br><br><div style="width: 675px;margin:auto;text-align:center;" ><input type="text" id='picker'></div><br>
        <div style="width: 675px;margin:auto;text-align:center;"><button class="btn btn-primary" id='getTime'>Get Unix Time</button></div>
        <br><div style="width: 675px;margin:auto;text-align:center;font-size: 14px;font-weight: bold;" id='unc'></div>
        <script type="text/javascript">

            $(document).ready(function () {
                console.log("ready!");
                $("#picker").datepicker();
                $("#getTime").click(function () {
                    var hdate = $('#picker').val();
                    if (hdate != '') {
                        var url = "/lms/get_unix_stamp.php";
                        $.post(url, {date: hdate}).done(function (data) {
                            $('#unc').html(data);
                        }); // end of post
                    } // end if hdate != ''
                }); // end of click function
            }); // end of document ready

        </script>

    </body>
</html>


