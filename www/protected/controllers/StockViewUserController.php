<?php 
/**
 * 库存操作统计控制器（根据用户）
 * @author     2015-09-29
 */
class StockViewUserController extends AdminController {

    /**
     * 首页acion
     * 默认显示本周内数据统计及图表
     */
    public function actionIndex () {
        $object = intval(Yii::app()->request->getParam('object', 0));

        if ($object != 0) {
            $date_end = strtotime(date('Y-m-d', strtotime('+1 day', time())));
            $date_start = strtotime(date('Y-m-d', strtotime('-6 day', time())));

            $data = $this->getDataByUser($date_start, $date_end, $object);
            $data_str = $this->getDataStr($data);

            $criteria_user = new EMongoCriteria();
            $criteria_user->_id('==', $object);
            $cursor = User::model()->find($criteria_user);
            $objectName = $cursor->name;

            $date_arr = $this->getDateArr($date_start, $date_end);
            $date_str = $this->getTimeStr($date_arr);

            $date_range = date('Y-m-d', $date_start).'至'.date('Y-m-d', strtotime('-1 day', $date_end));
        } else {
            $date_range = '';
            $date_str = '';
            $data_str = array('price_count'=>'', 'operate_count'=>'');
            $object = 0;
            $objectName = '';
        }

        $this->render('index', array(
            'date_range'    => $date_range,
            'date_str'      => $date_str,
            'price_count'   => $data_str['price_count'],
            'operate_count' => $data_str['operate_count'],
            'object'        => $object,
            'objectName'    => $objectName
        ));
    }

    /**
     * 显示所有用户在时间范围内的领取情况
     * @param string date_start | 开始的时间
     * @param string date_end   | 结束的时间
     * @param string date_range | 时间范围，用于前端显示
     * @param array  data       | 时间范围内用户名、总价统计、操作统计
     */
    public function actionAll () {
        $date_start = Yii::app()->request->getParam('date_start', '');
        $date_end = Yii::app()->request->getParam('date_end', '');
        // 默认查询当天数据
        if (empty($date_start) || empty($date_end)) {
            $date_range = date('Y-m-d', time());
            $date_start = strtotime(date('Y-m-d', time()));
            $date_end = strtotime('+1 day', $date_start);
        } else {
            $date_range = $date_start == $date_end ? $date_start : $date_start.'至'.$date_end;
            $date_start = strtotime($date_start);
            $date_end = strtotime('+1 day', strtotime($date_end));
        }

        $data = $this->getAll($date_start, $date_end);
        $data = $data['data'];

        $price_count_arr = array();
        $objectName_str = '';
        foreach ($data as $key => $value) {
            $objectName_str .= '"'.$value['objectName'].'",';
            $price_count_arr[$value['objectName']] = $value['price_count'];
        }
        $objectName_str = substr($objectName_str, 0, mb_strlen($objectName_str)-1);

        $this->render('all', array(
            'date_start'      => $date_start,
            'date_end'        => $date_end,
            'date_range'      => $date_range,
            'objectNames'     => $objectName_str,
            'price_count_arr' => $price_count_arr,
            'object'          => ''
        ));
    }

    /**
     * 左边列表显示
     * @param criteria_stockViewUser : criteria for StockViewUser
     * @param criteria_users         : criteria for User
     */
    public function actionList () {
        $merge_data_days = Yii::app()->request->getParam('merge_data_days', false);
        $merge_data_weeks = Yii::app()->request->getParam('merge_data_weeks', false);
        $merge_data_months = Yii::app()->request->getParam('merge_data_months', false);

        $objectName = Yii::app()->request->getParam('s_user', '');
        $date_start = Yii::app()->request->getParam('date_start', '');
        $date_end = Yii::app()->request->getParam('date_end', '');

        // 时间为空则查询所有数据
        if (!empty($date_start) && !empty($date_end)) {
            $date_start = strtotime($date_start);
            $date_end = strtotime('+1 day', strtotime($date_end));
        }

        $data = $this->getAll($date_start, $date_end, $objectName, true, $merge_data_days, $merge_data_weeks, $merge_data_months);
        $total = $data['total'];
        $data = $data['data'];
        echo CommonFn::composeDatagridData($data, $total);
    }

    /**
     * 根据用户ID查询本月领取情况
     */
    public function actionFindByWeeks () {
        $object = intval(Yii::app()->request->getParam('object', 0));
        // 默认日期已设，二次开发已预留根据时间调整范围的代码
        $date_end = strtotime('monday', time());
        if (strtotime(date('Y-m-d', time())) == $date_end) {
            $date_end = strtotime('+7 day', $date_end);
        }
        $date_start = strtotime('-35 day', $date_end);

        if ($object != 0) {
            $criteria_user = new EMongoCriteria();
            $criteria_user->_id = $object;
            $cursor = User::model()->find($criteria_user);
            $objectName = $cursor->name;

            $week_arr = $this->getWeekArr($date_start, $date_end);
            $week_str = $this->getTimeStr($week_arr);
            $data_temp = $this->getDataByUser($date_start, $date_end, $object);
            $data = $this->parseDataByWeeks($data_temp, $date_start, $date_end);
            $data_str = $this->getDataStr($data);
            $date_range = $month_range = date('Y-m-d', $date_start).'至'.date('Y-m-d', strtotime('-1 day', $date_end));
        } else {
            $date_range = '';
            $week_str = '';
            $data_str = array('price_count'=>'', 'operate_count'=>'');
            $object = 0;
            $objectName = '';
        }

        $this->render('findByWeeks', array(
            'date_range' => $date_range,
            'weeks'      => $week_str,
            'price'      => $data_str['price_count'],
            'operate'    => $data_str['operate_count'],
            'object'     => $object,
            'objectName' => $objectName
        ));
    }

    /**
     * 根据用户ID及月份范围查询领取情况
     */
    public function actionFindByMonths () {
        $object = intval(Yii::app()->request->getParam('object', 0));
        $date_end = strtotime(date('Y-m', strtotime('+1 month', time())));
        $date_start = strtotime(date('Y-m', strtotime('-5 month', time())));

        if ($object != 0) {
            $data_temp = $this->getDataByUser($date_start, $date_end, $object);
            $data = $this->parseDataByMonths($data_temp, $date_start, $date_end, $object);

            $criteria_user = new EMongoCriteria();
            $criteria_user->_id = $object;
            $cursor = User::model()->find($criteria_user);
            $objectName = $cursor->name;

            $data_str = $this->getDataStr($data);
            $month_arr = $this->getMonthArr($date_start, $date_end);
            $month_str = $this->getTimeStr($month_arr);
            
            $month_range = date('Y-m', $date_start).'至'.date('Y-m', strtotime('-1 month', $date_end));
        } else {
            $month_range = '';
            $month_str = '';
            $data_str = array('price_count'=>'', 'operate_count'=>'');
            $object = 0;
            $objectName = '';
        }

        $this->render('findByMonths', array(
            'month_range' => $month_range,
            'month' => $month_str,
            'price' => $data_str['price_count'],
            'operate' => $data_str['operate_count'],
            'object' => $object,
            'objectName' => $objectName
        ));
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
     * @param  boolean $empty_data        : 查询的stock结果为空时是否记录
     * @param  boolean $merge_data_days   : 是否在结果中记录时间范围内（按照天）的单用户数据
     * @param  boolean $merge_data_weeks  : 是否在结果中记录时间范围内（按照周）的单用户数据
     * @param  boolean $merge_data_months : 是否在结果中记录时间范围内（按照月）的单用户数据
     */
    private function getAll($date_start = '', $date_end = '', $objectName = '', $empty_data = false, $merge_data_days = false, $merge_data_weeks = false, $merge_data_months = false) {
        $params = CommonFn::getPageParams();

        $data = array();
        $criteria_users = new EMongoCriteria($params);

        // 筛选为保洁师
        $MongoDbAuthManager = new CMongoDbAuthManager();
        $users_id = $MongoDbAuthManager->getAuthUser('保洁师');

        $criteria_users->name = new MongoRegex('/'.$objectName.'/');
        $criteria_users->_id('in', $users_id);

        $cursor = User::model()->findAll($criteria_users);
        $total = count($cursor);
        $users = CommonFn::getRowsFromCursor($cursor);

        if (empty($users)) {
            $data = array('total' => 0, 'data' => array());
            return $data;
        }

        foreach ($users as $key => $value) {
            $criteria_stock = new EMongoCriteria();
            $criteria_stock->object = $value['_id'];
            // 时间为空则查询所有数据
            if (!empty($date_start) && !empty($date_end)) {
                $criteria_stock->time('>=', intval($date_start));
                $criteria_stock->time('<', intval($date_end));
            }
            $criteria_stock->sort('time', EMongoCriteria::SORT_ASC);

            $cursor = Stock::model()->findAll($criteria_stock);
            $stock = CommonFn::getRowsFromCursor($cursor);

            // 若查询数据为空则continue
            if (empty($stock)) {
                // 若$empty_data 及$merge_data_days 为真，则加入空数据（针对列表及默认柱状图）
                if ($empty_data) {
                    $data_temp = array(
                        'price_count' => 0,
                        'operate_count' => 0,
                        'object' => $value['_id'],
                        'objectName' => $value['name'],
                    );

                    // 按照每天整合数据
                    if ($merge_data_days) {
                        $date_range = date('Y-m-d', $date_start);
                        $date_range .= '至'.date('Y-m-d', strtotime('-1 day', $date_end));

                        $date_arr = $this->getDateArr($date_start, $date_end);
                        $price_count_arr = array();
                        $operate_count_arr = array();
                        foreach ($date_arr as $k => $v) {
                            array_push($price_count_arr, 0);
                            array_push($operate_count_arr, 0);
                        }

                        $data_temp['data']['date_range'] = $date_range;
                        $data_temp['data']['price_count'] = $price_count_arr;
                        $data_temp['data']['operate_count'] = $operate_count_arr;
                        $data_temp['data']['date_arr'] = $date_arr;
                    // 按照每周整合数据
                    } else if ($merge_data_weeks) {
                        $date_end = strtotime('monday', time());
                        if (strtotime(date('Y-m-d', time())) == $date_end) {
                            $date_end = strtotime('+7 day', $date_end);
                        }
                        $date_start = strtotime('-35 day', $date_end);
                        $date_range = $date_range = $month_range = date('Y-m-d', $date_start).'至'.date('Y-m-d', strtotime('-1 day', $date_end));

                        $week_str = $this->getWeekArr($date_start, $date_end);
                        $price_count_arr = array();
                        $operate_count_arr = array();
                        foreach ($week_arr as $k => $v) {
                            array_push($price_count_arr, 0);
                            array_push($operate_count_arr, 0);
                        }

                        $data_temp['data']['price_count'] = $price_count_arr;
                        $data_temp['data']['operate_count'] = $operate_count_arr;
                        $data_temp['data']['week_arr'] = $week_arr;
                        $data_temp['data']['date_range'] = $date_range;
                    // 按照每月整合数据
                    } else if ($merge_data_months) {
                        $date_end = strtotime(date('Y-m', strtotime('+1 month', time())));
                        $date_start = strtotime(date('Y-m', strtotime('-5 month', time())));
                        $month_range = date('Y-m', $date_start).'至'.date(date('Y-m', strtotime('-1 month', $date_end)));

                        $month_arr = $this->getMonthArr($date_start, $date_end);
                        $price_count_arr = array();
                        $operate_count_arr = array();
                        foreach ($month_arr as $k => $v) {
                            array_push($price_count_arr, 0);
                            array_push($operate_count_arr, 0);
                        }

                        $data_temp['data']['price_count'] = $price_count_arr;
                        $data_temp['data']['operate_count'] = $operate_count_arr;
                        $data_temp['data']['month_arr'] = $month_arr;
                        $data_temp['data']['month_range'] = $month_range;
                    }

                    $data[] = $data_temp;
                }
                
                continue;
            }

            $data_temp = array();
            $data_temp['price_count'] = 0;
            $data_temp['operate_count'] = 0;
            foreach ($stock as $k => $v) {
                $data_temp['price_count'] += $v['tot_price'];
                $data_temp['operate_count']++;
            }
            $data_temp['object'] = $value['_id'];
            $data_temp['objectName'] = $value['name'];

            // 判断并加入每个用户时间范围内的统计情况（按照天）
            if ($merge_data_days) {
                $date_range = date('Y-m-d', $date_start);
                $date_range .= '至'.date('Y-m-d', strtotime('-1 day', $date_end));

                $data_user_temp_days = $this->parseDataByDays($stock, $date_start, $date_end);

                $date_arr = $this->getDateArr($date_start, $date_end);

                $data_temp['data']['date_range'] = $date_range;
                
                $price_count_arr = array();
                $operate_count_arr = array();
                foreach ($data_user_temp_days as $key => $value) {
                    array_push($price_count_arr, $value['price_count']);
                    array_push($operate_count_arr, $value['operate_count']);
                }

                $data_temp['data']['price_count'] = $price_count_arr;
                $data_temp['data']['operate_count'] = $operate_count_arr;
                $data_temp['data']['date_arr'] = $date_arr;
            } else if ($merge_data_weeks) {
                // 默认查询近一个月数据
                $date_end = strtotime('monday', time());
                if (strtotime(date('Y-m-d', time())) == $date_end) {
                    $date_end = strtotime('+7 day', $date_end);
                }
                $date_start = strtotime('-35 day', $date_end);
                $date_range = $date_range = $month_range = date('Y-m-d', $date_start).'至'.date('Y-m-d', strtotime('-1 day', $date_end));

                $data_user_temp = $this->parseDataByDays($stock, $date_start, $date_end);
                $data_user_temp_weeks = $this->parseDataByWeeks($data_user_temp, $date_start, $date_end);

                $week_arr = $this->getWeekArr($date_start, $date_end);

                $price_count_arr = array();
                $operate_count_arr = array();
                foreach ($data_user_temp_weeks as $key => $value) {
                    array_push($price_count_arr, $value['price_count']);
                    array_push($operate_count_arr, $value['operate_count']);
                }

                $data_temp['data']['price_count'] = $price_count_arr;
                $data_temp['data']['operate_count'] = $operate_count_arr;
                $data_temp['data']['week_arr'] = $week_arr;
                $data_temp['data']['date_range'] = $date_range;
            // 判断并加入每个用户时间范围内的统计情况（按照月）
            } else if ($merge_data_months) {
                // 默认查询最近半年数据
                $date_end = strtotime(date('Y-m', strtotime('+1 month', time())));
                $date_start = strtotime(date('Y-m', strtotime('-5 month', time())));
                $month_range = date('Y-m', $date_start).'至'.date(date('Y-m', strtotime('-1 month', $date_end)));

                $data_user_temp = $this->parseDataByDays($stock, $date_start, $date_end);
                $data_user_temp_months = $this->parseDataByMonths($data_user_temp, $date_start, $date_end);

                $month_arr = $this->getMonthArr($date_start, $date_end);

                $price_count_arr = array();
                $operate_count_arr = array();
                foreach ($data_user_temp_months as $key => $value) {
                    array_push($price_count_arr, $value['price_count']);
                    array_push($operate_count_arr, $value['operate_count']);
                }

                $data_temp['data']['price_count'] = $price_count_arr;
                $data_temp['data']['operate_count'] = $operate_count_arr;
                $data_temp['data']['month_arr'] = $month_arr;
                $data_temp['data']['month_range'] = $month_range;
            }
            

            $data[] = $data_temp;
        }

        $data = array('data' => $data, 'total' => $total);

        return $data;
    }

    /**
     * 获取单用户时间范围内所有数据（Y轴数据）
     * 按照日期归类
     * @param timestamp $date_start : 开始时间
     * @param timestamp $date_end   : 结束时间
     * @param number    $object     : 用户ID
     */
    private function getDataByUser ($date_start, $date_end, $object) {
        $data = array();
        $date_index = $date_start;

        $criteria = new EMongoCriteria();
        $criteria->time('>=', $date_start);
        $criteria->time('<', $date_end);
        $criteria->object('==', $object);
        $criteria->sort('time', EMongoCriteria::SORT_ASC);

        $cursor = Stock::model()->findAll($criteria);
        $rows = CommonFn::getRowsFromCursor($cursor);

        $rows_count = count($rows);
        $rows_index = 0;
        while ($date_index < $date_end) {
            $data_temp = array(
                'date' => $date_index,
                'price_count' => 0,
                'operate_count'=> 0
            );
            while ($rows_index < $rows_count) {
                if ($date_index <= $rows[$rows_index]['time'] && 
                    $rows[$rows_index]['time'] < strtotime('+1 day', $date_index)) {
                    $data_temp['price_count'] += $rows[$rows_index]['tot_price'];
                    $data_temp['operate_count']++;
                } else {
                    break;
                }

                $rows_index++;
            }

            $data[] = $data_temp;
            $date_index = strtotime('+1 day', $date_index);
        }

        return $data;
    }

    /**
     * 根据查询Stock表获得的Rows整理数据，按照每天分类
     * 减少与Mongo的交互，提高性能
     * @param  array     $rows       : 从Stock表查询的单用户数据
     * @param  timestamp $date_start : 开始的时间
     * @param  timestamp $date_end   : 结束的时间
     * @return array     $data       : 返回的数据
     */
    private function parseDataByDays ($rows, $date_start, $date_end) {
        $date_index = $date_start;
        $rows_count = count($rows);
        $rows_index = 0;

        while ($date_index < $date_end) {
            $data_temp = array(
                'date' => $date_index,
                'price_count' => 0,
                'operate_count'=> 0
            );

            while ($rows_index < $rows_count) {
                if ($date_index <= $rows[$rows_index]['time'] && 
                    $rows[$rows_index]['time'] < strtotime('+1 day', $date_index)) {
                    $data_temp['price_count'] += $rows[$rows_index]['tot_price'];
                    $data_temp['operate_count']++;
                } else {
                    break;
                }

                $rows_index++;
            }

            $data[] = $data_temp;
            $date_index = strtotime('+1 day', $date_index);
        }

        return $data;
    }

    /**
     * 按照每周整理单用户数据
     */
    private function parseDataByWeeks ($rows='', $date_start, $date_end, $object=0) {
        if (empty($rows) && $object != 0) {
            $rows = $this->getDataByUser($date_start, $date_end, $object);
        }

        $range = ($date_end - $date_start)/3600/24/7;
        $data = array();

        $rows_index = 0;
        $day_count = 1;
        for ($data_index=0; $data_index < $range; $data_index++) { 
            $data_temp = array(
                'price_count' => 0,
                'operate_count' => 0,
            );

            while ($day_count%8 != 0) {
                $data_temp['price_count'] += $rows[$rows_index]['price_count'];
                $data_temp['operate_count'] += $rows[$rows_index]['operate_count'];
                $rows_index++;
                $day_count++;
            }

            $data[] = $data_temp;
            $day_count = 1;
        }

        return $data;
    }

    /**
     * 按照月份整理单用户数据
     * @param $date_start timestamp : 开始时间
     * @param $date_end   timestamp : 结束时间
     * @param $object     number    : 用户ID
     */
    private function parseDataByMonths ($rows='', $date_start, $date_end, $object=0) {
        if (empty($rows) && $object != 0) {
            $rows = $this->getDataByUser($date_start, $date_end, $object);
        }

        $range = intval(date('m', $date_end)) - intval(date('m', $date_start));
        $num_of_days = array();
        for ($i=0; $i<$range; $i++) {
            $num_of_days[$i] = (strtotime('+'.($i+1).' month', $date_start) - strtotime('+'.$i.' month', $date_start))/3600/24;
        }

        $data = array();
        $data_index = 0;
        $day_count = 0;
        foreach ($num_of_days as $key => $value) {
            $day_count += $value;
            $data_temp = array(
                'price_count' => 0,
                'operate_count' => 0
            );

            while ($data_index < $day_count) {
                $data_temp['price_count'] += $rows[$data_index]['price_count'];
                $data_temp['operate_count'] += $rows[$data_index]['operate_count'];
                $data_index++;
            }

            $data[] = $data_temp;
        }

        return $data;
    }

    /**
     * 将数据转换为字符串
     * @param $data array : 需要转换为字符串的数据
     */
    private function getDataStr ($data) {
        $data_str = array(
            'price_count' => '',
            'operate_count' => ''
        );

        $price_count = '';
        $operate_count = '';

        foreach ($data as $key => $value) {
            $price_count .= '"'.$value['price_count'].'",';
            $operate_count .= '"'.$value['operate_count'].'",';
        }

        $price_count = substr($price_count, 0, strlen($price_count)-1);
        $operate_count = substr($operate_count, 0, strlen($operate_count)-1);
        $data_str['price_count'] = $price_count;
        $data_str['operate_count'] = $operate_count;

        return $data_str;
    }

    /**
     * 将时间范围数组转换为字符串
     * @param  array  $data     : 待转换数据
     * @return string $data_str : 转换后数据
     */
    private function getTimeStr ($data) {
        $data_str = '';
        foreach ($data as $key => $value) {
            $data_str .= '"'.$value.'",';
        }
        $data_str = substr($data_str, 0, strlen($data_str)-1);

        return $data_str;
    }

    /**
     * 获取日期数组
     */
    private function getDateArr ($date_start, $date_end) {
        $date_arr = array();
        $date_index = $date_start;
        while ($date_index < $date_end) {
            $date_arr[] = date('m-d', $date_index);
            $date_index = strtotime('+1 day', $date_index);
        }

        return $date_arr;
    }

    /**
     * 获取周数组
     */
    private function getWeekArr ($date_start, $date_end) {
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

    /**
     * 获取月份数组
     */
    private function getMonthArr ($date_start, $date_end) {
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