<?php

class ZCityLib extends ZComponent
{
    /**
     * 根据城市码返回城市
     */
    public function get($city_code){
        $criteria = new EMongoCriteria();
        $criteria->_id('==', $city_code);
        $city = CityLib::model()->find($criteria);
        return $city;
    }

    //增加城市信息
    public function addCity($city_code,$name,$parent_province_id,$parent_city_id,$parent_area_id = 0){
        $city = $this->get($city_code);
        if($city){
            $city->name = $name;
            $city->parent_province_id = $parent_province_id;
            $city->parent_city_id = $parent_city_id;
            $city->parent_area_id = $parent_area_id;
            return $city->update(array('name','parent_province_id','parent_city_id','parent_area_id'),true);
        }else{
            $city = new CityLib();
            $city->_id = $city_code;
            $city->name = $name;
            $city->parent_province_id = $parent_province_id;
            $city->parent_city_id = $parent_city_id;
            $city->parent_area_id = $parent_area_id;
            return $city->save();
        }
    }

    //获取某个城市下一级城市列表
    public function getSubCity($city_code){
        $city = $this->get($city_code);
        $res = false;
        if($city){
            if($city->parent_city_id){
                $res = array();
            }elseif($city->parent_province_id){
                $criteria = new EMongoCriteria();
                $criteria->parent_city_id('==', $city->_id);
                $cursor = CityLib::model()->findAll($criteria);
                $res = CommonFn::getRowsFromCursor($cursor);
                $res = CityLib::model()->parse($res);
            }else{
                $criteria = new EMongoCriteria();
                $criteria->parent_province_id('==',$city_code);
                $criteria->parent_city_id('==',0);
                $cursor = CityLib::model()->findAll($criteria);
                $res = CommonFn::getRowsFromCursor($cursor);
                $res = CityLib::model()->parse($res);
            }
        }elseif($city_code==1){
            $criteria = new EMongoCriteria();
            $criteria->parent_province_id('==',0);
            $cursor = CityLib::model()->findAll($criteria);
            $res = CommonFn::getRowsFromCursor($cursor);
            $res = CityLib::model()->parse($res);
        }
        return $res;
    }


}

