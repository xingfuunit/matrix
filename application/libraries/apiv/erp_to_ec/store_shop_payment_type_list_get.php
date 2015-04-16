<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 订单状态更新 (erp 到ec)
 * @author Administrator
 *
 */

class Store_shop_payment_type_list_get {

	public function __construct()
	{
	//	 $data =  $this->_init();
	//	 return $data;
	}
    
// 	post_data:Array
// 	(
// 	[to_node_id] => 1964902530
// 	[node_type] => ecos.b2c
// 	[to_api_v] => 2.0
// 	[from_api_v] => 2.2
// 	[app_id] => ecos.ome
// 	[method] => store.shop.payment_type.list.get
// 	[date] => 2015-04-16 14:02:04
// 	[callback_url] => http://lwqerp.pinzhen365.com/index.php/openapi/rpc_callback/async_result_handler/id/1429164124759764001528-1429164124/app_id/ome
// 	[format] => json
// 	[certi_id] => 1066139131
// 	[v] => 1
// 	[from_node_id] => 1266942530
// 	[task] => 1429164124759764001528
// 	[sign] => 4744056C8102B1D318DA6B92341FFC4D
// 	)

	
// 	1接收:Array
// 	(
// 			[task] => 1429164124759764001528
// 			[date] => 2015-04-16 14:02:04
// 			[sign] => 4567F4F98A3A6C3A194A10DAF6AF05E5
// 			[from_api_v] => 2.2
// 			[app_id] => ecos.b2c
// 			[node_id] => 1266942530
// 			[method] => ectools.get_payments.get_all
// 	)
	
    function _init() {
    	$request_data = get_post(NULL);
    	
    	$response_data['task'] = $request_data['task'];
    	$response_data['date'] = $request_data['date'];
//     	$response_data['sign'] = $request_data['task'];
    	$response_data['from_api_v'] = $request_data['from_api_v'];
    	$response_data['app_id'] = $request_data['node_type'];
    	$response_data['node_id'] = $request_data['to_node_id'];
    	$response_data['method'] = 'ectools.get_payments.get_all';
    	
    	
    	return array('response_data'=>$response_data,
    			'from_method'=>$request_data['method'],
    			'node_type'=>$request_data['node_type'],
    			'is_callback'=>true,
    			);
    	
    }
    
    /**
     * 处理结果返回
     */
    function result($params){
    	$return_data = json_decode($params['return_data']);
    	
    	return json_encode(array('res'=>'', 'msg_id'=>1, 'rsp'=>'succ', 'err_msg'=>'', 'data'=>''));
    }
    
    
    //callback 处理
    function callback($data) {
    	$request_data = get_post(NULL);
    	$return_data = json_decode($data['return_data']);
    	$return_data = object_array($return_data);
    	 
    	if ($return_data['rsp'] == 'succ') {
    		//回调接口
    		$callback_data = array();
    		$callback_data['res'] = '';
    		$callback_data['err_msg'] = '';
    		$callback_data['data'] = json_encode($return_data['data']);
    		$callback_data['sign'] = '';
    		$callback_data['rsp'] = 'succ';
    		$callback_data['msg_id'] = $data['msg_id'];
    	} else {
    		$callback_data = array();
    		$callback_data['res'] = $return_data['res'];
    		$callback_data['err_msg'] = '';
    		$callback_data['data'] = json_encode($return_data['data']);
    		$callback_data['sign'] = '';
    		$callback_data['rsp'] = 'fail';
    		$callback_data['msg_id'] = $data['msg_id'];
    	}
    	
    	return array('callback_data'=>$callback_data,'callback_url'=>$request_data['callback_url']);
    
    }
    
    
}

?>