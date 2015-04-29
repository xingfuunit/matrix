<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 支付方式同步接口(erp 到ec)   
 * @author Administrator
 *
 */

class Store_shop_payment_type_list_get {

	public function __construct()
	{
	//	 $data =  $this->_init();
	//	 return $data;
	}
	
    function _init() {
    	$request_data = get_post(NULL);
    	
    	$response_data['task'] = $request_data['task'];
    	$response_data['date'] = $request_data['date'];
//     	$response_data['sign'] = $request_data['sign'];
    	$response_data['from_api_v'] = $request_data['from_api_v'];
    	$response_data['app_id'] = $request_data['node_type'];
    	$response_data['node_id'] = $request_data['to_node_id'];
    	$response_data['method'] = 'ectools.get_payments.get_all';
    	
    	
    	return array('response_data'=>$response_data,
    			'from_method'=>$request_data['method'],
    			'node_type'=>$request_data['node_type'],
    			);
    	
    }
    
    /**
     * 处理结果返回
     */
	function result($params){
    	$return_data = json_decode($params['return_data']);
    	$return_data = object_array($return_data);
    	$response_data = $params['response_data'];
    	if($return_data['rsp'] !=  'succ'){
    		return json_encode(array('res'=>$return_data['res'], 'msg_id'=>$params['msg_id'], 'rsp'=>'fail', 'err_msg'=>'', 'data'=>''));
    	}else{
    		return json_encode(array('res'=>$return_data['res'], 'msg_id'=>$params['msg_id'], 'rsp'=>'running', 'err_msg'=>'', 'data'=>''));
    	}
    }
    
    
    //callback 处理
    function callback($data) {
    	$request_data = get_post(NULL);
    	
    	$return_data = json_decode($data['return_data']);
    	$return_data = object_array($return_data);
    	
    	sort($return_data['data']);
    	 
    		//回调接口
    	$callback_data = array();
    	$callback_data['res'] = '';
    	$callback_data['err_msg'] = '';
    	$callback_data['data'] = json_encode($this->build_data($return_data['data']));
    	$callback_data['sign'] = '';
    	$callback_data['rsp'] = 'succ';
    	$callback_data['msg_id'] = $data['msg_id'];
    	
    	return array('callback_data'=>$callback_data,'callback_url'=>$request_data['callback_url']);
    
    }
    
    /**
     * 构造返回的数据结构
     */
    function build_data($return_data){
    	
    	$data = array();
    	if($return_data){
    		foreach($return_data as $k =>$v ){
    			$data[$k]['pay_type'] 		= $v['payout_type'];
    			$data[$k]['pay_bn'] 		= $v['payment_id'];
    			$data[$k]['custom_name'] 	= $v['payment_name'];
    		}
    	}
    	return $data;
    }
    
    
}

?>