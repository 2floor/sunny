<?php

require_once __DIR__ . '/../../logic/admin/base_logic.php';

use App\Models\Category;

class category_logic extends base_logic
{
    public function getModel() {
        return Category::class;
    }

    public function create_data_list($params, $search_select = null)
    {}

    public function get_grouped_data_list($group)
    {
        return $this->model->select('id', 'level1', 'level2', 'level3', 'order_num2', 'order_num3', 'is_whole_cancer')
            ->where([
                'category_group' => $group,
            ])
            ->get()
            ->groupBy('level1')->map(function ($items) {
                return $items->groupBy('level2')->map(function ($subItems) {
                    return $subItems->groupBy('level3')->map(function ($groupedItems) {
                        return $groupedItems->sortBy('order_num3')->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'level3' => $item->level3,
                                'order_num2' => $item->order_num2,
                                'order_num3' => $item->order_num3,
                                'is_whole_cancer' => $item->is_whole_cancer,
                            ];
                        });
                    })->sortBy(function ($groupedItems) {
                        return $groupedItems->first()['order_num3'];
                    });
                })->sortBy(function ($subItems, $key) {
                    return $subItems->first()->first()['order_num2'];
                });
            });
    }
}