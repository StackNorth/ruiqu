<?php

class ZCityInfo extends ZComponent
{
    /**
     *  检查city_info数组是否合法
     */
    public function checkCity($para,&$ret){
        $result = true;

        $city = array();
        if(is_string($para)){
            $city = explode(",",$para);
        }elseif(is_array($para)){
            $city = $para;
        }else{
            $result = false;
            return $result;
        }

        if(!empty($city)){
            if(count($city)>3){
                $result = false;
            }else{
                $ret['province'] = isset($city[0])?$city[0]:'';
                $ret['city'] = isset($city[1])?$city[1]:'';
                $ret['area'] = isset($city[2])?$city[2]:'';
            }
        }

        return $result;
    }
}

