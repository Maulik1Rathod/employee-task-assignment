<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use App\Models\Employee;

/**
 * @OA\Tag(name="todos")
 */
class TodoController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/todos",
     *   tags={"todos"},
     *   summary="List todos",
     *   @OA\Response(response=200, description="OK")
     * )
     */
    public function index()
    {
        return Todo::all();
    }

    /**
     * @OA\Post(
     *   path="/api/todos",
     *   tags={"todos"},
     *   summary="Create todo",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"title","status","due_date","assign_emp_id"},
     *       @OA\Property(property="title", type="string"),
     *       @OA\Property(property="status", type="string"),
     *       @OA\Property(property="due_date", type="string"),
     *       @OA\Property(property="assign_emp_id", type="integer")
     *     )
     *   ),
     *   @OA\Response(response=201, description="Created")
     * )
     */
    public function store(Request $request)
    {
         $data = $request->validate([
            'title'=>'required',
            'status'=>'required',
            'due_date'=>'required',
            'assign_emp_id'=>'required|integer'
        ]);
        $data = Todo::create($data);
        return response()->json($data,201);
    }

    /** @OA\Get(path="/api/todos/{id}",
     *  tags={"todos"},
     *  summary="Get todo",
     *  @OA\Parameter(name="id", in="path", required=true,@OA\Schema(type="integer")),
     *  @OA\Response(response=200, description="OK")) */
    public function show($id)
    {
        $todo = Todo::find($id);
        return response()->json($todo);
    }

    /** @OA\Put(path="/api/todos/{id}", tags={"todos"}, summary="Update todo",
     *  @OA\Parameter(name="id", in="path", required=true,@OA\Schema(type="integer")),
     *  @OA\RequestBody(@OA\JsonContent(
     *     @OA\Property(property="title", type="string"),
     *     @OA\Property(property="status", type="string"),
     *     @OA\Property(property="due_date", type="string"),
     *     @OA\Property(property="assign_emp_id", type="integer"))),
     *  @OA\Response(response=200, description="OK")) */
    public function update(Request $request, $id)
    {
        $todo = Todo::find($id);
        if (!$todo) {
            return response()->json(['message' => 'Todo not found'], 404);
        }
        $data = $request->validate([
            'title' => 'required',
            'status' => 'required',
            'due_date' => 'required',
            'assign_emp_id' => 'required|integer'
        ]);
        $todo->update($data);
        return response()->json($todo);
    }

    /**
     * @OA\Delete(path="/api/todos/{id}",
     *   tags={"todos"},
     *   summary="Delete todo",
     *   @OA\Parameter(name="id", in="path", required=true,@OA\Schema(type="integer")),
     *   @OA\Response(response=204, description="No content")) */
    public function destroy($id)
    {
        $todo = Todo::find($id);
        if (!$todo) {
            return response()->json(['message' => 'Todo not found'], 404);
        }
        $todo->delete();
        return response()->json(['message' => 'Todo deleted successfully']);
    }

    /**
     * @OA\Post(path="/api/todos/{id}/assign",
     *   tags={"todos"},
     *   summary="Assign employee to todo",
     *   @OA\Parameter(name="id", in="path", required=true,@OA\Schema(type="integer")),
     *   @OA\RequestBody(@OA\JsonContent(
     *     @OA\Property(property="assign_emp_id", type="integer")
     *   )),
     *   @OA\Response(response=200, description="OK")) */
    public function assignEmployee(Request $request, $id)
    {
        $todo = Todo::find($id);
        if (!$todo) {
            return response()->json(['message' => 'Todo not found'], 404);
        }

        $data = $request->validate([
            'assign_emp_id' => 'required|integer|exists:employees,id'
        ]);

        $todo->assign_emp_id = $data['assign_emp_id'];
        $todo->save();

        return response()->json($todo);
    }

    /** @OA\Get(path="/api/todos-filter",
     *   tags={"todos"}, summary="Todo filter",
     *  @OA\Parameter(name="status", in="query", @OA\Schema(type="string")),
     *  @OA\Parameter(name="due_date_from", in="query", @OA\Schema(type="string",format="date")),
     *  @OA\Parameter(name="due_date_to", in="query", @OA\Schema(type="string",format="date")),
     *  @OA\Parameter(name="assign_emp_id", in="query", @OA\Schema(type="integer")),
     *  @OA\Response(response=200, description="OK")) */
    public function filter(Request $request)
    {
        $query = Todo::query();

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('due_date_from')) {
            $query->where('due_date', '>=', $request->input('due_date_from'));
        }

        if ($request->has('due_date_to')) {
            $query->where('due_date', '<=', $request->input('due_date_to'));
        }

        if ($request->has('assign_emp_id')) {
            $query->where('assign_emp_id', $request->input('assign_emp_id'));
        }

        return response()->json($query->get());
    }

}
