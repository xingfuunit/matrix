<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Request extends Api_Controller {

	
	
	public function index()
	{
		
		error_log('-------0-------',3,'e.log');
		$certi = get_post('matrix_certi',true);//证书名，32位

		$timestamp = get_post('matrix_timestamp',true); //时间戳
		$token = get_post('matrix_token',true); //验证码 md5(证书名加密匙加时间戳)
		$to_certi = get_post('matrix_to_certi',true); //验证码 md5(证书名加密匙加时间戳)
		
		
		$txt = get_post(NULL);
		$txt = print_r($txt,1);
		
	//	var_dump($txt);
		error_log($txt,3,'e.log');
		
		
		//验证请求
		$this->load->model('certi_model');
		$check_data = $this->certi_model->check($certi,$timestamp,$token,$to_certi);
		if ($check_data == false) {
		//	die('{"res": "fail", "msg_id": "", "rsp": "e00093", "err_msg": "sign error", "data": "sign error"}');
		}

		error_log('-------1-------',3,'e.log');
		
		$this->load->model('stream_model');
		if (get_post('method',true)) {
			error_log('-------2-------',3,'e.log');
			$method_name = get_post('method',true);
			$method_name = str_replace('.','_',$method_name);
			$filenames = get_filenames('application/libraries/apiv');
			foreach ($filenames as $key=>$value) {
				if ($method_name.'.php' == $value) {
					$this->load->library('apiv/'.$method_name);
					$data = $this->$method_name->_init();
					error_log('-------3-------',3,'e.log');
					//记录数据1
					$stream_id = $this->stream_model->log_first($data);
					//转发
					$this->load->library('common/httpclient');
					error_log('-------4-------',3,'e.log');
					$now = time();
					$data['response_data']['matrix_certi'] = 'b54f6e3ec64220e1c1e23a49e493b796';
					$data['response_data']['matrix_timestamp'] = $now;
					$data['response_data']['matrix_token'] = md5('b54f6e3ec64220e1c1e23a49e493b796'.'erp&@%$'.$now);
					
					$post_data = $this->httpclient->post('http://lwqerp.pinzhen365.com/api',$data['response_data']);
					
					//记录数据2
					$this->stream_model->log_second($post_data,$stream_id);
				}
			}
			
		} else {
			die('{"res": "fail", "msg_id": "", "rsp": "e00093", "err_msg": "no method", "data": "no method"}');
		}
		
	}
	
	
	function test() {
		$txt = 'a:73:{s:9:"node_type";s:8:"ecos.ome";s:10:"from_api_v";s:3:"2.0";s:10:"to_node_id";s:10:"1964902530";s:8:"to_api_v";s:3:"2.2";s:3:"tid";s:15:"150407092699196";s:5:"title";s:12:"Order Create";s:7:"created";s:19:"2015-04-07 09:26:50";s:8:"modified";s:19:"2015-04-07 09:26:50";s:10:"lastmodify";s:19:"2015-04-07 09:26:50";s:6:"status";s:12:"TRADE_ACTIVE";s:10:"pay_status";s:6:"PAY_NO";s:11:"ship_status";s:7:"SHIP_NO";s:11:"has_invoice";s:1:"0";s:13:"invoice_title";s:0:"";s:11:"invoice_fee";s:4:"0.00";s:8:"tax_type";s:5:"false";s:11:"tax_content";s:0:"";s:15:"total_goods_fee";s:7:"297.000";s:15:"total_trade_fee";s:6:"297.00";s:12:"discount_fee";s:4:"0.00";s:18:"goods_discount_fee";s:4:"0.00";s:19:"orders_discount_fee";s:5:"23.00";s:7:"shop_bn";s:10:"xbd_store_";s:17:"promotion_details";s:528:"[{"promotion_name":"\u6ce8\u518c\u4f1a\u5458\u8d2d\u7269\u6ee1199\u5143\u514d\u90ae","promotion_fee":"23.00"},{"promotion_name":"\u6ce8\u518c\u4f1a\u5458\u53ca\u94f6\u5361\u4f1a\u5458\u6d88\u8d391\u5143\u79ef\u5206+1","promotion_fee":"0.00"},{"promotion_name":"","promotion_fee":"0.00"},{"promotion_name":"\u8d2d\u4e09\u6587\u9c7c\u9c7c\u8089\u6216\u6fb3\u6d32\u8c37\u9972\u4e0a\u8111\uff0c\u8ba2\u5355\u514d\u90ae\uff01","promotion_fee":"0.00"},{"promotion_name":"\u6d4b\u8bd5\u8ba2\u5355\u8d60\u54c12","promotion_fee":"0.00"}]";s:9:"payed_fee";s:4:"0.00";s:8:"currency";s:3:"CNY";s:13:"currency_rate";s:6:"1.0000";s:18:"total_currency_fee";s:6:"297.00";s:22:"buyer_obtain_point_fee";s:6:"297.00";s:9:"point_fee";s:4:"0.00";s:12:"total_weight";s:7:"2100.00";s:13:"receiver_time";s:28:"任意日期,任意时间段";s:12:"shipping_tid";s:1:"1";s:13:"shipping_type";s:6:"顺丰";s:12:"shipping_fee";s:5:"23.00";s:10:"is_protect";s:5:"false";s:11:"protect_fee";s:4:"0.00";s:11:"payment_tid";s:7:"deposit";s:12:"payment_type";s:9:"预存款";s:13:"receiver_name";s:9:"潘冠辉";s:14:"receiver_email";s:0:"";s:15:"receiver_mobile";s:11:"18675956257";s:14:"receiver_state";s:6:"广东";s:13:"receiver_city";s:9:"广州市";s:17:"receiver_district";s:9:"天河区";s:18:"receiver_community";s:0:"";s:16:"receiver_address";s:53:"广东广州市天河区天河区太阳新天地10号";s:12:"receiver_zip";s:6:"510000";s:14:"receiver_phone";s:0:"";s:8:"pay_cost";s:4:"0.00";s:10:"buyer_memo";s:0:"";s:13:"orders_number";s:1:"1";s:16:"is_auto_complete";s:5:"false";s:9:"branch_id";s:1:"0";s:16:"branch_name_user";s:0:"";s:11:"buyer_uname";s:8:"gamtypan";s:10:"buyer_name";s:0:"";s:13:"buyer_address";s:0:"";s:12:"buyer_mobile";s:0:"";s:11:"buyer_phone";s:0:"";s:8:"buyer_id";s:4:"5448";s:6:"orders";s:2760:"{"order":[{"oid":"24939","orders_bn":"13001601","type":"gift","type_alias":"\u8d60\u54c1\u533a\u5757","iid":"24939","title":"\u52a0\u62ff\u5927\u7fe1\u7fe0\u87ba","items_num":1,"order_status":"SHIP_NO","price":"68.000","total_order_fee":0,"discount_fee":0,"consign_time":"","order_items":{"item":[{"sku_id":"1166","iid":"185","bn":"13001601","name":"\u52a0\u62ff\u5927\u7fe1\u7fe0\u87ba","sku_properties":"\u89c4\u683c:1000g\/\u888b","weight":"1000","score":"0","price":"0.000","num":"1","sendnum":"0","total_item_fee":0,"sale_price":"0.000","item_type":"gift","item_status":"normal","discount_fee":0}]},"weight":"1000"},{"oid":"24940","orders_bn":"11002401","type":"goods","type_alias":"\u5546\u54c1\u533a\u5757","iid":"24940","title":"\u65b0\u897f\u5170\u6d3b\u7eff\u9752\u53e3\u3010\u9884\u552e\u3011","items_num":1,"order_status":"SHIP_NO","price":"88.000","total_order_fee":88,"discount_fee":0,"consign_time":"","order_items":{"item":[{"sku_id":"1148","iid":"14","bn":"11002401","name":"\u65b0\u897f\u5170\u6d3b\u7eff\u9752\u53e3\u3010\u9884\u552e\u3011","sku_properties":"\u89c4\u683c:800g\/\u76d2","weight":"800","score":"88","price":"88.000","num":"1","sendnum":"0","total_item_fee":88,"sale_price":"88.000","item_type":"product","item_status":"normal","discount_fee":0}]},"weight":"800"},{"oid":"24941","orders_bn":"11001701","type":"goods","type_alias":"\u5546\u54c1\u533a\u5757","iid":"24941","title":"\u65b0\u897f\u5170\u751f\u869d1\u53f7 150-170g\/\u53ea","items_num":1,"order_status":"SHIP_NO","price":"150.000","total_order_fee":150,"discount_fee":0,"consign_time":"","order_items":{"item":[{"sku_id":"1100","iid":"164","bn":"11001701","name":"\u65b0\u897f\u5170\u751f\u869d1\u53f7 150-170g\/\u53ea","sku_properties":"\u89c4\u683c:6\u53ea\/\u76d2","weight":"900","score":"150","price":"150.000","num":"1","sendnum":"0","total_item_fee":150,"sale_price":"150.000","item_type":"product","item_status":"normal","discount_fee":0}]},"weight":"900"},{"oid":"24942","orders_bn":"12000102","type":"goods","type_alias":"\u5546\u54c1\u533a\u5757","iid":"24942","title":"\u82f1\u56fd\u8fdb\u53e3\u51b0\u9c9c\u4e09\u6587\u9c7c\u9c7c\u8089\u523a\u8eab\u3010\u5305\u90ae\uff01\u70ed\u5356\u4e2d\uff01\u3011","items_num":1,"order_status":"SHIP_NO","price":"59.000","total_order_fee":59,"discount_fee":0,"consign_time":"","order_items":{"item":[{"sku_id":"1435","iid":"18","bn":"12000102","name":"\u82f1\u56fd\u8fdb\u53e3\u51b0\u9c9c\u4e09\u6587\u9c7c\u9c7c\u8089\u523a\u8eab\u3010\u5305\u90ae\uff01\u70ed\u5356\u4e2d\uff01\u3011","sku_properties":"\u89c4\u683c:400g","weight":"400","score":"59","price":"59.000","num":"1","sendnum":"0","total_item_fee":59,"sale_price":"59.000","item_type":"product","item_status":"normal","discount_fee":0}]},"weight":"400"}]}";s:13:"payment_lists";s:19:"{"payment_list":[]}";s:6:"app_id";s:8:"ecos.b2c";s:6:"method";s:15:"store.trade.add";s:4:"date";s:19:"2015-04-07 09:26:51";s:12:"callback_url";s:130:"http://devjason.pinzhen365.com/openapi/rpc_callback/async_result_handler/id/ef47d6a68f94cab29079e15861362c94-1428370010/app_id/b2c";s:6:"format";s:4:"json";s:8:"certi_id";s:10:"1766199631";s:1:"v";s:1:"1";s:12:"from_node_id";s:10:"1264972434";s:4:"task";s:32:"ef47d6a68f94cab29079e15861362c94";s:4:"sign";s:32:"4FA42908D2CBBDEF2C31FB3842E5DCDA";}';
		$txt = unserialize($txt);
		
	//	var_dump($txt);exit;
		$this->load->library('common/httpclient');
		$post_data = $this->httpclient->post('http://mosrerp.pinzhen365.com/index.php/api/',$txt);
		var_dump($post_data);
	}
	
}

