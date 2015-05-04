<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Youzan  {

	
	
	public function _init($sync='')
	{
		$from_certi = get_post('from_node_id',true);//证书名，32位
		
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
						
						
					//	$this->stream_model->log_send_all(array('return_data'=>$return_data,'callback_url'=>$callback_url,'callback_data'=>$callback_data,'return_callback'=>$return_callback),$stream_id);
					}
					
					$result = $this->$method_name->result(array('return_data'=>$return_data,'response_data'=>$data['response_data'],'msg_id'=>md5($stream_id)));
					echo $result;
				}
			}
			
		} else {
			die('{"res": "fail", "msg_id": "", "rsp": "e00093", "err_msg": "no method", "data": "no method"}');
		}
		
	}
	
	
}

