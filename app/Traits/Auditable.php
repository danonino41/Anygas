<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            static::registrar('created', $model, [], $model->toArray());
        });

        static::updated(function ($model) {
            $cambios = $model->getDirty();
            $viejos = [];
            foreach ($cambios as $attr => $nuevo) {
                $viejos[$attr] = $model->getOriginal($attr);
            }
            if (!empty($viejos)) {
                static::registrar('updated', $model, $viejos, $cambios);
            }
        });

        static::deleted(function ($model) {
            static::registrar('deleted', $model, $model->toArray(), []);
        });
    }

    protected static function registrar(string $accion, $model, array $viejos, array $nuevos)
    {
        $user = Auth::user();
        AuditLog::create([
            'usuario_id' => $user?->id,
            'usuario_nombre' => $user ? ($user->nombre_completo ?? $user->correo) : 'Sistema',
            'accion' => $accion,
            'modelo' => get_class($model),
            'modelo_id' => $model->id,
            'datos_viejos' => !empty($viejos) ? json_encode($viejos) : null,
            'datos_nuevos' => !empty($nuevos) ? json_encode($nuevos) : null,
            'ip' => request()->ip(),
        ]);
    }
}
