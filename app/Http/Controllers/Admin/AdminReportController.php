<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $reports = Report::query()->with('user');

        if (!$request->has('created_at')) {
            $reports->orderBy('created_at', 'desc');
        }

        if ($request->has('search') && $request->search !== null) {
            $reports->where('reason', 'like', '%' . $request->search . '%')->orWhereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('reason') && $request->reason !== null) {
            $reports->orderBy('reason', $request->reason === 'desc' ? 'desc' : 'asc');
        }

        if ($request->has('reportable') && $request->reportable !== null) {
            $reports->orderBy('reportable_type', $request->reportable === 'desc' ? 'desc' : 'asc');
        }

        if ($request->has('created_at') && $request->created_at !== null) {
            $reports->orderBy('created_at', $request->created_at === 'desc' ? 'desc' : 'asc');
        }

        $reports = $reports->paginate($request->per_page ?? 10);

        return Inertia::render('Admin/Report/List', [
            'reports' => $reports->getCollection()->map(function ($report) {
                return [
                    'id' => $report->id,
                    'reason' => $report->reason,
                    'user' => [
                        'id' => $report->user->id,
                        'name' => $report->user->name,
                    ],
                    'reportable' => [
                        'id' => $report->reportable_id,
                        'type' => $report->reportable_type,
                    ],
                    'created_at' => $report->created_at->format('d/m/Y H:i'),
                ];
            }),
            'filters' => $request->all(),
            'pagination' => [
                'current_page' => $reports->currentPage(),
                'last_page' => $reports->lastPage(),
                'per_page' => $reports->perPage(),
                'total' => $reports->total(),
            ],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $report = Report::where('id', $id)->first();
        return Inertia::render('Admin/Report/Show', [
            'report' => $report->load('user')->load('reportable'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }
}
