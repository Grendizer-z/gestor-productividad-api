<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectsRequest;
use App\Http\Resources\ProjectsResource;
use App\Models\Projects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectsController extends Controller
{
    public function index(Request $request)
    {
        $Projects = $request->user()
            ->Projects()
            ->orderBy('name')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => ProjectsResource::collection($Projects)->response()->getData()
        ]);
    }

    public function store(ProjectsRequest $request)
    {
        $Projects = $request->user()->Projects()->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Proyecto creado exitosamente',
            'data' => new ProjectsResource($Projects)
        ], 201);
    }

    public function show(Request $request, Projects $Project)
    {
        //dd($Project);

        // Verificar que el Projects pertenece al usuario autenticado
        if ($Project->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new ProjectsResource($Project)
        ]);
    }

    public function update(Request $request, Projects $Project)
    {
        //dd($request->all());
        // Verificar que el Proyecto pertenece al usuario autenticado
        if ($Project->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        // Validación manual para PATCH
        $validatedData = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'is_archived' => ['sometimes', 'integer', 'in:0,1'], // <-- CAMBIO CLAVE
        ]);

        // Usamos los datos validados manualmente
        $Project->update($validatedData);

        $Project->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Proyecto actualizado exitosamente',
            'data' => new ProjectsResource($Project)
        ]);
    }


    public function destroy(Request $request, Projects $Project)
    {
        //dd($Project->user_id, $request->user()->id);
        // Verificar que el Projects pertenece al usuario autenticado
        if ($Project->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        $Project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Proyecto eliminado exitosamente'
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        if (!$query) {
            return response()->json([
                'success' => false,
                'message' => 'Parámetro de búsqueda requerido'
            ], 400);
        }

        $Projects = $request->user()
            ->Projects()
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('nombre', 'like', "%{$query}%")
                    ->orWhere('apellido', 'like', "%{$query}%")
                    ->orWhere('telefono', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => [
                'Projects' => ProjectsResource::collection($Projects),
                'pagination' => [
                    'current_page' => $Projects->currentPage(),
                    'per_page' => $Projects->perPage(),
                    'total' => $Projects->total(),
                    'last_page' => $Projects->lastPage(),
                    'from' => $Projects->firstItem(),
                    'to' => $Projects->lastItem(),
                ]
            ]
        ]);
    }
}
