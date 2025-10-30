<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display the task management page (AJAX single-page app)
     */
    public function manage()
    {
        $staffMembers = User::where('status', 'active')->orderBy('name')->get();
        return view('admin.tasks.manage', compact('staffMembers'));
    }

    /**
     * Get tasks as JSON for AJAX requests
     */
    public function index(Request $request)
    {
        $query = Task::with(['responsiblePerson', 'creator']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by responsible person
        if ($request->filled('responsible_person')) {
            $query->where('responsible_person_id', $request->responsible_person);
        }

        // Sort by due date and created_at
        $query->orderBy('due_date', 'asc')->orderBy('created_at', 'desc');

        $tasks = $query->get()->map(function($task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'due_date' => $task->due_date?->format('Y-m-d'),
                'due_date_formatted' => $task->due_date?->format('M d, Y'),
                'due_date_human' => $task->due_date?->diffForHumans(),
                'category' => $task->category,
                'priority' => $task->priority,
                'status' => $task->status,
                'notes' => $task->notes,
                'responsible_person_id' => $task->responsible_person_id,
                'responsible_person' => $task->responsiblePerson ? [
                    'id' => $task->responsiblePerson->id,
                    'name' => $task->responsiblePerson->name,
                    'role' => $task->responsiblePerson->role,
                    'initials' => strtoupper(substr($task->responsiblePerson->name, 0, 1))
                ] : null,
                'creator' => [
                    'id' => $task->creator->id,
                    'name' => $task->creator->name,
                ],
                'is_overdue' => $task->isOverdue(),
                'created_at' => $task->created_at->format('M d, Y h:i A'),
                'updated_at' => $task->updated_at->format('M d, Y h:i A'),
                'status_badge_class' => $task->getStatusBadgeClass(),
                'priority_badge_class' => $task->getPriorityBadgeClass(),
                'category_badge_class' => $task->getCategoryBadgeClass(),
            ];
        });

        return response()->json([
            'success' => true,
            'tasks' => $tasks
        ]);
    }

    /**
     * Get a single task as JSON
     */
    public function show($id)
    {
        $task = Task::with(['responsiblePerson', 'creator'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'task' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'due_date' => $task->due_date?->format('Y-m-d'),
                'due_date_formatted' => $task->due_date?->format('M d, Y'),
                'due_date_human' => $task->due_date?->diffForHumans(),
                'category' => $task->category,
                'priority' => $task->priority,
                'status' => $task->status,
                'notes' => $task->notes,
                'responsible_person_id' => $task->responsible_person_id,
                'responsible_person' => $task->responsiblePerson ? [
                    'id' => $task->responsiblePerson->id,
                    'name' => $task->responsiblePerson->name,
                    'role' => $task->responsiblePerson->role,
                    'initials' => strtoupper(substr($task->responsiblePerson->name, 0, 1))
                ] : null,
                'creator' => [
                    'id' => $task->creator->id,
                    'name' => $task->creator->name,
                ],
                'is_overdue' => $task->isOverdue(),
                'created_at' => $task->created_at->format('M d, Y h:i A'),
                'updated_at' => $task->updated_at->format('M d, Y h:i A'),
                'status_badge_class' => $task->getStatusBadgeClass(),
                'priority_badge_class' => $task->getPriorityBadgeClass(),
                'category_badge_class' => $task->getCategoryBadgeClass(),
            ]
        ]);
    }

    /**
     * Store a new task (AJAX)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'category' => 'required|in:finance,operation,hr,marketing,it,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'responsible_person_id' => 'nullable|exists:users,id',
            'status' => 'required|in:to_do,in_progress,completed',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id();
        $task = Task::create($validated);
        $task->load(['responsiblePerson', 'creator']);

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully!',
            'task' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'due_date' => $task->due_date?->format('Y-m-d'),
                'due_date_formatted' => $task->due_date?->format('M d, Y'),
                'due_date_human' => $task->due_date?->diffForHumans(),
                'category' => $task->category,
                'priority' => $task->priority,
                'status' => $task->status,
                'notes' => $task->notes,
                'responsible_person_id' => $task->responsible_person_id,
                'responsible_person' => $task->responsiblePerson ? [
                    'id' => $task->responsiblePerson->id,
                    'name' => $task->responsiblePerson->name,
                    'role' => $task->responsiblePerson->role,
                    'initials' => strtoupper(substr($task->responsiblePerson->name, 0, 1))
                ] : null,
                'creator' => [
                    'id' => $task->creator->id,
                    'name' => $task->creator->name,
                ],
                'is_overdue' => $task->isOverdue(),
                'created_at' => $task->created_at->format('M d, Y h:i A'),
                'updated_at' => $task->updated_at->format('M d, Y h:i A'),
                'status_badge_class' => $task->getStatusBadgeClass(),
                'priority_badge_class' => $task->getPriorityBadgeClass(),
                'category_badge_class' => $task->getCategoryBadgeClass(),
            ]
        ]);
    }

    /**
     * Update a task (AJAX)
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'category' => 'required|in:finance,operation,hr,marketing,it,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'responsible_person_id' => 'nullable|exists:users,id',
            'status' => 'required|in:to_do,in_progress,completed',
            'notes' => 'nullable|string',
        ]);

        $task->update($validated);
        $task->load(['responsiblePerson', 'creator']);

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully!',
            'task' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'due_date' => $task->due_date?->format('Y-m-d'),
                'due_date_formatted' => $task->due_date?->format('M d, Y'),
                'due_date_human' => $task->due_date?->diffForHumans(),
                'category' => $task->category,
                'priority' => $task->priority,
                'status' => $task->status,
                'notes' => $task->notes,
                'responsible_person_id' => $task->responsible_person_id,
                'responsible_person' => $task->responsiblePerson ? [
                    'id' => $task->responsiblePerson->id,
                    'name' => $task->responsiblePerson->name,
                    'role' => $task->responsiblePerson->role,
                    'initials' => strtoupper(substr($task->responsiblePerson->name, 0, 1))
                ] : null,
                'creator' => [
                    'id' => $task->creator->id,
                    'name' => $task->creator->name,
                ],
                'is_overdue' => $task->isOverdue(),
                'created_at' => $task->created_at->format('M d, Y h:i A'),
                'updated_at' => $task->updated_at->format('M d, Y h:i A'),
                'status_badge_class' => $task->getStatusBadgeClass(),
                'priority_badge_class' => $task->getPriorityBadgeClass(),
                'category_badge_class' => $task->getCategoryBadgeClass(),
            ]
        ]);
    }

    /**
     * Update task status (AJAX)
     */
    public function updateStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:to_do,in_progress,completed',
        ]);

        $task->update(['status' => $validated['status']]);
        $task->load(['responsiblePerson', 'creator']);

        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully!',
            'task' => [
                'id' => $task->id,
                'status' => $task->status,
                'status_badge_class' => $task->getStatusBadgeClass(),
            ]
        ]);
    }

    /**
     * Delete a task (AJAX)
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully!'
        ]);
    }
}
