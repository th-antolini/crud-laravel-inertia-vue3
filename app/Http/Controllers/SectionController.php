<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Http\Resources\ClassesResource;
use App\Http\Resources\SectionResource;
use App\Http\Requests\StoreSectionRequest;
use App\Http\Requests\UpdateSectionRequest;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $sectionsQuery = Section::query();

        $this->applySearch($sectionsQuery, $request->search);

        $sections = SectionResource::collection(
            $sectionsQuery->paginate(10)
        );

        return inertia('Sections/Index', [
            'sections' => $sections,
            'search' => $request->search ?? ''
        ]);
    }

    protected function applySearch($query, $search)
    {
        return $query->when($search, function ($query, $search) {
            $query->where('name', 'like', '%'. $search .'%')
            ->orWhereHas('class', function ($query) use ($search) {
                $query->where('name', 'like', '%'. $search .'%');
            });
        });
    }
    public function create()
    {
        $classes = ClassesResource::collection(Classes::all());

        return inertia('Sections/Create', [
            'classes' => $classes
        ]);
    }

    public function store(StoreSectionRequest $request)
    {
        Section::create($request->validated());

        return redirect()->route('sections.index');
    }

    public function edit(Section $section)
    {
        return inertia('Sections/Edit', [
            'section' => SectionResource::make($section)
        ]);
    }

    public function update(UpdateSectionRequest $request, Section $section)
    {
        $section->update($request->validated());

        return redirect()->route('sections.index');
    }

    public function destroy(Section $section)
    {
        if ($section->students->count() == 0) {
            $section->delete();
            return redirect()->route('sections.index')->with('success', 'Sezione eliminata con successo!');
        }

        return redirect()->route('sections.index')->with('error', 'Ci sono studenti assegnati alla sezione, non e\' possibile eliminarla!');
    }
}
