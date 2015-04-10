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
					$data['response_data']['matrix_token'] = md5($check_data['certi_name'].$check_data['certi_key'].$now);
					
					$post_data = $this->httpclient->post($check_data['api_url'],$data['response_data']);
					
					//记录数据2
					$this->stream_model->log_second($post_data,$stream_id);
					var_dump($post_data);
					echo $check_data['api_url'];
					die('success');
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
		$txt = 'a:82:{s:9:"node_type";s:8:"ecos.ome";s:10:"from_api_v";s:3:"2.0";s:10:"to_node_id";s:10:"1904932630";s:8:"to_api_v";s:3:"2.2";s:3:"tid";s:15:"150410114677631";s:5:"title";s:12:"Order Create";s:7:"created";s:19:"2015-04-10 11:46:32";s:8:"modified";s:19:"2015-04-10 11:46:32";s:10:"lastmodify";s:19:"2015-04-10 11:46:32";s:6:"status";s:12:"TRADE_ACTIVE";s:10:"pay_status";s:6:"PAY_NO";s:11:"ship_status";s:7:"SHIP_NO";s:11:"has_invoice";b:0;s:13:"invoice_title";s:0:"";s:11:"invoice_fee";s:4:"0.00";s:8:"tax_type";s:5:"false";s:11:"tax_content";s:0:"";s:15:"total_goods_fee";s:5:"88.00";s:15:"total_trade_fee";s:6:"103.00";s:12:"discount_fee";s:4:"0.00";s:18:"goods_discount_fee";s:4:"0.00";s:19:"orders_discount_fee";s:4:"0.00";s:8:"local_id";N;s:7:"shop_bn";s:10:"xbd_store_";s:17:"promotion_details";s:133:"[{"promotion_name":"\u6ce8\u518c\u4f1a\u5458\u53ca\u94f6\u5361\u4f1a\u5458\u6d88\u8d391\u5143\u79ef\u5206+1","promotion_fee":"0.00"}]";s:9:"payed_fee";s:4:"0.00";s:8:"currency";s:3:"CNY";s:13:"currency_rate";s:6:"1.0000";s:18:"total_currency_fee";s:6:"103.00";s:22:"buyer_obtain_point_fee";s:5:"88.00";s:9:"point_fee";s:4:"0.00";s:12:"total_weight";s:6:"800.00";s:13:"receiver_time";s:28:"任意日期,任意时间段";s:12:"shipping_tid";s:1:"1";s:13:"shipping_type";s:6:"顺丰";s:12:"shipping_fee";s:5:"15.00";s:10:"is_protect";s:5:"false";s:11:"protect_fee";s:4:"0.00";s:11:"payment_tid";s:6:"alipay";s:12:"payment_type";s:9:"支付宝";s:6:"is_cod";N;s:13:"receiver_name";s:11:"13690182120";s:14:"receiver_email";s:0:"";s:15:"receiver_mobile";s:11:"13690182120";s:14:"receiver_state";s:6:"广东";s:13:"receiver_city";s:9:"广州市";s:17:"receiver_district";s:9:"东山区";s:18:"receiver_community";s:0:"";s:16:"receiver_address";s:26:"广东广州市东山区11";s:12:"receiver_zip";s:0:"";s:14:"receiver_phone";s:0:"";s:8:"pay_cost";s:4:"0.00";s:10:"buyer_memo";s:0:"";s:13:"orders_number";i:1;s:16:"is_auto_complete";s:5:"false";s:9:"branch_id";i:0;s:16:"branch_name_user";s:0:"";s:11:"buyer_uname";s:7:"freedom";s:10:"buyer_name";s:0:"";s:13:"buyer_address";s:0:"";s:12:"buyer_mobile";s:0:"";s:11:"buyer_phone";s:0:"";s:11:"buyer_email";s:16:"171868746@qq.com";s:9:"buyer_zip";N;s:8:"buyer_id";i:343;s:6:"orders";s:684:"{"order":[{"oid":"25100","orders_bn":"11002401","type":"goods","type_alias":"\u5546\u54c1\u533a\u5757","iid":"25100","title":"\u65b0\u897f\u5170\u6d3b\u7eff\u9752\u53e3\u3010\u9884\u552e\u3011","items_num":1,"order_status":"SHIP_NO","price":"88.00","total_order_fee":88,"discount_fee":0,"consign_time":"","order_items":{"item":[{"sku_id":"1148","iid":"14","bn":"11002401","name":"\u65b0\u897f\u5170\u6d3b\u7eff\u9752\u53e3\u3010\u9884\u552e\u3011","sku_properties":"\u89c4\u683c:800g\/\u76d2","weight":"800","score":"88","price":"88.00","num":"1","sendnum":"0","total_item_fee":88,"sale_price":"88.00","item_type":"product","item_status":"normal","discount_fee":0}]},"weight":"800"}]}";s:13:"payment_lists";s:19:"{"payment_list":[]}";s:10:"trade_memo";N;s:6:"app_id";s:8:"ecos.b2c";s:6:"method";s:15:"store.trade.add";s:4:"date";s:19:"2015-04-10 11:46:33";s:12:"callback_url";s:126:"http://mosr.pinzhen365.com/openapi/rpc_callback/async_result_handler/id/49580fef130f39da0487ebd24fe91c0b-1428637593/app_id/b2c";s:6:"format";s:4:"json";s:8:"certi_id";s:10:"1404149837";s:1:"v";i:1;s:12:"from_node_id";s:10:"1848982633";s:4:"task";s:32:"49580fef130f39da0487ebd24fe91c0b";s:4:"sign";s:32:"D67C58A849FE67C101D6BE387317CC99";s:12:"matrix_certi";s:32:"9b2ae9e45dfa6ec64f4b3789e417a022";s:16:"matrix_timestamp";i:1428637593;s:12:"matrix_token";s:32:"4c67cea4bbe30a182e233b5f4130b03e";s:15:"matrix_to_certi";s:32:"b54f6e3ec64220e1c1e23a49e493b796";}';
		$txt = unserialize($txt);
		$post_data = $this->httpclient->post('http://mosrapi.pinzhen365.com/index.php/api/',$txt);
		var_dump($post_data);
	}
	
}

