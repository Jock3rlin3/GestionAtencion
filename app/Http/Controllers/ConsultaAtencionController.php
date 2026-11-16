<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsultaAtencionRequest;
use App\Models\ConsultaAtencion;
use App\Models\Tratamiento;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConsultaAtencionController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'buscar' => ['nullable', 'string', 'max:120'],
            'orden' => ['nullable', 'in:paciente,fecha_atencion,estado'],
            'direccion' => ['nullable', 'in:asc,desc'],
        ]);

        $buscar = $filters['buscar'] ?? '';
        $orden = $filters['orden'] ?? 'fecha_atencion';
        $direccion = $filters['direccion'] ?? 'desc';

        $consultas = ConsultaAtencion::query()
            ->with('tratamiento')
            ->when($buscar !== '', fn (Builder $query) => $query->where(function (Builder $query) use ($buscar) {
                $query->where('paciente', 'like', "%{$buscar}%")
                    ->orWhere('motivo', 'like', "%{$buscar}%")
                    ->orWhereHas('tratamiento', fn (Builder $tratamientos) => $tratamientos->where('nombre', 'like', "%{$buscar}%"));
            }))
            ->orderBy($orden, $direccion)
            ->paginate(10)
            ->withQueryString();

        return view('consultas.index', compact('consultas', 'buscar', 'orden', 'direccion'));
    }

    public function create(): View
    {
        $tratamientos = Tratamiento::query()->orderBy('nombre')->get();

        return view('consultas.create', compact('tratamientos'));
    }

    public function store(ConsultaAtencionRequest $request): RedirectResponse
    {
        ConsultaAtencion::create($request->validated());

        return to_route('consultas.index')->with('success', 'Consulta de atencion creada correctamente.');
    }

    public function edit(ConsultaAtencion $consulta): View
    {
        $tratamientos = Tratamiento::query()->orderBy('nombre')->get();

        return view('consultas.edit', compact('consulta', 'tratamientos'));
    }

    public function update(ConsultaAtencionRequest $request, ConsultaAtencion $consulta): RedirectResponse
    {
        $consulta->update($request->validated());

        return to_route('consultas.index')->with('success', 'Consulta de atencion actualizada correctamente.');
    }

    public function destroy(ConsultaAtencion $consulta): RedirectResponse
    {
        $consulta->delete();

        return to_route('consultas.index')->with('success', 'Consulta de atencion eliminada correctamente.');
    }
}
