<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PageController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();
    	$tasks = Task::where('team_id', $user->currentTeam->id)->get();

    	$data = [
    		'user' => $user,
    		'tasks' => $tasks
    	];

        return Inertia::render('Dashboard', $data);
    }

    public function modalDemo()
    {
        return Inertia::render('ModalDemo');
    }
}
