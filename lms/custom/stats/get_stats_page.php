<?php
require_once './classes/Stats.php';
$stats = new Stats();
$src_users = $stats->get_users_source_page();
$state_users = $stats->get_users_states_page();
$src_list = $stats->get_users_source_page();
$states_list = $stats->get_users_states_page();
$src_data = $stats->sources;
$states_data=$stats->states;
echo $src_list;
//echo $states_list;
?>

<script type="text/javascript">

    var data;
    var chart;
    var data2
    var chart2;

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);

    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.
    function drawChart() {

        /************************************************************
         * 
         *                 Source users chart
         * 
         *************************************************************/

        // Create our data table for sources.
        data = new google.visualization.DataTable();
        data.addColumn('string', 'Source');
        data.addColumn('number', 'Users');
        data.addRows([
                <?php
                foreach ($src_data as $data) {
                echo "['" . $data->src . "', " . $data->counter . "],";
                } ?>
                    ]);

        // Set chart options
        var options = {'title': 'User sources',
            'width': 600,
            'height': 300};

        // Instantiate and draw our chart, passing in some options.
        chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);

        /************************************************************
         * 
         *                 State users chart
         * 
         *************************************************************/
        
        /*
        var options2 = {'title': 'User states',
            'width': 600,
            'height': 300};
        
        // Create our data table for states.
        data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'States');
        data2.addColumn('number', 'Users');
        data2.addRows([
                <?php
                foreach ($states_data as $data) {
                echo "['" . $data->state . "', " . $data->counter . "],";
                } ?>
                    ]);
        // Instantiate and draw our chart, passing in some options.
        chart2 = new google.visualization.ColumnChart(document.getElementById('chart_div2'));
        chart2.draw(data2, options2);    
        */
    } // end of function






</script>
