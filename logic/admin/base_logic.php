<?php

require_once __DIR__ . '/../../third_party/bootstrap.php';

use \App\Models\BaseModel;

abstract class base_logic {
    protected $model;

    public function __construct()
    {
        $this->setModel();
    }

    abstract public function getModel();

    /**
     * @throws Exception
     */
    public function setModel() {
        $modelClass = $this->getModel();
        if (class_exists($modelClass)) {
            $this->model = new $modelClass;
        } else {
            throw new Exception("Model class does not exist");
        }
    }

    public function getListData($params, $searchSelect = null, $withRelation = []) {
        $query = $this->createSearchQuery($searchSelect);

        $count = $query->count();
        $pagerCount = ceil($count / $params[2]);
        $offset = ($params[1] - 1) * $params[2];
        $data = $query->with($withRelation)->offset($offset)->limit($params[2])->get()->toArray();

        return [
            'offset' => $offset,
            'pagerCount' => $pagerCount,
            'total' => $count,
            'data' => $data
        ];
    }

    public function createData($params) {
        return $this->model::create($params);
    }

    public function getDetailById($id){
        $result = $this->model::find($id);

        if (empty($result)) {
            return [];
        }

        return $result->toArray();
    }

    public function updateData($params){
        return $this->model::update($params);
    }

    public function recoveryData($id) {
        return $this->model::update(['del_flg' => BaseModel::NOT_DELETED]);
    }

    public function deleteData($id) {
        return $this->model::update(['del_flg' => BaseModel::DELETED]);
    }

    public function privateData($id) {
        return $this->model::update(['public_flg' => BaseModel::PUBLISHED]);
    }

    public function releaseData($id) {
        return $this->model::update(['public_flg' => BaseModel::NOT_DELETED]);
    }

    protected function createSearchQuery($searchSelect)
    {
        $query = $this->model->newQuery();
        $searchSelect = json_decode(htmlspecialchars_decode($searchSelect), true);

        if (!empty($searchSelect['value'])) {
            $search = $searchSelect['value'];
            $keySearch = preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $search['value']);

            if ($search['foreignRelation']) {
                $query->whereHas($search['foreignRelation'], function ($query2) use ($search, $keySearch) {
                    $query2->where($search['target'], 'like', '%'.$keySearch.'%');
                });
            } else {
                $query->where($search['target'], 'like', '%'.$keySearch.'%');
            }
        }

        $query->orderBy('del_flg', 'ASC');

        if (!empty($searchSelect['order'])) {
            $order = $searchSelect['order'];

            if ($order['foreignRelation']) {
                $relationName = $order['foreignRelation'];
                $relation = $this->model->$relationName();
                $foreignTable = $relation->getRelated()->getTable();
                $foreignKey = $relation->getForeignKeyName();
                $table = $this->model->getTable();

                $query->leftJoin($foreignTable, "$table.$foreignKey", '=', "$foreignTable.id")
                    ->orderBy("$foreignTable.{$order['target']}", $order['order']);
            } else {
                $query->orderBy($order['target'], $order['order']);
            }
        }

        return $query;
    }
}