<?php 
/**
 * o2o订单统计控制器
 * @author     2015-10-10
 */
class ROrderCountViewController extends AdminController {

    /**
     * 首页
     */
    public function actionIndex () {
        $filter_data = ROrder::$order_filter;
        $filter = CommonFn::getComboboxData($filter_data, 1, false);

        $time_range = array(
            'noSelect' => array('name' => '未选择'),
            'Months'   => array('name' => '最近半年'),
            'Weeks'    => array('name' => '最近一月'),
            'Days'     => array('name' => '最近一周')
        );
        $filter_time = CommonFn::getComboboxData($time_range, 'noSelect', false, '');

        $this->render('index', array(
            'filter' => $filter,
            'filter_time' => $filter_time
        ));
    }

    /**
     * 显示列表
     */
    public function actionList () {
        $date_start = Yii::app()->request->getParam('date_start', '');
        $date_end   = Yii::app()->request->getParam('date_end', '');
        $filter     = Yii::app()->request->getParam('filter', 0);

        if (!empty($date_start) && !empty($date_end)) {
            $date_start = strtotime($date_start);
            $date_end = strtotime('+1 day', strtotime($date_end));
        } else {
            $date_start = 0;
            $date_end   = 0;
        }

        $data = $this->getAll($date_start, $date_end, $filter, true);
        echo json_encode($data);
    }

    /**
     * 返回数据生成柱状图
     */
    public function actionGetChartBar () {
        $filter = intval(Yii::app()->request->getParam('filter', 0));
        $filter_time = Yii::app()->request->getParam('filter_time', 'noSelect');
        $date_start = Yii::app()->request->getParam('date_start', '');
        $date_end = Yii::app()->request->getParam('date_end', '');

        if ($filter == 0 || $filter_time == 'noSelect') {
            $data = array();
        } else {
            // 默认查询一周数据
            if (empty($date_start) || empty($date_end)) {
                $date_range = $this->getDateRnage($filter_time);
                $date_start = $date_range['date_start'];
                $date_end   = $date_range['date_end'];
            } else {
                $date_start = strtotime($date_start);
                $date_end   = strtotime('+1 day', strtotime($date_end));
            }

            $data = $this->getBarData($filter, $filter_time, $date_start, $date_end);
        }
        
        echo json_encode($data);
    }

    /**
     * 返回数据生成饼状图
     */
    public function actionGetChartPie () {
        $filter = intval(Yii::app()->request->getParam('filter', 0));
        $filter_time = Yii::app()->request->getParam('filter_time', 'Days');
        $date_start = Yii::app()->request->getParam('date_start', '');
        $date_end = Yii::app()->request->getParam('date_end', '');

        if ($filter == 0) {
            $data = array();
        } else {
            // 检查是否填写了时间范围
            if (!empty($date_start) && !empty($date_end)) {
                $date_start = strtotime($date_start);
                $date_end = strtotime('+1 day', strtotime($date_end));
            // 否则挑选时间范围
            } else {
                // 若选择了时间范围则获取时间点
                if ($filter_time != 'noSelect') {
                    $date_range = $this->getDateRnage($filter_time);
                    $date_start = $date_range['date_start'];
                    $date_end   = $date_range['date_end']; 
                // 否则查询所有数据
                } else {
                    $date_start = $date_end = 0;
                }
            }

            $data = $this->getPieData($filter, $date_start, $date_end);
        }

        echo json_encode($data);
    }

    /**
     * ----------------------------------
     *
     *      私有方法，对数据进行整理
     * 
     * ----------------------------------
     */

    /**
     * 获取所有数据的统计
     * @param timestamp $date_start : 开始的日期
     * @param timestamp $date_end   : 结束的日期
     * @param number    $filter     : 选择筛选的条件（来源、服务、状态）
     * @param boolean   $empty_data : 是否合并空数据
     */
    private function getAll ($date_start = 0, $date_end = 0, $filter = 0, $empty_data = false) {
        if ($filter == 0) {
            return array();
        }

        // 筛选条件的获取
        $orderFilterContent = $this->getOrderFilterContent();
        $filter_name = $orderFilterContent[$filter]['name'];
        $filter_content = $orderFilterContent[$filter]['content'];

        $data = array();
        // 遍历筛选条件下的所有条目
        foreach ($filter_content as $key => $value) {
            $criteria = new EMongoCriteria();
            // 若键值类似'number'（引号内为整型数字），php会自动将键值转换为整型
            // 该处对此种情况进行处理，进行强制类型转换
            $criteria->{$filter_name} = $filter == 2 ? (string)$key : $key;
            // 状态筛选
            if ($filter != 3) {
                $criteria->status('>', 0);
            }
            if ($date_start != 0 && $date_end != 0) {
                $criteria->booking_time('>=', $date_start);
                $criteria->booking_time('<', $date_end);
            }

            $cursor = ROrder::model()->findAll($criteria);
            $rows = CommonFn::getRowsFromCursor($cursor);

            $data_temp = array(
                'price'        => 0,
                'ori_price'    => 0,
                'count'        => 0,
                'filter'       => $filter,
                'filter_name'  => $filter_name,
                'filter_index' => $key,
                'filter_str'   => $value,
            );

            // 查询数据为空时的处理
            if (empty($rows)) {
                if ($empty_data) {
                    $data[] = $data_temp;
                }
                continue;
            }
            
            foreach ($rows as $k => $v) {
                $data_temp['price'] += $v['final_price'];
                $data_temp['ori_price'] += $v['price'];
            }
            $data_temp['count'] = count($rows);

            $data[] = $data_temp;
        }

        return $data;
    }

    /**
     * 获取时间范围
     * @return array $date_range : 时间范围数组，包括查询开始及结束的时间
     */
    private function getDateRnage ($filter_time) {
        // ------ 最近一月的时间点 ------
        if ($filter_time == 'Weeks') {
            $date_end = strtotime('monday', time());
            if (strtotime(date('Y-m-d', time())) == $date_end) {
                $date_end = strtotime('+7 day', $date_end);
            }
            $date_start = strtotime('-35 day', $date_end);
        // ------ 最近半年的时间点 ------
        } else if ($filter_time == 'Months') {
            $date_end = strtotime(date('Y-m', strtotime('+1 month', time())));
            $date_start = strtotime(date('Y-m', strtotime('-5 month', time())));
        // ------ 最近一周的时间点 ------
        } else {
            $date_end = strtotime(date('Y-m-d', strtotime('+1 day', time())));
            $date_start = strtotime(date('Y-m-d', strtotime('-6 day', time())));
        }

        $date_range = array(
            'date_start' => $date_start,
            'date_end'   => $date_end
        );
        return $date_range;
    }

    /**
     * 获取柱状图数据，默认显示最近一周的数据
     * @param number $filter      : 筛选条件
     * @param string $filter_time : 时间范围
     */
    private function getBarData ($filter, $filter_time = 'Days', $date_start = 0, $date_end = 0) {
        if ($date_start == 0 || $date_end == 0) {
            $date_end = strtotime(date('Y-m-d', strtotime('+1 day', time())));
            $date_start = strtotime(date('Y-m-d', strtotime('-6 day', time())));
        }

        // 筛选条件字典的获取
        $orderFilterContent = $this->getOrderFilterContent();
        $filter_name = $orderFilterContent[$filter]['name'];
        $filter_content = $orderFilterContent[$filter]['content'];

        // 整理函数的选取
        $parseMethod = 'parseDataBy'.$filter_time;

        $data = array();
        $filter_arr = array();
        foreach ($filter_content as $key => $value) {
            $criteria = new EMongoCriteria();
            $criteria->{$filter_name} = $filter == 2 ? (string)$key : $key;
            $criteria->booking_time('>=', $date_start);
            $criteria->booking_time('<', $date_end);
            // 状态筛选
            if ($filter != 3) {
                $criteria->status('>', 0);
            }
            // 防止数据库内时间的混乱
            $criteria->sort('booking_time', EMongoCriteria::SORT_ASC);

            $cursor = ROrder::model()->findAll($criteria);
            $rows = CommonFn::getRowsFromCursor($cursor);

            // parseDataByDays()/parseDataByWeeks()/parseDataByMonths()
            $data_temp = $this->$parseMethod($rows, $date_start, $date_end);
            $data_temp['filter'] = $value;
            $filter_arr[] = $value;

            $data['content'][] = $data_temp;
        }
        $getDateArrMethod = 'get'.$filter_time.'Arr';
        $data['date_arr'] = $this->$getDateArrMethod($date_start, $date_end);
        $data['filter_arr'] = $filter_arr;

        return $data;
    }

    /**
     * 获取饼图数据
     */
    private function getPieData ($filter, $date_start = 0, $date_end = 0) {
        $data = array();
        $price = array();
        $count = array();
        $filter_arr = array();

        $data_all = $this->getAll($date_start, $date_end, $filter, true);
        $data_temp = array();
        foreach ($data_all as $key => $value) {
            $data_temp['price'] = $value['price'];
            $data_temp['count'] = $value['count'];
            $data_temp['filter'] = $value['filter_str'];

            $filter_arr[] = $value['filter_str'];
            $data['content'][] = $data_temp;
        }

        $data['filter_arr'] = $filter_arr;
        return $data;
    }

    /**
     * 按照每天整理数据（一周数据）
     */
    private function parseDataByDays ($rows, $date_start, $date_end) {
        $data = array();
        $date_index = $date_start;
        $rows_count = count($rows);
        $rows_index = 0;

        $price_arr = array();
        $count_arr = array();

        while ($date_index < $date_end) {
            $data_temp = array(
                'price' => 0,
                'count' => 0
            );

            while ($rows_index < $rows_count) {
                if ($date_index <= $rows[$rows_index]['booking_time']
                    && $rows[$rows_index]['booking_time'] < strtotime('+1 day', $date_index)) {
                    $data_temp['price'] += $rows[$rows_index]['final_price'];
                    $data_temp['count']++;
                } else {
                    break;
                }

                $rows_index++;
            }

            $price_arr[] = $data_temp['price'];
            $count_arr[] = $data_temp['count'];
            $date_index = strtotime('+1 day', $date_index);
        }

        $data['price'] = $price_arr;
        $data['count'] = $count_arr;
        return $data;
    }

    /**
     * 按照每周整理数据（一月数据）
     */
    private function parseDataByWeeks ($rows, $date_start, $date_end) {
        $rows = $this->parseDataByDays($rows, $date_start, $date_end);
        $range = ($date_end - $date_start)/3600/24/7;
        $data = array();

        $rows_index = 0;
        $day_count = 1;
        $date_index = $date_start;

        $price_arr = array();
        $count_arr = array();

        for ($week_index = 0; $week_index < $range; $week_index++) {
            $data_temp = array(
                'price' => 0,
                'count' => 0,
            );

            while ($day_count%8 != 0) {
                $data_temp['price'] += $rows['price'][$rows_index];
                $data_temp['count'] += $rows['count'][$rows_index];
                $rows_index++;
                $day_count++;
            }

            $price_arr[] = $data_temp['price'];
            $count_arr[] = $data_temp['count'];
            $day_count = 1;
        }

        $data['price'] = $price_arr;
        $data['count'] = $count_arr;
        return $data;
    }

    /**
     * 按照每月整理数据（半年数据）
     */
    private function parseDataByMonths ($rows, $date_start, $date_end) {
        $rows = $this->parseDataByDays($rows, $date_start, $date_end);

        $range = intval(date('m', $date_end)) - intval(date('m', $date_start));
        // 每月天数获取
        $num_of_days = array();
        for ($i = 0; $i < $range; $i++) {
            $num_of_days[$i] = (strtotime('+'.($i+1).' month', $date_start) - strtotime('+'.$i.' month', $date_start))/3600/24;
        }

        $data = array();
        $data_index = 0;
        $day_count = 0;
        $date_index = $date_start;

        $price_arr = array();
        $count_arr = array();

        foreach ($num_of_days as $key => $value) {
            $day_count += $value;
            $data_temp = array(
                'price' => 0,
                'count' => 0
            );

            while ($data_index < $day_count) {
                $data_temp['price'] += $rows['price'][$data_index];
                $data_temp['count'] += $rows['count'][$data_index];
                $data_index++;
            }

            $price_arr[] = $data_temp['price'];
            $count_arr[] = $data_temp['count'];
        }

        $data['price'] = $price_arr;
        $data['count'] = $count_arr;
        return $data;
    }

    /**
     * 获取时间点数组
     */
    private function getDaysArr ($date_start, $date_end) {
        $date_arr = array();
        $date_index = $date_start;
        while ($date_index < $date_end) {
            $date_arr[] = date('m-d', $date_index);
            $date_index = strtotime('+1 day', $date_index);
        }
        $data['date_arr'] = $date_arr;

        return $date_arr;
    }

    private function getWeeksArr ($date_start, $date_end) {
        $week_arr = array();
        $date_index = $date_start;
        do {
            $week_temp = date('m-d', $date_index);
            $date_index = strtotime('+7 day', $date_index);
            $week_temp .= '至'.date('m-d', strtotime('-1 day', $date_index));

            $week_arr[] = $week_temp;
        } while ($date_index < $date_end);

        return $week_arr;
    }

    private function getMonthsArr ($date_start, $date_end) {
        $month_arr = array();
        $date_index = $date_start;
        while ($date_index < $date_end) {
            $month_arr[] = intval(date('m', $date_index)).'月';
            $date_index = strtotime('+1 month', $date_index);
        }

        return $month_arr;
    }

    /**
     * 整理订单筛选内容
     */
    private function getOrderFilterContent () {
        $channel = array();
        $channel_data = ROrder::$channel_option;
        foreach ($channel_data as $key => $value) {
            $channel[$key] = $value['name'];
        }

        $type = array();
        $type_data = Yii::app()->params['o2o_service'];
        foreach ($type_data as $key => $value) {
            $type[$key] = $value['name'];
        }

        $status = array();
        $status_data = ROrder::$status_option;
        foreach ($status_data as $key => $value) {
            $status[$key] = $value['name'];
        }

        $data = array(
            1 => array(
                'name'    => 'channel',
                'content' => $channel,
            ),
            2 => array(
                'name'    => 'type',
                'content' => $type,
            ),
            3 => array(
                'name'    => 'status',
                'content' => $status,
            ),
        );

        return $data;
    }

}
?>