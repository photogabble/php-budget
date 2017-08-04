<table class="table">
    <thead>
        <tr>
            <th>Category Name</th>
            <th width="7%" class="text-right"># <small>(%total)</small></th>
            <th width="6%" class="text-right">Paid In</th>
            <th width="6%" class="text-right">Paid Out</th>
        </tr>
    </thead>
    <tbody>
    @if($results->count() < 1)
        <tr>
            <td colspan="3">No expenses or income was found for the filter you supplied.</td>
        </tr>
    @else
        @foreach($results as $row)
        <tr>
            <td>{{ $row->getName() }}</td>
            <td class="text-right">{{ $row->getTransactionCount() }} <small>({{ $row->getPercentage() }}%)</small></td>
            <td class="text-right">{{ $row->getPaidIn() }}</td>
            <td class="text-right">{{ $row->getPaidOut() }}</td>
        </tr>
        @endforeach
    @endif
    </tbody>
    <tfoot>
        <tr>
            <th>&nbsp;</th>
            <th class="text-right">{{ $totals['transactions'] }}</th>
            <th class="text-right">{{ number_format(($totals['paid_in']/100), 2) }}</th>
            <th class="text-right">{{ number_format(($totals['paid_out']/100), 2) }}</th>
        </tr>
    </tfoot>
</table>