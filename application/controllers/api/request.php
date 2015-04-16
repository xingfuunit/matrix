<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Request extends Api_Controller {

	
	
	public function index()
	{
		$certi = get_post('matrix_certi',true);//证书名，32位
		$timestamp = get_post('matrix_timestamp',true); //时间戳
		$sign = get_post('sign',true); //验证码 md5(证书名加密匙加时间戳)
		$to_certi = get_post('matrix_to_certi',true); //验证码 md5(证书名加密匙加时间戳)
		
		
		//验证请求
		$this->load->model('certi_model');
		$check_data = $this->certi_model->check($certi,$timestamp,$sign,$to_certi);
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
					$node_type = get_post('node_type',true) == 'ecos.b2c' ? 'erp_to_ec' : 'ec_to_erp';
					$this->load->library('apiv/'.$node_type.'/'.$method_name);
					$data = $this->$method_name->_init();
					//记录数据1
					$stream_id = $this->stream_model->log_first($data,$certi,$check_data['certi_name']);
					//转发
					$this->load->library('common/httpclient');
					$now = time();
					$data['response_data']['matrix_certi'] = $check_data['certi_name'];
					$data['response_data']['matrix_timestamp'] = $now;
					$data['response_data']['sign'] = md5($check_data['certi_name'].$check_data['certi_key'].$now);
					//发送前增加msg_id
					if (isset($data['response_data']['msg_id'])) {
						$data['response_data']['msg_id'] = md5($stream_id);
					}
					
					$return_data = $this->httpclient->post($check_data['api_url'],$data['response_data']);//发送
					error_log("return_data:".print_r($return_data,1));
					$return_callback = '';
					$callback_url = '';
					$callback_data = '';
					if (isset($data['callback_data'])) {
						
						//增加msg_id
						if (isset($data['callback_data']['msg_id'])) {
							$data['callback_data']['msg_id'] = md5($stream_id);
						}
						
						$callback_data = $data['callback_data'];
						$callback_url = $data['callback_url'];
					}
					
					$result = $this->$method_name->result(array('return_data'=>$return_data,'msg_id'=>md5($stream_id)));
					error_log("return_result:".print_r($result,1));
					echo $result;
					//记录数据2
					$this->stream_model->log_second(array('return_data'=>$return_data,'callback_url'=>$callback_url,'callback_data'=>$callback_data,'return_callback'=>''),$stream_id);
				}
			}
			
		} else {
			die('{"res": "fail", "msg_id": "", "rsp": "e00093", "err_msg": "no method", "data": "no method"}');
		}
		
	}
	
	/*
	* 定时回调函数
	*/
	function time_callback() {
		$this->load->model('stream_model');
		$rs = $this->stream_model->findByAttributes("callback_retry = '0' and callback_status='0'",'callback_time desc');
		if ($rs) {
			$this->load->library('common/httpclient');
			
			$callback_data = unserialize($rs['callback_data']);
			$this->load->model('certi_model');
			$certi_rs = $this->certi_model->findByAttributes(array('certi_name'=>$rs['form_certi']));
			
			$now = time();
			$callback_data['matrix_certi'] = $certi_rs['certi_name'];
			$callback_data['matrix_timestamp'] = $now;
			$callback_data['sign'] = md5($certi_rs['certi_name'].$certi_rs['certi_key'].$now);
			
			$return_callback = $this->httpclient->post($rs['callback_url'],$callback_data);//发送
				
			
			$data = array();
			$data['return_callback'] = $return_callback;
			$data['callback_retry'] = $rs['callback_retry'] + 1;
			$data['callback_time'] = time();
			$data['callback_status'] = 1;
			$this->stream_model->update($data,array('stream_id'=>$rs['stream_id']));
		}
		echo 'success';
	}
	
	function ent_check() {
		echo '{"res":"succ","msg":"ok","info":""}';
	}
	
	
	
	
	
	
	function test() {
		$this->load->library('common/httpclient');

		$post_data = $this->httpclient->post('http://www.163.com',$txt);
		
		var_dump($post_data);
		
		
		echo '111111';
	}
	
}

