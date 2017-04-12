
<!DOCTYPE html>

<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>

        <!-- Skype meeting library -->
        <script type='text/javascript' src='https://swx.cdn.skype.com/shared/v/1.2.15/SkypeBootstrap.min.js'></script>

        <!-- Latest compiled and minified Bootstrap JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <!-- Latest compiled and minified Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >

        <!-- Optional Bootstrap theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" >

    </head>
    <body>
        <div class="row">



        </div>

        <script type="text/javascript">


            $(document).ready(function () {

                var sessionID = Math.random().toString(36).substring(7);

                Skype.initialize({
                    apiKey: 'a42fcebd-5b43-4b89-a065-74450fb91255',
                    correlationIds: {
                        sessionId: sessionID, 
                    }}, function (api) {

                    console.log('SessionID: ' + sessionID);
                    app = new api.application();

                    
                    

                }); // end of document.ready ...

            });

        </script>

    </body>
</html>



