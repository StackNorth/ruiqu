<?php
/**
* 系统变量sevice
* 使用示例：
* $result = Service::factory('VariableService')->getVariable('test');
*/
class VariableService extends Service
{

    public function getVariable($key){
        $Key = HelperKey::generateRedisKey($key);
        $result = VariableRedis::get($Key);
        if(empty($result)){
            $criteria = new EMongoCriteria();
            $criteria->_id('==', $key);
            $variable = Variable::model()->find($criteria);
            if($variable){
                $value = $variable->value;
                VariableRedis::set($Key,$value);
                return $value;
            }else{
                return null;
            }
        }else{
            return $result;
        }
    }

    public function setVariable($key,$value){
        $criteria = new EMongoCriteria();
        $criteria->_id('==', $key);
        $variableModel = Variable::model()->find($criteria);
        if(!$variableModel){
            if($this->addVariable($key,$value)){
                $Key = HelperKey::generateRedisKey($key);
                return VariableRedis::set($Key,$value);
            }else{
                return false;
            }
        }else{
            $variableModel->value = $value;
            if($variableModel->update(array('value'),true)){
                $Key = HelperKey::generateRedisKey($key);
                return VariableRedis::set($Key,$value);
            }else{
                return false;
            }
        }
    }

    public function delVariable($key){
        $criteria = new EMongoCriteria();
        $criteria->_id('==', $key);
        $variableModel = Variable::model()->find($criteria);
        $Key = HelperKey::generateRedisKey($key);
        if (VariableRedis::remove($Key)&&$variableModel->delete()) {
            return true;
        }
        return false;
    }

    private function addVariable($key,$value){
        $variableModel = new Variable();
        $variableModel->_id = $key;
        $variableModel->value = $value;
        if ($variableModel->save()){
            return true;
        }else{
            return false;
        }
    }

}
?>