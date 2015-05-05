<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Request extends Api_Controller {

	
	
	public function index($sync='')
	{
		
	
		$from_node_id = get_post('from_node_id',true);//
		$timestamp = get_post('matrix_timestamp',true); //时间戳
		$sign = get_post('sign',true); //验证码 md5(证书名加密匙加时间戳)
		$to_node_id = get_post('to_node_id',true); //验证码 md5(证书名加密匙加时间戳)
		
		//验证请求
		$this->load->model('certi_model');
		$check_data = $this->certi_model->check($from_node_id,$timestamp,$sign,$to_node_id);

	//	error_log(print_r(get_post(NULL),1),3,'e.log');
		if ($check_data == false) {
			die('{"res": "fail", "msg_id": "", "rsp": "e00093", "err_msg": "sign error", "data": "sign error"}');
		}
		

		
	//	$check_data['library_type'] = 'shopex';
		if ($check_data['library_type']) {
			$this->load->library('handle/'.$check_data['library_type'].'.php');
			$this->$check_data['library_type']->_init($sync,$check_data);
		}
		

/*
		$from_certi = get_post('matrix_certi',true);//证书名，32位

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
					
					$now = time();
					$data['response_data']['matrix_certi'] = $check_data['certi_name'];
					$data['response_data']['matrix_timestamp'] = $now;
					$data['response_data']['sign'] = md5($check_data['certi_name'].$check_data['certi_key'].$now);
					//发送前增加msg_id
					if (isset($data['response_data']['msg_id'])) {
						$data['response_data']['msg_id'] = md5($stream_id);
					}
					
					$send_status = 0;
					if (method_exists($this->$method_name,'callback')) {
						$send_status = 1;
					}
					//记录数据1
					$stream_id = $this->stream_model->log_first($data,$from_certi,$check_data['certi_name'],$send_status);
					
					
					//url判断同步
					if($sync){
						$this->$method_name->right_away = TRUE;
					}
					
					//立即发送
					$return_data = '';
					if ($this->$method_name->right_away == TRUE) {
						$this->load->library('common/httpclient');
						
						file_put_contents('matrix_juzhen.log', date("Y-m-d H:i:s",time()).'matrix_send_url :'.print_r($check_data['api_url'],1)."\r\n",FILE_APPEND);
						file_put_contents('matrix_juzhen.log', date("Y-m-d H:i:s",time()).'matrix_send_data:'.print_r($check_data['response_data'],1)."\r\n",FILE_APPEND);
						
						$return_data = $this->httpclient->set_timeout(20)->post($check_data['api_url'],$data['response_data']);//发送
						
						file_put_contents('matrix_juzhen.log', date("Y-m-d H:i:s",time()).'matrix_send_return:'.print_r($return_data,1)."\r\n",FILE_APPEND);
						
						//立即回调
						$callback_url = '';
						$callback_data = '';
						$return_callback = '';
						if (method_exists($this->$method_name,'callback')) {
							$callback_rs = $this->$method_name->callback(array('return_data'=>$return_data,'response_data'=>$data['response_data'],'request_data'=>get_post(NULL),'msg_id'=>md5($stream_id)));
							$callback_data = $callback_rs['callback_data'];
							$callback_url = $callback_rs['callback_url'];
							$form_certi = $this->certi_model->findByAttributes(array('certi_name'=>$from_certi));
							$callback_data['matrix_certi'] = $form_certi['certi_name'];
							$callback_data['matrix_timestamp'] = $now;
							$callback_data['sign'] = md5($form_certi['certi_name'].$form_certi['certi_key'].$now);
							
							file_put_contents('matrix_juzhen.log', date("Y-m-d H:i:s",time()).'matrix_callback_url:'.print_r($rs['callback_url'],1)."\r\n",FILE_APPEND);
							file_put_contents('matrix_juzhen.log', date("Y-m-d H:i:s",time()).'matrix_callback_data:'.print_r($callback_data,1)."\r\n",FILE_APPEND);
							
							$return_callback = $this->httpclient->set_timeout(15)->post($rs['callback_url'],$callback_data);//发送
							
							file_put_contents('matrix_juzhen.log', date("Y-m-d H:i:s",time()).'matrix_callback_return:'.print_r($return_callback,1)."\r\n",FILE_APPEND);
						}
						
						$this->stream_model->log_send_all(array('return_data'=>$return_data,'callback_url'=>$callback_url,'callback_data'=>$callback_data,'return_callback'=>$return_callback),$stream_id);
					}
					
					$result = $this->$method_name->result(array('return_data'=>$return_data,'response_data'=>$data['response_data'],'msg_id'=>md5($stream_id)));
					echo $result;
				}
			}
			
		} else {
			die('{"res": "fail", "msg_id": "", "rsp": "e00093", "err_msg": "no method", "data": "no method"}');
		}
		
		*/

	}
	
	
	
	
	
	function ent_check() {
		echo '{"res":"succ","msg":"ok","info":""}';
	}
	
}

