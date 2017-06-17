<?php

class ZOrder extends ZComponent
{
    /**
     * 根据订单ID返回订单
     */
    public function get($order_id){
        $criteria = new EMongoCriteria();
        $criteria->_id('==', $order_id);
        $order = Order::model()->find($criteria);
        return $order;
    }


}

