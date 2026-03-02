<?php

namespace App\Http\Controllers\Dependency;

use App\Http\Controllers\Controller;
use App\Models\Dependency\DependencySubunit;
use App\Models\Dependency\DependencyUnit;
use Illuminate\Http\Request;

class DependencyController extends Controller
{
    public function index()
    {
        $dependencies = DependencyUnit::with('subunits')->get();

        return view('dependencies.index', compact('dependencies'));
    }

    public function create()
    {
        return view('dependencies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'short_name' => 'required|string|max:50',
            'full_name'  => 'required|string|max:255',
            'description' => 'nullable|string',
            'subunits.*.subunit_code' => 'required|string|max:50',
            'subunits.*.name' => 'required|string|max:255',
            'subunits.*.description' => 'nullable|string'
        ]);

        $dependency = DependencyUnit::create(
            $request->only('short_name', 'full_name', 'description')
        );

        if ($request->has('subunits')) {
            foreach ($request->subunits as $subunit) {
                $dependency->subunits()->create($subunit);
            }
        }

        return redirect()
            ->route('dependencies.index')
            ->with('success', 'Dependencia creada correctamente');
    }

    public function show(DependencyUnit $dependency)
    {
        $dependency->load('subunits');

        return view('dependencies.show', compact('dependency'));
    }

    public function edit(DependencyUnit $dependency)
    {
        return view('dependencies.edit', compact('dependency'));
    }

    public function update(Request $request, DependencyUnit $dependency)
    {
        $request->validate([
            'short_name' => 'required|string|max:50',
            'full_name'  => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $dependency->update($request->all());

        return redirect()
            ->route('dependencies.index')
            ->with('success', 'Dependencia actualizada correctamente');
    }

    public function destroy(DependencyUnit $dependency)
    {
        $dependency->delete();

        return redirect()
            ->route('dependencies.index')
            ->with('success', 'Dependencia eliminada correctamente');
    }

    public function reorder(Request $request)
    {
        foreach ($request->order as $item) {
            DependencySubunit::where('id', $item['id'])
                ->update(['position' => $item['position']]);
        }

        return response()->json(['success' => true]);
    }

    /* ===============================
       SUBDEPENDENCIAS
    =============================== */

    public function storeSubunit(Request $request, DependencyUnit $dependency)
    {
        $request->validate([
            'subunit_code' => 'required|string|max:50',
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string'
        ]);

        $dependency->subunits()->create($request->all());

        return back()->with('success', 'Subdependencia creada');
    }

    public function destroySubunit(DependencySubunit $subunit)
    {
        $subunit->delete();

        return back()->with('success', 'Subdependencia eliminada');
    }
}
