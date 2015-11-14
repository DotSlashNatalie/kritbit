<?php

namespace system\engine;

use \vendor\DB\DB;

abstract class HF_Model {

    protected $id = null;
    public static function saveFromArray($data) {
        $fieldMap = [];
        $table = strtolower(get_class());
        foreach(DB::getColumns($table) as $column) {
            $fieldMap[$column] = $data[$column];
        }
        if ($fieldMap["id"] == null) {
            DB::insert($table, $fieldMap);
        } else {
            $updateFields = $fieldMap;
            unset($updateFields["id"]);
            DB::update($table, $updateFields, $fieldMap["id"]);
        }
    }

    public function save() {
        $fieldMap = [];
        $function = new \ReflectionClass(get_called_class());
        $table = strtolower($function->getShortName());
        foreach(DB::getColumns($table) as $column) {
            $fieldMap[$column] = $this->$column;
        }
        if ($fieldMap["id"] == null) {
            DB::insert($table, $fieldMap);
        } else {
            $updateFields = $fieldMap;
            unset($updateFields["id"]);
            DB::update($table, $updateFields, $fieldMap["id"]);
        }
    }

    public static function getByField($field, $value) {
        $function = new \ReflectionClass(get_called_class());
        $table = strtolower($function->getShortName());
        $fields = implode(", ", DB::getColumns($table));
        return DB::fetchObject("SELECT $fields FROM $table WHERE $field = ?", get_called_class(), [$value]);
    }

}