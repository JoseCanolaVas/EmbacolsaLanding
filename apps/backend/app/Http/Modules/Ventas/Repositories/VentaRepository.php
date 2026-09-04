<?php

namespace App\Http\Modules\Ventas\Repositories;

use App\Http\Modules\Ventas\Models\Venta;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class VentaRepository
{
    public function listarVentas(array $data): LengthAwarePaginator|Collection
    {
        $paginacion = $data['paginacion'] ?? null;

        $ventas = Venta::with(['detalles', 'usuario:id,nombre,apellido,email'])
            ->orderBy('id', 'desc');

        if (! empty($data['buscar'])) {
            $ventas->where(function ($query) use ($data) {
                $query->where('codigo', 'like', '%' . $data['buscar'] . '%')
                    ->orWhere('cliente_nombre', 'like', '%' . $data['buscar'] . '%')
                    ->orWhere('cliente_documento', 'like', '%' . $data['buscar'] . '%')
                    ->orWhere('cliente_telefono', 'like', '%' . $data['buscar'] . '%');
            });
        }

        if (! empty($data['metodo_pago'])) {
            $ventas->where('metodo_pago', $data['metodo_pago']);
        }

        if (! empty($data['estado'])) {
            $ventas->where('estado', $data['estado']);
        }

        if (! empty($data['fecha_inicio'])) {
            $ventas->whereDate('created_at', '>=', $data['fecha_inicio']);
        }

        if (! empty($data['fecha_fin'])) {
            $ventas->whereDate('created_at', '<=', $data['fecha_fin']);
        }

        return ! empty($paginacion)
            ? $ventas->paginate($paginacion['cantidadRegistros'], ['*'], 'page', $paginacion['pagina'])
            : $ventas->get();
    }

    public function obtenerVentaPorId(int $id): ?Venta
    {
        return Venta::with(['detalles.producto.categoria', 'detalles.producto.marca', 'usuario:id,nombre,apellido,email'])->find($id);
    }

    public function resumenVentas(array $data): array
    {
        $ventas = Venta::with('detalles')
            ->when(! empty($data['fecha_inicio']), function ($query) use ($data) {
                $query->whereDate('created_at', '>=', $data['fecha_inicio']);
            })
            ->when(! empty($data['fecha_fin']), function ($query) use ($data) {
                $query->whereDate('created_at', '<=', $data['fecha_fin']);
            })
            ->where('estado', $data['estado'] ?? 'registrada')
            ->orderBy('created_at')
            ->get();

        $hoy = Carbon::today();
        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();

        $ventasHoy = $ventas->filter(fn (Venta $venta) => $venta->created_at?->isSameDay($hoy));
        $ventasMes = $ventas->filter(fn (Venta $venta) => $venta->created_at?->between($inicioMes, $finMes, true));
        $unidadesVendidas = $ventas->flatMap->detalles->sum('cantidad');
        $totalVendido = (float) $ventas->sum('total');

        $porMes = $ventas
            ->groupBy(fn (Venta $venta) => $venta->created_at?->format('Y-m'))
            ->map(function ($items, $mes) {
                return [
                    'mes' => $mes,
                    'ventas' => $items->count(),
                    'total' => (float) $items->sum('total'),
                ];
            })
            ->values();

        $porMetodoPago = $ventas
            ->groupBy('metodo_pago')
            ->map(function ($items, $metodo) {
                return [
                    'metodo_pago' => $metodo ?: 'sin_definir',
                    'ventas' => $items->count(),
                    'total' => (float) $items->sum('total'),
                ];
            })
            ->values();

        $productosMasVendidos = $ventas
            ->flatMap->detalles
            ->groupBy('producto_id')
            ->map(function ($items) {
                $primerItem = $items->first();

                return [
                    'producto_id' => $primerItem->producto_id,
                    'producto_nombre' => $primerItem->producto_nombre,
                    'cantidad' => (int) $items->sum('cantidad'),
                    'total' => (float) $items->sum('subtotal'),
                ];
            })
            ->sortByDesc('cantidad')
            ->take(10)
            ->values();

        return [
            'total_vendido' => $totalVendido,
            'total_vendido_mes' => (float) $ventasMes->sum('total'),
            'total_vendido_hoy' => (float) $ventasHoy->sum('total'),
            'cantidad_ventas' => $ventas->count(),
            'cantidad_ventas_mes' => $ventasMes->count(),
            'cantidad_ventas_hoy' => $ventasHoy->count(),
            'unidades_vendidas' => (int) $unidadesVendidas,
            'ticket_promedio' => $ventas->count() ? round($totalVendido / $ventas->count(), 2) : 0,
            'por_mes' => $porMes,
            'por_metodo_pago' => $porMetodoPago,
            'productos_mas_vendidos' => $productosMasVendidos,
        ];
    }
}
