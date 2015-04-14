<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Request extends Api_Controller {

	
	
	public function index()
	{
		$certi = get_post('matrix_certi',true);//证书名，32位
		$timestamp = get_post('matrix_timestamp',true); //时间戳
		$sign = get_post('sign',true); //验证码 md5(证书名加密匙加时间戳)
		$to_certi = get_post('matrix_to_certi',true); //验证码 md5(证书名加密匙加时间戳)
		
		$txt = get_post(NULL);
		$txt = print_r($txt,1);
		
		//验证请求
		$this->load->model('certi_model');
		$check_data = $this->certi_model->check($certi,$timestamp,$sign,$to_certi);
		if ($check_data == false) {
			die('{"res": "fail", "msg_id": "", "rsp": "e00093", "err_msg": "sign error", "data": "sign error"}');
		}
		
		file_put_contents('api_juzhen.log', 'matrix_get:'.print_r(get_post(),1),FILE_APPEND);
		
		$this->load->model('stream_model');
		if (get_post('method',true)) {
			$method_name = get_post('method',true);
			$method_name = str_replace('.','_',$method_name);
			$filenames = get_filenames('application/libraries/apiv');
			foreach ($filenames as $key=>$value) {
				if ($method_name.'.php' == $value) {
					$node_type = get_post('node_type',true) == 'ecos.b2c' ? 'erp_to_ec' : 'ec_to_erp';

					$this->load->library('apiv/'.$node_type.'/'.$method_name);
					
					file_put_contents('api_juzhen.log', 'method_name:'.$method_name."\r\n",FILE_APPEND);
					
					$data = $this->$method_name->_init();
					//记录数据1
					$stream_id = $this->stream_model->log_first($data);
					//转发
					$this->load->library('common/httpclient');
					$now = time();
					$data['response_data']['matrix_certi'] = $check_data['certi_name'];
					$data['response_data']['matrix_timestamp'] = $now;
					$data['response_data']['sign'] = md5($check_data['certi_name'].$check_data['certi_key'].$now);
					
					file_put_contents('api_juzhen.log', 'data:'.print_r($data,1)."\r\n",FILE_APPEND);
					file_put_contents('api_juzhen.log', 'data:'.$check_data['api_url']."\r\n",FILE_APPEND);
					
					$return_data = $this->httpclient->post($check_data['api_url'],$data['response_data']);//发送
					
					file_put_contents('api_juzhen.log', 'return_data:'.print_r($return_data,1)."\r\n",FILE_APPEND);
					
					$return_callback = '';
					$callback_url = '';
					$callback_data = '';
					if (isset($data['callback_data'])) {
						$callback_data = $data['callback_data'];
						$callback_url = $data['callback_url'];
					}
					
					$result = $this->$method_name->result(array('return_data'=>$return_data,'return_callback'=>$return_callback,'msg_id'=>md5($stream_id)));
					echo $result;
					//记录数据2
					$this->stream_model->log_second(array('return_data'=>$return_data,'callback_url'=>$callback_url,'callback_data'=>$callback_data,'return_callback'=>''),$stream_id);
				}
			}
			
		} else {
			die('{"res": "fail", "msg_id": "", "rsp": "e00093", "err_msg": "no method", "data": "no method"}');
		}
		
	}
	
	
	
	function ent_check() {
		echo '{"res":"succ","msg":"ok","info":""}';
	}
	
	
	
	
	
	
	function test() {
		$this->load->library('common/httpclient');
		$txt = '{"res":"","err_msg":"","data":"{\"tid\":\"150409155456787\",\"delivery_id\":\"1504101100002\"}","sign":"","rsp":"succ"}';
		$txt = json_decode($txt);
	//	var_dump();
		
		$post_data = $this->httpclient->post('http://mosrerp.pinzhen365.com/index.php/openapi/rpc_callback/async_result_handler/id/14289059185971454720460-1428905918/app_id/ome',$txt);
		var_dump($post_data);
	}
	
}

