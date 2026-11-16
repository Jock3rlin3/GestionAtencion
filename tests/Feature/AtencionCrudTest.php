<?php

namespace Tests\Feature;

use App\Models\ConsultaAtencion;
use App\Models\Tratamiento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AtencionCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_business_tables_use_spanish_timestamps_and_unused_framework_tables_are_removed(): void
    {
        foreach (['tratamientos', 'consultas_atencion'] as $table) {
            $this->assertTrue(Schema::hasColumn($table, 'creado_en'));
            $this->assertTrue(Schema::hasColumn($table, 'actualizado_en'));
            $this->assertFalse(Schema::hasColumn($table, 'created_at'));
            $this->assertFalse(Schema::hasColumn($table, 'updated_at'));
        }

        foreach (['sessions', 'password_reset_tokens', 'cache', 'cache_locks', 'jobs', 'job_batches', 'failed_jobs'] as $table) {
            $this->assertFalse(Schema::hasTable($table));
        }

        $this->assertTrue(Schema::hasTable('users'));
    }

    public function test_tratamientos_can_be_created_searched_updated_and_deleted(): void
    {
        $response = $this->post(route('tratamientos.store'), [
            'nombre' => 'Terapia manual',
            'descripcion' => 'Atención integral',
            'duracion_minutos' => 45,
            'precio' => 1250.50,
        ]);

        $response->assertRedirect(route('tratamientos.index'));
        $tratamiento = Tratamiento::query()->firstOrFail();
        Tratamiento::create([
            'nombre' => 'Masaje terapéutico',
            'duracion_minutos' => 60,
            'precio' => 2500,
        ]);

        $this->get(route('tratamientos.index', ['buscar' => 'manual']))
            ->assertOk()
            ->assertSee('Terapia manual');
        $this->get(route('tratamientos.index', ['orden' => 'precio', 'direccion' => 'desc']))
            ->assertOk()
            ->assertSeeInOrder(['Masaje terapéutico', 'Terapia manual'])
            ->assertSee('$1.250,50');

        $this->put(route('tratamientos.update', $tratamiento), [
            'nombre' => 'Terapia actualizada',
            'descripcion' => 'Nueva descripción',
            'duracion_minutos' => 60,
            'precio' => 1500,
        ])->assertRedirect(route('tratamientos.index'));

        $this->assertDatabaseHas('tratamientos', ['id' => $tratamiento->id, 'nombre' => 'Terapia actualizada']);

        $this->delete(route('tratamientos.destroy', $tratamiento))
            ->assertRedirect(route('tratamientos.index'));

        $this->assertDatabaseMissing('tratamientos', ['id' => $tratamiento->id]);
    }

    public function test_consultas_are_related_to_treatments_and_support_full_crud(): void
    {
        $tratamiento = Tratamiento::create([
            'nombre' => 'Evaluación inicial',
            'descripcion' => null,
            'duracion_minutos' => 30,
            'precio' => 800,
        ]);

        $this->post(route('consultas.store'), [
            'tratamiento_id' => $tratamiento->id,
            'paciente' => 'María Pérez',
            'fecha_atencion' => '2026-11-16T10:30',
            'motivo' => 'Primera consulta',
            'observaciones' => '',
            'estado' => 'pendiente',
        ])->assertRedirect(route('consultas.index'));

        $consulta = ConsultaAtencion::query()->firstOrFail();
        $this->assertSame($tratamiento->id, $consulta->tratamiento->id);
        $this->assertDatabaseHas('consultas_atencion', [
            'id' => $consulta->id,
            'tratamiento_id' => $tratamiento->id,
            'paciente' => 'María Pérez',
        ]);

        $this->get(route('consultas.index', ['buscar' => 'Evaluación']))
            ->assertOk()
            ->assertSee('María Pérez');

        $this->put(route('consultas.update', $consulta), [
            'tratamiento_id' => $tratamiento->id,
            'paciente' => 'María P.',
            'fecha_atencion' => '2026-11-17T11:00',
            'motivo' => 'Seguimiento',
            'observaciones' => 'Control',
            'estado' => 'atendida',
        ])->assertRedirect(route('consultas.index'));

        $this->assertDatabaseHas('consultas_atencion', [
            'id' => $consulta->id,
            'paciente' => 'María P.',
            'estado' => 'atendida',
        ]);

        $this->delete(route('consultas.destroy', $consulta))
            ->assertRedirect(route('consultas.index'));
        $this->assertDatabaseMissing('consultas_atencion', ['id' => $consulta->id]);
    }

    public function test_treatment_deletion_is_blocked_while_consultations_reference_it(): void
    {
        $tratamiento = Tratamiento::create([
            'nombre' => 'Tratamiento vinculado',
            'duracion_minutos' => 20,
            'precio' => 500,
        ]);
        ConsultaAtencion::create([
            'tratamiento_id' => $tratamiento->id,
            'paciente' => 'Paciente',
            'fecha_atencion' => '2026-11-16 10:30:00',
            'motivo' => 'Consulta de prueba',
            'estado' => 'pendiente',
        ]);

        $this->delete(route('tratamientos.destroy', $tratamiento))
            ->assertRedirect(route('tratamientos.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('tratamientos', ['id' => $tratamiento->id]);
    }

    public function test_treatment_form_request_rejects_invalid_data(): void
    {
        $this->from(route('tratamientos.create'))
            ->post(route('tratamientos.store'), [
                'nombre' => '',
                'duracion_minutos' => 0,
                'precio' => -5,
            ])
            ->assertRedirect(route('tratamientos.create'))
            ->assertSessionHasErrors(['nombre', 'duracion_minutos', 'precio']);
    }
}
