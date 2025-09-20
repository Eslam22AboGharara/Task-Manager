<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTask;
use App\Http\Requests\UpdateTask;
use App\Models\Task;
use Exception;
use Illuminate\Auth\Events\Validated;
use Illuminate\Container\Attributes\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function store(StoreTask $request)
    {
        $validate = $request->validated();
        $user_id = Auth::user()->id;
        $validate['user_id'] = $user_id;
        $task = Task::create($validate);
        return response()->json(
            [
                'task' => $task
            ],
            201
        );
    }

    public function update(UpdateTask $request, $id)
    {
        try {
            $task = Task::findOrFail($id);
            $user_id = Auth::user()->id;

            if ($user_id != $task->user_id) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 403);
            }

            $task->update($request->validated());

            return response()->json([
                'task' => $task,
                'message' => 'Task updated successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Task not found'
            ], 404);
        }
    }

    public function index()
    {
        $tasks = Task::all();
        return response()->json($tasks, 200);
    }

    public function show()
    {
        $tasks_user = Auth::user()->tasks;
        return response()->json([
            'message' => 'hello ' . Auth::user()->name,
            'your tasks' => $tasks_user
        ], 200);
    }

    public function display_priority()
    {
        $user = Auth::user();
        $tasks = $user->tasks()->orderByRaw("FIELD(priority,'low','medium','high')")->get();

        return response()->json([
            'Tasks' => $tasks
        ], 200);
    }

    public function favorites_tasks(Request $request)
    {
        try {
            $request->validate([
                'task_id' => 'required|integer|exists:tasks,id'
            ]);

            $user = Auth::user();
            $task = Task::find($request->task_id);

            if ($task->user_id !== $user->id) {
                return response()->json([
                    'message' => 'Unauthorized to favorite this task'
                ], 403);
            }

            $user->favorites_tasks()->syncWithoutDetaching($request->task_id);
            return response()->json([
                'message' => 'Task added to favorites successfully'
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Task not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to add task to favorites'
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $task = Task::findOrFail($id);
            $user_id = Auth::user()->id;
            if ($user_id != $task->user_id) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 403);
            }
            $task->delete();
            return response()->json([
                'message' => 'Task deleted successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Task Not Found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to delete task'
            ], 500);
        }
    }
}
