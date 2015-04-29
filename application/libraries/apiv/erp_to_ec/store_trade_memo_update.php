<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Store_trade_memo_update {
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
    	$response_data['task'] = $request_data['task'];	
		$response_data['memo'] = json_encode(array('op_time'=>$request_data['add_time'],'op_content'=>$request_data['memo'],'op_name'=>$request_data['sender']));
		$response_data['from_api_v'] = $request_data['from_api_v'];
		$response_data['app_id'] = $request_data['node_type'];
		//$response_data['sign'] = time();//签名
		$response_data['order_bn'] = $request_data['tid'];
		$response_data['mark_type'] = $request_data['flag'];//不确定字段
		$response_data['date'] = $request_data['date'];
		$response_data['method'] = 'b2c.order.remark';
		$response_data['node_id'] = $request_data['from_node_id'];
    	return array('response_data'=>$response_data,'order_bn'=>$response_data['order_bn'],'from_method'=>$request_data['method'],'node_type'=>$request_data['node_type'],'is_callback'=>TRUE);
    //	$CI->load->library('common/httpclient');
    	
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
			$callback_data['data'] = json_encode($return_data['data']);
			$callback_data['sign'] = '';
			$callback_data['rsp'] = 'succ';
		} else {
			$callback_data = array();
			$callback_data['res'] = $return_data['res'];
			$callback_data['msg_id'] = $data['msg_id'];
			$callback_data['err_msg'] = '';
			$callback_data['data'] = json_encode($return_data['data']);
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