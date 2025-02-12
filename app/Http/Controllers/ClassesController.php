<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;
use App\Http\Resources\ClassesResource;
use App\Http\Requests\StoreClassRequest;
use App\Http\Requests\UpdateClassRequest;

class ClassesController extends Controller
{
    public function index(Request $request)
    {
        $classesQuery = Classes::query();

        $this->applySearch($classesQuery, $request->search);

        $classes = ClassesResource::collection(
            $classesQuery->paginate(10)
        );

        return inertia('Classes/Index', [
            'classes' => $classes,
            'search' => $request->search ?? ''
        ]);
    }

    protected function applySearch($query, $search)
    {
        return $query->when($search, function ($query, $search) {
            $query->where('name', 'like', '%'. $search .'%');
        });
    }
    public function create()
    {
        return inertia('Classes/Create');
    }

    public function store(StoreClassRequest $request)
    {
        Classes::create($request->validated());

        return redirect()->route('classes.index');
    }

    public function edit(Classes $class)
    {
        return inertia('Classes/Edit', [
            'class' => ClassesResource::make($class)
        ]);
    }

    public function update(UpdateClassRequest $request, Classes $class)
    {
        $class->update($request->validated());

        return redirect()->route('classes.index');
    }

    public function destroy(Classes $class)
    {
        if ($class->students->count() == 0) {
            $class->delete();
            return redirect()->route('classes.index')->with('success', 'Classe eliminata con successo!');
        }

        return redirect()->route('classes.index')->with('error', 'Ci sono studenti assegnati alla classe, non e\' possibile eliminarla!');
    }
}
