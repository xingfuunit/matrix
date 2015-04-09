<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Request extends Api_Controller {

	
	
	public function index()
	{
		$certi = get_post('matrix_certi',true);//证书名，32位
		$timestamp = get_post('matrix_timestamp',true); //时间戳
		$token = get_post('matrix_token',true); //验证码 md5(证书名加密匙加时间戳)
		$to_certi = get_post('matrix_to_certi',true); //验证码 md5(证书名加密匙加时间戳)
		
		$txt = get_post(NULL);
		$txt = print_r($txt,1);
		
		//验证请求
		$this->load->model('certi_model');
		$check_data = $this->certi_model->check($certi,$timestamp,$token,$to_certi);
		if ($check_data == false) {
			die('{"res": "fail", "msg_id": "", "rsp": "e00093", "err_msg": "sign error", "data": "sign error"}');
		}
		
		$this->load->model('stream_model');
		if (get_post('method',true)) {
			$method_name = get_post('method',true);
			$method_name = str_replace('.','_',$method_name);
			$filenames = get_filenames('application/libraries/apiv');
			foreach ($filenames as $key=>$value) {
				if ($method_name.'.php' == $value) {
					$this->load->library('apiv/'.$method_name);
					$data = $this->$method_name->_init();
					//记录数据1
					$stream_id = $this->stream_model->log_first($data);
					//转发
					$this->load->library('common/httpclient');
					$now = time();
					$data['response_data']['matrix_certi'] = $check_data['certi_name'];
					$data['response_data']['matrix_timestamp'] = $now;
					$data['response_data']['matrix_token'] = md5($check_data['certi_name'].$check_data['certi_key'].$now);
					
					$post_data = $this->httpclient->post($check_data['api_url'],$data['response_data']);
					
					//记录数据2
					$this->stream_model->log_second($post_data,$stream_id);
				}
			}
			
		} else {
			die('{"res": "fail", "msg_id": "", "rsp": "e00093", "err_msg": "no method", "data": "no method"}');
		}
		
	}
	
	
	function test() {
		$this->load->library('common/httpclient');
		$txt = array('matrix_certi'=>'9b2ae9e45dfa6ec64f4b3789e417a022','matrix_timestamp'=>'1428399472','matrix_token'=>'bfcf4255d1b60e4504a9315b0db26d73','matrix_to_certi'=>'b54f6e3ec64220e1c1e23a49e493b796');
		$post_data = $this->httpclient->post('http://mosrerp.pinzhen365.com/index.php/api/',$txt);
		var_dump($post_data);
	}
	
}

