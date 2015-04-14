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
					$stream_id = $this->stream_model->log_first($data);
					//转发
					$this->load->library('common/httpclient');
					$now = time();
					$data['response_data']['matrix_certi'] = $check_data['certi_name'];
					$data['response_data']['matrix_timestamp'] = $now;
					$data['response_data']['sign'] = md5($check_data['certi_name'].$check_data['certi_key'].$now);
					
					
					$return_data = $this->httpclient->post($check_data['api_url'],$data['response_data']);//发送
					
					$return_callback = '';
					$callback_url = '';
					$callback_data = '';
					if (isset($data['callback_data'])) {
						$callback_data = $data['callback_data'];
						$callback_url = $data['callback_url'];
					}
					
					$result = $this->$method_name->result(array('return_data'=>$return_data,'msg_id'=>md5($stream_id)));
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
		$rs = $this->stream_model->findByAttributes(array('callback_retry'=>0),'callback_time desc');
		if ($rs && $rs['send_time'] != 0) {
			$this->load->library('common/httpclient');
			$return_callback = $this->httpclient->post($rs['callback_url'],unserialize($rs['callback_data']));//发送
			
			$data = array();
			$data['return_callback'] = $return_callback;
			$data['callback_retry'] = $rs['callback_retry'] + 1;
			$data['callback_time'] = time();
			$this->stream_model->update($data);
		}
		echo 'success';
	}
	
	function ent_check() {
		echo '{"res":"succ","msg":"ok","info":""}';
	}
	
	
	
	
	
	
	function test() {
		$this->load->library('common/httpclient');
		$txt = '{"is_cod":"false","money":"","ship_distinct":"\u4e1c\u5c71\u533a","app_id":"ecos.b2c","sign":"","date":"2015-04-13 20:13:29","ship_states":"\u5e7f\u4e1c","ship_addr":"\u5e7f\u4e1c\u5e7f\u5dde\u5e02\u4e1c\u5c71\u533a11","ship_name":"13690182120","order_bn":"150413200115901","method":"b2c.delivery.create","status":"READY","ship_email":"","from_api_v":"2.2","delivery":"\u987a\u4e30","logi_name":"\u987a\u4e30\u901f\u8fd0","node_id":"1964902530","ship_tel":"","ship_zip":"\u5e7f\u4e1c\u5e7f\u5dde\u5e02\u4e1c\u5c71\u533a11","delivery_bn":"1504131100001","task":"14289272095387749647654","logi_no":"","ship_city":"\u5e7f\u5dde\u5e02","is_protect":"false","t_begin":1428927209,"items":[{"product_bn":"11002401","product_name":"\u65b0\u897f\u5170\u6d3b\u7eff\u9752\u53e3\u3010\u9884\u552e\u3011","number":"1"}],"buyer_id":"freedom"}';
		
		$txt = json_decode($txt);
		
		
		$test = json_decode('[{"product_bn": "23000901", "product_name": "beher\u9ed1\u6807\u624b\u5207\u7247\u5305\u88c5\u98ce\u5e7248\u4e2a\u6708", "number": "1"}]', 1);
		echo '<xmp>'; 
		var_dump($txt);
		exit;
	//	echo '<xmp>';
	//	var_dump($txt);
	//	exit;
	//	echo md5($txt['matrix_certi'].'pzstore!@#$'.$txt['matrix_timestamp']);
	//	exit;
		$post_data = $this->httpclient->post('http://mosrapi.pinzhen365.com/index.php/api',$txt);
		var_dump($post_data);
	}
	
}

