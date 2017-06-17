<?php

class ZUser extends ZComponent
{
    /**
     * 返回用户的昵称
     */
    public function getUserNames($user_ids){
        $fields = array('name' => 1);
        if (is_array($user_ids)){
            $where = array('_id' => array('$in' => $user_ids));
            $cursor = RUser::model()->getCollection()->find($where, $fields);
            $user_names = array();
            foreach ($cursor as $v){
                $user_names[(string)$v['_id']] = $v['name'];
            }
        } else {
            $user_names = '';
            $where = array('_id' => $user_ids);
            $cursor = RUser::model()->getCollection()->findOne($where, $fields);
            if ($cursor){
                $user_names = $cursor['name'];
            }
        }
        return $user_names;
    }

    public function getUserNames2($user_ids){
        $criteria = new EMongoCriteria();
        $criteria->_id('in', $user_ids);
        $cursor = RUser::model()->findAll($criteria);
        $user_names = array();
        $i=0;
        foreach ($cursor as $v){
            $user_names[$i]['user_name'] = $v->name;
            $user_names[$i]['user_id'] = (string)$v->_id;
            $i++;
        }
        return $user_names;
    }

    /**
     * 根据用户ID返回用户
     */
    public function get($user_id){
        $criteria = new EMongoCriteria();
        $criteria->_id('==', $user_id);
        $user = RUser::model()->find($criteria);
        return $user;
    }

    /**
     * 根据用户id返回用户名称
     *
     * 如果存在用户则返回名称，不存在则返回默认字符串
     * @param $user_id 用户id号
     * @return string 用户名
     */
    public function getUserName($user_id) {
        $user = $this->get($user_id);
        if (isset($user) && $user) {
            return $user->name;
        }
        return '未知用户';
    }

    /**
     * 根据用户name返回用户
     */
    public function getUserByName($user_name){
        $criteria = new EMongoCriteria();
        $criteria->user_name('==', $user_name);
        $user = RUser::model()->find($criteria);
        return $user;
    }


    /**
     * 根据用户email返回用户
     */
    public function getUserByEmail($email){
        $criteria = new EMongoCriteria();
        $criteria->email('==', $email);
        $user = RUser::model()->find($criteria);
        return $user;
    }

    /**
     *  获取用户信息
     */
    public function getUserInfo($user_id, $fields=array(), $default=''){
        if (is_array($user_id)){
            $where = array('_id' => array('$in' => $user_id));
            $items = $this->getMultipleModelInfo(RUser::model(), $where, $fields);
            $user_info = array();
            if (is_array($fields) && count($fields) == 1){
                $keys = array_keys($fields);
                $key = $keys[0];
                foreach ($items as $v){
                    $user_info[] = $v[$key];
                }
            } else {
                foreach ($items as $v){
                    $user_info[] = $v;
                }
            }
        } else {
            //var_dump(RUser::model());exit;
            $where = array('_id' => $user_id);
            $user_info = $this->getSingleModelInfo(RUser::model(), $where, $fields, $default);
        }
        return $user_info;
    }


    /**
     *  检查用户id的数组是否合法
     */
    public function checkUsers($para,&$ret){
        $result = true;

        $users = array();
        if(is_string($para)){
            if($para != ''){
                $users = explode(",",$para);
            }
        }elseif(is_array($para)){
            $users = $para;
        }else{
            $result = false;
            return $result;
        }

        if(!empty($users)){
            foreach($users as $user){
                if(count($user) != 1){
                    $result = false;
                }else{
                    if(!CommonFn::isMongoId($user)){
                        $result = false;
                    }else{
                        $_user = $this->get(new MongoId($user));
                        if(empty($_user)){
                            $result = false;
                        }else{
                            $ret[] = new MongoId($user);
                        }
                    }
                }
            }
        }
        return $result;
    }

    public function validate_user_name(&$user_name){

        $user_name = preg_replace(array('/\s/','/@/','/^/'),'',$user_name);
        $ban_words = Yii::app()->params['user_name_ban_words'];
        preg_match_all("/$ban_words/i",$user_name,$matchs);
        if(isset($matchs[0])){
            foreach ($matchs[0] as $value) {
                $user_name = str_replace($value,str_repeat('*',mb_strlen($value,'utf-8')),$user_name);
            }
        }

        if(mb_strlen($user_name, 'utf-8') < 1 ){
            $user_name = 'wz_'.dechex(time());
            return true;
        }
        
    }
}

