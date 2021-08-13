<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LabelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $labels = Label::orderBy('id')->paginate(10);
        return view('label.index', compact('labels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function create()
    {
        $label = new Label();
        $this->authorize('create', $label);
        return view('label.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException|ValidationException
     */
    public function store(Request $request)
    {
        $label = new Label();
        $this->authorize('create', $label);
        $data = $this->validate($request, ['name' => [
            'required',
            function ($attribute, $value, $fail) {
                if (Label::where($attribute, $value)->first() !== null) {
                    $fail('Метка с таким именем уже существует');
                }
            }
        ]]);
        $data['description'] = $request->input('description', '');
        $label->fill($data);
        if ($label->save()) {
            flash('Метка успешно создана')->success();
        } else {
            flash('Ошибка создания метки')->error();
        }
        return redirect()->route('labels.index');
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
        $label = Label::findOrFail($id);
        $this->authorize('update', $label);
        return view('label.edit', compact('label'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function update(Request $request, int $id)
    {
        $label = Label::findOrFail($id);
        $this->authorize('update', $label);
        $data = $this->validate($request, ['name' => [
            'required',
            function ($attribute, $value, $fail) use ($label) {
                if ((Label::where($attribute, $value)->first() !== null) && ($value !== $label->name)) {
                    $fail('Метка с таким именем уже существует');
                }
            }
            ]]);
        $data['description'] = $request->input('description', '');
        $label->fill($data);
        if ($label->save()) {
            flash('Метка успешно изменена')->success();
        } else {
            flash('Ошибка изменения метки')->error();
        }
        return redirect()->route('labels.index');
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
        $label = Label::findOrFail($id);
        $this->authorize('delete', $label);
        try {
            $label->delete();
            flash('Метка успешно удалена')->success();
        } catch (\Exception $e) {
            flash('Не удалось удалить метку')->error();
        } finally {
            return redirect()->route('labels.index');
        }
    }
}
