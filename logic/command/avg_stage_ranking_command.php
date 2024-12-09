<?php

use App\Models\Stage;
use App\Models\StageAvg;

class avg_stage_ranking_command
{
    public function handle($cancerId, $year)
    {
        $this->genData($cancerId, $year);
        $this->genRank($cancerId, $year);
    }

    private function genData($cancerId, $year)
    {
        $currentGroup = null;
        $currentRecords = [];

        Stage::select('hospital_id', 'cancer_id', 'area_id', 'year', 'total_num_new', 'stage_new1', 'stage_new2', 'stage_new3', 'stage_new4')
            ->where('cancer_id', $cancerId)
            ->where('year', '<=', $year)
            ->orderBy('hospital_id')
            ->orderBy('year', 'desc')
            ->cursor()
            ->each(function ($record) use (&$currentGroup, &$currentRecords, $cancerId) {
                $key = $record->hospital_id;

                if ($currentGroup !== $key) {
                    if ($currentGroup !== null) {
                        $this->processGroup($currentGroup, $currentRecords, $cancerId);
                    }


                    $currentGroup = $key;
                    $currentRecords = [];
                }

                if (count($currentRecords) < 3) {
                    $currentRecords[] = $record;
                }
            });

        if ($currentGroup !== null) {
            $this->processGroup($currentGroup, $currentRecords, $cancerId);
        }
    }

    private function processGroup($key, $records, $cancerId) {
        $firstRecord = $records[0];
        $colRecord = collect($records);

        StageAvg::updateOrCreate(
            [
                'hospital_id' => $key,
                'cancer_id' => $cancerId,
                'lasted_year' => $firstRecord->year,
            ],
            [
                'area_id' => $firstRecord->area_id,
                'avg_total_num_new' => $colRecord->avg('total_num_new'),
                'avg_stage_new1' => $colRecord->avg('stage_new1'),
                'avg_stage_new2' => $colRecord->avg('stage_new2'),
                'avg_stage_new3' => $colRecord->avg('stage_new3'),
                'avg_stage_new4' => $colRecord->avg('stage_new4'),
            ]
        );
    }

    private function genRank($cancerId, $year)
    {
        $grouped = StageAvg::where('lasted_year', $year)->where('cancer_id', $cancerId)->get()->groupBy(function($item) {
            return $item->lasted_year . '-' . $item->cancer_id;
        });

        foreach ($grouped as $key => $group) {
            $this->rankAndUpdate($group, 'avg_total_num_new', 'avg_total_num_rank');
            $this->rankAndUpdate($group, 'avg_stage_new1', 'avg_total_num_rank_stage1');
            $this->rankAndUpdate($group, 'avg_stage_new2', 'avg_total_num_rank_stage2');
            $this->rankAndUpdate($group, 'avg_stage_new3', 'avg_total_num_rank_stage3');
            $this->rankAndUpdate($group, 'avg_stage_new4', 'avg_total_num_rank_stage4');
        }

        $areaGrouped = StageAvg::where('lasted_year', $year)->where('cancer_id', $cancerId)->get()->groupBy(function($item) {
            return $item->lasted_year . '-' . $item->area_id . '-' .$item->cancer_id;
        });

        foreach ($areaGrouped as $key => $group) {
            $this->rankAndUpdate($group, 'avg_total_num_new', 'avg_pref_num_rank');
            $this->rankAndUpdate($group, 'avg_stage_new1', 'avg_pref_num_rank_stage1');
            $this->rankAndUpdate($group, 'avg_stage_new2', 'avg_pref_num_rank_stage2');
            $this->rankAndUpdate($group, 'avg_stage_new3', 'avg_pref_num_rank_stage3');
            $this->rankAndUpdate($group, 'avg_stage_new4', 'avg_pref_num_rank_stage4');
        }

        $localGrouped = StageAvg::leftJoin('m_area', 't_stage_avg.area_id', '=', 'm_area.id')
            ->select('t_stage_avg.*', 'm_area.area_name')->where('t_stage_avg.lasted_year', $year)->where('t_stage_avg.cancer_id', $cancerId)
            ->get()
            ->groupBy(function($item) {
                return $item->lasted_year . '-' . $item->area_name . '-' . $item->cancer_id;
            });

        foreach ($localGrouped as $key => $group) {
            $this->rankAndUpdate($group, 'avg_total_num_new', 'avg_local_num_rank');
            $this->rankAndUpdate($group, 'avg_stage_new1', 'avg_local_num_rank_stage1');
            $this->rankAndUpdate($group, 'avg_stage_new2', 'avg_local_num_rank_stage2');
            $this->rankAndUpdate($group, 'avg_stage_new3', 'avg_local_num_rank_stage3');
            $this->rankAndUpdate($group, 'avg_stage_new4', 'avg_local_num_rank_stage4');
        }
    }

    private function rankAndUpdate($group, $sortBy, $updateColumn) {
        $ranked = $group->sortByDesc($sortBy)->values();

        $currentRank = 1;
        $previousValue = null;
        $sameRankCount = 0;

        foreach ($ranked as $index => $item) {
            if ($item->$sortBy === null) {
                StageAvg::where('id', $item->id)->update([$updateColumn => null]);
                continue;
            }

            if ($previousValue !== null && $item->$sortBy != $previousValue) {
                $currentRank += $sameRankCount;
                $sameRankCount = 0;
            }

            StageAvg::where('id', $item->id)->update([$updateColumn => $currentRank]);

            $previousValue = $item->$sortBy;
            $sameRankCount += 1;
        }
    }
}