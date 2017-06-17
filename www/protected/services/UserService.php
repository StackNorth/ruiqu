<?php
class UserService extends Service{
    /**
     * @param $user_id mongoid 
     */
    public function getUser($user_id,$need_parse = true){
        $user = RUser::get($user_id);
        if(!$user){
            return false;
        }
        if($need_parse){
            $user = $user->parseRow($user->attributes);
        }
        return $user;
    }

}
?>