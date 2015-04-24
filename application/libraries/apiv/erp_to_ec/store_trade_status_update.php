<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 订单状态更新 (erp 到ec)
 * @author Administrator
 *
 */

class Store_trade_status_update {

	public function __construct()
	{
	//	 $data =  $this->_init();
	//	 return $data;
	}
	
// 	接收erp的数据
// post_data:Array
// (
//     [tid] => 150413102745968
//     [status] => TRADE_CLOSED
//     [type] => status
//     [modify] => 2015-04-13 10:28:49
//     [is_update_trade_status] => true
//     [reason] => 订单被取消 qqqq
//     [to_node_id] => 1964902530
//     [node_type] => ecos.b2c
//     [to_api_v] => 2.0
//     [from_api_v] => 2.2
//     [app_id] => ecos.ome
//     [method] => store.trade.status.update
//     [date] => 2015-04-13 10:28:49
//     [format] => json
//     [certi_id] => 1066139131
//     [v] => 1
//     [from_node_id] => 1266942530
//     [sign] => FC86280D44E258B6F8A3F084A5F479F2
// )
	
// 	发送到ecstore数据
// 1接收:Array
// (
//     [status] => dead
//     [task] => 552B2A49C0A81729CDF91D9A56F39D24
//     [from_api_v] => 2.2
//     [consignee] => {}
//     [app_id] => ecos.b2c
//     [sign] => 9675464A69C039AF155D1DEBE426FB19
//     [order_bn] => 150413102745968
//     [date] => 2015-04-13 10:28:49
//     [method] => b2c.order.status_update
//     [node_id] => 1266942530
// )
    
    function _init() {
    	$request_data = get_post(NULL);
    	
    	if($request_data['type'] == 'status'){
    		switch ($request_data['status'])
    		{
    			//取消订单
    			case 'TRADE_CLOSED':
    				$response_data['status'] = 'dead';
    				$response_data['task'] = get_tark($request_data['tid']);
    				$response_data['from_api_v'] = $request_data['from_api_v'];
    				$response_data['consignee'] = json_encode(array());
    				$response_data['app_id'] = $request_data['node_type'];
    				$response_data['order_bn'] = $request_data['tid'];
    				$response_data['date'] = $request_data['date'];
    				$response_data['method'] = 'b2c.order.status_update';
    				$response_data['node_id'] = $request_data['from_node_id'];
			    	return array('response_data'=>$response_data,
			    			'order_bn'=>$response_data['order_bn'],
			    			'from_method'=>$request_data['method'],
			    			'node_type'=>$request_data['node_type'],
			    			);
    				break;
    			//订单已发货
    			case 'TRADE_FINISHED':
    				$response_data['status'] = 'finish';
    				$response_data['task'] = $request_data['task'];
    				$response_data['from_api_v'] = $request_data['from_api_v'];
    				$response_data['consignee'] = '{}';
    				$response_data['app_id'] = $request_data['node_type'];
    				$response_data['order_bn'] = $request_data['tid'];
    				$response_data['date'] = $request_data['date'];
    				$response_data['method'] = 'b2c.order.status_update';
    				$response_data['node_id'] = $request_data['from_node_id'];
    				$response_data['sign'] ='';
    				
    				
			    	return array('response_data'=>$response_data,
			    			'order_bn'=>$response_data['order_bn'],
			    			'from_method'=>$request_data['method'],
			    			'node_type'=>$request_data['node_type'],
			    			'is_callback'=>TRUE,
			    			);
    				break;
    				
    			default:
    				
    		}
    	}
    	
    }
    
    
    function callback($data) {
    	$request_data = get_post(NULL);
    	
    	if($request_data['type'] == 'status'){
    		switch ($request_data['status'])
    		{
    			case 'TRADE_FINISHED':
			    	$return_data = json_decode($data['return_data']);
			    	$return_data = object_array($return_data);

					//回调接口
					$callback_data = array();
					$callback_data['res'] = '';
					$callback_data['err_msg'] = '';
					$callback_data['data'] = json_encode(array('tid'=>$request_data['tid']));
					$callback_data['sign'] = '';
					$callback_data['rsp'] = 'succ';
					$callback_data['msg_id'] = $data['msg_id'];

					return array('callback_data'=>$callback_data,'callback_url'=>$request_data['callback_url']);
   				break;
    			default:
    				return array('callback_data'=>'','callback_url'=>'');
    		}
    	}

		
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
    
    
}

?>