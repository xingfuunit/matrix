<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class store_trade_reship_add {
	var $right_away = FALSE;
	public function __construct()
	{
	//	 $data =  $this->_init();
	//	 return $data;
	}
    
    function _init() {
    	$request_data = get_post(NULL);
    	$response_data = array();
    	$response_data['money'] = $request_data['reship_fee'];
    	$response_data['ship_distinct'] = $request_data['receiver_district'];
    	$response_data['app_id'] = 'ecos.b2c';
    	$response_data['sign'] = '';
    	$response_data['date'] = $request_data['date'];
    	$response_data['ship_states'] = $request_data['receiver_state'];
    	$response_data['ship_addr'] = $request_data['receiver_address'];
    	$response_data['ship_name'] = $request_data['receiver_name'];
    	$response_data['t_confirm'] = strtotime($request_data['date']);
    	$response_data['order_bn'] = $request_data['tid'];
    	$response_data['reship_bn'] = $request_data['reship_id'];
    	$response_data['method'] = 'b2c.reship.create';
    	$response_data['status'] = $request_data['status'];
    	$response_data['ship_email'] = $request_data['receiver_email'];
    	$response_data['memo'] = $request_data['memo'];
    	$response_data['from_api_v'] = $request_data['from_api_v'];
    	$response_data['t_send'] = strtotime($request_data['create_time']);
    	$response_data['ship_mobile'] = $request_data['receiver_mobile'];
    	$response_data['logi_name'] = $request_data['logistics_company'];
    	$response_data['node_id'] = $request_data['from_node_id'];
    	$response_data['ship_tel'] = $request_data['receiver_phone'];
    	$response_data['ship_zip'] = $request_data['receiver_zip'];
    	$response_data['delivery'] = $request_data['reship_type'];
    	$response_data['task'] = $request_data['task'];
    	$response_data['logi_no'] = $request_data['logistics_no'];
    	$response_data['ship_city'] = $request_data['receiver_city'];
    	$response_data['is_protect'] = $request_data['is_protect'];
    	$response_data['t_begin'] = strtotime($request_data['t_begin']);
    	
    	$reship_items = json_decode($request_data['reship_items']);
    	$items = array();
    	foreach ($reship_items as $key => $value) {
    		$tmp = new stdClass;
    		$tmp->item_type = $value->sku_type;
    		$tmp->product_bn = $value->bn;
    		$tmp->product_name = $value->name;
    		$tmp->number = $value->number;
    		$items[$key] = $tmp;
    	}
    	$response_data['items'] = json_encode($items);
    	$response_data['buyer_id'] = $request_data['buyer_id'];
    	return array('response_data'=>$response_data,'order_bn'=>$response_data['order_bn'],'from_method'=>$request_data['method'],'node_type'=>$request_data['node_type']);
    	
    }
    
    
    function callback($data) {
		
    //	$request_data = get_post(NULL);
    	$request_data = $data['request_data'];
    	$return_data = json_decode($data['return_data']);
    	$return_data = object_array($return_data);
    	
    	if ($return_data['rsp'] == 'succ') {
			//回调接口
			$callback_data = array();
			$callback_data['res'] = '';
			$callback_data['msg_id'] = $data['msg_id'];
			$callback_data['err_msg'] = '';
			$callback_data['data'] = json_encode(array('tid'=>$request_data['tid'],'return_id'=>$request_data['reship_id']));
			$callback_data['sign'] = '';
			$callback_data['rsp'] = 'succ';
		} else {
			$callback_data = array();
			$callback_data['res'] = $return_data['res'];
			$callback_data['msg_id'] = $data['msg_id'];
			$callback_data['err_msg'] = '';
			$callback_data['data'] = json_encode(array('tid'=>$request_data['tid'],'return_id'=>$request_data['reship_id']));
			$callback_data['sign'] = '';
			$callback_data['rsp'] = 'fail';
		}
		return array('callback_data'=>$callback_data,'callback_url'=>$request_data['callback_url']);
    }
    
    
    function result($data) {
    	
    	$return_data = json_decode($data['return_data']);
    	$return_data = object_array($return_data);
    	return '{"res": "'.$return_data['res'].'", "msg_id": "'.$data['msg_id'].'", "rsp": "running", "err_msg": "", "data": ""}';
    }
    
}

?>
