<?php

namespace App\Helpers;

use Carbon\Carbon;

class GoalDateHelper
{
    /**
     * Calcula a próxima data válida de contribuição a partir de uma data base
     * Considera casos especiais onde o dia não existe em alguns meses
     *
     * Ex: 31 de janeiro -> 1 de março (não 31 de fevereiro)
     * Ex: 29 de janeiro (ano bissexto) -> 29 de fevereiro
     *
     * @param Carbon $baseDate Data base para calcular a próxima data
     * @return Carbon Próxima data válida
     */
    public static function calculateNextContributionDate(Carbon $baseDate): Carbon
    {
        $nextDate = $baseDate->copy()->addMonth();

        $dayOfMonth = $baseDate->day;
        $lastDayOfNextMonth = $nextDate->copy()->endOfMonth()->day;

        if ($dayOfMonth <= $lastDayOfNextMonth) {
            $nextDate->day($dayOfMonth);
        } else {
            $nextDate->addMonth()->startOfMonth();
        }

        return $nextDate;
    }

    /**
     * Calcula a próxima data válida a partir de uma data de início e a data atual
     * Usado para reagendar quando auto_mode é reativado
     *
     * @param Carbon $startDate Data de início da contribuição
     * @param Carbon $now Data atual
     * @return Carbon Próxima data válida
     */
    public static function calculateNextValidDateFromStart(Carbon $startDate, Carbon $now): Carbon
    {
        $dayOfMonth = $startDate->day;

        $nextDate = $now->copy()->startOfMonth()->day($dayOfMonth);

        if ($nextDate->isPast() || $nextDate->isToday()) {
            $nextDate->addMonth();

            $lastDayOfNextMonth = $nextDate->copy()->endOfMonth()->day;

            if ($dayOfMonth <= $lastDayOfNextMonth) {
                $nextDate->day($dayOfMonth);
            } else {
                $nextDate->addMonth()->startOfMonth();
            }
        }

        return $nextDate;
    }
}

