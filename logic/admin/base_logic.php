<?php

require_once __DIR__ . '/../../third_party/bootstrap.php';

use \App\Models\BaseModel;
use Illuminate\Support\Facades\DB;
use App\Models\AutoRank;

abstract class base_logic
{
    protected $model;

    public function __construct()
    {
        $this->setModel();
    }

    abstract public function getModel();

    /**
     * @throws Exception
     */
    public function setModel()
    {
        $modelClass = $this->getModel();
        if (class_exists($modelClass)) {
            $this->model = new $modelClass;
        } else {
            throw new Exception("Model class does not exist");
        }
    }

    public function getQueryWithoutGlobalScopes()
    {
        return $this->model->newQuery()->withoutGlobalScopes();
    }

    public function getListData($params, $searchSelect = null, $withRelation = [], $whereClass = [])
    {
        $query = $this->createSearchQuery($searchSelect);

        if (!empty($whereClass)) {
            $query->where($whereClass);
        }

        $count = $query->count();
        $data = $query->with($withRelation)->paginate($params[0], ['*'], 'page', $params[1])->toArray();

        return [
            'total' => $count,
            'data' => $data['data'] ?? []
        ];
    }

    public function getListAutoRankData($params, $data_type, $auto_type, $searchSelect = null)
    {
        $query = $this->createSearchAutoRankingQuery($searchSelect, $data_type, $auto_type);

        $count = $query->count();
        $data = $query->paginate($params[0], ['*'], 'page', $params[1])->toArray();

        return [
            'total' => $count,
            'data' => $data['data'] ?? []
        ];
    }

    public function createData($params)
    {
        return $this->model::create($params);
    }

    public function getDetailById($id)
    {
        return $this->getQueryWithoutGlobalScopes()->find($id);
    }

    public function updateData($id, $params)
    {
        return $this->getQueryWithoutGlobalScopes()->where('id', $id)->update($params);
    }

    public function recoveryData($id)
    {
        return $this->getQueryWithoutGlobalScopes()->where('id', $id)->update(['del_flg' => BaseModel::NOT_DELETED]);
    }

    public function deleteData($id)
    {
        return $this->getQueryWithoutGlobalScopes()->where('id', $id)->update(['del_flg' => BaseModel::DELETED]);
    }

    public function privateData($id)
    {
        return $this->getQueryWithoutGlobalScopes()->where('id', $id)->update(['public_flg' => BaseModel::UNPUBLISHED]);
    }

    public function releaseData($id)
    {
        return $this->getQueryWithoutGlobalScopes()->where('id', $id)->update(['public_flg' => BaseModel::PUBLISHED]);
    }

    protected function createSearchQuery($searchSelect)
    {
        $query = $this->getQueryWithoutGlobalScopes();
        $searchSelect = json_decode(htmlspecialchars_decode($searchSelect), true);
        $table = $this->model->getTable();

        if (!empty($searchSelect['value'])) {
            $search = $searchSelect['value'];
            $keySearch = preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $search['value']);

            if ($search['foreignRelation']) {
                $query->whereHas($search['foreignRelation'], function ($query2) use ($search, $keySearch) {
                    $query2->where($search['target'], 'like', '%' . $keySearch . '%');
                });
            } else {
                $query->where($table . '.' . $search['target'], 'like', '%' . $keySearch . '%');
            }
        }

        $query->orderBy("$table.del_flg", 'ASC');

        if (!empty($searchSelect['order'])) {
            $order = $searchSelect['order'];
            $name = $searchSelect['order']['name'];
            $orderObj = $searchSelect['selectArea'][$name];
            $type = $orderObj['type'] ?? null;

            if ($order['foreignRelation']) {
                $relationName = $order['foreignRelation'];
                $relation = $this->model->$relationName();
                $foreignTable = $relation->getRelated()->getTable();
                $foreignKey = $relation->getForeignKeyName();

                if ($type === 'int' || $type === 'bigint') {
                    $query->leftJoin($foreignTable, "$table.$foreignKey", '=', "$foreignTable.id")
                        ->orderByRaw("CAST($foreignTable.{$order['target']} AS SIGNED) {$order['order']}");
                } else {
                    $query->leftJoin($foreignTable, "$table.$foreignKey", '=', "$foreignTable.id")
                        ->orderBy("$foreignTable.{$order['target']}", $order['order']);
                }
            } else {
                if ($type === 'int' || $type === 'bigint') {
                    $query->orderByRaw("CAST($table.{$order['target']} AS SIGNED) {$order['order']}");
                } else {
                    $query->orderBy($table . '.' . $order['target'], $order['order']);
                }
            }
        } else {
            $query->orderBy("$table.created_at", 'DESC');
        }

        return $query;
    }

    protected function createSearchAutoRankingQuery($searchSelect, $data_type, $auto_type)
    {
        $searchSelect = json_decode(htmlspecialchars_decode($searchSelect), true);
        $query = $this->getQueryWithoutGlobalScopes();

        if ($auto_type === AutoRank::AUTO_TYPE_RANK) {
            $subQuery = $query->select('cancer_id', 'year', DB::raw('COUNT(`hospital_id`) as total_records'))
                ->groupBy('cancer_id', 'year');
        } else {
            $subQuery1 = $query->select('cancer_id', DB::raw('year as sub_year'), DB::raw('COUNT(`hospital_id`) as total_hospitals'))
                ->groupBy('cancer_id', 'year');

            $subQuery = $this->model->newQuery()->withoutGlobalScopes()
                ->select('cancer_id', DB::raw('GROUP_CONCAT(`sub_year` ORDER BY `sub_year` DESC) AS year'), DB::raw('SUM(total_hospitals) AS total_records'))
                ->fromSub($subQuery1, 'subquery')
                ->groupBy('cancer_id')
                ->havingRaw('COUNT(*) <= 3');
        }


        $mainQuery = $this->model->newQuery()->withoutGlobalScopes()->fromSub($subQuery, 'tb_grouped')
            ->join('m_cancer', 'tb_grouped.cancer_id', '=', 'm_cancer.id')
            ->leftJoin('t_auto_rank', function ($join) use ($data_type, $auto_type) {
                $join->on('tb_grouped.cancer_id', '=', 't_auto_rank.cancer_id')
                    ->on('tb_grouped.year', '=', 't_auto_rank.year')
                    ->where('t_auto_rank.data_type', '=', $data_type)
                    ->where('t_auto_rank.auto_type', '=', $auto_type);
            })
            ->selectRaw('
                tb_grouped.cancer_id,
                tb_grouped.year,
                m_cancer.cancer_type,
                (CASE
                    WHEN t_auto_rank.status = 1 THEN 1
                    WHEN t_auto_rank.status = 3 THEN 2
                    WHEN t_auto_rank.status IS NULL THEN 3
                    WHEN t_auto_rank.status = 2
                        AND tb_grouped.total_records > COALESCE(t_auto_rank.total_affect, 0)
                        AND t_auto_rank.total_affect IS NOT NULL THEN 4
                    ELSE 5 END) as status,
                t_auto_rank.updated_at,
                t_auto_rank.completed_time,
                tb_grouped.total_records,
                t_auto_rank.total_affect
            ');

        if (!empty($searchSelect['value'])) {
            $search = $searchSelect['value'];
            $keySearch = preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $search['value']);

            $mainQuery->where($search['target'], 'like', '%' . $keySearch . '%');
        }

        if (!empty($searchSelect['order'])) {
            $order = $searchSelect['order'];
            $name = $searchSelect['order']['name'];
            $orderObj = $searchSelect['selectArea'][$name];
            $type = $orderObj['type'] ?? null;

            if ($type === 'int' || $type === 'bigint') {
                $mainQuery->orderByRaw("CAST({$order['target']} AS SIGNED) {$order['order']}");
            } else {
                $mainQuery->orderBy($order['target'], $order['order']);
            }
        } else {
            $mainQuery->orderBy('status')->orderBy('tb_grouped.year', 'desc');
        }

        return $mainQuery;
    }

    public function getListDataCustomJoin($params, $searchSelect = [], $withRelation = [], $whereClass = [], $applyCustomJoins = null, $tablePrefix = [], $applyCustomeGroupBy = null)
    {
        $table = $this->model->getTable();
        $query = $this->model->newQuery();

        if (is_callable($applyCustomJoins)) {
            $applyCustomJoins($query, $searchSelect);
        }

        if (!empty($searchSelect['commonSearch'])) {
            $searchSelect['commonSearch'] = array_map(function ($condition) use ($tablePrefix) {
                if (isset($tablePrefix[$condition[0]])) {
                    $condition[0] = $tablePrefix[$condition[0]] . '.' . $condition[0];
                }
                return $condition;
            }, $searchSelect['commonSearch']);

            $query->where($searchSelect['commonSearch']);
        }

        if (!empty($whereClass)) {
            $query->where($whereClass);
        }

        if (is_callable($applyCustomeGroupBy)) {
            $applyCustomeGroupBy($query);
        } else {
            $query->orderBy("$table.del_flg", 'ASC');
            $query->orderBy("$table.created_at", 'DESC');
        }



        // var_dump($query->toSql());
        // die;

        $cloneQuery = clone $query;
        $count = $query->count();

        $data = $query->with($withRelation)
            ->paginate($params[0], ['*'], 'page', $params[1])
            ->toArray();

        return [
            'total' => $count,
            'data' => $data['data'] ?? [],
            'query' => $cloneQuery,
        ];
    }
}
