<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Storage, Log};
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use App\Models\{User, Categoria, Producto, Servicio, Factura, Estado, Empresa, Inventario, Historial_mantenimiento, Personal_encargado, Role};
use App\Exports\InventarioExport;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Roles con acceso total
        $rolesConAccesoTotal = ['admin', 'tecnico'];

        // ¿Tiene acceso total?
        $tieneAccesoTotal = $user->hasAnyRole($rolesConAccesoTotal);

        if ($tieneAccesoTotal) {
            // Admin y técnico ven todo
            return view('dashboard', [
                'users' => User::with(['empresas', 'roles'])->get(),
                'categorias' => Categoria::all(),
                'productos' => Producto::all(),
                'servicios' => Servicio::all(),
                'facturas' => Factura::all(),
                'estados' => Estado::all(),
                'empresas' => Empresa::all(),
                'inventarios' => Inventario::all(),
                'historial_mantenimiento' => Historial_mantenimiento::all(),
                'personal_encargado' => Personal_encargado::all(),
                'roles' => Role::all()
            ]);
        } else {
            // Usuario empresarial: solo accede a sus empresas
            $empresaIds = $user->empresas()->pluck('empresas.id')->toArray();

            return view('dashboard', [
                'users' => User::with(['empresas', 'roles'])
                    ->whereHas('empresas', function ($q) use ($empresaIds) {
                        $q->whereIn('empresas.id', $empresaIds);
                    })->get(),

                'empresas' => Empresa::whereIn('id', $empresaIds)->get(),
                'inventarios' => Inventario::whereIn('id_empresa', $empresaIds)->get(),

                'historial_mantenimiento' => Historial_mantenimiento::all(),

                'personal_encargado' => Personal_encargado::all(),

                'roles' => Role::all() // o limitar si lo necesitás
            ]);
        }
    }

    public function store(Request $request, $section)
    {
        try {
            $model = $this->getModel($section);
            if (!$model) {
                return redirect()->route('dashboard')->with('error', 'Sección no válida.');
            }

            $validatedData = $this->validateData($request, $section);

            if ($section === 'usuarios') {
                $empresaId = $request->input('empresa');
                $rolName = $request->input('rol');

                $empresa = Empresa::findOrFail($empresaId);
                $rol = \Spatie\Permission\Models\Role::where('name', $rolName)->firstOrFail();

                $user = new User($validatedData);
                $user->save();

                $user->assignRole($rolName);
                $user->empresas()->attach($empresaId, ['role_id' => $rol->id]);

                return redirect()->route('dashboard')->with('success', 'Usuario creado y asignado a la empresa correctamente.');
            }

            // 👉 Procesar imágenes para secciones que no son "usuarios"
            $validatedData = $this->handleImages($request, $validatedData, $section);

            $newItem = new $model($validatedData);
            $newItem->save();

            return redirect()->route('dashboard')->with('success', ucfirst($section) . ' creado con éxito.');
        } catch (\Exception $e) {
            Log::error("Error al agregar $section: " . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Ocurrió un error al guardar los datos.');
        }
    }

    public function update(Request $request, $section, $id)
    {
        try {
            $model = $this->getModel($section);
            if (!$model) {
                return redirect()->route('dashboard')->with('error', 'Sección no válida.');
            }

            $item = $model::findOrFail($id);
            $validatedData = $this->validateData($request, $section, $id);

            // Guardar imágenes si existen
            $validatedData = $this->handleImages($request, $validatedData, $section, $item);

            $item->update($validatedData);

            return redirect()->route('dashboard')->with('success', ucfirst($section) . ' actualizado con éxito.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar $section con ID $id: " . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Ocurrió un error al actualizar.');
        }
    }

    public function destroy($section, $id)
    {
        try {
            $model = $this->getModel($section);
            if (!$model) {
                return redirect()->route('dashboard')->with('error', 'Sección no válida.');
            }

            $item = $model::findOrFail($id);
            $item->delete();

            return redirect()->route('dashboard')->with('success', ucfirst($section) . ' eliminado con éxito.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar $section con ID $id: " . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Ocurrió un error al eliminar.');
        }
    }

    public function exportExcel($section)
    {
        if ($section === 'inventarios') {
            $user = auth()->user();
            $rolesConAccesoTotal = ['admin', 'tecnico'];

            // Si el usuario es admin o técnico, exporta todo
            if ($user->hasAnyRole($rolesConAccesoTotal)) {
                return Excel::download(new InventarioExport(), 'inventario.xlsx');
            }

            // Si no, exporta solo su inventario
            $empresaIds = $user->empresas()->pluck('empresas.id')->toArray();
            return Excel::download(new InventarioExport($empresaIds), 'inventario_empresa.xlsx');
        }

        return redirect()->route('dashboard')->with('error', 'Exportación no disponible para esta sección.');
    }

    public function exportPdf($section, $id)
    {
        if ($section !== 'inventarios') {
            return redirect()->route('dashboard')->with('error', 'PDF no disponible para esta sección.');
        }

        $inventario = Inventario::with('empresa')->findOrFail($id);

        if (!$inventario->empresa) {
            return redirect()->route('dashboard')->with('error', 'No se encontró la empresa asociada.');
        }

        $empresa = $inventario->empresa;
        $logo = $empresa->logo ?? 'default-logo.png';

        $pdf = PDF::loadView('inventario.pdf', compact('inventario', 'empresa', 'logo'));
        return $pdf->download('Hoja_de_vida_Equipo-' . $inventario->id . '.pdf');
    }

    private function getModel($section)
    {
        $models = [
            'usuarios' => User::class,
            'categorias' => Categoria::class,
            'productos' => Producto::class,
            'servicios' => Servicio::class,
            'facturas' => Factura::class,
            'inventarios' => Inventario::class,
            'empresas' => Empresa::class,
            'historial_mantenimiento' => Historial_mantenimiento::class,
            'personal_encargado' => Personal_encargado::class,
        ];
        return $models[$section] ?? null;
    }

    private function validateData(Request $request, $section, $id = null)
    {
        $uniqueSerial = $id ? "unique:inventarios,numero_serial,$id" : 'unique:inventarios,numero_serial';

        $rules = [
            'usuarios' => [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . ($id ?? 'NULL') . ',id',
                'password' => $id ? 'nullable|min:6|confirmed' : 'required|min:6|confirmed',
            ],
            'categorias' => [
                'categoria' => 'required|string|max:100',
                'explicacion' => 'nullable|string',
                'imagen' => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048',
            ],
            'servicios' => [
                'servicio' => 'required|string|max:150',
                'tipo' => 'required|string',
                'especificacion' => 'nullable|string',
                'imagen' => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048',
                'imagen1' => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048',
                'imagen2' => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048',
                'imagen3' => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048',
            ],
            'productos' => [
                'producto' => 'required|string|max:150',
                'descripcion' => 'nullable|string',
                'stock' => 'required|integer',
                'precio' => 'required|numeric',
                'imagen' => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048',
                'id_categoria' => 'required|exists:categorias,id',
                'id_estado' => 'required|exists:estados,id',
            ],
            'inventarios' => [
                'id_empresa' => 'required|exists:empresas,id',
                'nombre_equipo' => 'required|string|max:255',
                'sticker' => 'nullable|string|max:100',
                'marca_equipo' => 'required|string|max:100',
                'tipo_equipo' => 'required|string|max:100',
                'sistema_operativo' => 'required|string|max:100',
                'numero_serial' => "required|string|max:100",
                'idioma' => 'required|string|max:50',
                'procesador' => 'required|string|max:100',
                'velocidad_procesador' => 'required|string|max:50',
                'tipo_conexion' => 'required|string|max:50',
                'memoria_ram' => 'required|string|max:50',
                'cantidad_memoria' => 'required|integer',
                'slots_memoria' => 'required|integer',
                'frecuencia_memoria' => 'required|integer',
                'version_bios' => 'nullable|string|max:100',
                'cantidad_discos' => 'required|integer',
                'tipo_discos' => 'required|string|max:100',
                'espacio_discos' => 'required|string|max:100',
                'grafica' => 'nullable|string|max:100',
                'licencias' => 'nullable|string',
                'perifericos' => 'nullable|string',
                'observaciones' => 'nullable|string',
            ],
            'empresas' => [
                'nombre_empresa' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('empresas', 'nombre_empresa')->ignore($id),
                ],
                'nit' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('empresas', 'nit')->ignore($id),
                ],
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048',
            ],
            'historial_mantenimiento' => [
                'id_equipo' => 'required|exists:inventario,id',
                'encargado' => 'required|string|max:255',
                'tipo_mantenimiento' => 'required|string|max:255',
                'fecha_mantenimiento' => 'required|date',
                'descripcion' => 'required|string|max:255',
                'observaciones' => 'nullable|string',
            ],
            'personal_encargado' => [
                'id_equipo' => 'required|exists:inventario,id',
                'usuario_responsable' => 'required|string|max:255',
                'area_ubicacion' => 'required|string|max:255',
                'fecha_asignacion' => 'required|date',
                'fecha_devolucion' => 'nullable|date',
                'observacion' => 'nullable|string',
            ],
        ];

        $validated = $request->validate($rules[$section] ?? []);

        if ($section === 'usuarios' && isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        return $validated;
    }

    private function handleImages(Request $request, $validatedData, $section, $item = null)
    {
        foreach (['imagen', 'imagen1', 'imagen2', 'imagen3', 'logo'] as $field) {
            if ($request->hasFile($field)) {
                // Guardar el logo de empresas en la carpeta 'logos', el resto en 'imagenes/$section'
                if ($section === 'empresas' && $field === 'logo') {
                    $folder = 'logos';
                } else {
                    $folder = "imagenes/$section";
                }

                // Guardar la imagen en storage/app/public/$folder
                $path = $request->file($field)->store($folder, 'public');

                // Guardar solo la ruta relativa en la base de datos
                $validatedData[$field] = $path;
            }
        }

        return $validatedData;
    }
}
