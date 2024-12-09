<?php

use App\Models\DPC;
use App\Models\DPCAvg;

class avg_dpc_ranking_command
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

        DPC::select('hospital_id', 'cancer_id', 'year', 'area_id', 'n_dpc')
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

        DPCAvg::updateOrCreate(
            [
                'hospital_id' => $key,
                'cancer_id' => $cancerId,
                'lasted_year' => $firstRecord->year,
            ],
            [
                'area_id' => $firstRecord->area_id,
                'avg_n_dpc' => collect($records)->avg('n_dpc'),
            ]
        );
    }

    private function genRank($cancerId, $year)
    {
        $grouped = DPCAvg::where('lasted_year', $year)->where('cancer_id', $cancerId)->get()->groupBy(function($item) {
            return $item->lasted_year . '-' . $item->cancer_id;
        });

        foreach ($grouped as $key => $group) {
            $this->rankAndUpdate($group, 'avg_n_dpc', 'avg_rank_nation_dpc');
        }

        $areaGrouped = DPCAvg::where('lasted_year', $year)->where('cancer_id', $cancerId)->get()->groupBy(function($item) {
            return $item->lasted_year . '-' . $item->area_id . '-' .$item->cancer_id;
        });

        foreach ($areaGrouped as $key => $group) {
            $this->rankAndUpdate($group, 'avg_n_dpc', 'avg_rank_pref_dpc');
        }

        $localGrouped = DPCAvg::leftJoin('m_area', 't_dpc_avg.area_id', '=', 'm_area.id')
            ->select('t_dpc_avg.*', 'm_area.area_name')->where('t_dpc_avg.lasted_year', $year)->where('t_dpc_avg.cancer_id', $cancerId)
            ->get()
            ->groupBy(function($item) {
                return $item->lasted_year . '-' . $item->area_name . '-' . $item->cancer_id;
            });

        foreach ($localGrouped as $key => $group) {
            $this->rankAndUpdate($group, 'avg_n_dpc', 'avg_rank_area_dpc');
        }
    }

    private function rankAndUpdate($group, $sortBy, $updateColumn) {
        $ranked = $group->sortByDesc($sortBy)->values();

        $currentRank = 1;
        $previousValue = null;
        $sameRankCount = 0;

        foreach ($ranked as $index => $item) {
            if ($item->$sortBy === null) {
                DPCAvg::where('id', $item->id)->update([$updateColumn => null]);
                continue;
            }

            if ($previousValue !== null && $item->$sortBy != $previousValue) {
                $currentRank += $sameRankCount;
                $sameRankCount = 0;
            }

            DPCAvg::where('id', $item->id)->update([$updateColumn => $currentRank]);

            $previousValue = $item->$sortBy;
            $sameRankCount += 1;
        }
    }
}