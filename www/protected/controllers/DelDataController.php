<?php
/**
 * Created by PhpStorm.
 * User: PHP
 * Date: 2016/10/19
 * Time: 15:30
 */
class DelDataController extends AdminController
{
    public function actionIndex(){

        $data = array("57f9b5f29f5160b8048b489f",
            "57f9b5e29f5160d5048b49dc",
            "57f9b4689f5160b8048b489d",
            "57f9b4549f5160d4048b49c8",
            "57f9b38e9f5160a6048b4999",
            "57f9b3759f5160c3048b49a3",
            "57f9b34a9f5160c9048b493e",
            "57f9b32c9f5160aa048b48c5",
            "57f9b5d39f5160db048b4a91",
            "57fdf0bf9f5160a5048b4b3a",
            "57fdeefc9f5160bf048b4ba9",
            "57fdeef19f5160b8048b4b6e",
            "57fdeeda9f5160d4048b4cea",
            "57fdeed09f5160c4048b4b01",
            "57faf4229f5160db048b4b2f",
            "57f9b6599f5160a9048b4934",
            "57f9b64a9f5160d5048b49dd",
            "57f9b1699f5160dc048b48f9",
            "57f9b12e9f5160a5048b48e3",
            "57f9b1159f5160b2048b48ef",
            "57f9afae9f5160d3048b48ed");

        foreach ($data as $value) {
            $id = new MongoId($value);
            $criteria = new EMongoCriteria();
            $criteria->_id = $id;
            Stock::model()->deleteAll($criteria);
            var_dump(Stock::model()->save());
        }
    }

}
