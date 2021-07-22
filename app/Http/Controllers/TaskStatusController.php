<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TaskStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = TaskStatus::paginate();
        return view('task_statuses.index', compact('statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $status = new TaskStatus();
        return view('task_statuses.create', compact('status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, ['name' => 'required|unique:task_statuses']);
        $status = new TaskStatus();
        $status->fill($data);
        // При ошибках сохранения возникнет исключение
        $status->save();
        return redirect()->route('task_statuses.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TaskStatus  $taskStatuses
     * @return \Illuminate\Http\Response
     */
    public function show(TaskStatus $taskStatuses)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TaskStatus  $taskStatuses
     * @return \Illuminate\Http\Response
     */
    public function edit(TaskStatus $taskStatuses)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaskStatus  $taskStatuses
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaskStatus $taskStatuses)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TaskStatus  $taskStatuses
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaskStatus $taskStatuses)
    {
        //
    }
}
