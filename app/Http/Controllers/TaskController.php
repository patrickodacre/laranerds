<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function createTask(Request $request, Team $team)
    {

        $task = Task::create([
            'team_id' => $team->id,
            'creator_id' => Auth::user()->id,
            'title' => $request->get('title'),
            'description' => $request->get('description'),
        ]);

        return response()
            ->json([
                'task' => $task,
            ], Response::HTTP_CREATED);
    }

    public function patchTask(Request $request, Task $task)
    {
        $data = $request->all();

        if (isset($data['is_complete']))
        {
            $task->is_complete = $data['is_complete'];
        }

        if (isset($data['title']))
        {
            $task->title = $data['title'];
        }

        if (isset($data['description']))
        {
            $task->description = $data['description'];
        }

        $task->save();

        return response()
            ->json([
                'task' => $task,
            ], Response::HTTP_OK);
    }


    public function deleteTask(Task $task)
    {
        $task->delete();

        return response()
            ->json([
                'task_id' => $task->id
            ], Response::HTTP_OK);


    }

}

