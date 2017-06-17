<?php 
/**
 * 根据优惠券统计订单控制器
 * @author     2015-10-13
 */
class CouponsCountViewController extends AdminController {

    /**
     * 首页
     */
    public function actionIndex () {
        $status_option = Coupon::$status_option;
        $time_range = array(
            'noSelect' => array('name' => '未选择'),
            'Months'   => array('name' => '最近半年'),
            'Weeks'    => array('name' => '最近一月'),
            'Days'     => array('name' => '最近一周')
        );

        $status_filter = CommonFn::getComboboxData($status_option, 1, true, 100);
        $time_filter = CommonFn::getComboboxData($time_range, 'noSelect', false, '');

        $data = array(
            'status_filter' => $status_filter,
            'time_filter'   => $time_filter
        );
        $this->render('index', $data);
    }

    /**
     * 显示列表
     */
    public function actionList () {
        // echo json_encode(array());return;

        $date_start = Yii::app()->request->getParam('date_start', '');
        $date_end   = Yii::app()->request->getParam('date_end', '');
        $status     = intval(Yii::app()->request->getParam('status', 100));

        if (!empty($date_start) && !empty($date_end)) {
            $date_start = strtotime($date_start);
            $int_start_date = intval(date('Ymd',$date_start));
            $date_end = strtotime('+1 day', strtotime($date_end));
            $int_end_date = intval(date('Ymd',$date_end));
        } else {
            $date_start = 0;
            $int_start_date = intval(date('Ymd'));
            $date_end = 0;
            $int_end_date = intval(date('Ymd')) + 1;
        }
        $criteria = new EMongoCriteria();
        $criteria->date('>=',$int_start_date);
        $criteria->date('<',$int_end_date);
        $count_list = OfflineOrderCount::model()->findAll($criteria);
        $offline_order_sum['title'] = '线下推广用户完成首单数';
        $offline_order_sum['value'] = 0;
        foreach ($count_list as $offline_order_count) {
            $offline_order_sum['value'] += $offline_order_count->count; 
        }
        $data = $this->getAll($date_start, $date_end, true, $status, true);
        $total = $data['total'];
        $data = $data['data'];
        $offline =  array(array(
                            'price' => 0,
                            'final_price' => 0,
                            'count' => $offline_order_sum['value'],
                            'id' => '',
                            'name' => $offline_order_sum['title'],
                            'memo' => '',
                            'status' => 1,
                            'status_str' => '正常',
                            'alias_name' => '',
                        ));
        $data = array_merge($offline,$data);
        echo CommonFn::composeDatagridData($data, $total);
    }

    /**
     * 返回数据生成柱状图
     */
    public function actionGetChartBar () {
        $time_filter = Yii::app()->request->getParam('time_filter', 'noSelect');
        $status_filter = intval(Yii::app()->request->getParam('status_filter', 100));
        $date_start = Yii::app()->request->getParam('date_start', '');
        $date_end = Yii::app()->request->getParam('date_end', '');

        if ($time_filter == 'noSelect') {
            $data = array();
        } else {
            // 默认查询一周数据
            if (empty($date_start) || empty($date_end)) {
                $date_range = $this->getDateRnage($time_filter);
                $date_start = $date_range['date_start'];
                $date_end   = $date_range['date_end'];
            } else {
                $date_start = strtotime($date_start);
                $date_end = strtotime('+1 day', strtotime($date_end));
            }

            $data = $this->getBarData($status_filter, $time_filter, $date_start, $date_end);
        }

        echo json_encode($data);
    }

    /**
     * 返回数据生成饼状图
     */
    public function actionGetChartPie () {
        $time_filter = Yii::app()->request->getParam('time_filter', 'noSelect');
        $status_filter = intval(Yii::app()->request->getParam('status_filter', 100));
        $date_start = Yii::app()->request->getParam('date_start', '');
        $date_end = Yii::app()->request->getParam('date_end', '');

        // 时间范围处理
        // 检查是否填写了时间范围
        if (!empty($date_start) && !empty($date_end)) {
            $date_start = strtotime($date_start);
            $date_end = strtotime('+1 day', strtotime($date_end));
        // 否则挑选时间范围
        } else {
            // 获取时间点
            if ($time_filter != 'noSelect') {
                $date_range = $this->getDateRnage($time_filter);
                $date_start = $date_range['date_start'];
                $date_end   = $date_range['date_end']; 
            // 查询所有数据
            } else {
                $date_start = $date_end = 0;
            }
        }

        $data = $this->getPieData($status_filter, $date_start, $date_end);
        echo json_encode($data);
    }

    /**
     * 返回数据生成单个优惠券的柱状图及折线图
     */
    public function actionGetChartById () {
        $id = Yii::app()->request->getParam('id', '');
        $date_start = Yii::app()->request->getParam('date_start', '');
        $date_end = Yii::app()->request->getParam('date_end', '');
        $time_filter = Yii::app()->request->getParam('time_filter', 'noSelect');
        $filter_week = Yii::app()->request->getParam('filter_week', 0);

        if ($id == '' || $time_filter == 'noSelct') {
            echo json_encode(array());
            return false;
        }

        $coupon_id = new MongoId($id);

        // 检查是否选择了时间范围
        if ($time_filter != 'Days' || ($filter_week == 1 && $time_filter == 'Days')) {
            $date_range = $this->getDateRnage($time_filter);
            $date_start = $date_range['date_start'];
            $date_end   = $date_range['date_end'];
        // 否则获取具体时间
        } else if (!empty($date_start) && !empty($date_end)) {
            $date_start = strtotime($date_start);
            $date_end = strtotime('+1 day', strtotime($date_end));
        } else {
            echo json_encode(array());
            return false;
        }
        
        $data = $this->getBarDataById($coupon_id, $time_filter, $date_start, $date_end);
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
     * @param timestamp $date_start    : 开始的日期
     * @param timestamp $date_end      : 结束的日期
     * @param boolean   $empty_data    : 是否加入空数据
     * @param number    $status_filter : 状态筛选，默认查询所有状态
     * @param boolean   $pageParam     : 是否加入分页信息
     */
    private function getAll ($date_start = 0, $date_end = 0, $empty_data = false, $status_filter = 100, $pageParam = false) {
        $data = array();
        $coupons = $this->getCoupons($status_filter, $pageParam);

        if ($pageParam) {
            $data['total'] = $coupons['total'];
            $coupons = $coupons['rows'];

            if ($data['total'] == 0) {
                return array('total' => 0, 'data' => array());
            }
        }        

        foreach ($coupons as $key => $value) {
            $alias_name = isset($value['alias_name']) ? $value['alias_name'] : '';
            $data_temp = array(
                'price'       => 0,
                'final_price' => 0,
                'count'       => 0,
                'id'          => (string)$value['_id'],
                'name'        => $value['name'].'('.$alias_name.')',
                'memo'        => $value['memo'],
                'status'      => $value['status'],
                'status_str'  => $this->getStatusStr($value['status']),
                'alias_name'  => $alias_name
            );

            $orderRows = $this->getOrderRows($value['_id'], $date_start, $date_end);
            if (empty($orderRows)) {
                if ($empty_data) {
                    $data['data'][] = $data_temp;
                }
                continue;
            }

            foreach ($orderRows as $k => $v) {
                $data_temp['price'] += $v['price'];
                $data_temp['final_price'] += $v['final_price'];
            }
            $data_temp['count'] = count($orderRows);

            $data['data'][] = $data_temp;
        }

        return $data;
    }

    /**
     * 根据状态获取优惠券数组
     * @param number  $status    : 状态码，默认为100（所有状态）
     * @param boolean $pageParam : 是否加入分页信息
     */
    private function getCoupons ($status_filter = 100, $pageParam = false) {
        $pageParams = CommonFn::getPageParams();
        $criteria = $pageParam ? new EMongoCriteria($pageParams) : new EMongoCriteria();
        if ($status_filter != 100) $criteria->status('==', $status_filter);
        $cursor = Coupon::model()->findAll($criteria);
        $rows = CommonFn::getRowsFromCursor($cursor);
        $total = count($cursor);

        if ($pageParam) {
            $rows['rows'] = $rows;
            $rows['total'] = $total;
        }

        return $rows;
    }

    /**
     * 根据优惠券ID获取使用该优惠券的所有订单
     * @param MongoID   $coupon_id  : 优惠券ID，来自于coupon表
     * @param timestamp $date_start : 开始的时间，默认为0（查询所有）
     * @param timestamp $date_end   : 结束的时间，默认为0（查询所有）
     */
    private function getOrderRows ($coupon_id, $date_start = 0, $date_end = 0) {
        $criteria = new EMongoCriteria();
        $criteria->coupon('==', $coupon_id);
        $criteria->status('==', -1);
        $cursor = UserCoupon::model()->findAll($criteria);

        $rows = CommonFn::getRowsFromCursor($cursor);

        $userCoupons = array();
        foreach ($rows as $value) {
            $userCoupons[] = $value['_id'];
        }

        $criteria = new EMongoCriteria();
        $criteria->coupons('in', $userCoupons);
        $criteria->status('>=', 1);

        if ($date_start != 0 && $date_end != 0) {
            $criteria->order_time('>=', $date_start);
            $criteria->order_time('<', $date_end);
        }

        $criteria->sort('order_time', EMongoCriteria::SORT_ASC);
        $cursor = ROrder::model()->findAll($criteria);
        $rows = CommonFn::getRowsFromCursor($cursor);

        return $rows;
    }

    /**
     * 获取时间范围
     * @return array $date_range : 时间范围数组，包括查询开始及结束的时间
     */
    private function getDateRnage ($time_filter) {
        // ------ 最近一月的时间点 ------
        if ($time_filter == 'Weeks') {
            $date_end = strtotime('monday', time());
            if (strtotime(date('Y-m-d', time())) == $date_end) {
                $date_end = strtotime('+7 day', $date_end);
            }
            $date_start = strtotime('-35 day', $date_end);
        // ------ 最近半年的时间点 ------
        } else if ($time_filter == 'Months') {
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
     * 获取柱状图数据，默认显示近一周的数据
     */
    private function getBarData ($status_filter, $time_filter, $date_start, $date_end) {
        // 获取优惠券数组
        $coupons = $this->getCoupons($status_filter);
        // 整理函数的选取
        $parseMethod = 'parseDataBy'.$time_filter;

        $data = array();
        $coupons_arr = array();
        foreach ($coupons as $key => $value) {
            $orderRows = $this->getOrderRows($value['_id'], $date_start, $date_end);

            // parseDataByDays()/parseDataByWeeks()/parseDataByMonths()
            $data_temp = $this->$parseMethod($orderRows, $date_start, $date_end);
            $alias_name = isset($value['alias_name']) ? $value['alias_name'] : '';
            $data_temp['coupon'] = $value['name'].'('.$alias_name.')';
            $coupons_arr[] = $value['name'].'('.$alias_name.')';

            $data['content'][] = $data_temp;
        }

        $getDateArrMethod = 'get'.$time_filter.'Arr';
        $data['date_arr'] = $this->$getDateArrMethod($date_start, $date_end);
        $data['coupons'] = $coupons_arr;

        return $data;
    }

    /**
     * 获取饼图数据，默认显示所有数据
     */
    private function getPieData ($status_filter = 100, $date_start = 0, $date_end = 0) {
        $data = array();
        $price = array();
        $final_price = array();
        $count = array();
        $coupons = array();

        $data_all = $this->getAll($date_start, $date_end, true, $status_filter, false);
        $data_all = $data_all['data'];
        $data_temp = array();
        foreach ($data_all as $key => $value) {
            $data_temp['price'] = $value['price'];
            $data_temp['final_price'] = $value['final_price'];
            $data_temp['count'] = $value['count'];
            $data_temp['coupon'] = $value['name'];

            $coupons[] = $value['name'];
            $data['content'][] = $data_temp;
        }

        $data['coupons'] = $coupons;
        return $data;
    }

    /**
     * 获取单个优惠券类型的数据，默认显示近一周的数据
     */
    public function getBarDataById ($coupon_id, $time_filter, $date_start, $date_end) {
        // 整理函数的选取
        $parseMethod = 'parseDataBy'.$time_filter;

        $orderRows = $this->getOrderRows($coupon_id, $date_start, $date_end);
        // parseDataByDays()/parseDataByWeeks()/parseDataByMonths()
        $data['content'] = $this->$parseMethod($orderRows, $date_start, $date_end);
        $getDateArrMethod = 'get'.$time_filter.'Arr';
        $data['date_arr'] = $this->$getDateArrMethod($date_start, $date_end);

        return $data;
    }

    /**
     * 返回优惠券状态字符串
     * @param number $status : 状态码
     */
    private function getStatusStr ($status) {
        switch ($status) {
            case 0:
                return '暂停';
                break;
            case 1:
                return '正常';
                break;
            case -1:
                return '删除';
                break;
            default:
                break;
        }

        return '未知';
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
        $final_price_arr = array();
        $count_arr = array();

        while ($date_index < $date_end) {
            $data_temp = array(
                'price'       => 0,
                'final_price' => 0,
                'count'       => 0
            );

            while ($rows_index < $rows_count) {
                if ($date_index <= $rows[$rows_index]['order_time']
                    && $rows[$rows_index]['order_time'] < strtotime('+1 day', $date_index)) {
                    $data_temp['price'] += $rows[$rows_index]['price'];
                    $data_temp['final_price'] += $rows[$rows_index]['final_price'];
                    $data_temp['count']++;
                } else {
                    break;
                }

                $rows_index++;
            }

            $price_arr[] = $data_temp['price'];
            $final_price_arr[] = $data_temp['final_price'];
            $count_arr[] = $data_temp['count'];
            $date_index = strtotime('+1 day', $date_index);
        }

        $data['price'] = $price_arr;
        $data['final_price'] = $final_price_arr;
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
        $final_price_arr = array();
        $count_arr = array();

        for ($week_index = 0; $week_index < $range; $week_index++) {
            $data_temp = array(
                'price'       => 0,
                'final_price' => 0,
                'count'       => 0,
            );

            while ($day_count%8 != 0) {
                $data_temp['price'] += $rows['price'][$rows_index];
                $data_temp['final_price'] += $rows['final_price'][$rows_index];
                $data_temp['count'] += $rows['count'][$rows_index];
                $rows_index++;
                $day_count++;
            }

            $price_arr[] = $data_temp['price'];
            $final_price_arr[] = $data_temp['final_price'];
            $count_arr[] = $data_temp['count'];
            $day_count = 1;
        }

        $data['price'] = $price_arr;
        $data['final_price'] = $final_price_arr;
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
        $final_price_arr = array();
        $count_arr = array();

        foreach ($num_of_days as $key => $value) {
            $day_count += $value;
            $data_temp = array(
                'price'       => 0,
                'final_price' => 0,
                'count'       => 0
            );

            while ($data_index < $day_count) {
                $data_temp['price'] += $rows['price'][$data_index];
                $data_temp['final_price'] += $rows['final_price'][$data_index];
                $data_temp['count'] += $rows['count'][$data_index];
                $data_index++;
            }

            $price_arr[] = $data_temp['price'];
            $final_price_arr[] = $data_temp['final_price'];
            $count_arr[] = $data_temp['count'];
        }

        $data['price'] = $price_arr;
        $data['final_price'] = $final_price_arr;
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

}
?>