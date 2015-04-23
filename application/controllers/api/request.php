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
		
	//	file_put_contents('api_juzhen.log', 'matrix_get:'.print_r(get_post(),1),FILE_APPEND);
		
		$this->load->model('stream_model');
		if (get_post('method',true)) {
			$method_name = get_post('method',true);
			$method_name = str_replace('.','_',$method_name);
			
			$node_type = get_post('node_type',true) == 'ecos.b2c' ? 'erp_to_ec' : 'ec_to_erp';
			$filenames = get_filenames('application/libraries/apiv/'.$node_type);
			foreach ($filenames as $key=>$value) {
				if ($method_name.'.php' == $value) {
					
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
					
					//返回
					$result = $this->$method_name->result(array('return_data'=>$return_data,'response_data'=>$data['response_data'],'msg_id'=>md5($stream_id)));
					echo($result);
					
					//回调
					$callback_data = '';
					$callback_url = '';
					if (method_exists($this->$method_name,'callback')) {
						$callback_rs = $this->$method_name->callback(array('return_data'=>$return_data,'msg_id'=>md5($stream_id)));
						$callback_data = $callback_rs['callback_data'];
						$callback_url = $callback_rs['callback_url'];
					}
					
					//记录数据2
					$this->stream_model->log_second(array('return_data'=>$return_data,'callback_url'=>$callback_url,'callback_data'=>$callback_data,'return_callback'=>''),$stream_id);
					
					if (isset($data['is_callback']) && $data['is_callback'] == TRUE) {
						$this->time_callback(md5($stream_id));//立即回调
					}
					
				}
			}
			
		} else {
			die('{"res": "fail", "msg_id": "", "rsp": "e00093", "err_msg": "no method", "data": "no method"}');
		}
		
	}
	
	/*
	* 定时回调函数
	*/
	
	function time_callback($msg_id = '') {
		$where = '';
		if ($msg_id) {
			$where = " and msg_id='{$msg_id}'";
		}
		
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
	//	echo 'success';
	}
	
	
	function ent_check() {
		echo '{"res":"succ","msg":"ok","info":""}';
	}
	
	
	
	
	
	
	function test() {
		
		$post_data = $this->httpclient->post('http://mosrapi.pinzhen365.com/index.php/api',$txt);
		var_dump($post_data);
	}
	
}

