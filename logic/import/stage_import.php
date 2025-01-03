<?php

require_once __DIR__ . '/../../logic/import/base_import.php';

use App\Models\Hospital;
use App\Models\MissMatch;
use App\Models\Stage;
use App\Models\Cancer;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class stage_import extends base_import implements ToModel, WithBatchInserts, WithChunkReading, WithStartRow, WithUpserts
{
    public function getMMType ()
    {
        return MissMatch::TYPE_STAGE;
    }

    public function model(array $row): Model|null
    {
        if (!$row[1]) {
            $this->addError($row, '病院名は空白にすることはできません');
            return null;
        }

        $hospital_name = trim($row[1]);
        $hospital = Hospital::withoutGlobalScope('unpublish')->where('hospital_name', $hospital_name)->first();
        $cancer = Cancer::withoutGlobalScope('unpublish')->where('cancer_type', ($row[7] ?? null))
            ->orWhere('cancer_type_stage', ($row[7] ?? null))->first();

        if (!$cancer) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => 'マスターデータにがん情報が見つかりません'
            ];

            return null;
        }

        if (!$row[8] || !is_numeric($row[8])) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => '無効な年情報です'
            ];

            return null;
        }

        if (!$hospital) {
            $hospital =  $this->handleMMHospital($hospital_name, $cancer->id, $row[8], $row);
            if (!$hospital) {
                return null;
            }
        }

        $this->success += 1;

        return new Stage([
            'area_id' => $hospital->area_id,
            'cancer_id' => $cancer->id,
            'cancer_name_stage' => $cancer->cancer_type_stage,
            'hospital_id' => $hospital->id,
            'hospital_name' => $hospital->hospital_name,
            'year' => $row[8],
            'total_num_new' => is_numeric($row[6]) ? $row[6] : null,
            'stage_new1' => is_numeric($row[2]) ? $row[2] : null,
            'stage_new2' => is_numeric($row[3]) ? $row[3] : null,
            'stage_new3' => is_numeric($row[4]) ? $row[4] : null,
            'stage_new4' => is_numeric($row[5]) ? $row[5] : null,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function uniqueBy()
    {
        return ['year', 'cancer_id', 'hospital_id'];
    }

    public function getErrors()
    {
        if (!empty($this->errors)) {
            $header = [
                'row' =>  json_encode(["病院ID", "施設", "Ⅰ期", "Ⅱ期", "Ⅲ期", "Ⅳ期", "総数", "がん種", "年"], JSON_UNESCAPED_UNICODE),
                'error' => '',
            ];

            array_unshift($this->errors, $header);
        }

        return $this->errors;
    }
}
