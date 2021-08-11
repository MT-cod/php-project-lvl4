<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters([
                AllowedFilter::exact('status_id'),
                AllowedFilter::exact('created_by_id'),
                AllowedFilter::exact('assigned_to_id')
            ])
            ->paginate(10);
        $taskStatuses = TaskStatus::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        return view('task.index', compact('tasks', 'taskStatuses', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function create()
    {
        $task = new Task();
        $this->authorize('create', $task);
        $taskStatuses = TaskStatus::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $labels = Label::orderBy('name')->get();
        return view('task.create', compact('task', 'taskStatuses', 'users', 'labels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $task = new Task();
        $this->authorize('create', $task);
        $data = $this->validate($request, ['name' => 'required|unique:tasks', 'status_id' => 'required']);
        $data['description'] = $request->input('description', '');
        $data['created_by_id'] = Auth::id();
        $data['assigned_to_id'] = $request->input('assigned_to_id') ?? Auth::id();
        $labels = $request->input('labels', []);
        $task->fill($data);
        if ($task->save()) {
            flash('Задача успешно создана')->success();
            $task->labels()->attach($labels);
        } else {
            flash('Ошибка создания задачи')->error();
        }
        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function show(int $id)
    {
        $task = Task::findOrFail($id);
        $labels = $task->labels()->get();
        return view('task.show', compact('task', 'labels'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function edit(int $id)
    {
        $task = Task::findOrFail($id);
        $this->authorize('update', $task);
        $taskStatuses = TaskStatus::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $labels = Label::orderBy('name')->get();
        return view('task.edit', compact('task', 'taskStatuses', 'users', 'labels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     * @throws AuthorizationException|ValidationException
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
        $labels = $request->input('labels', []);
        if ($task->save()) {
            $task->labels()->sync($labels);
            flash('Задача успешно изменена')->success();
        } else {
            flash('Ошибка изменения задачи')->error();
        }
        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(int $id)
    {
        $task = Task::findOrFail($id);
        if (env('APP_ENV') !== 'testing') {
            $this->authorize('delete', $task);
        }
        try {
            $task->delete();
            flash('Задача успешно удалена')->success();
        } catch (\Exception $e) {
            flash('Не удалось удалить задачу')->error();
        } finally {
            return redirect()->route('tasks.index');
        }
    }
}
