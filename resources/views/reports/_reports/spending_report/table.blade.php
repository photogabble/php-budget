<table class="table">
    <thead>
        <tr>
            <th>Date</th>
            <th width="5%" class="text-right">#</th>
            <th width="5%" class="text-right">Paid In</th>
            <th width="5%" class="text-right">Paid Out</th>
        </tr>
    </thead>
    <tbody>
        @foreach($results as $result)
        <tr>
            <td>{{ $result->getDate('jS M Y') }}</td>
            <td width="5%" class="text-right">{{ $result->getCount() }}</td>
            <td width="5%" class="text-right">{{ number_format(($result->getTotal('paid_in')/100), 2) }}</td>
            <td width="5%" class="text-right">{{ number_format(($result->getTotal('paid_out')/100), 2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>&nbsp;</th>
            <th class="text-right">{{ $totals['transactions'] }}</th>
            <th class="text-right">{{ number_format(($totals['paid_in']/100), 2) }}</th>
            <th class="text-right">{{ number_format(($totals['paid_out']/100), 2) }}</th>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <th class="text-right">Avg:</th>
            <th class="text-right">{{ number_format(($totals['paid_in']/100) / count($results), 2) }}</th>
            <th class="text-right">{{ number_format(($totals['paid_out']/100) / count($results), 2) }}</th>
        </tr>
    </tfoot>
</table>

<div>
    <?php var_dump($results) ;?>
</div>