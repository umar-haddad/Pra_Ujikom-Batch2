<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransOrderDetail;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Report';

        if ($request->date_start && $request->date_end) {
            $startDate = $request->date_start;
            $endDate = $request->date_end;

            $details = TransOrderDetail::with(['transOrder.customer', 'service'])
                ->whereDate('order_date', '>=', $startDate)
                ->whereDate('order_date', '<=', $endDate)
                ->get();


            return view('report.index', compact('title', 'details'));
        }

        $details = TransOrderDetail::with(['transOrder.customer', 'service'])->get();
        return view('report.index', compact('details', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
