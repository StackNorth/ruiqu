<?php
/**
 * 注册表单
 * add by justin
 * 2013.12.5
 */
class RegisterForm extends CFormModel
{
	public $email;
	public $name;
	public $idNum;
	public $password;
	public $passwordAgain;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('email, name, password, passwordAgain', 'required'),
			array('email', 'email'),
			array('name', 'length', 'min' => 2, 'max' => 20),
			//array('idNum', 'length', 'is' => 18),
			array('passwordAgain',  'compare', 'compareAttribute'=>'password', 'on'=>'register'),
			array('email,name,idNum', 'authenticate', 'on'=>'register'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
            'email'  => '公司邮箱',
            'name'   => '用户姓名',
            'idNum'   => '身份证号',
        );
	}

	/**
	 * 验证表单
	 */
	public function authenticate($attribute, $params)
	{
		if (!$this->hasErrors()){
			$criteria = new EMongoCriteria;
        	$criteria->$attribute = $this->$attribute;
			$cursor = User::model()->find($criteria);			
			if ($cursor){
				$labels = $this->attributeLabels();
				$this->addError($attribute, $labels[$attribute] . '已存在');
			}
		} 
	}

	/**
	 * 注册用户
	 */
	public function register()
	{	
		$user = new User;
        $user->email = $this->email;
        $user->name = $this->name;
        $user->id_num = '';//$this->idNum;
        $user->pass = md5($this->password);
        $user->status = 0;
        $user->type=1;
        $user->reg_time=time();
        $user->last_login = 0;
        $user->login_times = 0;
        $user->_id = $user->get_new_id();
        if ($user->email == 'admin@yiguanjia.me'){ //设置默认的超级管理员
        	$auth = Yii::app()->authManager;
        	$user->status = 1;
        	if (!$auth->getAuthItem($auth->super_admin)){
        		$auth->createAuthItem($auth->super_admin, 2, '超级管理员');
        	}
        	if (!$auth->isAssigned($auth->super_admin, $user->_id)){
        		$auth->assign($auth->super_admin, $user->_id);
        	}
        	$auth->save();
        }
        $cursor = $user->save(false);
        return $user;
	}
}
