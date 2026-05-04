<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PollDashboardController extends Controller
{
    public function index(Request $request)
    {
        $polls = $request->user()->polls()->orderBy('created_at', 'desc')->get();

        return view('polls.dashboard', [
            'polls' => $polls,
            'mode' => 'index',
            'pollToEdit' => null,
        ]);
    }

    public function create(Request $request)
    {
        $polls = $request->user()->polls()->orderBy('created_at', 'desc')->get();

        return view('polls.dashboard', [
            'polls' => $polls,
            'mode' => 'create',
            'pollToEdit' => null,
        ]);
    }

    public function edit(Request $request, int $id)
    {
        $poll = $request->user()->polls()
            ->with('options')
            ->where('id', $id)
            ->firstOrFail();

        $polls = $request->user()->polls()->orderBy('created_at', 'desc')->get();

        return view('polls.dashboard', [
            'polls' => $polls,
            'mode' => 'edit',
            'pollToEdit' => $poll,
        ]);
    }
}