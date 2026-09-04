<?php

namespace App\Http\Modules\Ventas\Services;

use App\Http\Modules\Productos\Models\Productos;
use App\Http\Modules\Ventas\Models\Venta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class VentaService
{
    public function registrarVenta(array $data, ?int $usuarioId = null): Venta
    {
        $data = $this->normalizarDatosFormulario($data);

        $validator = Validator::make($data, [
            'cliente_nombre' => ['nullable', 'string', 'max:255'],
            'cliente_documento' => ['nullable', 'string', 'max:80'],
            'cliente_telefono' => ['nullable', 'string', 'max:80'],
            'metodo_pago' => ['required', 'string', 'max:80'],
            'descuento' => ['nullable', 'numeric', 'min:0'],
            'observaciones' => ['nullable', 'string', 'max:1000'],
            'productos' => ['required', 'array', 'min:1'],
            'productos.*.producto_id' => ['required', 'exists:productos,id'],
            'productos.*.cantidad' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $datosValidados = $validator->validated();

        return DB::transaction(function () use ($datosValidados, $usuarioId) {
            $subtotalVenta = 0;
            $detalles = [];

            foreach ($datosValidados['productos'] as $item) {
                $producto = Productos::whereKey($item['producto_id'])->lockForUpdate()->firstOrFail();
                $cantidad = (int) $item['cantidad'];
                $stockDisponible = (int) ($producto->stock ?? 0);

                if (! $producto->estado) {
                    throw ValidationException::withMessages([
                        'productos' => ["El producto {$producto->nombre} no está activo."],
                    ]);
                }

                if ($stockDisponible <= 0) {
                    throw ValidationException::withMessages([
                        'productos' => ["El producto {$producto->nombre} está agotado."],
                    ]);
                }

                if ($cantidad > $stockDisponible) {
                    throw ValidationException::withMessages([
                        'productos' => ["Solo hay {$stockDisponible} unidades disponibles de {$producto->nombre}."],
                    ]);
                }

                $precioUnitario = (float) ($producto->precio ?? 0);
                $subtotalDetalle = $precioUnitario * $cantidad;
                $subtotalVenta += $subtotalDetalle;

                $producto->decrement('stock', $cantidad);

                $detalles[] = [
                    'producto_id' => $producto->id,
                    'producto_nombre' => $producto->nombre,
                    'producto_unidad_medida' => $producto->unidad_medida,
                    'precio_unitario' => $precioUnitario,
                    'cantidad' => $cantidad,
                    'subtotal' => $subtotalDetalle,
                ];
            }

            $descuento = min((float) ($datosValidados['descuento'] ?? 0), $subtotalVenta);

            $venta = Venta::create([
                'codigo' => $this->generarCodigoVenta(),
                'cliente_nombre' => $datosValidados['cliente_nombre'] ?? null,
                'cliente_documento' => $datosValidados['cliente_documento'] ?? null,
                'cliente_telefono' => $datosValidados['cliente_telefono'] ?? null,
                'metodo_pago' => $datosValidados['metodo_pago'],
                'subtotal' => $subtotalVenta,
                'descuento' => $descuento,
                'total' => $subtotalVenta - $descuento,
                'observaciones' => $datosValidados['observaciones'] ?? null,
                'estado' => 'registrada',
                'usuario_id' => $usuarioId,
            ]);

            $venta->detalles()->createMany($detalles);

            return $venta->fresh(['detalles', 'usuario:id,nombre,apellido,email']);
        });
    }

    private function generarCodigoVenta(): string
    {
        do {
            $codigo = 'VEN-' . now()->format('Ymd-His') . '-' . Str::upper(Str::random(4));
        } while (Venta::where('codigo', $codigo)->exists());

        return $codigo;
    }

    private function normalizarDatosFormulario(array $data): array
    {
        foreach (['cliente_nombre', 'cliente_documento', 'cliente_telefono', 'observaciones'] as $campo) {
            if (array_key_exists($campo, $data) && $data[$campo] === '') {
                $data[$campo] = null;
            }
        }

        if (array_key_exists('descuento', $data) && ($data['descuento'] === '' || $data['descuento'] === null)) {
            $data['descuento'] = 0;
        }

        return $data;
    }
}
