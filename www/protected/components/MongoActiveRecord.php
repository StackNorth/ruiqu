<?php 
/**
 * 拓展MongoDB-ActiveRecord
 * 添加fields()和extraFields()函数
 * 重写toArray()函数，根据需要取回调函数对字段进行整理
 */
class MongoActiveRecord extends MongoAr {

    public function __construct($scenario = 'insert') {
        parent::__construct($scenario);
    }

    /**
     * 需要处理的字段
     *
     * @return [
     *     'field_1',                   // 直接返回对应attribute
     *     'field_2' => function() {},  // 回调函数模式
     * ];
     */
    public function baseFields() {
        return [];
    }

    /**
     * 需要处理的拓展字段
     *
     * @return [
     *     'field' => function() {},    // 回调函数模式
     * ];
     */
    public function extraFields() {
        return [];
    }

    /**
     * 根据需要的字段名取相应的回调函数处理字段
     *
     * @param Array $fields : 需要处理的字段，默认为空。为空则返回所有已在baseFields()中定义的字段。
     * 当baseFields()函数及本参数均为空时会返回AR对象所有属性值，其中_id会被转换为字符串。
     * @param Array $extraFields : 需要处理的拓展字段，为空则不处理拓展字段。
     *
     * @return Array $result : 处理结果
     */
    public function asArray($fields = [], $extraFields = []) {
        $result = [];
        $_fields = $this->baseFields();
        // fields
        if (!$fields && !$_fields) {
            foreach ($this->attributes as $key => $value) {
                if ($key == '_id') {
                    $result[$key] = (string)$this->_id;
                    continue;
                }
                $result[$key] = $value;
            }
        } else {
            foreach ($_fields as $key => $defination) {
                if (is_int($key)) {
                    $key = $defination;
                    if ($fields && !in_array($key, $fields)) {
                        continue;
                    }

                    if ($key == 'id' || $key == '_id') {
                        $result[$key] = (string)$this->_id;
                        continue;
                    }
                    $result[$key] = $this->attributes[$key];
                    continue;
                } else {
                    if ($fields && !in_array($key, $fields)) {
                        continue;
                    }

                    if (is_callable($defination)) {
                        $result[$key] = call_user_func($defination);
                    } else {
                        $result[$key] = $defination;
                    }
                }
            }
        }

        // extraFields
        if ($extraFields) {
            $_extraFields = $this->extraFields();
            foreach ($extraFields as $value) {
                if (is_callable($_extraFields[$value])) {
                    $result[$value] = call_user_func($_extraFields[$value]);
                } else {
                    $result[$value] = $_extraFields[$value];
                }
            }
        }

        return $result;
    }

    /**
     * 获取AR对象的属性值
     *
     * @param String $field   : 字段名
     * @param Mixed  $default : 当字段不存在时默认返回的值
     * @param String $trans   : 可选，转换函数，例如`intval`
     *
     * @return Mixed $result  : 结果
     */
    public function getAttr($field, $default, $trans = '') {
        $result = isset($this->$field)? $this->$field : $default;
        if ($trans) {
            $result = $trans($result);
        }
        return $result;
    }

}
