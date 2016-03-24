<?php
require_once './classes/Report.php';
$report = new Report();
$courseid = $_POST['courseid'];
$from = $_POST['from'];
$to = $_POST['to'];
$list = $report->get_revenue_report_data($courseid, $from, $to, false);
$src_data = $report->get_revenue_payments_stats();
echo $list;
?>

<script type="text/javascript">

    var data;
    var chart;

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
        data.addColumn('string', 'Income source');
        data.addColumn('number', '($)');
        data.addRows([
        <?php
        foreach ($src_data as $data) {
            echo "['" . $data->src . "', " . $data->counter . "],";
        }
?>
        ]);

        // Set chart options
        var options = {'title': 'User payments',
            'width': 625,
            'height': 200};

        // Instantiate and draw our chart, passing in some options.
        chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);

    }
</script>

