<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * 获取定单详情接口
 */
class store_trade_fullinfo_get {
	var $right_away = FALSE;
	public function __construct()
	{
	//	 $data =  $this->_init();
	//	 return $data;
	}
    
    function _init() {
    	$CI =& get_instance();
    	$request_data = get_post(NULL);
    	
    	$certi = get_post('matrix_certi',true);//证书名，32位
    	$to_certi = get_post('matrix_to_certi',true); //验证码 md5(证书名加密匙加时间戳)
    	$CI->load->model('certi_model');
    	$from_rs = $CI->certi_model->findByAttributes(array('certi_name'=>$certi));
    	$to_rs = $CI->certi_model->findByAttributes(array('certi_name'=>$to_certi));
    	
    	$response_data = array();
    	$response_data['to_node_id'] = $request_data['to_node_id'];
    	$response_data['rights_level'] = 'custom';
    	$response_data['refresh_time'] = date('Y-m-d H:i:s');
    	$response_data['to_api_v'] = $request_data['to_api_v'];
		$response_data['format'] = $request_data['format'];
		$response_data['timestamp'] = time();
		$response_data['msg_id'] = '';
    	$response_data['from_api_v'] = $request_data['from_api_v'];
    	$response_data['app_id'] = 'ecos.b2c';
    	$response_data['sign'] = '';
    	$response_data['to_type'] = 'ecos.b2c';
    	$response_data['node_type'] = 'ecos.b2c';
    	$response_data['node_id'] = $request_data['from_node_id'];
    	$response_data['certi_id'] = $request_data['certi_id'];
    	$response_data['date'] = $request_data['date'];
    	$response_data['from_node_id'] = $request_data['from_node_id'];
    	$response_data['task'] = get_tark(rand(0,100));
    	$response_data['api_url'] = $to_rs['api_url'];
    	$response_data['channel_ver'] = '';
    	$response_data['from_type'] = 'ecos.ome';
    	$response_data['v'] = '1';
    	$response_data['tid'] = $request_data['tid'];
    	$response_data['_id'] = 'rel_'.$request_data['from_node_id'].'_store.trade.fullinfo.get_'.$request_data['to_node_id'];
    	$response_data['method'] = 'b2c.order.detail';
    	$response_data['channel'] = '';
    	$response_data['from_token'] = $from_rs['token'];
    	$response_data['to_token'] = $to_rs['token'];
    	
    	
		
    	return array('response_data'=>$response_data,'order_bn'=>$response_data['tid'],'from_method'=>$request_data['method'],'node_type'=>$request_data['node_type']);
    	
    }
    
    
    
    
    function result($data) {
    	
    	$return_data = json_decode($data['return_data']);
    	$return_data = object_array($return_data);
    	
    	
		$data_rs = array();
		$promotion_details = object_array(json_decode($return_data['data']['promotion_details']));
		$data_rs['trade']['discount_fee'] = $return_data['data']['discount_fee'] ; 
		$data_rs['trade']['promotion_details'] = $promotion_details;
		$data_rs['trade']['buyer_name'] = $return_data['data']['buyer_name'];
		$data_rs['trade']['is_cod'] = $return_data['data']['is_cod'];
		$data_rs['trade']['receiver_email'] = $return_data['data']['receiver_email'];
		$data_rs['trade']['point_fee'] = $return_data['data']['point_fee'];
		$data_rs['trade']['currency_rate'] = $return_data['data']['currency_rate'];
		$data_rs['trade']['currency'] = $return_data['data']['currency'];
		$data_rs['trade']['total_weight'] = $return_data['data']['total_weight'];
		$data_rs['trade']['total_currency_fee'] = $return_data['data']['total_currency_fee'];
		$data_rs['trade']['shipping_type'] = $return_data['data']['shipping_type'];
		$data_rs['trade']['receiver_address'] = $return_data['data']['receiver_address'];
		$data_rs['trade']['payment_tid'] = $return_data['data']['receiver_zip'];
		$orders = object_array(json_decode($return_data['data']['orders']));
		foreach ($orders['order'] as $key=>$value) {
			$item = $orders['order'][$key]['order_items']['item'];
			unset($orders['order'][$key]['order_items']);
			$orders['order'][$key]['order_items']['orderitem'] = $item;
		}
		$data_rs['trade']['orders'] = $orders;
		$data_rs['trade']['trade_memo'] = $return_data['data']['trade_memo'];
		$data_rs['trade']['lastmodify'] = $return_data['data']['lastmodify'];
		$data_rs['trade']['branch_id'] = $return_data['data']['branch_id'];
		$data_rs['trade']['has_invoice'] = $return_data['data']['has_invoice'];
		$data_rs['trade']['receiver_district'] = $return_data['data']['receiver_district'];
		$data_rs['trade']['receiver_city'] = $return_data['data']['receiver_city'];
		$data_rs['trade']['title'] = $return_data['data']['title'];
		$data_rs['trade']['orders_discount_fee'] = $return_data['data']['orders_discount_fee'];
		$data_rs['trade']['tax_type'] = $return_data['data']['tax_type'];
		$data_rs['trade']['buyer_memo'] = $return_data['data']['buyer_memo'];
		$data_rs['trade']['invoice_title'] = $return_data['data']['invoice_title'];
		$data_rs['trade']['receiver_state'] = $return_data['data']['receiver_state'];
		$data_rs['trade']['branch_name_user'] = $return_data['data']['branch_name_user'];
		$data_rs['trade']['local_id'] = $return_data['data']['local_id'];
		$data_rs['trade']['tax_content'] = $return_data['data']['tax_content'];
		$data_rs['trade']['receiver_time'] = $return_data['data']['receiver_time'];
		$data_rs['trade']['protect_fee'] = $return_data['data']['protect_fee'];
		$data_rs['trade']['receiver_phone'] = $return_data['data']['receiver_phone'];
		$data_rs['trade']['pay_status'] = $return_data['data']['pay_status'];
		$data_rs['trade']['shop_bn'] = $return_data['data']['shop_bn'];
		$data_rs['trade']['status'] = $return_data['data']['status'];
		$data_rs['trade']['total_trade_fee'] = $return_data['data']['total_trade_fee'];
		$data_rs['trade']['buyer_address'] = $return_data['data']['buyer_address'];
		$data_rs['trade']['pay_cost'] = $return_data['data']['pay_cost'];
		$data_rs['trade']['buyer_uname'] = $return_data['data']['buyer_uname'];
		$data_rs['trade']['buyer_email'] = $return_data['data']['buyer_email'];
		$data_rs['trade']['tid'] = $return_data['data']['tid'];
		$data_rs['trade']['receiver_community'] = $return_data['data']['receiver_community'];
		$data_rs['trade']['buyer_zip'] = $return_data['data']['buyer_zip'];
		$payment_lists = object_array(json_decode($return_data['data']['payment_lists']));
		$data_rs['trade']['payment_lists'] = $payment_lists;
		$data_rs['trade']['receiver_mobile'] = $return_data['data']['receiver_mobile'];
		$data_rs['trade']['buyer_mobile'] = $return_data['data']['buyer_mobile'];
		$data_rs['trade']['goods_discount_fee'] = $return_data['data']['goods_discount_fee'];
		$data_rs['trade']['orders_number'] = $return_data['data']['orders_number'];
		$data_rs['trade']['shipping_tid'] = $return_data['data']['shipping_tid'];
		$data_rs['trade']['total_goods_fee'] = $return_data['data']['total_goods_fee'];
		$data_rs['trade']['created'] = $return_data['data']['created'];
		$data_rs['trade']['is_auto_complete'] = $return_data['data']['is_auto_complete'];
		$data_rs['trade']['payed_fee'] = $return_data['data']['payed_fee'];
		$data_rs['trade']['invoice_fee'] = $return_data['data']['invoice_fee'];
		$data_rs['trade']['modified'] = $return_data['data']['modified'];
		$data_rs['trade']['is_protect'] = $return_data['data']['is_protect'];
		$data_rs['trade']['ship_status'] = $return_data['data']['ship_status'];
		$data_rs['trade']['buyer_obtain_point_fee'] = $return_data['data']['buyer_obtain_point_fee'];
		$data_rs['trade']['payment_type'] = $return_data['data']['payment_type'];
		$data_rs['trade']['buyer_phone'] = $return_data['data']['buyer_phone'];
		$data_rs['trade']['receiver_name'] = $return_data['data']['receiver_name'];
		$data_rs['trade']['shipping_fee'] = $return_data['data']['shipping_fee'];
		$data_rs['trade']['receiver_zip'] = $return_data['data']['receiver_zip'];
		$data_rs['trade']['buyer_id'] = $return_data['data']['buyer_id'];
    	
    	
    	
    	$result_data = array();
    	$result_data['res'] = '';
    	$result_data['msg_id'] = $data['msg_id'];
    	$result_data['err_msg'] = '';
    	$result_data['data'] = json_encode($data_rs);
    	$result_data['rsp'] = 'succ';
    	
    	
    //	$txt = print_r($result_data,1);
    	return '{"res":"","msg_id":"5534B399C0A81729C278EEB1F8539164","err_msg":"","data":"{\"trade\": {\"discount_fee\": \"0.00\", \"promotion_details\": [{\"promotion_fee\": \"0.00\", \"promotion_name\": \"\u6ce8\u518c\u4f1a\u5458\u53ca\u94f6\u5361\u4f1a\u5458\u6d88\u8d391\u5143\u79ef\u5206+1\"}], \"buyer_name\": \"\", \"is_cod\": null, \"receiver_email\": \"\", \"point_fee\": \"0.00\", \"currency_rate\": \"1.0000\", \"currency\": \"CNY\", \"total_weight\": \"800.00\", \"total_currency_fee\": \"101.24\", \"shipping_type\": \"\u987a\u4e30\", \"receiver_address\": \"\u5e7f\u4e1c\u5e7f\u5dde\u5e02\u4e1c\u5c71\u533a11\", \"payment_tid\": \"deposit\", \"orders\": {\"order\": [{\"consign_time\": \"\", \"weight\": \"800\", \"title\": \"\u65b0\u897f\u5170\u6d3b\u7eff\u9752\u53e3\u3010\u9884\u552e\u3011\", \"discount_fee\": 0, \"type\": \"goods\", \"price\": \"88.000\", \"oid\": \"25150\", \"order_status\": \"SHIP_NO\", \"order_items\": {\"orderitem\": [{\"sku_id\": \"1148\", \"name\": \"\u65b0\u897f\u5170\u6d3b\u7eff\u9752\u53e3\u3010\u9884\u552e\u3011\", \"weight\": \"800\", \"iid\": \"14\", \"discount_fee\": 0, \"bn\": \"11002401\", \"sku_properties\": \"\u89c4\u683c:800g\/\u76d2\", \"item_status\": \"normal\", \"item_type\": \"product\", \"num\": \"1\", \"sendnum\": \"0\", \"sale_price\": \"86.240\", \"score\": \"86\", \"price\": \"86.240\", \"total_item_fee\": 86.239999999999995}]}, \"iid\": \"25150\", \"type_alias\": \"\u5546\u54c1\u533a\u5757\", \"total_order_fee\": 86.239999999999995, \"items_num\": 1, \"orders_bn\": \"11002401\"}]}, \"trade_memo\": null, \"lastmodify\": \"2015-04-20 15:57:06\", \"branch_id\": 0, \"has_invoice\": false, \"receiver_district\": \"\u4e1c\u5c71\u533a\", \"receiver_city\": \"\u5e7f\u5dde\u5e02\", \"title\": \"Order Create\", \"orders_discount_fee\": \"0.00\", \"tax_type\": \"false\", \"buyer_memo\": \"\", \"invoice_title\": \"\", \"receiver_state\": \"\u5e7f\u4e1c\", \"branch_name_user\": \"\", \"local_id\": null, \"tax_content\": \"\", \"receiver_time\": \"\u4efb\u610f\u65e5\u671f,\u4efb\u610f\u65f6\u95f4\u6bb5\", \"protect_fee\": \"0.00\", \"receiver_phone\": \"\", \"pay_status\": \"PAY_NO\", \"shop_bn\": \"xbd_store_\", \"status\": \"TRADE_ACTIVE\", \"total_trade_fee\": \"101.24\", \"buyer_address\": \"\", \"pay_cost\": \"0.00\", \"buyer_uname\": \"freedom\", \"buyer_email\": \"171868746@qq.com\", \"tid\": \"150420155724776\", \"receiver_community\": \"\", \"buyer_zip\": null, \"payment_lists\": {\"payment_list\": []}, \"receiver_mobile\": \"13690182120\", \"buyer_mobile\": \"\", \"goods_discount_fee\": \"0.00\", \"orders_number\": 1, \"shipping_tid\": \"1\", \"total_goods_fee\": \"86.24\", \"created\": \"2015-04-20 15:57:06\", \"is_auto_complete\": \"false\", \"payed_fee\": \"0.00\", \"invoice_fee\": \"0.00\", \"modified\": \"2015-04-20 15:57:06\", \"is_protect\": \"false\", \"ship_status\": \"SHIP_NO\", \"buyer_obtain_point_fee\": \"86.00\", \"payment_type\": \"\u9884\u5b58\u6b3e\", \"buyer_phone\": \"\", \"receiver_name\": \"13690182120\", \"shipping_fee\": \"15.00\", \"receiver_zip\": \"\", \"buyer_id\": 343}}","rsp":"succ"}';
    }
    
}

?>
