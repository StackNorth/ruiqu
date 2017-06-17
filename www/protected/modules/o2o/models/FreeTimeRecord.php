<?php
class FreeTimeRecord extends MongoAr
{
    public $_id;//时间戳，整点时间戳格式

    public $free_technician = array();//此时间段空闲保洁师列表

    public function __construct($scenario='insert'){
        $this->setMongoDBComponent(Yii::app()->getComponent('mongodb_o2o'));
        parent::__construct($scenario);
    }


    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function get($_id) {
        $criteria = new EMongoCriteria();
        $criteria->_id('==', $_id);
        $model = self::model()->find($criteria);
        return $model;
    }

    /**
     * 获取所有可用时间戳
     */
    public function getAvailableTimeList() {
        $timeList = [];
        for ($index = 0; $index < 15; $index++) {
            $date = date('Ymd', strtotime('+'.$index.' days', time()));
            $timeList[$date] = [
                'name' => date('m月d日', strtotime($date)),
                'timestampList' => [
                    strtotime($date.'0900'), strtotime($date.'1000'), strtotime($date.'1100'),
                    strtotime($date.'1200'), strtotime($date.'1300'), strtotime($date.'1400'),
                    strtotime($date.'1500'), strtotime($date.'1600'), strtotime($date.'1700'),
                    strtotime($date.'1800'), strtotime($date.'1900'),
                ],
            ];
        }
        return $timeList;
    }

    /**
     * 获取某个保洁师的时间线
     * 0: 此时段未设置
     * 1: 此时段已设置且空闲
     * 2: 此时段已预约
     */
    public function getTechTimeline($tech_id){
        // 检索Order表
        $criteria = new EMongoCriteria();
        $timestamp_start_book = strtotime('today');
        $timestamp_end_book = strtotime('+15 day', strtotime('today'));
        $criteria->technician('==', $tech_id);
        $criteria->status('notin',array(-3,-2,-1,7));
        $criteria->booking_time('>=', $timestamp_start_book);
        $criteria->booking_time('<=', $timestamp_end_book);
        $criteria->sort('booking_time',EMongoCriteria::SORT_ASC);
        $cursor = ROrder::model()->findAll($criteria);
        $time_line = [];
        foreach ($cursor as $key => $order) {
            $booking_time = strtotime(date('YmdH00',$order->booking_time));
            $time_line[$booking_time] = 2;//此时段已预约
        }

        // 检索free_time_record表
        $free_list = array();
        $criteria = new EMongoCriteria();
        $criteria->free_technician('==', $tech_id);
        $criteria->sort('_id', EMongoCriteria::SORT_ASC);
        $cursor = FreeTimeRecord::model()->findAll($criteria);
        foreach ($cursor as $key => $value) {
            if(array_key_exists($value->_id, $time_line)){
                $value->TechUnsetFreetime($tech_id,$value->_id);
            }else{
                $time_line[$value->_id] = 1;//此时段空闲
            }
        }

        // 遍历availableTimeList生成TimeLine
        $availabelTimeList = $this->getAvailableTimeList();
        $data = [];
        foreach ($availabelTimeList as $key => $item) {
            $data[$key]['name'] = $item['name'];
            $data[$key]['selected'] = 0;
            $all_day = true;
            foreach ($item['timestampList'] as $k => $time) {
                if (array_key_exists($time, $time_line)) {
                    $data[$key]['selectedHours'][] = $time_line[$time];
                    $data[$key]['selected'] = 1;
                } else {
                    $data[$key]['selectedHours'][] = 0;
                    $all_day = false;
                }
            }
            $data[$key]['selected'] = $all_day ? 2 : $data[$key]['selected'];
            $data[$key]['selectedHours'] = array_merge(
                [0, 0, 0, 0, 0, 0, 0, 0, 0],
                $data[$key]['selectedHours'],
                [0, 0, 0, 0]
            );
        }
        return $data;
    }

    //设置某时段保洁师忙碌
    public static function TechUnsetFreetime($tech_id,$time_stamp){
        if($time_stamp > time()+86400*15 || $time_stamp < strtotime('today') ){
            return false;
        }
        $time_record = self::get($time_stamp);
        if($time_record){
            $old_free_list = $time_record->free_technician;
            if(in_array($tech_id,$old_free_list) !== false){
                unset($old_free_list[array_search($tech_id,$old_free_list)]);
                $time_record->free_technician = array_values($old_free_list);
                return $time_record->update(array('free_technician'),true);
            }else{
                return true;
            }
        }else{
            return true;
        }
    }

    //设置保洁师某时段空闲
    public static function TechsetFreetime($tech_id,$time_stamp){
        if($time_stamp > time()+86400*15 || $time_stamp < strtotime('today') ){
            return false;
        }
        $time_record = self::get($time_stamp);
        if($time_record){
            $old_free_list = $time_record->free_technician;
            if(in_array($tech_id,$old_free_list)){
                return true;
            }else{
                $old_free_list[] = $tech_id;
                $time_record->free_technician = array_values($old_free_list);
                return $time_record->update(array('free_technician'),true);
            }
        }else{
            $time_record = new FreeTimeRecord();
            $time_record->_id = $time_stamp;
            $time_record->free_technician = array($tech_id);
            return $time_record->save();
        }
    }
    

    public function getCollectionName()
    {
        return 'free_time_record';
    }

    /**
     * 空闲时间段格式转换
     * 具体时间 -> 明天9:00
     */
    public static function parseFreeTime($time) {
        $today = date('Ymd');

        // 时间整理
        $hour = date('H', $time);
        if (intval($hour) != 10 && intval($hour) != 0) {
            $hour = str_replace('0', '', $hour);
        }
        $min = date('i', $time);
        $parsedTime = $hour.':'.$min;

        // 是否今天
        if ($today == date('Ymd', $time)) {
            return '今天 '.$parsedTime;
        // 是否明天
        } else if ($today == date('Ymd', strtotime('-1 day', $time))) {
            return '明天 '.$parsedTime;
        // 是否后天
        } else if ($today == date('Ymd', strtotime('-2 day', $time))) {
            return '后天 '.$parsedTime;
        } else {
            $month = date('m', $time);
            $day = date('d', $time);
            if (intval($month) != 10) {
                $month = str_replace('0', '', $month);
            }
            if (!in_array(intval($day), [10, 20, 30])) {
                $day = str_replace('0', '', $day);
            }
            return $month.'-'.$day.' '.$parsedTime;
        }
    }
 
}