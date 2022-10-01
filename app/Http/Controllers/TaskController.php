<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function allTasks()
    {
        $all_tasks = Task::all()->toArray();
        $returnDataArr = [];

        foreach ($all_tasks as $task) {
            if ($task['status_id'] == 1) {
                $returnDataArr['todoArr'][] = $task;
            }
            if ($task['status_id'] == 2) {
                $returnDataArr['in_progressArr'][] = $task;
            }
            if ($task['status_id'] == 3) {
                $returnDataArr['doneArr'][] = $task;
            }
        }
        return response()->json(['responseCode' => 1, 'status' => 'success', 'msg' => 'Data found', 'data' => $returnDataArr]);
    }

    public function store(Request $request)
    {
        if(Task::create([
            'task' => $request->task,
            'status_id' => 1,
        ]))
        {
            return response()->json(['responseCode' => 1, 'status' => 'success', 'msg' => 'Data added']);
        }else{
            return response()->json(['responseCode' => -1, 'status' => 'failed', 'msg' => 'Data cannot be added']);
        }
    }

    public function update(Request $request)
    {
        $todos_list = isset($request['todos_list']) ? $request['todos_list'] : [];
        $in_progress_list = isset($request['in_progress_list']) ? $request['in_progress_list'] : [];
        $done_list = isset($request['done_list']) ? $request['done_list'] : [];

        try {
            if (count($todos_list) > 0) {
                Task::whereIn('id', $todos_list)->update(['status_id' => 1]);
            }
            if (count($in_progress_list) > 0) {
                Task::whereIn('id', $in_progress_list)->update(['status_id' => 2]);
            }
            if (count($done_list) > 0) {
                Task::whereIn('id', $done_list)->update(['status_id' => 3]);
            }
            return response()->json(['responseCode' => 1, 'status' => 'success', 'msg' => 'Data updated']);
        } catch (\Exception $e) {
            return response()->json(['responseCode' => -1,'status' => 'failed', 'msg' => 'Data cannot be updated']);
        }
    }
}
