<?php

use App\Models\Cancer;
use App\Models\DPC;
use App\Models\Hospital;
use App\Models\MissMatch;
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
    protected $hospitalMaster = [];

    public function __construct(protected $fileName = null)
    {
        $this->hospitalMaster = Hospital::withoutGlobalScope('unpublish')
            ->select(['id', 'hospital_name', 'area_id'])
            ->get();
    }

    public function model(array $row): Model|null
    {
        if (!$row[1]) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => '病院名は空白にすることはできません'
            ];

            return null;
        }

        $hospital_name = trim($row[1]);
        $hospital = Hospital::withoutGlobalScope('unpublish')->where('hospital_name', $hospital_name)->first();
        $cancer = Cancer::withoutGlobalScope('unpublish')->where('cancer_type', ($row[3] ?? null))
            ->orWhere('cancer_type_dpc', ($row[3] ?? null))->first();

        if (!$cancer) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => 'マスターデータにがん情報が見つかりません'
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

        if (!$hospital) {
            $mm = MissMatch::where([
                'hospital_name' => $hospital_name,
                'status' => MissMatch::STATUS_CONFIRMED,
                'cancer_id' => $cancer->id
            ])->orderBy('year', 'desc')->first();
            if ($mm) {
                $hospital = Hospital::withoutGlobalScope('unpublish')->find($mm->hospital_id);
            } else {
                $mostSimilar = $this->hospitalMaster->map(function ($hospital) use ($hospital_name) {
                    similar_text($hospital_name, $hospital->hospital_name, $percent);
                    return [
                        'hospital' => $hospital,
                        'similarity' => $percent,
                    ];
                })->sortByDesc('similarity')->first();

                $mmHospitalId = null;
                $percent = null;
                if (!empty($mostSimilar) && $mostSimilar['similarity'] > 70) {
                    $hospital = $mostSimilar['hospital'];
                    $percent = $mostSimilar['similarity'];
                    $mmHospitalId = $hospital->id;
                }

                if ($mmHospitalId) {
                    MissMatch::updateOrCreate([
                        'hospital_id' => $mmHospitalId,
                        'type' => MissMatch::TYPE_DPC,
                        'year' => $row[4],
                        'cancer_id' => $cancer->id,
                    ], [
                        'hospital_name' => $hospital_name,
                        'area_id' => $hospital?->area_id,
                        'percent_match' => $percent,
                        'import_file' => $this->fileName,
                        'import_value' => json_encode($row, JSON_UNESCAPED_UNICODE),
                    ]);
                } else {
                    $vr = MissMatch::create([
                        'hospital_id' => null,
                        'hospital_name' => $hospital_name,
                        'type' => MissMatch::TYPE_DPC,
                        'cancer_id' => $cancer->id,
                        'area_id' => $hospital?->area_id,
                        'year' => $row[4],
                        'percent_match' => $percent,
                        'import_file' => $this->fileName,
                        'import_value' => json_encode($row, JSON_UNESCAPED_UNICODE),
                    ]);
                }

                if (!$mmHospitalId) {
                    $this->errors[] = [
                        'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                        'error' => 'マスターデータに病院名がありません'
                    ];
                    return null;
                }

            }
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
                'row' =>  json_encode(["病院ID", "病院名", "退院患者数", "がん種", "年"], JSON_UNESCAPED_UNICODE),
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
