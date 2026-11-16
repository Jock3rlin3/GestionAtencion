<?php

namespace App\Http\Controllers;

use App\Http\Requests\TratamientoRequest;
use App\Models\Tratamiento;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TratamientoController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'buscar' => ['nullable', 'string', 'max:120'],
            'orden' => ['nullable', 'in:nombre,precio,duracion_minutos,creado_en'],
            'direccion' => ['nullable', 'in:asc,desc'],
        ]);

        $buscar = $filters['buscar'] ?? '';
        $orden = $filters['orden'] ?? 'nombre';
        $direccion = $filters['direccion'] ?? 'asc';

        $tratamientos = Tratamiento::query()
            ->withCount('consultas')
            ->when($buscar !== '', fn (Builder $query) => $query->where(function (Builder $query) use ($buscar) {
                $query->where('nombre', 'like', "%{$buscar}%")
                    ->orWhere('descripcion', 'like', "%{$buscar}%");
            }))
            ->orderBy($orden, $direccion)
            ->paginate(10)
            ->withQueryString();

        return view('tratamientos.index', compact('tratamientos', 'buscar', 'orden', 'direccion'));
    }

    public function create(): View
    {
        return view('tratamientos.create');
    }

    public function store(TratamientoRequest $request): RedirectResponse
    {
        Tratamiento::create($request->validated());

        return to_route('tratamientos.index')->with('success', 'Tratamiento creado correctamente.');
    }

    public function edit(Tratamiento $tratamiento): View
    {
        return view('tratamientos.edit', compact('tratamiento'));
    }

    public function update(TratamientoRequest $request, Tratamiento $tratamiento): RedirectResponse
    {
        $tratamiento->update($request->validated());

        return to_route('tratamientos.index')->with('success', 'Tratamiento actualizado correctamente.');
    }

    public function destroy(Tratamiento $tratamiento): RedirectResponse
    {
        if ($tratamiento->consultas()->exists()) {
            return to_route('tratamientos.index')
                ->with('error', 'No se puede eliminar este tratamiento porque tiene consultas asociadas.');
        }

        $tratamiento->delete();

        return to_route('tratamientos.index')->with('success', 'Tratamiento eliminado correctamente.');
    }
}
