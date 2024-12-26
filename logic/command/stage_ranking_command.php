<?php

use App\Models\Stage;

class stage_ranking_command
{
    public function handle($cancerId, $year)
    {
        $grouped = Stage::where('year', $year)->where('cancer_id', $cancerId)->get()->groupBy(function($item) {
            return $item->year . '-' . $item->cancer_id;
        });

        foreach ($grouped as $key => $group) {
            $this->rankAndUpdate($group, 'total_num_new', 'total_num_rank');
            $this->rankAndUpdate($group, 'stage_new1', 'total_num_rank_stage1');
            $this->rankAndUpdate($group, 'stage_new2', 'total_num_rank_stage2');
            $this->rankAndUpdate($group, 'stage_new3', 'total_num_rank_stage3');
            $this->rankAndUpdate($group, 'stage_new4', 'total_num_rank_stage4');
        }

        $areaGrouped = Stage::where('year', $year)->where('cancer_id', $cancerId)->get()->groupBy(function($item) {
            return $item->year . '-' . $item->area_id . '-' .$item->cancer_id;
        });

        foreach ($areaGrouped as $key => $group) {
            $this->rankAndUpdate($group, 'total_num_new', 'pref_num_rank');
            $this->rankAndUpdate($group, 'stage_new1', 'pref_num_rank_stage1');
            $this->rankAndUpdate($group, 'stage_new2', 'pref_num_rank_stage2');
            $this->rankAndUpdate($group, 'stage_new3', 'pref_num_rank_stage3');
            $this->rankAndUpdate($group, 'stage_new4', 'pref_num_rank_stage4');
        }

        $localGrouped = Stage::leftJoin('m_area', 't_stage.area_id', '=', 'm_area.id')
            ->select('t_stage.*', 'm_area.area_name')->where('t_stage.year', $year)->where('t_stage.cancer_id', $cancerId)
            ->get()
            ->groupBy(function($item) {
                return $item->year . '-' . $item->area_name . '-' . $item->cancer_id;
            });

        foreach ($localGrouped as $key => $group) {
            $this->rankAndUpdate($group, 'total_num_new', 'local_num_rank');
            $this->rankAndUpdate($group, 'stage_new1', 'local_num_rank_stage1');
            $this->rankAndUpdate($group, 'stage_new2', 'local_num_rank_stage2');
            $this->rankAndUpdate($group, 'stage_new3', 'local_num_rank_stage3');
            $this->rankAndUpdate($group, 'stage_new4', 'local_num_rank_stage4');
        }
    }

    private function rankAndUpdate($group, $sortBy, $updateColumn) {
        $ranked = $group->sortByDesc($sortBy)->values();

        $currentRank = 1;
        $previousValue = null;
        $sameRankCount = 0;

        foreach ($ranked as $index => $item) {
            if ($item->$sortBy === null) {
                Stage::where('id', $item->id)->update([$updateColumn => null]);
                continue;
            }

            if ($previousValue !== null && $item->$sortBy != $previousValue) {
                $currentRank += $sameRankCount;
                $sameRankCount = 0;
            }

            Stage::where('id', $item->id)->update([$updateColumn => $currentRank]);

            $previousValue = $item->$sortBy;
            $sameRankCount += 1;
        }
    }
}