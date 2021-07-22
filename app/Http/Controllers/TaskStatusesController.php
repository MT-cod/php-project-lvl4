<?php

namespace App\Http\Controllers;

use App\Models\TaskStatuses;
use Illuminate\Http\Request;

class TaskStatusesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = TaskStatuses::paginate();
        return view('task_statuses.index', compact('statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $article = new TaskStatuses();
        return view('article.create', compact('article'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TaskStatuses  $taskStatuses
     * @return \Illuminate\Http\Response
     */
    public function show(TaskStatuses $taskStatuses)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TaskStatuses  $taskStatuses
     * @return \Illuminate\Http\Response
     */
    public function edit(TaskStatuses $taskStatuses)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaskStatuses  $taskStatuses
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaskStatuses $taskStatuses)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TaskStatuses  $taskStatuses
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaskStatuses $taskStatuses)
    {
        //
    }
}
