<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryTaskController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required'
        ]);
        try {
            // code...
            $user_id = Auth::user()->id;
            $task = Task::findOrFail($id);
            if ($user_id === $task->user_id) {
                $task->categories()->attach($request->category_id);
                return response()->json([
                    'message' => 'successful'
                ], 201);
            }
        } catch (\Exception $e) {
            //throw $th;
            return response()->json([
                'message' => 'Fail'
            ], 400);
        }
    }

    public function store_by_name(Request $request, $name)
    {
        $request->validate([
            'category_name' => 'required|string|exists:categories,name'
        ]);
        try {
            // code...
            $task = Task::where('title', $name)->first();

            if (!$task) {
                return response()->json([
                    'message' => 'Task not found'
                ], 404);
            }

            $category = Category::where('name', $request->category_name)->first();

            if (!$category) {
                return response()->json([
                    'message' => 'Category not found'
                ], 404);
            }
            $user_id = Auth::user()->id;
            if ($task->user_id === $user_id) {
                $task->categories()->attach($category->id);
                return response()->json([
                    'message' => 'Category added to task successfully'
                ], 201);
            }
        } catch (\Exception $e) {
            //throw $th;
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function all_tasks_in_category(Request $request)
    {
        $tasks = Category::where('name', $request->name)->with('tasks')->get();
        return response()->json([
            'tasks' => $tasks
        ], 200);
    }
}
