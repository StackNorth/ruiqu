<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	public $_id;
	
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
        $criteria = new EMongoCriteria;
        $criteria->email = $this->username;
        $user = User::model()->find($criteria);
        if (!$user){ 
            $this->errorCode=self::ERROR_USERNAME_INVALID;
            $this->errorMessage = "用户不存在";
        } else if ($user->status == 0){
        	$this->errorCode=self::ERROR_USERNAME_INVALID;
            $this->errorMessage = "用户未激活";
        } else if ($user->status == -1){
        	$this->errorCode=self::ERROR_USERNAME_INVALID;
            $this->errorMessage = "用户不存在";
        } else if ($user->pass !== md5($this->password)){
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
            $this->errorMessage = "密码错误";
        } else {
        	$this->_id = $user->_id;
            $this->errorCode=self::ERROR_NONE;
        }
        return !$this->errorCode;
	}
	
	public function getId()
	{
		return $this->_id;
	}
}