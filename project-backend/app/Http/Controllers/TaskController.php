<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Services\TaskService;

/**
 * Contrôleur pour la gestion des taches CRUD.
 * le controleur utilise la couche Service pour la logique metier, la Couche Repository pour l'interaction avec base de donnees
 */
class TaskController extends Controller
{
    protected $taskService;

    /**
     * Injection de dépendance du TaskService.
     */
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * affichage du liste des tâches de l'utilisateur connecte.
     */
    public function index()
    {
        $user_id = auth()->id();

        // Récuperation de toutes les taches de l'utilisateur en utilisant le service
        $tasks = $this->taskService->getUserTasks($user_id);

        return response()->json([
            'tasks' => $tasks
        ]);
    }

    /**
     * Crée une nouvelle tache pour l'utilisateur connecte.
     */
    public function store(Request $request)
    {
        $user_id = auth()->id();

        // Validation du Title du Tache
        $request->validate([
            'title' => 'required|string|min:3|max:255',
        ]);

        // Creation du tâche utilisant le service
        $task = $this->taskService->storeUserTask($user_id, $request->all());

        return response()->json([
            'message' => 'Task created successfully',
            'task' => $task
        ], 201);
    }

    /**
     * Met à jour une tache existante.
     */
    public function update(Request $request, string $id)
    {
        // Validation de titre
        $request->validate([
            'title' => 'sometimes|string|min:3|max:255',
        ]);

        // Mise à jour de la tâche utilisant le service
        $task = $this->taskService->updateUserTask(
            $id,
            auth()->id(),
            $request->only(['title','description','status'])
        );

        return response()->json([
            'message' => 'Task updated successfully',
            'task' => $task
        ]);
    }

    /**
     * Supprime une tâche de l'utilisateur connecte.
     */
    public function destroy(string $id)
    {
        // Suppression utilisant le service
        $this->taskService->deleteUserTask($id, auth()->id());

        return response()->json([
            'message' => 'Task deleted successfully'
        ]);
    }
}
