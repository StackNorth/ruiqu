<?php
//用户等级service
class OrderTimeCalService extends Service{
    
    // public function evaluaTime($products,$user_position,$tech_position = array()){
    public function evaluaTime($products){
        $sum_time = 0;//分钟
        foreach ($products as $product) {
            if(isset($product['product']) && $product['count'] >= 1){
                $product_obj = Product::get(new MongoId($product['product']));
                if($product_obj){
                    if($product_obj->cost_time){
                        $sum_time += ($product_obj->cost_time*$product['count']);
                    }
                }
            }
        }
        // if(!empty($user_position) && !empty($tech_position)){
        //     $distance_res = CommonFn::simple_http('http://api.map.baidu.com/direction/v1?mode=driving&origin=23.158633,113.326345&destination=23.558633,113.826345&origin_region=上海&destination_region=上海&output=json&ak=B349f0b32ef6e78b2e678f45cb9fddaf');
        //     $addres_res = json_decode($distance_res,true);
        //     if(isset($addres_res['result']['taxi']['duration'])){
        //         $sum_time += ($addres_res['result']['taxi']['duration']/60);
        //     }else{
        //         $sum_time += 30;
        //     }
        // }else{
        //     $sum_time += 30;
        // }
        return $sum_time;
    }

}
?>