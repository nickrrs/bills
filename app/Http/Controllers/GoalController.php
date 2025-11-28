<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Services\GoalService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\BulkDeleteGoalRequest;

class GoalController extends Controller
{
    private $goalService;

    public function __construct(GoalService $goalService)
    {
        $this->goalService = $goalService;
    }

    public function apiIndex(Request $request): JsonResponse
    {
        $payload = $request->all();
        $payload['user_id'] = $request->user()->id;
        $goals = $this->goalService->getAllGoals($payload);
        return response()->json($goals);
    }

    public function apiStore(Request $request): JsonResponse
    {
        $data = $request->all();
        $data['user_id'] = $request->user()->id;
        $goal = $this->goalService->createGoal($data);
        return response()->json($goal);
    }

    public function apiUpdate(Request $request, Goal $goal): JsonResponse
    {
        $data = $request->all();
        $goal = $this->goalService->updateGoal($goal, $data);
        return response()->json($goal);
    }

    public function apiDestroy(Request $request, Goal $goal): JsonResponse
    {
        $this->goalService->deleteGoal($goal);
        return response()->json(['message' => 'objetivo deletado com sucesso']);
    }

    public function apiBulkDestroy(BulkDeleteGoalRequest $request): JsonResponse
    {
        $this->goalService->bulkDeleteGoals($request->ids);
        return response()->json(['message' => 'objetivos deletados com sucesso']);
    }

    public function apiIncrementAmount(Request $request, Goal $goal): JsonResponse
    {
        $amount = $request->input('amount');
        $goal = $this->goalService->incrementGoalAmount($goal, $amount);
        return response()->json($goal);
    }

    public function apiDecrementAmount(Request $request, Goal $goal): JsonResponse
    {
        $amount = $request->input('amount');
        $goal = $this->goalService->decrementGoalAmount($goal, $amount);
        return response()->json($goal);
    }
}
