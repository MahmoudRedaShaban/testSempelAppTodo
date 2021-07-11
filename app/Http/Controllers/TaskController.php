<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    //

    public function index()
    {
        $tasks =  auth()->user()->statuses()->with('tasks')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:56',
            'description' => 'required|string',
            'status_id' => 'required|exists:statuses,id'
        ]);
        return  $request->user()->tasks()->create($request->only('title','description', 'status_id'));
    }


    public function sync(Request $request)
    {
        $this->validate(request(), [
            'columns' => ['required', 'array'],
        ]);

        foreach ($request->columns as $status) {
            // dd($status);
            foreach ($status['tasks'] as $i => $task) {
                $order = $i + 1;
                if ($task['status_id'] !== $status['id'] || $task['order'] !== $order) {
                    // dump($task['id']);
                    request()->user()->tasks()
                        ->find($task['id'])
                        ->update(['status_id' => $status['id'], 'order' => $order]);
                }
            }
        }
        return $request->user()->statuses()->with('tasks')->get();
    }


    public function update(Request $request, $task)
    {
        $this->validate($request, [
            'title' => 'required|string|max:56',
            'description' => 'required|string',
        ]);
        if($request->user()->tasks()->find($task)->update($request->only('title','description'))){
            return auth()->user()->statuses()->with('tasks')->get();
        }
    }
    public function distroy($task)
    {
        if(auth()->user()->tasks()->find($task)->delete()){
            return auth()->user()->statuses()->with('tasks')->get();
        }
    }

}
