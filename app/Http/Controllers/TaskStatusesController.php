<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TaskStatusesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $taskStatuses = TaskStatus::orderBy('id')->paginate(10);
        return view('task_status.index', compact('taskStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     * @throws AuthorizationException
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
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException|AuthorizationException
     */
    public function store(Request $request)
    {
        $taskStatus = new TaskStatus();
        $this->authorize('create', $taskStatus);
        $data = $this->validate($request, ['name' => [
            'required',
            function ($attribute, $value, $fail) {
                if (TaskStatus::where($attribute, $value)->first() !== null) {
                    $fail('Статус с таким именем уже существует');
                }
            }
            ]]);
        $taskStatus->fill($data);
        if ($taskStatus->save()) {
            flash('Статус успешно создан')->success();
        } else {
            flash('Ошибка создания статуса')->error();
        }
        return redirect()->route('task_statuses.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function show(int $id)
    {
        return redirect()->route('task_statuses.index');
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
        $taskStatus = TaskStatus::findOrFail($id);
        $this->authorize('update', $taskStatus);
        return view('task_status.edit', compact('taskStatus'));
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
        $taskStatus = TaskStatus::findOrFail($id);
        $this->authorize('update', $taskStatus);
        $data = $this->validate($request, ['name' => [
            'required',
            function ($attribute, $value, $fail) use ($taskStatus) {
                if ((TaskStatus::where($attribute, $value)->first() !== null) && ($value !== $taskStatus->name)) {
                    $fail('Статус с таким именем уже существует');
                }
            }
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
     * @param int $id
     * @return RedirectResponse
     * @throws AuthorizationException
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
