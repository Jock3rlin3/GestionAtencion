<form action="{{ isset($consulta) ? route('consultas.update', $consulta) : route('consultas.store') }}" method="POST">
    @csrf
    @isset($consulta)
        @method('PUT')
    @endisset

    <div class="row g-3">
        <div class="col-md-6">
            <label for="paciente" class="form-label fw-semibold">Paciente <span class="required-mark">*</span></label>
            <input id="paciente" name="paciente" value="{{ old('paciente', $consulta->paciente ?? '') }}" class="form-control @error('paciente') is-invalid @enderror" maxlength="120" required autofocus>
            @error('paciente')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label for="tratamiento_id" class="form-label fw-semibold">Tratamiento <span class="required-mark">*</span></label>
            <select id="tratamiento_id" name="tratamiento_id" class="form-select @error('tratamiento_id') is-invalid @enderror" required>
                <option value="">Selecciona un tratamiento</option>
                @foreach ($tratamientos as $tratamiento)
                    <option value="{{ $tratamiento->id }}" @selected(old('tratamiento_id', $consulta->tratamiento_id ?? '') == $tratamiento->id)>
                        {{ $tratamiento->nombre }} · {{ $tratamiento->duracion_minutos }} min · ${{ number_format((float) $tratamiento->precio, 2, ',', '.') }}
                    </option>
                @endforeach
            </select>
            @error('tratamiento_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            @if ($tratamientos->isEmpty())
                <div class="form-text text-danger">Primero debes <a href="{{ route('tratamientos.create') }}">crear un tratamiento</a>.</div>
            @endif
        </div>
        <div class="col-md-6">
            <label for="fecha_atencion" class="form-label fw-semibold">Fecha y hora <span class="required-mark">*</span></label>
            <input id="fecha_atencion" name="fecha_atencion" type="datetime-local" value="{{ old('fecha_atencion', isset($consulta) ? $consulta->fecha_atencion->format('Y-m-d\TH:i') : '') }}" class="form-control @error('fecha_atencion') is-invalid @enderror" required>
            @error('fecha_atencion')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label for="estado" class="form-label fw-semibold">Estado <span class="required-mark">*</span></label>
            <select id="estado" name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                @foreach (['pendiente' => 'Pendiente', 'atendida' => 'Atendida', 'cancelada' => 'Cancelada'] as $valor => $etiqueta)
                    <option value="{{ $valor }}" @selected(old('estado', $consulta->estado ?? 'pendiente') === $valor)>{{ $etiqueta }}</option>
                @endforeach
            </select>
            @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label for="motivo" class="form-label fw-semibold">Motivo de consulta <span class="required-mark">*</span></label>
            <input id="motivo" name="motivo" value="{{ old('motivo', $consulta->motivo ?? '') }}" class="form-control @error('motivo') is-invalid @enderror" maxlength="255" required>
            @error('motivo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label for="observaciones" class="form-label fw-semibold">Observaciones</label>
            <textarea id="observaciones" name="observaciones" rows="4" maxlength="2000" class="form-control @error('observaciones') is-invalid @enderror" placeholder="Notas adicionales de la atencion">{{ old('observaciones', $consulta->observaciones ?? '') }}</textarea>
            @error('observaciones')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="d-flex flex-wrap gap-2 mt-4">
        <button type="submit" class="btn btn-primary px-4" @disabled($tratamientos->isEmpty())>{{ isset($consulta) ? 'Guardar cambios' : 'Crear consulta' }}</button>
        <a href="{{ route('consultas.index') }}" class="btn btn-light px-4">Cancelar</a>
    </div>
</form>
