<?php

require_once __DIR__ . '/../../third_party/bootstrap.php';

use App\Models\Category;
use App\Models\Hospital;
use App\Models\Area;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class hospital_import implements OnEachRow, WithBatchInserts, WithChunkReading, WithStartRow
{
    protected $errors = [];
    protected $success = 0;

    public function onRow($row)
    {
        $row = $row->toArray();

        while (count($row) < 20) {
            $row[] = '';
        }

        if (empty($row[4]) || !is_numeric($row[4])) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => '病院IDが空か数字ではありません'
            ];

            return null;
        }

        if (empty($row[2]) || !Area::where('id', $row[2])->exists()) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => 'エリア情報が間違っています'
            ];

            return null;
        }

        $hospital = Hospital::updateOrCreate([
            'hospital_code' => $row[4],
        ], [
            'area_id' => $row[2] ?? null,
            'hospital_name' => $row[3] ?? null,
            'addr' => $row[5] ?? null,
            'tel' => $row[6] ?? null,
            'hp_url' => $row[7] ?? null,
            'social_info' => $row[10] ?? null,
            'support_url' => $row[13] ?? null,
            'introduction_url' => $row[14] ?? null,
            'remarks' => $row[17] ?? null,
            'public_flg' => 0,
        ]);

        if (!$hospital) {
            $this->errors[] = [
                'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
                'error' => 'データのインポートに失敗しました'
            ];

            return null;
        }

        $this->updateCategories($hospital, $row);
        $this->success += 1;
    }

    private function updateCategories(Hospital $hospital, array $row)
    {
        $categoryIds = $hospital->categories()->where('is_whole_cancer', Category::FOR_ALL_CANCER)->pluck('t_category.id')->unique()->toArray();
        $hospital->categories()->detach($categoryIds);

        $this->attachCategory($hospital, $row[8], '病院区分', 2, 'hospital_type');
        $this->attachCategory($hospital, $row[9], 'ゲノム拠点病院区分', 3, 'hospital_gen');
        $this->attachCategoryWithContent($hospital, $row[11], $row[12], 'special_clinic');
        $this->attachCategoryWithContent($hospital, $row[15], $row[16], 'light_care');
    }

    private function attachCategory(Hospital $hospital, $level3, $level2, $orderNum, $hard_name2)
    {
        if (!empty($level3)) {
            $category = Category::firstOrCreate([
                'data_type' => Category::HOSPITAL_DETAIL_TYPE,
                'hard_name2' => $hard_name2,
                'level3' => $level3,
            ], [
                'level1' => '病院詳細',
                'level2' => $level2,
                'order_num' => $orderNum,
                'category_group' => Category::HOSPITAL_GROUP,
            ]);

            $hospital->categories()->attach($category->id);
        }
    }

    private function attachCategoryWithContent(Hospital $hospital, $level2, $content, $hard_name3)
    {
        if (!empty($level2) && !empty($content)) {
            $category = Category::where([
                'data_type' => Category::HOSPITAL_DETAIL_TYPE,
                'hard_name3' => $hard_name3,
            ])->first();

            if ($category) {
                $hospital->categories()->attach($category->id, ['content1' => $content]);
            }
        }
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
                'row' =>  json_encode(["","都道府県","","SHデータベース登録名称","病院ID","住所","代表電話番号","URL","がん拠点病院","ゲノム拠点病院","学会認定施設情報","特別室/個室","特別室/個室URL","がん相談支援センター","患者紹介方法","緩和ケア","緩和ケアURL","備考","情報更新日","公開フラグ", "エラー"], JSON_UNESCAPED_UNICODE),
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
