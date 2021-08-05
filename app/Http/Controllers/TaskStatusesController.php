<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use Illuminate\Http\Request;

class TaskStatusesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $taskStatuses = TaskStatus::orderBy('id')->paginate(10);
        return view('task_status.index', compact('taskStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $taskStatus = new TaskStatus();
        return view('task_status.create', compact('taskStatus'));
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
        if ($status->save()) {
            flash('Статус успешно создан')->success();
        } else {
            flash('Ошибка создания статуса')->error();
        }
        return redirect()->route('task_statuses.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(int $id)
    {
        $taskStatus = TaskStatus::find($id);
        return view('task_status.edit', compact('taskStatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $taskStatus = TaskStatus::findOrFail($id);
        $data = $this->validate($request, ['name' => 'required|unique:task_statuses']);
        $taskStatus->fill($data);
        if ($taskStatus->save()) {
            flash('Статус успешно изменён')->success();
        } else {
            flash('Ошибка изменения статуса')->error();
        }
        return redirect()->route('task_statuses.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $taskStatus = TaskStatus::findOrFail($id);
        if ($taskStatus?->delete()) {
            flash('Статус успешно удалён')->success();
        } else {
            flash('Не удалось удалить статус')->error();
        }
        return redirect()->route('task_statuses.index');
    }
}
