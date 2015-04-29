<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class store_trade_shipping_update {
	
	var $right_away = FALSE;
	
	public function __construct()
	{
	//	 $data =  $this->_init();
	//	 return $data;
	}
    
    function _init() {
    	$request_data = get_post(NULL);
    	$response_data = array();
    	//发送到ecstore数据
    	$response_data['logi_code'] = $request_data['logistics_code'];
		$response_data['app_id'] = 'ecos.b2c';
		$response_data['sign'] = '';//签名
		$response_data['order_bn'] = $request_data['tid'];//订单
		$response_data['method'] = 'b2c.delivery.update';
		$response_data['from_api_v'] = '2.2';
		$response_data['logi_name'] = $request_data['logistics_company'];//物流公司
		$response_data['node_id'] = $request_data['from_node_id'];
		$response_data['date'] = $request_data['date'];
		$response_data['delivery_bn'] = $request_data['shipping_id'];
		$response_data['task'] = $request_data['task'];
		$response_data['logi_no'] = $request_data['logistics_no'];
		
		
    	return array('response_data'=>$response_data,'order_bn'=>$response_data['order_bn'],'from_method'=>$request_data['method'],'node_type'=>$request_data['node_type'],'is_callback'=>FALSE);
    //	$CI->load->library('common/httpclient');
    	
    }
    
    
    function callback($data) {
    	$request_data = $data['request_data'];
    	$return_data = json_decode($data['return_data']);
    	$return_data = object_array($return_data);
    	
    	if ($return_data['rsp'] == 'succ') {
			//回调接口
			$callback_data = array();
			$callback_data['res'] = '';
			$callback_data['err_msg'] = '';
			$callback_data['data'] = json_encode(array('tid'=>$request_data['tid'],'delivery_id'=>$request_data['shipping_id']));
			$callback_data['sign'] = '';
			$callback_data['rsp'] = 'succ';
			$callback_data['msg_id'] = $data['msg_id'];
		} else {
			$callback_data = array();
			$callback_data['res'] = $return_data['res'];
			$callback_data['err_msg'] = '';
			$callback_data['data'] = json_encode(array('tid'=>$request_data['tid'],'delivery_id'=>$request_data['shipping_id']));
			$callback_data['sign'] = '';
			$callback_data['rsp'] = 'fail';
			$callback_data['msg_id'] = $data['msg_id'];
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