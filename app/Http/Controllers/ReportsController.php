<?php

namespace App\Http\Controllers;

use App\Account;
use App\Category;
use App\Reports\Configuration\DateRange;
use App\Reports\Report;
use App\Repositories\CategoryRepository;
use App\Repositories\ReportRepository;
use App\Repositories\TransactionRepository;
use App\Transaction;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Http\UploadedFile;
use League\Csv\Reader;
use Symfony\Component\HttpFoundation\JsonResponse;

class ReportsController extends Controller
{
    /**
     * @var ReportRepository
     */
    private $reportRepository;

    /**
     * ReportsController constructor.
     * @param ReportRepository $reportRepository
     */
    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function index()
    {
        return view('reports.index')
            ->with('reports', $this->reportRepository->reports())
            ->with('records', $this->reportRepository->all());
            //->with('actionButtons', [
            //    ['class' => 'btn-primary', 'href' => route('reports.create'), 'text' => 'Create']
            //]);
    }

    public function create($className)
    {
        return view('reports.create')
            ->with('report', $this->reportRepository->report(base64_decode($className)))
            ->with('actionButtons', [
                ['class' => 'btn-default', 'href' => route('reports'), 'text' => 'Back']
            ]);
    }

    public function store(Request $request)
    {
        dd($request->all());
    }

    public function preview(Request $request, $className)
    {
        $model = $this->reportRepository->getNew([
            'name' => '',
            'class_name' => base64_decode($className),
            'configuration' => $request->except('_token')
        ]);

        $report = $this->reportRepository->report(base64_decode($className), $model);
        return $report->generate();
    }
}
