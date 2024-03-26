<?php


namespace App\Http\Controllers;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Expenses",
 *     description="API Endpoints of Expenses"
 * )
 */
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class ExpenseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/expenses",
     *     tags={"Expenses"},
     *     summary="List all expenses",
     *     description="Returns a list of all expenses for the authenticated user",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="amount", type="number", format="float", example=100.50),
     *                 @OA\Property(property="description", type="string", example="Grocery shopping"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-03-26T11:23:14Z"),
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $expenses = Auth::user()->expenses()->get();
        return response()->json($expenses);
    }

    /**
     * @OA\Post(
     *     path="/expenses",
     *     tags={"Expenses"},
     *     summary="Store a new expense",
     *     description="Stores a new expense for the authenticated user",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Expense created",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'description' => 'required|string|max:255',
        ]);

        $expense = Auth::user()->expenses()->create([
            'amount' => $request->amount,
            'description' => $request->description
        ]);

        return response()->json($expense, 201);
    }

    /**
     * @OA\Get(
     *     path="/expenses/{id}",
     *     tags={"Expenses"},
     *     summary="Show an expense",
     *     description="Returns a single expense for the authenticated user",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Expense ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Expense not found"
     *     )
     * )
     */

    public function show($id)
    {
        $expense = Auth::user()->expenses()->find($id);
        $this->authorize('view', $expense);
        if (!$expense) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($expense);
    }

    /**
     * @OA\Put(
     *     path="/expenses/{id}",
     *     tags={"Expenses"},
     *     summary="Update an expense",
     *     description="Updates an existing expense for the authenticated user",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Expense ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Expense not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'amount' => 'sometimes|required|numeric',
            'description' => 'sometimes|required|string|max:255',
        ]);

        $expense = Auth::user()->expenses()->find($id);
        $this->authorize('update', $expense);
        if (!$expense) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $expense->update($request->only(['amount', 'description']));

        return response()->json($expense);
    }

    /**
     * @OA\Delete(
     *     path="/expenses/{id}",
     *     tags={"Expenses"},
     *     summary="Delete an expense",
     *     description="Deletes an existing expense for the authenticated user",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Expense ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Expense deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Expense not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $expense = Auth::user()->expenses()->find($id);
        $this->authorize('delete', $expense);

        if (!$expense) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $expense->delete();

        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}

