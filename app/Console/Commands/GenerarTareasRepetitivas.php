<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;
use App\Notifications\TareaAsignadaNotification;

class GenerarTareasRepetitivas extends Command
{
    protected $signature = 'app:generar-tareas-repetitivas';
    protected $description = 'Activa tareas repetitivas según la configuración definida (diarias, semanales y mensuales)';

    public function handle()
    {
        $hoy = Carbon::today();

        $tareas = DB::table('tareas')
            ->whereNotNull('repeticion_tipo')
            ->where('es_activa', 0) //  solo buscamos las inactivas
            ->get();

        foreach ($tareas as $tarea) {
            // Validar límite de repetición
            if ($tarea->repeticion_hasta && Carbon::parse($tarea->repeticion_hasta)->lt($hoy)) {
                continue;
            }

            $activar = false;
            $fechaVencimiento = null;
            $anticipacion = intval($tarea->repeticion_anticipacion ?? 0);

            // ===============================
            //  LÓGICA DE REPETICIÓN
            // ===============================

            // Diario → siempre se activa hoy
            if ($tarea->repeticion_tipo === 'diario') {
                $activar = true;
                $fechaVencimiento = $hoy->copy();
            }

            // Semanal
            if ($tarea->repeticion_tipo === 'semanal' && $tarea->repeticion_dia) {
                $dias = [
                    'domingo' => 0, 'lunes' => 1, 'martes' => 2,
                    'miercoles' => 3, 'jueves' => 4, 'viernes' => 5, 'sabado' => 6
                ];

                $diaSemana = strtolower($tarea->repeticion_dia);
                if (!isset($dias[$diaSemana])) continue;

                $fechaObjetivo = Carbon::now()->next($dias[$diaSemana]);
                if ($hoy->dayOfWeek === $dias[$diaSemana]) {
                    $fechaObjetivo = $hoy->copy();
                }

                $fechaDisponible = $fechaObjetivo->copy()->subDays($anticipacion);

                if ($hoy->equalTo($fechaDisponible)) {
                    $activar = true;
                    $fechaVencimiento = $fechaObjetivo;
                }
            }

            // Mensual
            if ($tarea->repeticion_tipo === 'mensual' && $tarea->repeticion_dia_mes) {
                $diaMes = intval($tarea->repeticion_dia_mes);
                $fechaObjetivo = Carbon::create($hoy->year, $hoy->month, $diaMes, 0, 0, 0);

                if ($fechaObjetivo->lt($hoy)) {
                    $fechaObjetivo->addMonth();
                }

                $fechaDisponible = $fechaObjetivo->copy()->subDays($anticipacion);

                if ($hoy->equalTo($fechaDisponible)) {
                    $activar = true;
                    $fechaVencimiento = $fechaObjetivo;
                }
            }

            // ===============================
            // ACTIVAR LA TAREA
            // ===============================
            if ($activar) {
                DB::table('tareas')
                    ->where('id', $tarea->id)
                    ->update([
                        'es_activa' => 1,
                        'fecha_vencimiento' => $fechaVencimiento,
                        'updated_at' => now(),
                    ]);

                \Log::info("Activada tarea repetitiva: {$tarea->nombre_tarea} para fecha {$fechaVencimiento}");

                // Notificar usuarios asignados
                $usuariosAsignados = DB::table('tareas_usuarios')
                    ->where('tarea_id', $tarea->id)
                    ->pluck('usuario_id');

                $usuario_asignador = DB::table('usuarios')->where('id', $tarea->creado_por)->first();

                foreach ($usuariosAsignados as $usuarioId) {
                    $usuario = Usuario::find($usuarioId);
                    if ($usuario && $usuario_asignador) {
                        $usuario->notify(new TareaAsignadaNotification(
                            $tarea->id,
                            $usuario_asignador->nombre,
                            $usuario_asignador->apellido_p,
                            $tarea->nombre_tarea,
                            $usuario_asignador->foto_perfil
                        ));
                    }
                }

            }
        }

        $this->info('✅ Tareas repetitivas revisadas y activadas si correspondía.');
        \Log::info('GenerarTareasRepetitivas ejecutado: ' . now());
    }
}
