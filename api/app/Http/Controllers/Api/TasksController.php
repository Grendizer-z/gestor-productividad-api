<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TasksRequest;
use App\Http\Resources\TasksResource;
use App\Models\Tasks;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    public function index(Request $request)
    {
        $Tasks = $request->user()
            ->Tasks()
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => TasksResource::collection($Tasks)->response()->getData()
        ]);
    }

    public function store(TasksRequest $request)
    {
        $Tasks = $request->user()->Tasks()->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Proyecto creado exitosamente',
            'data' => new TasksResource($Tasks)
        ], 201);
    }

    public function show(Request $request, Tasks $Tasks)
    {
        // Verificar que el Tasks pertenece al usuario autenticado
        if ($Tasks->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new TasksResource($Tasks)
        ]);
    }

    public function update(TasksRequest $request, Tasks $Tasks)
    {
        // Verificar que el Proyecto pertenece al usuario autenticado
        if ($Tasks->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        $Tasks->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Proyecto actualizado exitosamente',
            'data' => new TasksResource($Tasks)
        ]);
    }

    public function destroy(Request $request, Tasks $Tasks)
    {
        // Verificar que el Tasks pertenece al usuario autenticado
        if ($Tasks->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        $Tasks->delete();

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

        $Tasks = $request->user()
            ->Tasks()
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
                'Tasks' => TasksResource::collection($Tasks),
                'pagination' => [
                    'current_page' => $Tasks->currentPage(),
                    'per_page' => $Tasks->perPage(),
                    'total' => $Tasks->total(),
                    'last_page' => $Tasks->lastPage(),
                    'from' => $Tasks->firstItem(),
                    'to' => $Tasks->lastItem(),
                ]
            ]
        ]);
    }
}
