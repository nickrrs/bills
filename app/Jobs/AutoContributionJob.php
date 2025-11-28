<?php

namespace App\Jobs;

use App\Helpers\GoalDateHelper;
use App\Models\Goal;
use App\Models\ScheduledJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AutoContributionJob implements ShouldQueue
{
    use Queueable;

    public $uuid;
    protected $goal;

    /**
     * Create a new job instance.
     */
    public function __construct(Goal $goal)
    {
        $this->goal = $goal;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->markCurrentJobAsCompleted();

        $goal = Goal::find($this->goal->id);

        if (!$goal || !$goal->auto_mode) {
            return;
        }

        if ($goal->status === 'completed') {
            return;
        }

        $goal->amount += $goal->contribution_amount;

        $baseDate = $goal->next_auto_contribution_at
            ? Carbon::parse($goal->next_auto_contribution_at)
            : Carbon::parse($goal->start_contribution_at);

        $nextDate = GoalDateHelper::calculateNextContributionDate($baseDate);
        $goal->next_auto_contribution_at = $nextDate;

        if ($goal->amount >= $goal->final_amount) {
            $goal->status = 'completed';
        }

        $goal->save();

        if ($goal->status !== 'completed' && $goal->auto_mode && $nextDate && $nextDate->isFuture()) {
            $jobUuid = (string) Str::uuid();

            ScheduledJob::create([
                'goal_id' => $goal->id,
                'job_uuid' => $jobUuid,
                'job_class' => AutoContributionJob::class,
                'queue_name' => 'auto-contribution',
                'scheduled_at' => $nextDate,
                'status' => 'pending',
            ]);

            $nextJob = new AutoContributionJob($goal->fresh());
            $nextJob->uuid = $jobUuid;

            dispatch($nextJob)
                ->onQueue('auto-contribution')
                ->delay($nextDate);
        }
    }

    /**
     * Marca o job atual como completado na tabela de controle
     */
    protected function markCurrentJobAsCompleted(): void
    {
        if ($this->uuid) {
            ScheduledJob::where('job_uuid', $this->uuid)
                ->where('status', 'pending')
                ->update(['status' => 'completed']);
        }
    }
}
