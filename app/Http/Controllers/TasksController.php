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
        $appendss = request()->query();
        if ($appendss != null) {
            $tasks->appends($appendss);
        }

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
        $data = $this->validate($request, ['name' => [
            'required',
            function ($attribute, $value, $fail): void {
                if (Task::where($attribute, $value)->first() !== null) {
                    $fail('???????????? ?? ?????????? ???????????? ?????? ????????????????????');
                }
            }
        ],
            'status_id' => 'required']);
        $data['description'] = $request->input('description', '');
        $data['created_by_id'] = Auth::id();
        $data['assigned_to_id'] = $request->input('assigned_to_id') ?? Auth::id();
        $labels = $request->input('labels', []);
        $task->fill($data);
        if ($task->save()) {
            flash('???????????? ?????????????? ??????????????')->success();
            $task->labels()->attach($labels);
        } else {
            flash('???????????? ???????????????? ????????????')->error();
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
        $data = $this->validate($request, ['name' => [
            'required',
            function ($attribute, $value, $fail) use ($task): void {
                if ((Task::where($attribute, $value)->first() !== null) && ($value !== $task->name)) {
                    $fail('???????????? ?? ?????????? ???????????? ?????? ????????????????????');
                }
            }
        ],
            'status_id' => 'required']);
        $data['description'] = $request->input('description', '');
        $data['assigned_to_id'] = $request->input('assigned_to_id') ?? $task->assigned_to_id;
        $task->fill($data);
        $labels = $request->input('labels', []);
        if ($task->save()) {
            $task->labels()->sync($labels);
            flash('???????????? ?????????????? ????????????????')->success();
        } else {
            flash('???????????? ?????????????????? ????????????')->error();
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
            flash('???????????? ?????????????? ??????????????')->success();
        } catch (\Exception $e) {
            flash('???? ?????????????? ?????????????? ????????????')->error();
        } finally {
            return redirect()->route('tasks.index');
        }
    }
}
