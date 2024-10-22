<?php

use App\Models\DPC;

class dpc_ranking_command
{
    public function handle($cancerId, $year)
    {
        $grouped = DPC::where('year', $year)->where('cancer_id', $cancerId)->get()->groupBy(function($item) {
            return $item->year . '-' . $item->cancer_id;
        });

        foreach ($grouped as $key => $group) {
            $this->rankAndUpdate($group, 'n_dpc', 'rank_nation_dpc');
        }

        $areaGrouped = DPC::where('year', $year)->where('cancer_id', $cancerId)->get()->groupBy(function($item) {
            return $item->year . '-' . $item->area_id . '-' .$item->cancer_id;
        });

        foreach ($areaGrouped as $key => $group) {
            $this->rankAndUpdate($group, 'n_dpc', 'rank_pref_dpc');
        }

        $localGrouped = DPC::leftJoin('m_area', 't_dpc.area_id', '=', 'm_area.id')
            ->select('t_dpc.*', 'm_area.area_name')
            ->where('t_dpc.year', $year)
            ->where('t_dpc.cancer_id', $cancerId)
            ->get()
            ->groupBy(function($item) {
                return $item->year . '-' . $item->area_name . '-' . $item->cancer_id;
            });

        foreach ($localGrouped as $key => $group) {
            $this->rankAndUpdate($group, 'n_dpc', 'rank_area_dpc');
        }
    }

    private function rankAndUpdate($group, $sortBy, $updateColumn) {
        $ranked = $group->sortByDesc($sortBy)->values();

        $currentRank = 1;
        $previousValue = null;
        $sameRankCount = 0;

        foreach ($ranked as $index => $item) {
            if ($item->$sortBy === null) {
                DPC::where('id', $item->id)->update([$updateColumn => null]);
                continue;
            }

            if ($previousValue !== null && $item->$sortBy != $previousValue) {
                $currentRank += $sameRankCount;
                $sameRankCount = 0;
            }

            DPC::where('id', $item->id)->update([$updateColumn => $currentRank]);

            $previousValue = $item->$sortBy;
            $sameRankCount += 1;
        }
    }
}