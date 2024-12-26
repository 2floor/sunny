<?php

use App\Models\Cancer;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class cancer_import implements ToModel, WithBatchInserts, WithChunkReading, WithStartRow
{
    protected $errors = [];
    protected $success = 0;

    public function model(array $row): Model|null
    {
        if ($row[6] && !is_numeric($row[6])) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => '無効な並べ替え順序情報'
            ];

            return null;
        }

        $this->success += 1;
        return new Cancer([
            'cancer_type' => $row[0] ?? null,
            'cancer_type_dpc' => $row[1] ?? null,
            'cancer_type_stage' => $row[3] ?? null,
            'cancer_type_surv' => $row[4] ?? null,
            'public_flg' => ($row[5] == 1) ? Cancer::PUBLISHED : Cancer::UNPUBLISHED,
            'order_num' => $row[6] ?? null,
        ]);
    }

    public function startRow(): int
    {
        return 3;
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function getErrors()
    {
        if (!empty($this->errors)) {
            $header = [
                'row' =>  json_encode(["データベース登録癌名称（案）", "DPC　登録疾患名称", "DPC疾患コード", "病期別総数　対応がん", "5年生存率", "公開フラグ", "表示順"], JSON_UNESCAPED_UNICODE),
                'error' => '',
            ];

            array_unshift($this->errors, $header);

            $header = [
                'row' =>  json_encode(["胃がん", "", "DPCと病期別で完全に一致していないもの", "", "DPCと5年生存率で完全に一致していないもの", "", ""], JSON_UNESCAPED_UNICODE),
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

    public function getCountError(): int
    {
        return count($this->errors);
    }
}
