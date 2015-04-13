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
					
				//	error_log('------matrix_certi------'.$data['response_data']['matrix_certi'].'------',3,'e.log');
				//	error_log('------matrix_timestamp------'.$data['response_data']['matrix_timestamp'].'------',3,'e.log');
				//	error_log('------sign------'.$data['response_data']['sign'].'------',3,'e.log');
				//	error_log('------certi_key------'.$check_data['certi_key'].'------',3,'e.log');
					
					
					$return_data = $this->httpclient->post($check_data['api_url'],$data['response_data']);//发送
					
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
		$txt = 'a:46:{s:3:"tid";s:15:"150410111830553";s:7:"shop_id";s:32:"e0f1a4afdc30e0724bd9be57355de70e";s:12:"shipping_fee";s:5:"0.000";s:11:"shipping_id";s:13:"1504101100006";s:11:"create_time";s:19:"2015-04-10 11:23:37";s:10:"is_protect";s:5:"false";s:6:"is_cod";s:5:"false";s:8:"buyer_id";s:7:"freedom";s:6:"status";s:4:"SUCC";s:13:"shipping_type";s:51:"广州太阳新天地自提（周五、六、日）";s:12:"logistics_id";s:2:"16";s:17:"logistics_company";s:12:"门店自提";s:12:"logistics_no";s:11:"15041233444";s:14:"logistics_code";s:1:"s";s:13:"receiver_name";s:11:"13690182120";s:14:"receiver_state";s:6:"广东";s:13:"receiver_city";s:9:"广州市";s:17:"receiver_district";s:9:"东山区";s:16:"receiver_address";s:26:"广东广州市东山区11";s:12:"receiver_zip";s:0:"";s:14:"receiver_email";s:0:"";s:15:"receiver_mobile";s:11:"13690182120";s:14:"receiver_phone";s:0:"";s:4:"memo";s:0:"";s:7:"t_begin";s:19:"2015-04-10 11:23:37";s:15:"refund_operator";s:5:"admin";s:14:"shipping_items";s:115:"[{"name":"beher\u9ed1\u6807\u624b\u5207\u7247\u5305\u88c5\u98ce\u5e7248\u4e2a\u6708","bn":"23000901","number":"1"}]";s:9:"ship_type";s:8:"delivery";s:6:"modify";s:19:"2015-04-13 17:44:00";s:10:"to_node_id";s:10:"1266942530";s:9:"node_type";s:8:"ecos.b2c";s:8:"to_api_v";s:3:"2.0";s:10:"from_api_v";s:3:"2.2";s:6:"app_id";s:8:"ecos.ome";s:6:"method";s:24:"store.trade.shipping.add";s:4:"date";s:19:"2015-04-13 17:44:00";s:12:"callback_url";s:131:"http://mosrerp.pinzhen365.com/index.php/openapi/rpc_callback/async_result_handler/id/142891824092271958382677-1428918240/app_id/ome";s:6:"format";s:4:"json";s:8:"certi_id";s:10:"1706149837";s:1:"v";i:1;s:12:"from_node_id";s:10:"1964902530";s:4:"task";s:24:"142891824092271958382677";s:4:"sign";s:32:"eec7169fefdb98269f80184a97e5b81c";s:12:"matrix_certi";s:32:"9b2ae9e45dfa6ec64f4b3789e417a022";s:15:"matrix_to_certi";s:32:"9b2ae9e45dfa6ec64f4b3789e417a022";s:16:"matrix_timestamp";i:1428918240;}';
		$txt = unserialize($txt);
		echo '<xmp>';
		var_dump($txt);
		exit;
		$post_data = $this->httpclient->post('http://mosrerp.pinzhen365.com/index.php/openapi/rpc_callback/async_result_handler/id/14289059185971454720460-1428905918/app_id/ome',$txt);
		var_dump($post_data);
	}
	
}

