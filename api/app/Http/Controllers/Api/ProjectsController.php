<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectsRequest;
use App\Http\Resources\ProjectsResource;
use App\Models\Projects;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index(Request $request)
    {
        $Projects = $request->user()
            ->Projects()
            ->orderBy('nombre')
            ->orderBy('apellido')
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

    public function show(Request $request, Projects $Projects)
    {
        // Verificar que el Projects pertenece al usuario autenticado
        if ($Projects->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new ProjectsResource($Projects)
        ]);
    }

    public function update(ProjectsRequest $request, Projects $Projects)
    {
        // Verificar que el Proyecto pertenece al usuario autenticado
        if ($Projects->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        $Projects->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Proyecto actualizado exitosamente',
            'data' => new ProjectsResource($Projects)
        ]);
    }

    public function destroy(Request $request, Projects $Projects)
    {
        // Verificar que el Projects pertenece al usuario autenticado
        if ($Projects->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        $Projects->delete();

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
