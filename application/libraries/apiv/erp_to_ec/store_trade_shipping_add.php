<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class store_trade_shipping_add {

	public function __construct()
	{
	//	 $data =  $this->_init();
	//	 return $data;
	}
    
    function _init() {
    	$request_data = get_post(NULL);
    	$response_data = array();
    	//发送到ecstore数据
    	$response_data['is_cod'] = $request_data['is_cod'];
		$response_data['money'] = $request_data['shipping_fee'];//未知字段
		$response_data['ship_distinct'] = $request_data['receiver_district'];//接收区域
		$response_data['app_id'] = $request_data['node_type'];//接口类型
		$response_data['sign'] = '';//签名
		$response_data['date'] = $request_data['create_time'];//时间
		$response_data['ship_states'] = $request_data['receiver_state'];//省
		$response_data['ship_addr'] = $request_data['receiver_address'];//收货地址
		$response_data['ship_name'] = $request_data['receiver_name'];//收货人名称
		$response_data['order_bn'] = $request_data['tid'];//订单ID
		$response_data['method'] = 'b2c.delivery.create';
		$response_data['status'] = $request_data['status'];//状态
		$response_data['ship_email'] = $request_data['receiver_email'];//email
		$response_data['from_api_v'] = '2.2';
		$response_data['delivery'] = $request_data['shipping_type'];//配送方式
		$response_data['logi_name'] = $request_data['logistics_company'];//物流公司
		$response_data['node_id'] = $request_data['from_node_id'];
		$response_data['ship_tel'] = $request_data['receiver_phone'];//电话
		$response_data['ship_zip'] = $request_data['receiver_zip'];
		$response_data['delivery_bn'] = $request_data['shipping_id'];//发货单id
		$response_data['task'] = $request_data['task'];
		$response_data['logi_no'] = $request_data['logistics_no'];
		$response_data['ship_city'] = $request_data['receiver_city'];//市
		$response_data['is_protect'] = $request_data['is_protect'];
		$response_data['t_begin'] = strtotime($request_data['t_begin']);
		
		$shipping_items = json_decode($request_data['shipping_items']);
		$items = array();
		foreach ($shipping_items as $key => $value) {
			$tmp = new stdClass;
			$tmp->product_bn = $value->bn;
			$tmp->product_name = $value->name;
			$tmp->number = $value->number;
			$items[$key] = $tmp;
		}	
		
		$response_data['items'] = json_encode($items);
		$response_data['buyer_id'] = $request_data['buyer_id'];
		
		//回调接口
		

		
    	return array('response_data'=>$response_data,'order_bn'=>$response_data['order_bn'],'from_method'=>$request_data['method'],'node_type'=>$request_data['node_type']);
    //	$CI->load->library('common/httpclient');
    	
    }
    
    function callback($data) {
		
    	$request_data = get_post(NULL);
    	$return_data = json_decode($data['return_data']);
    	$return_data = object_array($return_data);
    	
    	if ($return_data['rsp'] == 'succ') {
			//回调接口
			$callback_data = array();
			$callback_data['res'] = '';
			$callback_data['msg_id'] = $data['msg_id'];
			$callback_data['err_msg'] = '';
			$callback_data['data'] = json_encode(array('tid'=>$request_data['tid'],'delivery_id'=>$request_data['shipping_id']));
			$callback_data['sign'] = '';
			$callback_data['rsp'] = 'succ';
		} else {
			$callback_data = array();
			$callback_data['res'] = $return_data['res'];
			$callback_data['msg_id'] = $data['msg_id'];
			$callback_data['err_msg'] = '';
			$callback_data['data'] = json_encode(array('tid'=>$request_data['tid'],'delivery_id'=>$request_data['shipping_id']));
			$callback_data['sign'] = '';
			$callback_data['rsp'] = 'fail';
		}
		return array('callback_data'=>$callback_data,'callback_url'=>$request_data['callback_url']);
		
		
    }
    
    function result($data) {
    	return '{"res": "", "msg_id": "'.$data['msg_id'].'", "rsp": "running", "err_msg": "", "data": ""}';
    }
    
    
}

?>