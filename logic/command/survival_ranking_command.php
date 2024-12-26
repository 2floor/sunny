<?php

use App\Models\SurvHospital;

class survival_ranking_command
{
    public function handle($cancerId, $year)
    {
        $grouped = SurvHospital::all()->groupBy(function($item) {
            return $item->year . '-' . $item->cancer_id;
        });

        foreach ($grouped as $key => $group) {
            $this->rankAndUpdate($group, 'total_num', 'total_stage_total_taget');
            $this->rankAndUpdate($group, 'stage_target1', 'total_stage_taget1');
            $this->rankAndUpdate($group, 'stage_target2', 'total_stage_taget2');
            $this->rankAndUpdate($group, 'stage_target3', 'total_stage_taget3');
            $this->rankAndUpdate($group, 'stage_target4', 'total_stage_taget4');

            $this->rankAndUpdate($group, 'survival_rate', 'total_survival_rate');
            $this->rankAndUpdate($group, 'stage_survival_rate1', 'total_survival_rate1');
            $this->rankAndUpdate($group, 'stage_survival_rate2', 'total_survival_rate2');
            $this->rankAndUpdate($group, 'stage_survival_rate3', 'total_survival_rate3');
            $this->rankAndUpdate($group, 'stage_survival_rate4', 'total_survival_rate4');
        }

        $areaGrouped = SurvHospital::all()->groupBy(function($item) {
            return $item->year . '-' . $item->area_id . '-' .$item->cancer_id;
        });

        foreach ($areaGrouped as $key => $group) {
            $this->rankAndUpdate($group, 'total_num', 'pref_stage_total_taget');
            $this->rankAndUpdate($group, 'stage_target1', 'pref_stage_taget1');
            $this->rankAndUpdate($group, 'stage_target2', 'pref_stage_taget2');
            $this->rankAndUpdate($group, 'stage_target3', 'pref_stage_taget3');
            $this->rankAndUpdate($group, 'stage_target4', 'pref_stage_taget4');

            $this->rankAndUpdate($group, 'survival_rate', 'pref_survival_rate');
            $this->rankAndUpdate($group, 'stage_survival_rate1', 'pref_survival_rate1');
            $this->rankAndUpdate($group, 'stage_survival_rate2', 'pref_survival_rate2');
            $this->rankAndUpdate($group, 'stage_survival_rate3', 'pref_survival_rate3');
            $this->rankAndUpdate($group, 'stage_survival_rate4', 'pref_survival_rate4');
        }

        $localGrouped = SurvHospital::leftJoin('m_area', 't_surv_hospital.area_id', '=', 'm_area.id')
            ->select('t_surv_hospital.*', 'm_area.area_name')
            ->get()
            ->groupBy(function($item) {
                return $item->year . '-' . $item->area_name . '-' . $item->cancer_id;
            });

        foreach ($localGrouped as $key => $group) {
            $this->rankAndUpdate($group, 'total_num', 'local_stage_total_taget');
            $this->rankAndUpdate($group, 'stage_target1', 'local_stage_taget1');
            $this->rankAndUpdate($group, 'stage_target2', 'local_stage_taget2');
            $this->rankAndUpdate($group, 'stage_target3', 'local_stage_taget3');
            $this->rankAndUpdate($group, 'stage_target4', 'local_stage_taget4');

            $this->rankAndUpdate($group, 'survival_rate', 'local_survival_rate');
            $this->rankAndUpdate($group, 'stage_survival_rate1', 'local_survival_rate1');
            $this->rankAndUpdate($group, 'stage_survival_rate2', 'local_survival_rate2');
            $this->rankAndUpdate($group, 'stage_survival_rate3', 'local_survival_rate3');
            $this->rankAndUpdate($group, 'stage_survival_rate4', 'local_survival_rate4');

        }
    }

    private function rankAndUpdate($group, $sortBy, $updateColumn) {
        $ranked = $group->sortByDesc($sortBy)->values();

        $currentRank = 1;
        $previousValue = null;
        $sameRankCount = 0;

        foreach ($ranked as $index => $item) {
            if ($item->$sortBy === null) {
                SurvHospital::where('id', $item->id)->update([$updateColumn => null]);
                continue;
            }

            if ($previousValue !== null && $item->$sortBy != $previousValue) {
                $currentRank += $sameRankCount;
                $sameRankCount = 0;
            }

            SurvHospital::where('id', $item->id)->update([$updateColumn => $currentRank]);

            $previousValue = $item->$sortBy;
            $sameRankCount += 1;
        }
    }
}