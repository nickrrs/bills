<?php

namespace App\Observers;

use App\Helpers\GoalDateHelper;
use App\Jobs\AutoContributionJob;
use App\Models\Goal;
use App\Models\ScheduledJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GoalObserver
{
    public function saved(Goal $goal)
    {
        if ($goal->auto_mode && $goal->wasRecentlyCreated) {
            $this->scheduleJob($goal, $goal->start_contribution_at);
            return;
        }

        if ($goal->wasChanged('auto_mode')) {
            $originalAutoMode = $goal->getOriginal('auto_mode');
            $currentAutoMode = $goal->auto_mode;

            if ($originalAutoMode && !$currentAutoMode) {
                $this->cancelScheduledJobs($goal);
            }

            if (!$originalAutoMode && $currentAutoMode) {
                $this->scheduleNextJob($goal);
            }
        }

        if ($goal->wasChanged('status') && $goal->status === 'completed') {
            $this->cancelScheduledJobs($goal);
        }
    }

    public function deleted(Goal $goal)
    {
        $this->cancelScheduledJobs($goal);
    }

    /**
     * Agenda um job e salva o registro na tabela de controle
     */
    protected function scheduleJob(Goal $goal, Carbon $scheduledAt): void
    {
        $jobUuid = (string) Str::uuid();

        ScheduledJob::create([
            'goal_id' => $goal->id,
            'job_uuid' => $jobUuid,
            'job_class' => AutoContributionJob::class,
            'queue_name' => 'auto-contribution',
            'scheduled_at' => $scheduledAt,
            'status' => 'pending',
        ]);

        $job = new AutoContributionJob($goal);
        $job->uuid = $jobUuid;

        dispatch($job)
            ->onQueue('auto-contribution')
            ->delay($scheduledAt);
    }

    /**
     * Cancela todos os jobs agendados para um goal específico
     */
    protected function cancelScheduledJobs(Goal $goal): void
    {
        $scheduledJobs = ScheduledJob::where('goal_id', $goal->id)
            ->where('status', 'pending')
            ->get();

        foreach ($scheduledJobs as $scheduledJob) {
            $this->cancelJobByUuid($scheduledJob->job_uuid, $scheduledJob->queue_name);
            $scheduledJob->markAsCancelled();
        }
    }

    /**
     * Cancela um job específico usando seu UUID
     */
    protected function cancelJobByUuid(string $jobUuid, string $queueName): void
    {
        $this->cancelRedisJobByUuid($jobUuid, $queueName);
    }

    /**
     * Cancela um job no Redis usando seu UUID
     */
    protected function cancelRedisJobByUuid(string $jobUuid, string $queueName): void
    {
        $redis = Redis::connection(config('queue.connections.redis.connection', 'default'));
        $prefix = config('database.redis.options.prefix', '');

        // Chaves onde os jobs são armazenados no Redis
        $queueKey = $prefix . 'queues:' . $queueName;
        $delayedKey = $prefix . 'queues:' . $queueName . ':delayed';
        $reservedKey = $prefix . 'queues:' . $queueName . ':reserved';

        // Remover das filas
        $this->removeJobFromRedisList($redis, $queueKey, $jobUuid);
        $this->removeJobFromRedisZSet($redis, $delayedKey, $jobUuid);
        $this->removeJobFromRedisZSet($redis, $reservedKey, $jobUuid);
    }

    /**
     * Remove um job específico de uma lista Redis usando UUID
     */
    protected function removeJobFromRedisList($redis, string $key, string $jobUuid): void
    {
        $length = $redis->llen($key);

        if ($length === 0) {
            return;
        }

        $jobsToKeep = [];

        for ($i = 0; $i < $length; $i++) {
            $job = $redis->lindex($key, $i);

            if (!$job) {
                continue;
            }

            // Verificar se o job contém o UUID
            if (strpos($job, $jobUuid) === false) {
                $jobsToKeep[] = $job;
            }
        }

        // Reconstruir a lista apenas com os jobs que devem ser mantidos
        if (count($jobsToKeep) < $length) {
            $redis->del($key);
            if (!empty($jobsToKeep)) {
                foreach (array_reverse($jobsToKeep) as $job) {
                    $redis->lpush($key, $job);
                }
            }
        }
    }

    /**
     * Remove um job específico de um sorted set Redis usando UUID
     */
    protected function removeJobFromRedisZSet($redis, string $key, string $jobUuid): void
    {
        $jobs = $redis->zrange($key, 0, -1);

        if (empty($jobs)) {
            return;
        }

        foreach ($jobs as $job) {
            if ($job && strpos($job, $jobUuid) !== false) {
                $redis->zrem($key, $job);
            }
        }
    }

    /**
     * Agenda o próximo job baseado na data de início e próxima contribuição
     */
    protected function scheduleNextJob(Goal $goal): void
    {
        if (!$goal->auto_mode || !$goal->start_contribution_at) {
            return;
        }

        $now = Carbon::now();
        $nextDate = null;

        if ($goal->next_auto_contribution_at && $goal->next_auto_contribution_at->isFuture()) {
            $nextDate = $goal->next_auto_contribution_at;
        } else {
            $startDate = Carbon::parse($goal->start_contribution_at);

            if ($startDate->format('Y-m') === $now->format('Y-m') && $startDate->isFuture()) {
                $nextDate = $startDate;
            } else {
                $nextDate = GoalDateHelper::calculateNextValidDateFromStart($startDate, $now);
            }
        }

        if ($nextDate && $nextDate->isFuture()) {
            $this->scheduleJob($goal, $nextDate);
        }
    }
}
