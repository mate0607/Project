<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Issue;
use App\Http\Controllers\Traits\AdminHelpers;
use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;

class IssueController extends Controller
{
    use AdminHelpers;

    public function index()
    {
        $this->authorize('viewAny', Issue::class);

        $query = Issue::with('car')->latest();

        if (!$this->isAdmin()) {
            $query->whereHas('car', function ($carQuery) {
                $carQuery->where('user_id', auth()->id());
            });
        }

        $issues = $query->get();

        return view('issues.index', compact('issues'));
    }

    public function create()
    {
        $this->authorize('create', Issue::class);

        $cars = $this->userCarsQuery()->get();

        return view('issues.create', compact('cars'));
    }

    public function store(StoreIssueRequest $request)
    {
        $this->authorize('create', Issue::class);

        $validated = $request->validated();

        Issue::create($validated);

        return redirect()->route('issues.index')
            ->with('success', 'Hiba sikeresen létrehozva!');
    }

    public function show(Issue $issue)
    {
        $this->authorize('view', $issue);

        $issue->load('car');

        return view('issues.show', compact('issue'));
    }

    public function edit(Issue $issue)
    {
        $this->authorize('update', $issue);

        $cars = $this->userCarsQuery()->get();

        return view('issues.edit', compact('issue', 'cars'));
    }

    public function update(UpdateIssueRequest $request, Issue $issue)
    {
        $this->authorize('update', $issue);

        $validated = $request->validated();

        $issue->update($validated);

        return redirect()->route('issues.index')
            ->with('success', 'Hiba sikeresen frissítve!');
    }

    public function destroy(Issue $issue)
    {
        $this->authorize('delete', $issue);

        $issue->delete();

        return redirect()->route('issues.index')
            ->with('success', 'Hiba törölve!');
    }
}
