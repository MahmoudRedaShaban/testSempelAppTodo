<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatusController extends Controller
{

    public function index()
    {
        $statuses = auth()->user()->statuses;
        return view('status.index', compact('statuses'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:56'
        ]);

        $slug = strtolower(preg_replace('/[^a-zA-Z0-9\-]/', '',preg_replace('/\s+/', '-', $request->get('title')) ));
        return auth()->user()->statuses()->create([
            'title' => $request->get('title'),
            'slug' => $slug,
            'order' => (integer) $request->get('order'),
        ]);
    }

    public function show($id)
    {
        return  auth()->user()->statuses()->find($id);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:56',
            'order' => 'number'
        ]);
        $statuse = $this->show($id);
        return $statuse->update($request->only('title', 'order'));
    }

    public function distroy($id)
    {
        if($this->show($id)->delete()){
            return  auth()->user()->statuses;
        }
    }
}
