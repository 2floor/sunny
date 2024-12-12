<?php

use App\Models\Hospital;
use App\Models\MissMatch;

abstract class base_import
{
    protected $errors = [];
    protected $success = 0;
    protected $hospitalMaster = [];
    protected $type = null;

    public function __construct(protected $fileName = null)
    {
        $this->hospitalMaster = Hospital::withoutGlobalScope('unpublish')
            ->select(['id', 'hospital_name', 'area_id'])
            ->get();

        $this->setMMType();
    }

    abstract public function getMMType();
    public function setMMType()
    {
        $this->type = $this->getMMType();
    }

    public function getSuccess()
    {
        return $this->success;
    }

    public function getCountError(): int
    {
        return count($this->errors);
    }

    protected function handleMMHospital($hospitalName, $cancerId, $year, $row)
    {
        $mm = $this->getMissMatch($hospitalName, $cancerId);
        if ($mm) {
            $hospital = Hospital::withoutGlobalScope('unpublish')->find($mm->hospital_id);
        } else {
            $mm = MissMatch::where([
                'hospital_name' => $hospitalName,
                'status' => MissMatch::STATUS_CONFIRMED,
            ])->orderBy('year', 'desc')->first();

            if ($mm) {
                $this->createMissMatch([
                    'hospital_id' => $mm->hospital_id,
                    'hospital_name' => $hospitalName,
                    'cancer_id' => $cancerId,
                    'area_id' => $mm->area_id,
                    'year' => $year,
                    'percent_match' => $mm->percent_match,
                    'import_value' => $row,
                ]);

                $hospital = Hospital::withoutGlobalScope('unpublish')->find($mm->hospital_id);
            } else {
                $mostSimilar = $this->findMostSimilarHospital($hospitalName);

                $mmHospitalId = null;
                $percent = null;
                $hospital = null;
                if (!empty($mostSimilar) && $mostSimilar['similarity'] > 70) {
                    $hospital = $mostSimilar['hospital'];
                    $percent = $mostSimilar['similarity'];
                    $mmHospitalId = $hospital->id;
                }

                if ($hospital) {
                    $existMM = MissMatch::where([
                        'hospital_id' => $mmHospitalId,
                        'type' => $this->type,
                        'year' => $year,
                        'cancer_id' => $cancerId,
                    ])->first();

                    if (!$existMM) {
                        $this->createMissMatch([
                            'hospital_id' => $mmHospitalId,
                            'hospital_name' => $hospitalName,
                            'cancer_id' => $cancerId,
                            'area_id' => $hospital?->area_id,
                            'year' => $year,
                            'percent_match' => $percent,
                            'import_value' => $row,
                        ]);
                    } else {
                        if ($existMM->percent_match >= $percent) {
                            $this->createMissMatch([
                                'hospital_id' => null,
                                'hospital_name' => $hospitalName,
                                'cancer_id' => $cancerId,
                                'area_id' => null,
                                'year' => $year,
                                'percent_match' => null,
                                'import_value' => $row,
                            ]);
                        } else {
                            $existMM->update([
                                'hospital_id' => null,
                                'area_id' => null,
                                'percent_match' => null,
                            ]);

                            $this->createMissMatch([
                                'hospital_id' => $mmHospitalId,
                                'hospital_name' => $hospitalName,
                                'cancer_id' => $cancerId,
                                'area_id' => $hospital?->area_id,
                                'year' => $year,
                                'percent_match' => $percent,
                                'import_value' => $row,
                            ]);
                        }
                    }
                } else {
                    $this->createMissMatch([
                        'hospital_id' => null,
                        'hospital_name' => $hospitalName,
                        'cancer_id' => $cancerId,
                        'area_id' => null,
                        'year' => $year,
                        'percent_match' => null,
                        'import_value' => $row,
                    ]);
                }

                if (!$hospital) {
                    $this->addError($row, 'マスターデータに病院名がありません');
                }
            }
        }

        return $hospital ?? null;
    }

    protected function findMostSimilarHospital($hospitalName)
    {
        return $this->hospitalMaster->map(function ($hospital) use ($hospitalName) {
            similar_text($hospitalName, $hospital->hospital_name, $percent);
            return [
                'hospital' => $hospital,
                'similarity' => $percent,
            ];
        })->sortByDesc('similarity')->first();
    }

    protected function getMissMatch($hospitalName, $cancerId) {
        return MissMatch::where([
            'hospital_name' => $hospitalName,
            'status' => MissMatch::STATUS_CONFIRMED,
            'cancer_id' => $cancerId,
            'type' => $this->type
        ])->orderBy('year', 'desc')->first();
    }

    protected function createMissMatch($data) {
        return MissMatch::create([
            'hospital_id' => $data['hospital_id'] ?? null,
            'hospital_name' => $data['hospital_name'],
            'type' => $this->type,
            'cancer_id' => $data['cancer_id'],
            'area_id' => $data['area_id'] ?? null,
            'year' => $data['year'],
            'percent_match' => $data['percent_match'] ?? null,
            'import_file' => $this->fileName,
            'import_value' => json_encode($data['import_value'], JSON_UNESCAPED_UNICODE),
        ]);
    }

    protected function addError(array $row, string $errorMessage)
    {
        $this->errors[] = [
            'row' => json_encode($row, JSON_UNESCAPED_UNICODE),
            'error' => $errorMessage,
        ];
    }
}