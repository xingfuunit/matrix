<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crontab_stream extends Api_Controller {
	
	var $send_list = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model('stream_model');
		$this->load->model('certi_model');

	}
	
	//定时运行
	public function stream()
	{
		$now = time();
		$stream_rs = $this->stream_model->query("SELECT * FROM api_stream WHERE (send_status=0 or send_status=1)  and retry <= 3 and locked=0 GROUP BY order_bn limit 5");
		$send_list = array();//发送数组
		$callback_list = array();//回调数组
		foreach ($stream_rs as $key=>$value) {
			$stream_id = $value['stream_id'];
			$form_certi = $this->certi_model->findByAttributes(array('certi_name'=>$value['form_certi']));//请求方
			$to_certi = $this->certi_model->findByAttributes(array('certi_name'=>$value['to_certi']));//被请求方
			//发送
			$return_data = $value['return_data'];
			$response_data  = mb_unserialize($value['response_data']);
			if (empty($value['return_data']) || $value['return_data'] == '-3') {
				
				$response_data['matrix_certi'] = $to_certi['certi_name'];
				$response_data['matrix_timestamp'] = $now;
				$response_data['sign'] = md5($to_certi['certi_name'].$to_certi['certi_key'].$now);
				
				$response_data['stream_id'] = $stream_id;//记录流水ＩＤ
				$send_list[] =     array(
					        'url' => $to_certi['api_url'], 
					        'method' => 'POST',
					        'post_data' => $response_data,
					        'header' => null,
					        'options' => array()
					    );
			}
			$retry = $value['retry'] + 1;
			$this->stream_model->retry_lock($stream_id,$retry);
		}
		
		if (empty($stream_rs)) {
			exit;
		}
		
		
		//多线程发送请求
		$this->load->library('common/multi_curl',array($this,'_multi_send'));
		
		
 		foreach ($send_list as $val) {
 			echo 'foreach send '.$val ['post_data']['stream_id'].'<br />';
		    $this->multi_curl->request($val ['url'], $val ['method'], $val ['post_data'], $val ['header'], $val ['options']);
		}
		
		
		$this->multi_curl->execute();

	
		
		$callback_list = array();
		foreach ($stream_rs as $key=>$value) {
			$return_data = $value['return_data'];
			if (isset($this->send_list[$value['stream_id']])) {
				$return_data = $this->send_list[$value['stream_id']];
			}
			if ($return_data && $return_data != '-3') {
				$callback_list[] = $this->_get_callback_data($value['request_data'],$value['response_data'],$return_data,$value['form_certi'],$value['stream_id']);
			}
		}
		
		
		$this->multi_curl->__destruct();
		$this->multi_curl->__set('callback',array($this,'_multi_callback'));
		foreach ($callback_list as $val) {
			echo 'foreach callback '.$val ['post_data']['stream_id'].'<br />';
		    $this->multi_curl->request($val ['url'], $val ['method'], $val ['post_data'], $val ['header'], $val ['options']);
		}
		$this->multi_curl->execute();
		foreach ($stream_rs as $key=>$value) {
			$this->stream_model->retry_unlock($value['stream_id']);
		}
	}
	
	
	function _get_callback_data($request_data,$response_data,$return_data,$form_certi,$stream_id) {
		//回调
		$form_certi = $this->certi_model->findByAttributes(array('certi_name'=>$form_certi));//请求方
		$callback_list = array();
		$request_data = mb_unserialize($request_data);
		$method_name = str_replace('.','_',$request_data['method']);
		$node_type = $request_data['node_type'] == 'ecos.b2c' ? 'erp_to_ec' : 'ec_to_erp';
		$filenames = get_filenames('application/libraries/apiv/'.$node_type);
		foreach ($filenames as $key2=>$value2) {
			if ($method_name.'.php' == $value2) {
				$this->load->library('apiv/'.$node_type.'/'.$method_name);
				if (method_exists($this->$method_name,'callback')) {
					//发送回调
					$callback_rs = $this->$method_name->callback(array('request_data'=>$request_data,'return_data'=>$return_data,'response_data'=>$response_data,'msg_id'=>md5($stream_id)));
					$now = time();
					$callback_data = $callback_rs['callback_data'];
					$callback_url = $callback_rs['callback_url'];
					$callback_data['matrix_certi'] = $form_certi['certi_name'];
					$callback_data['matrix_timestamp'] = $now;
					$callback_data['sign'] = md5($form_certi['certi_name'].$form_certi['certi_key'].$now);
					$callback_data['stream_id'] = $stream_id;
					//$return_callback = $this->httpclient->set_timeout(15)->post($rs['callback_url'],$callback_data);//回调发送
					$callback_list =     array(
						        'url' => $callback_url, 
						        'method' => 'POST',
						        'post_data' => $callback_data,
						        'header' => null,
						        'options' => array()
						    );
				}
			}
		}
		
		return $callback_list;
		
	}
	
	
	function _multi_send($return_data, $info,$error,$request)
	{
		
	    //记录返回数据
	    $stream_id = $request->post_data['stream_id'];
	    $this->send_list[$stream_id] = $return_data;
	    $this->stream_model->log_sended($return_data,$stream_id);
	    
	    echo ' sended '.$stream_id.'<br />';
	}
	
	function _multi_callback($return_callback, $info,$error, $request)
	{
		
	    //记录返回数据
	    
	    $stream_id = $request->post_data['stream_id'];
	    $callback_url = $request->url;
	    $callback_data = $request->post_data;
	    
		$this->stream_model->log_callback($callback_url,$callback_data,$return_callback,$stream_id);
		echo ' callback '.$stream_id.'<br />';
		
	}
	
	
	
	
	
}

