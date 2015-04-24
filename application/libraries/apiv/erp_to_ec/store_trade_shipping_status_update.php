<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class store_trade_shipping_status_update {

	public function __construct()
	{
	//	 $data =  $this->_init();
	//	 return $data;
	}
    
    function _init() {
    	$request_data = get_post(NULL);
    	$response_data = array();
		$response_data['app_id'] = $request_data['node_type'];//接口类型
		$response_data['sign'] = '';//签名
		$response_data['order_bn'] = $request_data['tid']; //订单ＩＤ
		$response_data['method'] = 'b2c.delivery.update';
		$response_data['status'] = $request_data['status'];//状态
		$response_data['from_api_v'] = $request_data['from_api_v'];
		$response_data['node_id'] = $request_data['from_node_id'];
		$response_data['date'] = $request_data['date'];
		$response_data['delivery_bn'] = $request_data['shipping_id'];
		$response_data['task'] = $request_data['task'];
		
		
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
    
    
    function result($params) {
    	$return_data = json_decode($params['return_data']);
    	$return_data = object_array($return_data);
    	$response_data = $params['response_data'];
    	if($return_data['rsp'] !=  'succ'){
    		return json_encode(array('res'=>$return_data['res'], 'msg_id'=>$params['msg_id'], 'rsp'=>'fail', 'err_msg'=>'', 'data'=>''));
    	}else{
    		return json_encode(array('res'=>$return_data['res'], 'msg_id'=>$params['msg_id'], 'rsp'=>'running', 'err_msg'=>'', 'data'=>''));
    	}
    }
    
    
}

?>