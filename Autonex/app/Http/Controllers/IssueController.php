<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Issue;
use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $issues = Issue::with('car')->latest()->get();

        return view('issues.index', compact('issues'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cars = Car::orderBy('make_model')->get();

        return view('issues.create', compact('cars'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIssueRequest $request)
    {
        Issue::create($request->validated());

        return redirect()->route('issues.index')
            ->with('success', 'Hiba sikeresen létrehozva!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Issue $issue)
    {
        $issue->load('car');

        return view('issues.show', compact('issue'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Issue $issue)
    {
        $cars = Car::orderBy('make_model')->get();

        return view('issues.edit', compact('issue', 'cars'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIssueRequest $request, Issue $issue)
    {
        $issue->update($request->validated());

        return redirect()->route('issues.index')
            ->with('success', 'Hiba sikeresen frissítve!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Issue $issue)
    {
        $issue->delete();

        return redirect()->route('issues.index')
            ->with('success', 'Hiba törölve!');
    }
}
