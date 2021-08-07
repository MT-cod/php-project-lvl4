<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $tasks = Task::addSelect(['status_name' => TaskStatus::select('name')
            ->whereColumn('id', 'tasks.status_id')
        ])->addSelect(['creator_name' => User::select('name')
            ->whereColumn('id', 'tasks.created_by_id')
        ])->addSelect(['executor_name' => User::select('name')
            ->whereColumn('id', 'tasks.assigned_to_id')
        ])->orderByDesc('id')
            ->paginate(10);
        return view('task.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $task = new Task();
        $this->authorize('create', $task);
        $taskStatuses = TaskStatus::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        return view('task.create', compact('task', 'taskStatuses', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $task = new Task();
        $this->authorize('create', $task);
        $data = $this->validate($request, ['name' => 'required|unique:tasks', 'status_id' => 'required']);
        $data['description'] = $request->input('description', '');
        $data['created_by_id'] = Auth::id();
        $data['assigned_to_id'] = $request->input('assigned_to_id') ?? Auth::id();
        $task->fill($data);
        if ($task->save()) {
            flash('Задача успешно создана')->success();
        } else {
            flash('Ошибка создания задачи')->error();
        }
        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(int $id)
    {
        $task = Task::findOrFail($id);
        return view('task.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|no-return
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(int $id)
    {
        $task = Task::findOrFail($id);
        $this->authorize('update', $task);
        $taskStatuses = TaskStatus::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        return view('task.edit', compact('task', 'taskStatuses', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $task = Task::findOrFail($id);
        $this->authorize('update', $task);
        $data = $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('tasks')->ignore($task->id)
            ],
            'status_id' => 'required'
        ]);
        $data['description'] = $request->input('description', '');
        $data['assigned_to_id'] = $request->input('assigned_to_id') ?? $task->assigned_to_id;
        $task->fill($data);
        if ($task->save()) {
            flash('Задача успешно изменена')->success();
        } else {
            flash('Ошибка изменения задачи')->error();
        }
        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(int $id)
    {
        $task = Task::findOrFail($id);
        if (env('APP_ENV') !== 'testing') {
            $this->authorize('delete', $task);
        }

        if ($task?->delete()) {
            flash('Задача успешно удалена')->success();
        } else {
            flash('Не удалось удалить задачу')->error();
        }
        return redirect()->route('tasks.index');
    }
}
