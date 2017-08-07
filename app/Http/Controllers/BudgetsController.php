<?php

namespace App\Http\Controllers;

class BudgetsController extends Controller
{
    public function index()
    {
        return view('budgets.index');
    }
}
