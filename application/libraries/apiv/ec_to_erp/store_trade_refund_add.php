<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 * @author EC端请求售后服务接口
 *
 */
class Store_trade_refund_add {

	public function __construct()
	{
	//	 $data =  $this->_init();
	//	 return $data;
	}
    
    function _init() {
    	$request_data = get_post(NULL);
    	$response_data = array();
    	$response_data['status'] = strtolower(($request_data['status']));
    	$response_data['t_received'] = strtotime($request_data['t_received']);
    	$response_data['paymethod'] = $request_data['payment_type'];  
    	$response_data['pay_account'] = $request_data['seller_account'];
    	$response_data['memo'] = $request_data['memo'];
    	$response_data['app_id'] = $request_data['node_type'];
    	$response_data['sign'] = $request_data['sign'];
    	$response_data['currency'] = $request_data['currency'];
    	$response_data['node_id'] = $request_data['from_node_id'];
    	$response_data['date'] = $request_data['date'];
    	$response_data['payment'] = $request_data['payment_tid'];
    	$response_data['bank'] = $request_data['buyer_bank'];
    	$response_data['t_sent'] = strtotime($request_data['t_sent']);
    	$response_data['trade_no'] = $request_data['outer_no'];
    	$response_data['pay_type'] = $request_data['pay_type'];
    	$response_data['account'] = $request_data['buyer_account'];
    	$response_data['task'] = $request_data['task'];
    	$response_data['cur_money'] = $request_data['currency_fee'];
    	$response_data['t_ready'] = strtotime($request_data['t_begin']);
    	$response_data['refund_bn'] = $request_data['refund_id'];
    	$response_data['node_version'] = '';
    	$response_data['order_bn'] = $request_data['tid'];
    	$response_data['money'] = $request_data['refund_fee'];
    	$response_data['method'] = 'ome.refund.add';
    	    	
    	return array('response_data'=>$response_data,'order_bn'=>$response_data['order_bn'],'from_method'=>$request_data['method'],'node_type'=>$request_data['node_type'],'is_callback'=>TRUE);
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
    		return json_encode(array('res'=>$return_data['res'], 'msg_id'=>$params['msg_id'], 'rsp'=>'succ', 'err_msg'=>'', 'data'=>''));
    	}
    }
    
    
}

?>