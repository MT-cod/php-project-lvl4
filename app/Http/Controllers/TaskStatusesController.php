<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        $this->authorize('create', $taskStatus);
        return view('task_status.create');
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
        $taskStatus = new TaskStatus();
        $this->authorize('create', $taskStatus);
        $data = $this->validate($request, ['name' => 'required|unique:task_statuses']);
        $taskStatus->fill($data);
        if ($taskStatus->save()) {
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
        $taskStatus = TaskStatus::findOrFail($id);
        $this->authorize('update', $taskStatus);
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
        $this->authorize('update', $taskStatus);
        $data = $this->validate($request, ['name' => [
            'required',
            Rule::unique('task_statuses')->ignore($taskStatus->id)
        ]]);
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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(int $id)
    {
        $taskStatus = TaskStatus::findOrFail($id);
        $this->authorize('delete', $taskStatus);
        try {
            $taskStatus->delete();
            flash('Статус успешно удалён')->success();
        } catch (\Exception $e) {
            flash('Не удалось удалить статус')->error();
        } finally {
            return redirect()->route('task_statuses.index');
        }
    }
}
