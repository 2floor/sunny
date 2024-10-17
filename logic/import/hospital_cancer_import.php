<?php

require_once __DIR__ . '/../../third_party/bootstrap.php';

use App\Models\Cancer;
use App\Models\Category;
use App\Models\Hospital;
use App\Models\HospitalCancer;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class hospital_cancer_import implements OnEachRow, WithBatchInserts, WithChunkReading, WithStartRow
{
    protected $errors = [];
    protected $success = 0;

    public function onRow($row)
    {
        $row = $row->toArray();

        $cancer = Cancer::where('cancer_type', ($row[4] ?? null))->first();

        $hospital = Hospital::where('hospital_code', ($row[2] ?? null))->first();

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

        HospitalCancer::updateOrCreate([
            'hospital_id' => $hospital->id,
            'cancer_id' => $cancer->id
        ], [
            'hospital_name' => $hospital->hospital_name,
            'cancer_name' => $cancer->cancer_type,
            'base_hospital' => $row[5] ?? null,
            'social_info' => $row[7] ?? null,
            'remarks' => $row[8] ?? null,
            'sp_treatment' => $row[14] ?? null,
        ]);

        $this->updateCategories($hospital, $cancer, $row);
        $this->success += 1;
    }

    private function updateCategories($hospital, $cancer, $row)
    {
        $categoryIds = $hospital->categories()
            ->where('is_whole_cancer', Category::NOT_FOR_ALL_CANCER)
            ->pluck('t_category.id')
            ->unique()
            ->toArray();

        $categoryIdsToDetach = $hospital->categories()
            ->wherePivot('cancer_id', $cancer->id)
            ->whereIn('category_id', $categoryIds)
            ->pluck('t_category.id')
            ->toArray();

        $hospital->categories()->detach($categoryIdsToDetach);

        if (!empty($row[6]) && $row[6] !== 0) {
            $category = Category::where([
                'data_type' => Category::HOSPITAL_DETAIL_TYPE,
                'hard_name3' => 'multi_treatment',
            ])->first();

            $hospital->categories()->attach($category->id, ['cancer_id' => $cancer->id]);
        }

        if (!empty($row[9]) && $row[9] !== 0) {
            $category = Category::where([
                'data_type' => Category::HOSPITAL_DETAIL_TYPE,
                'hard_name3' => 'famous_doctor',
            ])->first();


            $hospital->categories()->attach($category->id, ['content1' => ($row[11] ?? null), 'content2' => ($row[10] ?? null), 'cancer_id' => $cancer->id]);
        }

        if (!empty($row[13]) && $row[13] !== 0) {

            $category = Category::where([
                'data_type' => Category::HOSPITAL_DETAIL_TYPE,
                'hard_name3' => 'advanced_medical',
            ])->first();

            $hospital->categories()->attach($category->id, ['content1' => ($row[13] ?? null), 'cancer_id' => $cancer->id]);
        }

        $this->updateHospitalPolicy($row[15] ?? null, 'avoid_drug', $hospital, $cancer->id);
        $this->updateHospitalPolicy($row[16] ?? null, 'avoid_surgery', $hospital, $cancer->id);
        $this->updateHospitalPolicy($row[17] ?? null, 'avoid_radiation_therapy', $hospital, $cancer->id);
        $this->updateHospitalPolicy($row[18] ?? null, 'surgery_soon', $hospital, $cancer->id);
        $this->updateHospitalPolicy($row[19] ?? null, 'leave_hospital_soon', $hospital, $cancer->id);
        $this->updateHospitalPolicy($row[20] ?? null, 'good_anesthesiologist', $hospital, $cancer->id);
        $this->updateHospitalPolicy($row[21] ?? null, 'female_doctor', $hospital, $cancer->id);
        $this->updateHospitalPolicy($row[22] ?? null, 'apply_conditions', $hospital, $cancer->id);
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

    public function getErrors()
    {
        if (!empty($this->errors)) {
            $header = [
                'row' =>  json_encode(["都道府県", "", "ID", "SHデータベース登録新名称", "", "拠点病院（確認用）", "集学的治療体制","学会認定施設情報", "特記事項", "名医", "TOP名医　医師名", "名医 医師名", "先進医療", "先進医療　内容", "特別な治療法 陽子線、重粒子線など", "薬物療法を避けたい", "手術を避けたい", "放射線治療を避けたい", "早く手術を受けたい", "なるべく早く退院したい", "麻酔科の腕が良い", "女医が担当してくれる", "患者受け入れ条件あり"], JSON_UNESCAPED_UNICODE),
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

    private function updateHospitalPolicy($value, $hardName3, $hospital, $cancerId)
    {
        if (!empty($value) && $value !== 0) {
            $category = Category::where([
                'data_type' => Category::HOSPITAL_POLICY_TYPE,
                'hard_name3' => $hardName3,
            ])->first();

            $hospital->categories()->attach($category->id, ['cancer_id' => $cancerId]);
        }
    }
}
