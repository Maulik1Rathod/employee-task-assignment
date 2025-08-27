<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(name="Employees")
 */
class EmployeeController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/employees",
     *   tags={"Employees"},
     *   summary="List employees",
     *   @OA\Response(response=200, description="OK")
     * )
     */
    public function index() {
        return Employee::get();
    }

    /**
     * @OA\Post(
     *   path="/api/employees",
     *   tags={"Employees"},
     *   summary="Create employee",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"name","email","mobile"},
     *       @OA\Property(property="name", type="string"),
     *       @OA\Property(property="email", type="string"),
     *       @OA\Property(property="mobile", type="string")
     *     )
     *   ),
     *   @OA\Response(response=201, description="Created")
     * )
     */
    public function store(Request $request) {
        $data = $request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'mobile'=>'required'
        ]);
        return response()->json(Employee::create($data),201);
    }

    /** @OA\Get(path="/api/employees/{id}",
     *  tags={"Employees"},
     *  summary="Get employee",
     *  @OA\Parameter(name="id", in="path", required=true,@OA\Schema(type="integer")),
     *  @OA\Response(response=200, description="OK")) */
    public function show(Employee $employee) {
        return $employee;
    }

    /** @OA\Put(path="/api/employees/{id}", tags={"Employees"}, summary="Update employee",
     *  @OA\Parameter(name="id", in="path", required=true,@OA\Schema(type="integer")),
     *  @OA\RequestBody(@OA\JsonContent(
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="email", type="string"),
     *     @OA\Property(property="mobile", type="string"))),
     *  @OA\Response(response=200, description="OK")) */
    public function update(Request $request, $id) {
        $data=$request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'mobile'=>'required',
        ]);

        Employee::findOrFail($id)->update($data);

        return response()->json($data,200);
    }

    /**
     * @OA\Delete(path="/api/employees/{id}",
     *   tags={"Employees"},
     *   summary="Delete employee",
     *   @OA\Parameter(name="id", in="path", required=true,@OA\Schema(type="integer")),
     *   @OA\Response(response=204, description="No content")) */
    public function destroy($id) {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }
        $employee->delete();
        return response()->json(['message' => 'Employee deleted successfully']);
    }

    /** @OA\Get(path="/api/employees/{id}/todos",
     *  tags={"Employees"},
     *  summary="Get employee todos",
     *  @OA\Parameter(name="id", in="path", required=true,@OA\Schema(type="integer")),
     *  @OA\Response(response=200, description="OK")) */
    public function todos($id) {
        $employee = Employee::findOrFail($id);
        $todos = $employee->todos; // will get by relationship
        return response()->json($todos);
    }
}
