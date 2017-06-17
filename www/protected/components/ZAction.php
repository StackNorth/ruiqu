<?php
/**
 * summary: 自定义action事件，对action复用
 * author: justin
 * date: 2014-4-2
 */
class ZAction extends CAction
{ 	
	public $behaviors = array();
	protected $output = 'json';
	protected $attach_behaviors = false;
    
   	public function onBeforeRun($event){
		$this->raiseEvent('onBeforeRun',$event);
	}
    public function onAfterRun($event){
		$this->raiseEvent('onAfterRun',$event);
	}
    
    protected function beforeRun($params = array()){
    	$controller = $this->getController();
    	$params['controller'] = $controller;
    	if (!empty($this->behaviors) && !$this->attach_behaviors){
    		$this->attach_behaviors = true;
    		$this->attachBehaviors($this->behaviors);
    	}    	
		if ($this->hasEventHandler('onBeforeRun')){
			$event = new ZActionEvent($this, $params);
			$this->onBeforeRun($event);
			if (!$event->success){
				switch($this->output){
					case 'json':
						CommonFn::requestAjax($event->success, $event->message);
						break;
					default:
						break;
				}
			}
			return array('success' => $event->success, 'message' => $event->message, 'data' => $event->data);
		} else {
			return array('success' => true, 'message' => '', 'data' => array());
		}		
    }
    
    protected function afterRun($params = array()){
    	$controller = $this->getController();
    	$params['controller'] = $controller;
    	if (!empty($this->behaviors) && !$this->attach_behaviors){
    		$this->attach_behaviors = true;
    		$this->attachBehaviors($this->behaviors);
    	} 
		if ($this->hasEventHandler('onAfterRun')){
			$event = new ZActionEvent($this, $params);
			$this->onAfterRun($event);
			if (!$event->success){
				switch($this->output){
					case 'json':
						CommonFn::requestAjax($event->success, $event->message);
						break;
					default:
						break;
				}				
			}
			return array('success' => $event->success, 'message' => $event->message, 'data' => $event->data);
		} else {
			return array('success' => true, 'message' => '', 'data' => array());
		}		
    }

}