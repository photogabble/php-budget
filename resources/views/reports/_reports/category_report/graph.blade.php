@if($totals['transactions'] > 0)
<h4 style="padding-bottom: 5px; margin-bottom: 25px; border-bottom: 2px solid #ccc">
    Transaction Amount overall breakdown
</h4>
<div class="clearfix" style="height:350px;margin-bottom:25px;">
    <div style="height:350px;" class="col-md-9">
        <canvas id="category_breakdown_bar"></canvas>
    </div>

    <div style="height:350px;" class="col-md-3">
        <canvas id="category_breakdown_pie" width="350" height="350"></canvas>
    </div>
</div>

<script>

    var barChart = function(){
        var ctx = $("#category_breakdown_bar");

        var options = {
            maintainAspectRatio: false,
            responsive: true
        };

        var data = {
            labels: [<?= $graphData['bar']['labels']; ?>],
            datasets: [
                {
                    label: "Paid In (£)",
                    backgroundColor: 'rgba(102, 204, 0, 0.5)',
                    borderColor: 'rgba(25,51,0,0.8)',
                    borderWidth: 1,
                    data: [<?= $graphData['bar']['datasets']['paid_in'] ?>]
                },
                {
                    label: "Paid Out (£)",
                    backgroundColor: 'rgba(153, 0, 0, 0.5)',
                    borderColor: 'rgba(102,0,51,0.8)',
                    borderWidth: 1,
                    data: [<?= $graphData['bar']['datasets']['paid_out'] ?>]
                }
            ]
        };

        new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options
        });
    };

    var pieChart = function(){
        var ctx = $("#category_breakdown_pie");

        var options = {
            maintainAspectRatio: false,
            responsive: true
        };

        var data = {
            labels: [<?= $graphData['pie']['labels']; ?>],
            datasets: [
                {
                    data: [<?= $graphData['pie']['datasets']['data'] ?>],
                    backgroundColor: [
                        'rgba(102, 204, 0, 0.5)',
                        'rgba(153, 0, 0, 0.5)'
                    ]
                }
            ]
        }

        new Chart(ctx, {
            type: 'pie',
            data: data,
            options: options
        });

    };

    window.executeOnAjaxComplete = function(){
        barChart();
        pieChart();
    }
</script>
@else
    <span style="text-align: center; display:block;">No expenses or income was found for the filter you supplied.</span>
@endif