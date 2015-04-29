<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Store_trade_aftersale_status_update {
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
    	$response_data['status'] = $request_data['status'];
		$response_data['task'] = $request_data['task'];
		$response_data['from_api_v'] = $request_data['from_api_v'];
		$response_data['app_id'] = 'ecos.b2c';
		$response_data['sign'] = '';
		$response_data['order_bn'] = $request_data['tid'];
		$response_data['date'] = $request_data['date'];
		$response_data['return_bn'] = $request_data['aftersale_id'];
		$response_data['method'] = 'b2c.aftersale.update';
		$response_data['node_id'] = $request_data['from_node_id'];
		$response_data['addon'] = $request_data['addon'];
		
    	return array('response_data'=>$response_data,'order_bn'=>$response_data['order_bn'],'from_method'=>$request_data['method'],'node_type'=>$request_data['node_type']);
    	
    }
    
    function callback($data) {
		
    //	$request_data = get_post(NULL);
    	$request_data = $data['request_data'];
    	$return_data = json_decode($data['return_data']);
    	$return_data = object_array($return_data);
    	$CI =& get_instance();
    	$CI->load->model('stream_model');
    	$order_rs = $CI->stream_model->findByAttributes("order_bn = '".$request_data['tid']."' and from_method='store.trade.reship.add'",'stream_id desc');
    	$reship_id = '';
    	if ($order_rs) {
    		$request_rs = mb_unserialize($order_rs['request_data']);
    		$reship_id = $request_rs['reship_id'];
    	}
    	
    	
    	if ($return_data['rsp'] == 'succ') {
			//回调接口
			$callback_data = array();
			$callback_data['res'] = '';
			$callback_data['msg_id'] = $data['msg_id'];
			$callback_data['err_msg'] = '';
			$callback_data['data'] = json_encode(array('tid'=>$request_data['tid'],'reship_id'=>$reship_id));
			$callback_data['sign'] = '';
			$callback_data['rsp'] = 'succ';
		} else {
			$callback_data = array();
			$callback_data['res'] = $return_data['res'];
			$callback_data['msg_id'] = $data['msg_id'];
			$callback_data['err_msg'] = '';
			$callback_data['data'] = json_encode(array('tid'=>$request_data['tid'],'reship_id'=>$reship_id));
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