<?php

namespace App\Services;

use App\Jobs\AutoContributionJob;
use App\Models\Goal;
use Illuminate\Support\Facades\Bus;

class GoalService
{
    public function getAllGoals($payload = [])
    {
        $per_page = $payload['per_page'] ?? 10;
        $page = $payload['page'] ?? 1;
        $order_by = $payload['order_by'] ?? 'created_at';
        $order_direction = $payload['order_direction'] ?? 'desc';
        $search = $payload['search'] ?? null;
        $user_id = $payload['user_id'] ?? null;
        $status = $payload['status'] ?? null;

        $query = Goal::query();

        if ($user_id) {
            $query->where('user_id', $user_id);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($status) {
            $query->where('status', $status);
        }

        $query->orderBy($order_by, $order_direction);
        $query->with(['category', 'subcategory']);
        return $query->paginate($per_page, ['*'], 'page', $page);
    }

    public function createGoal(array $data)
    {
        return Goal::create($data);
    }

    public function updateGoal(Goal $goal, array $data)
    {
        $goal->update($data);
        return $goal;
    }

    public function deleteGoal(Goal $goal)
    {
        $goal->delete();
        return $goal;
    }

    public function bulkDeleteGoals(array $ids)
    {
        return Goal::whereIn('id', $ids)->delete();
    }

    public function incrementGoalAmount(Goal $goal, $amount)
    {
        $goal->amount += $amount;
        $goal->save();
        return $goal;
    }

    public function decrementGoalAmount(Goal $goal, $amount)
    {
        $goal->amount -= $amount;
        $goal->save();
        return $goal;
    }
}
