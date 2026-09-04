<?php

namespace App\Http\Modules\Productos\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrearProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'unidad_medida' => ['required', 'string', 'max:50'],
            'estado' => ['required', 'boolean'],
            'precio' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'marca_id' => ['nullable', 'exists:marcas,id'],
            'bodega_id' => ['nullable', 'exists:bodegas,id'],
            'imagen' => ['required', 'image', 'max:20480'],
        ];
    }

    
}
