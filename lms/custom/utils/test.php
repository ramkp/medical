


<?php 

require_once './classes/Util.php';
$util=new Util();
$util->create_typehead_data();

?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Example of Twitter Typeahead</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.js'></script>
<script type="text/javascript">
$(document).ready(function(){
	$.get('data.json', function(data){
			//console.log('JSON data: '+data);
		    $("#search").typeahead({ source:data });
	},'json');
	
});  
</script>
<style type="text/css">
.bs-example{
	font-family: sans-serif;
	position: relative;
	margin: 100px;
}
.typeahead, .tt-query, .tt-hint {
	border: 2px solid #CCCCCC;
	border-radius: 8px;
	font-size: 24px;
	height: 30px;
	line-height: 30px;
	outline: medium none;
	padding: 8px 12px;
	width: 396px;
}
.typeahead {
	background-color: #FFFFFF;
}
.typeahead:focus {
	border: 2px solid #0097CF;
}
.tt-query {
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
}
.tt-hint {
	color: #999999;
}
.tt-dropdown-menu {
	background-color: #FFFFFF;
	border: 1px solid rgba(0, 0, 0, 0.2);
	border-radius: 8px;
	box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
	margin-top: 12px;
	padding: 8px 0;
	width: 422px;
}

</style>
</head>
<body>
    <div class="bs-example">
		<h2>Enter your country name</h2>
        <input id='search' type="text" class="typeahead tt-query" autocomplete="off" spellcheck="false">
    </div>
</body>
</html>                                		

