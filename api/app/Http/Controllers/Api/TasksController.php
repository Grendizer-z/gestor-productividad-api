<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TasksRequest;
use App\Http\Resources\TasksResource;
use App\Models\Tasks;
use Illuminate\Http\Request;
use App\Models\Projects;

class TasksController extends Controller
{
    public function index(Projects $projects)
    {
        $Tasks = $projects->Tasks()
            ->orderBy('title')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => TasksResource::collection($Tasks)->response()->getData()
        ]);
    }

    public function store(TasksRequest $request)
    {
        $task = Tasks::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Tarea creada exitosamente',
            'data'    => new TasksResource($task)
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

    // Cambiar Tasks $tasks a Tasks $Task
    public function update(TasksRequest $request, Tasks $Task)
    {
        // Verificar que la tarea tenga proyecto y que el proyecto pertenezca al usuario autenticado
        // Asegúrese de usar $Task en lugar de $tasks en todo el cuerpo de la función
        if (!$Task->project || $Task->project->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        $Task->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Tarea actualizada exitosamente',
            'data' => new TasksResource($Task)
        ]);
    }




    public function destroy(Request $request, Tasks $Task)
    {
        // Verificar que el Tasks pertenece al usuario autenticado
        if ($Task->project->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        $Task->delete();

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
