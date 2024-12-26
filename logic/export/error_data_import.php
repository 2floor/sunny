<?php

use Maatwebsite\Excel\Concerns\FromArray;

class error_data_import implements FromArray
{
    protected $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    public function array(): array
    {
        $exportableErrors = [];
        foreach ($this->errors as $error) {
            $decodedRow = json_decode($error['row'], true);

            if ($decodedRow === null && json_last_error() !== JSON_ERROR_NONE) {
                $decodedRow = ['Invalid JSON format'];
            }

            $cleanedRow = array_map(function ($value) {
                return str_replace(['"', '[', ']'], '', $value ?? '');
            }, $decodedRow);

            $exportableErrors[] = array_merge($cleanedRow, [$error['error']]);
        }


        return $exportableErrors;
    }
}
