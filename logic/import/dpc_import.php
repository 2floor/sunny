<?php

use App\Models\Cancer;
use App\Models\DPC;
use App\Models\Hospital;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class dpc_import implements ToModel, WithStartRow, WithBatchInserts, WithChunkReading, WithUpserts
{
    protected $errors = [];
    protected $success = 0;

    public function model(array $row): Model|null
    {
        $hospital = Hospital::where('hospital_code', ($row[0] ?? null))->first();
        $cancer = Cancer::where('cancer_type', ($row[3] ?? null))
            ->orWhere('cancer_type_dpc', ($row[3] ?? null))->first();

        if (!$cancer) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => 'マスターデータにがん情報が見つかりません'
            ];

            return null;
        }

        if (!$hospital) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => 'マスターデータに病院情報がありません'
            ];

            return null;
        }

        if ($row[2] && !is_numeric($row[2])) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => '無効な患者退院情報'
            ];

            return null;
        }

        if (!$row[4] || !is_numeric($row[4])) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => '無効な年情報です'
            ];

            return null;
        }

        $this->success += 1;

        return new DPC([
            'area_id' => $hospital->area_id,
            'cancer_id' => $cancer->id,
            'cancer_name_dpc' => $cancer->cancer_type_dpc,
            'hospital_id' => $hospital->id,
            'hospital_name' => $hospital->hospital_name,
            'year' => $row[4],
            'n_dpc' => is_numeric($row[2]) ? $row[2] : null,
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
                'row' =>  json_encode(["病院ID", "病院名", "退院患者数", "がん種", "年", "エラー"], JSON_UNESCAPED_UNICODE),
                'error' => '',
            ];

            array_unshift($this->errors, $header);
        }

        return $this->errors;
    }

    public function getSuccess()
    {
        return $this->success;
    }
}
