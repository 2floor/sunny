<?php

require_once __DIR__ . '/../../logic/import/base_import.php';

use App\Models\Cancer;
use App\Models\Hospital;
use App\Models\MissMatch;
use App\Models\SurvAverage;
use App\Models\SurvHospital;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class survival_import extends base_import implements OnEachRow, WithBatchInserts, WithChunkReading
{
    protected $headers = [];

    public function getMMType ()
    {
        return MissMatch::TYPE_SURVIVAL;
    }

    public function onRow($row)
    {
        if ($row->getIndex() <= 3) {
            $this->generateHeader($row);
            return;
        }

        $row = $row->toArray();

        if (!$row[2]) {
            $this->addError($row, '病院名は空白にすることはできません');
            return null;
        }

        $hospital_name = trim($row[2]);
        $hospital_master = Hospital::withoutGlobalScope('unpublish')->where('hospital_name', $hospital_name)->first();
        $cancers = Cancer::withoutGlobalScope('unpublish')->where('cancer_type', ($row[3] ? trim($row[3]) : null))
            ->orWhere('cancer_type_surv', ($row[3] ? trim($row[3]) : null))->get();

        if (empty($cancers)) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => 'マスターデータにがん情報が見つかりません'
            ];

            return null;
        }

        if (!$row[0] || !is_numeric($row[0])) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => '無効な年情報です'
            ];

            return null;
        }

        if (isset($row[4]) && $row[4] !== '' && !is_numeric($row4 = $this->convertNumberFormat($row[4]))) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => '患者数総和数値形式ではありません'
            ];

            return null;
        }

        if (isset($row[5]) && $row[5] !== '' && !is_numeric($row5 = $this->convertNumberFormat($row[5]))) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => '患者数Ⅰ期数値形式ではありません'
            ];

            return null;
        }

        if (isset($row[6]) && $row[6] !== '' && !is_numeric($row6 = $this->convertNumberFormat($row[6]))) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => '患者数Ⅱ期数値形式ではありません'
            ];

            return null;
        }

        if (isset($row[7]) && $row[7] !== '' && !is_numeric($row7 = $this->convertNumberFormat($row[7]))) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => '患者数Ⅲ期数値形式ではありません'
            ];

            return null;
        }

        if (isset($row[8]) && $row[8] !== '' && !is_numeric($row8 = $this->convertNumberFormat($row[8]))) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => '患者数Ⅳ期数値形式ではありません'
            ];

            return null;
        }

        if (isset($row[9]) && $row[9] !== '' && !is_numeric($row9 = $this->convertNumberFormat($row[9]))) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => '生存率Ⅰ期数値形式ではありません'
            ];

            return null;
        }

        if (isset($row[10]) && $row[10] !== '' && !is_numeric($row10 = $this->convertNumberFormat($row[10]))) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => '生存率Ⅱ期数値形式ではありません'
            ];

            return null;
        }

        if (isset($row[11]) && $row[11] !== '' && !is_numeric($row11 = $this->convertNumberFormat($row[11]))) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => '生存率Ⅲ期数値形式ではありません'
            ];

            return null;
        }

        if (isset($row[12]) && $row[12] !== '' && !is_numeric($row12 = $this->convertNumberFormat($row[12]))) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => '生存率Ⅳ期数値形式ではありません'
            ];

            return null;
        }

        if (isset($row[23]) && $row[23] !== '' && !is_numeric($row23 = $this->convertNumberFormat($row[23]))) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => '生存率係数数値形式ではありません'
            ];

            return null;
        }

        if (isset($row[24]) && $row[24] !== '' && !is_numeric($row24 = $this->convertNumberFormat($row[24]))) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => '全国平均実測生存率Ⅰ期数値形式ではありません'
            ];

            return null;
        }

        if (isset($row[25]) && $row[25] !== '' && !is_numeric($row25 = $this->convertNumberFormat($row[25]))) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => '全国平均実測生存率Ⅱ期数値形式ではありません'
            ];

            return null;
        }

        if (isset($row[26]) && $row[26] !== '' && !is_numeric($row26 = $this->convertNumberFormat($row[26]))) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => '全国平均実測生存率Ⅲ期数値形式ではありません'
            ];

            return null;
        }

        if (isset($row[27]) && $row[27] !== '' && !is_numeric($row27 = $this->convertNumberFormat($row[27]))) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => '全国平均実測生存率Ⅳ期数値形式ではありません'
            ];

            return null;
        }


        $totalNum = $row4 ? round($row4, 2) : null;
        $stageTarget1 = $row5 ? round($row5, 2) : null;
        $stageTarget2 = $row6 ? round($row6, 2) : null;
        $stageTarget3 = $row7 ? round($row7, 2) : null;
        $stageTarget4 = $row8 ? round($row8, 2) : null;
        $stageSurvivalRate1 = $row9 ? (round($row9, 4) * 100): null;
        $stageSurvivalRate2 = $row10 ? (round($row10, 4) * 100): null;
        $stageSurvivalRate3 = $row11 ? (round($row11, 4) * 100): null;
        $stageSurvivalRate4 = $row12 ? (round($row12, 4) * 100): null;
        $survivalRate = $row23 ? round($row23, 2) : null;

        $avg1 =  $row24 ? (round($row24, 4) * 100): null;
        $avg2 =  $row25 ? (round($row25, 4) * 100): null;
        $avg3 =  $row26 ? (round($row26, 4) * 100): null;
        $avg4 =  $row27 ? (round($row27, 4) * 100): null;

        foreach ($cancers as $cancer) {
            if (!$hospital_master) {
                $hospital =  $this->handleMMHospital($hospital_name, $cancer->id, $row[0], $row);
                if (!$hospital) {
                    continue;
                }
            } else {
                $hospital = $hospital_master;
            }

            SurvHospital::withoutGlobalScope('unpublish')->updateOrCreate([
                'hospital_id' => $hospital->id,
                'cancer_id' => $cancer->id,
                'year' => $row[0],
            ],[
                'area_id' => $hospital->area_id,
                'total_num' => $totalNum,
                'stage_target1' => $stageTarget1,
                'stage_target2' => $stageTarget2,
                'stage_target3' => $stageTarget3,
                'stage_target4' => $stageTarget4,
                'stage_survival_rate1' => $stageSurvivalRate1,
                'stage_survival_rate2' => $stageSurvivalRate2,
                'stage_survival_rate3' => $stageSurvivalRate3,
                'stage_survival_rate4' => $stageSurvivalRate4,
                'survival_rate' => $survivalRate,
            ]);

            SurvAverage::updateOrCreate([
                'cancer_id' => $cancer->id,
                'year' => $row[0],
            ], [
                'stage_survival1' => $avg1,
                'stage_survival2' => $avg2,
                'stage_survival3' => $avg3,
                'stage_survival4' => $avg4,
            ]);

            $this->success += 1;
        }
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    private function convertNumberFormat($number) {
        return str_replace(',', '', $number);
    }

    private function generateHeader($row)
    {
        $header = array_map(function($value) {
            return $value === null ? "" : $value;
        }, $row->toArray());

        $this->headers[] = [
            'row' => json_encode($header, JSON_UNESCAPED_UNICODE),
            'error' => ''
        ];
    }

    public function getErrors()
    {
        if ($this->getCountError() > 0) {

            array_push($this->headers, ...$this->errors);
            return $this->headers;
        }

        return [];
    }
}
