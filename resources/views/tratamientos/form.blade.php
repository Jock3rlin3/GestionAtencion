<form action="{{ isset($tratamiento) ? route('tratamientos.update', $tratamiento) : route('tratamientos.store') }}" method="POST">
    @csrf
    @isset($tratamiento)
        @method('PUT')
    @endisset

    <div class="row g-3">
        <div class="col-md-8">
            <label for="nombre" class="form-label fw-semibold">Nombre <span class="required-mark">*</span></label>
            <input id="nombre" name="nombre" value="{{ old('nombre', $tratamiento->nombre ?? '') }}" class="form-control @error('nombre') is-invalid @enderror" maxlength="120" required autofocus>
            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label for="duracion_minutos" class="form-label fw-semibold">Duracion (minutos) <span class="required-mark">*</span></label>
            <input id="duracion_minutos" name="duracion_minutos" type="number" min="1" max="1440" value="{{ old('duracion_minutos', $tratamiento->duracion_minutos ?? '') }}" class="form-control @error('duracion_minutos') is-invalid @enderror" required>
            @error('duracion_minutos')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label for="precio" class="form-label fw-semibold">Precio <span class="required-mark">*</span></label>
            <div class="input-group">
                <span class="input-group-text">$</span>
                <input id="precio" name="precio" type="number" min="0" step="0.01" value="{{ old('precio', $tratamiento->precio ?? '') }}" class="form-control @error('precio') is-invalid @enderror" required>
                @error('precio')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="col-12">
            <label for="descripcion" class="form-label fw-semibold">Descripcion</label>
            <textarea id="descripcion" name="descripcion" rows="4" maxlength="2000" class="form-control @error('descripcion') is-invalid @enderror" placeholder="Describe brevemente el tratamiento">{{ old('descripcion', $tratamiento->descripcion ?? '') }}</textarea>
            @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="d-flex flex-wrap gap-2 mt-4">
        <button type="submit" class="btn btn-primary px-4">{{ isset($tratamiento) ? 'Guardar cambios' : 'Crear tratamiento' }}</button>
        <a href="{{ route('tratamientos.index') }}" class="btn btn-light px-4">Cancelar</a>
    </div>
</form>
