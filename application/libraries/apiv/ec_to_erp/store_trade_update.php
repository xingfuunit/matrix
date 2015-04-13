<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * 订单修改 
 */
class Store_trade_update {

	public function __construct()
	{
	//	 $data =  $this->_init();
	//	 return $data;
	}
    
	//接收ec的数据
// 	[node_type] => ecos.ome
// 	[from_api_v] => 2.0
// 	[to_node_id] => 1266942530
// 	[to_api_v] => 2.2
// 	[tid] => 150413102745968
// 	[title] => Order Create
// 	[created] => 2015-04-13 10:27:55
// 	[modified] => 2015-04-13 10:28:49
// 	[lastmodify] => 2015-04-13 10:28:49
// 	[status] => TRADE_CLOSED
// 	[pay_status] => PAY_NO
// 	[ship_status] => SHIP_NO
// 	[has_invoice] =>
// 	[invoice_title] =>
// 	[invoice_fee] => 0.00
// 	[tax_type] => false
// 	[tax_content] =>
// 	[total_goods_fee] => 111.60
// 	[total_trade_fee] => 111.60
// 	[discount_fee] => 0.00
// 	[goods_discount_fee] => 0.00
// 	[orders_discount_fee] => 13.00
// 	[local_id] =>
// 	[shop_bn] => xbd_store_
// 	[promotion_details] => [{"promotion_name":"\u94bb\u77f3\u4f1a\u5458\u514d\u90ae","promotion_fee":"13.00"},{"promotion_name":"","promotion_fee":"0.00"}]
// 	[payed_fee] => 0.00
// 	[currency] => CNY
// 	[currency_rate] => 1.0000
// 	[total_currency_fee] => 111.60
// 	[buyer_obtain_point_fee] => 167.00
// 	[point_fee] => 0.00
// 	[total_weight] => 0.00
// 	[receiver_time] => 任意日期,任意时间段
// 	[shipping_tid] => 1
// 	[shipping_type] => 顺丰
// 	[shipping_fee] => 13.00
// 	[is_protect] => false
// 	[protect_fee] => 0.00
// 	[payment_tid] => deposit
// 	[payment_type] => 预存款
// 	[is_cod] =>
// 	[receiver_name] => 伟强 李
// 	[receiver_email] =>
// 	[receiver_mobile] => 13516549872
// 	[receiver_state] => 广东
// 	[receiver_city] => 佛山市
// 	[receiver_district] => 顺德区/大良/镇中心区域
// 	[receiver_community] => 大良
// 	[receiver_address] => 广东佛山市顺德区大良镇中心区域南海区 桂城
// 	[receiver_zip] => 528200
// 	[receiver_phone] =>
// 	[pay_cost] => 0.00
// 	[buyer_memo] => dddd
// 	[orders_number] => 1
// 	[is_auto_complete] => false
// 	[branch_id] => 0
// 	[branch_name_user] =>
// 	[buyer_uname] => olwklj0zy0jwwbbjon1mshgerppa
// 	[buyer_name] =>
// 	[buyer_address] =>
// 	[buyer_mobile] =>
// 	[buyer_phone] =>
// 	[buyer_email] => tec@126.com
// 	[buyer_zip] =>
// 	[buyer_id] => 4023
// 	[orders] => {"order":[{"oid":"2499","orders_bn":"23000901","type":"goods","type_alias":"\u5546\u54c1\u533a\u5757","iid":"2499","title":"beher\u9ed1\u6807\u624b\u5207\u7247\u5305\u88c5\u98ce\u5e7248\u4e2a\u6708","items_num":1,"order_status":"SHIP_NO","price":"120.00","total_order_fee":111.6,"discount_fee":0,"consign_time":"","order_items":{"item":[{"sku_id":"912","iid":"130","bn":"23000901","name":"beher\u9ed1\u6807\u624b\u5207\u7247\u5305\u88c5\u98ce\u5e7248\u4e2a\u6708","sku_properties":"\u89c4\u683c:50g\/\u5305","weight":"0","score":"112","price":"111.60","num":"1","sendnum":"0","total_item_fee":111.6,"sale_price":"111.60","item_type":"product","item_status":"normal","discount_fee":0}]},"weight":"0"}]}
// 	[payment_lists] => {"payment_list":[]}
// 	[trade_memo] =>
// 	[app_id] => ecos.b2c
// 	[method] => store.trade.update
// 	[date] => 2015-04-13 10:28:50
// 	[callback_url] => http://lwq.pinzhen365.com/openapi/rpc_callback/async_result_handler/id/7b344f140652e701d79f099e80031855-1428892130/app_id/b2c
// 	[format] => json
// 	[certi_id] => 1365149539
// 	[v] => 1
// 	[from_node_id] => 1964902530
// 	[task] => 7b344f140652e701d79f099e80031855
// 	[sign] => 322217F406F2439DDF79134F23F606D1

	//发送到erp 数据
// 	Array
// 	(
// 	[pay_bn] => deposit
// 	[weight] => 0.00
// 	[cur_rate] => 1.0000
// 	[consignee] => {"r_time": "\u4efb\u610f\u65e5\u671f,\u4efb\u610f\u65f6\u95f4\u6bb5", "addr": "\u5e7f\u4e1c\u4f5b\u5c71\u5e02\u987a\u5fb7\u533a\u5927\u826f\u9547\u4e2d\u5fc3\u533a\u57df\u5357\u6d77\u533a \u6842\u57ce", "zip": "528200", "mobile": "13516549872", "telephone": "", "area_city": "\u4f5b\u5c71\u5e02", "email": "", "area_state": "\u5e7f\u4e1c", "area_district": "\u987a\u5fb7\u533a/\u5927\u826f/\u9547\u4e2d\u5fc3\u533a\u57df", "name": "\u4f1f\u5f3a \u674e"}
// 	[app_id] => ecos.ome
// 	[sign] => 14661E73F34F9FF6A312DB3A74C7B0B6
// 	[currency] => CNY
// 	[cost_item] => 111.60
// 	[pmt_detail] => [{"pmt_amount": "13.00", "pmt_describe": "\u94bb\u77f3\u4f1a\u5458\u514d\u90ae"}, {"pmt_amount": "0.00", "pmt_describe": ""}]
// 	[custom_mark] => dddd
// 	[cost_tax] => 0.00
// 	[lastmodify] => 2015-04-13 10:28:49
// 	[consigner] => {}
// 	[title] => Order Create
// 	[node_version] => 2.0
// 	[payinfo] => {"pay_name": "\u9884\u5b58\u6b3e", "cost_payment": "0.00"}
// 	[order_bn] => 150413102745968
// 	[selling_agent] => {"website": {}, "member_info": {"sex": ""}}
// 	[pay_status] => 0
// 	[status] => dead
// 	[pmt_goods] => 0.00
// 	[score_u] => 0.00
// 	[pmt_order] => 13.00
// 	[member_info] => {"tel": "", "addr": "", "mobile": "", "email": "tec@126.com", "uname": "olwklj0zy0jwwbbjon1mshgerppa", "name": ""}
// 	[discount] => 0.00
// 	[node_id] => 1964902530
// 	[score_g] => 167.00
// 	[date] => 2015-04-13 10:28:50
// 	[tax_title] =>
// 	[task] => 7b344f140652e701d79f099e80031855
// 	[total_amount] => 111.60
// 	[ship_status] => 0
// 	[cur_amount] => 111.60
// 	[modified] => 1428892129
// 	[shipping] => {"shipping_name": "\u987a\u4e30", "cost_protect": "0.00", "is_protect": "false", "cost_shipping": "13.00"}
// 	[method] => ome.order.add
// 	[payed] => 0.00
// 	[order_objects] => [{"obj_type": "goods", "name": "beher\u9ed1\u6807\u624b\u5207\u7247\u5305\u88c5\u98ce\u5e7248\u4e2a\u6708", "weight": "0", "pmt_price": 0, "bn": "23000901", "order_items": [{"score": "112", "addon": "", "name": "beher\u9ed1\u6807\u624b\u5207\u7247\u5305\u88c5\u98ce\u5e7248\u4e2a\u6708", "weight": "0", "pmt_price": 0, "bn": "23000901", "product_attr": [{"value": "50g/\u5305", "label": "\u89c4\u683c"}], "item_type": "product", "amount": 111.59999999999999, "shop_goods_id": "130", "sendnum": "0", "sale_price": "111.60", "quantity": "1", "price": "111.60", "shop_product_id": "912"}], "amount": 111.59999999999999, "score": "", "shop_goods_id": "2499", "obj_alias": "\u5546\u54c1\u533a\u5757", "price": "120.00", "quantity": 1}]
// 	[payments] => []
// 	[is_tax] => 0
// 	[createtime] => 1428892075
// 	)

    function _init() {
    	$request_data = get_post(NULL);
    	$response_data = array();
    	$response_data['pay_bn'] = $request_data['payment_tid'];
    	$response_data['to_api_v'] = $request_data['to_api_v'];
    	$response_data['weight'] = $request_data['total_weight'];
    	$response_data['cur_rate'] = $request_data['currency_rate'];
    	$response_data['from_node_id'] = $request_data['from_node_id'];
    	$response_data['msg_id'] = '';//信息ＩＤ空
    	$response_data['consignee'] = json_encode(
    												array('r_time'=>$request_data['receiver_time'],
    												'addr'=>$request_data['receiver_address'],
    												'zip'=>$request_data['receiver_zip'],
    												'mobile'=>$request_data['receiver_mobile'],
    												'telephone'=>$request_data['receiver_phone'],
    												'area_city'=>$request_data['receiver_city'],
    												'email'=>$request_data['receiver_email'],
    												'area_state'=>$request_data['receiver_state'],
    												'area_district'=>$request_data['receiver_district'],
    												'name'=>$request_data['receiver_name'],	
    		));
    	
    //	$response_data['app_id'] = $request_data['app_id'];
    //	$response_data['sign'] = $request_data['sign'];
    	$response_data['currency'] = $request_data['currency'];
    	$response_data['node_type'] = $request_data['node_type'];
    	$response_data['refresh_time'] = date("Y-m-d h:i:s");
    	$response_data['cost_item'] = $request_data['total_goods_fee'];
    	$find = array('promotion_name','promotion_fee',);
    	$replace = array('pmt_describe','pmt_amount',);
    	$response_data['pmt_detail'] = str_replace($find,$replace,$request_data['promotion_details']);
    	$response_data['custom_mark'] = '';//未知字段
    	$response_data['cost_tax'] = $request_data['pay_cost'];
    	$response_data['lastmodify'] = $request_data['lastmodify'];
    	$response_data['branch_id'] = $request_data['branch_id'];
    //	$response_data['consigner'] = array();//发货人
    	$response_data['title'] = $request_data['title'];
    	$response_data['tax_type'] = $request_data['tax_type'];
    	$response_data['node_version'] = '2.0';
    	$response_data['from_type'] = 'ecos.b2c';
    	$response_data['payinfo'] = json_encode(array('pay_name'=>$request_data['payment_type'],'cost_payment'=>$request_data['payed_fee']));
    	$response_data['branch_name_user'] = $request_data['branch_name_user'];
    	$response_data['order_bn'] = $request_data['tid'];
    	$response_data['tax_content'] = $request_data['tax_content'];
    	$response_data['shop_bn'] = $request_data['shop_bn'];
    	$response_data['selling_agent'] = '{"website": {}, "member_info": {"sex": ""}}';//未知字段
    	$response_data['pay_status'] = get_pay_status($request_data['pay_status']);
    //	var_dump($request_data['status']);exit; 
    	$response_data['status'] = get_order_status($request_data['status']);
    	$response_data['pmt_goods'] = $request_data['discount_fee'];
    	$response_data['score_u'] = '0.00';//未知字段
    	$response_data['pmt_order'] = $request_data['orders_discount_fee'];
    	$response_data['timestamp'] = time();
    	$response_data['member_info'] = json_encode(array('tel'=>$request_data['buyer_mobile'],'addr'=>$request_data['buyer_address'],'mobile'=>$request_data['buyer_mobile'],'uname'=>$request_data['buyer_uname'],'name'=>$request_data['buyer_name']));
    	$response_data['from_api_v'] = '2.0';
    	$response_data['task'] = $request_data['task'];
    	$response_data['discount'] = $request_data['discount_fee'];
    	$response_data['receiver_community'] = $request_data['receiver_community'];
    	$response_data['payment_lists'] = $request_data['payment_lists'];
    	$response_data['score_g'] = $request_data['buyer_obtain_point_fee'];
    	$response_data['date'] = $request_data['date'];
    	$response_data['tax_title'] = '';//未知字段
    	$response_data['orders_number'] = $request_data['orders_number'];
    	$response_data['shipping_tid'] = $request_data['shipping_tid'];
    	$response_data['total_amount'] = $request_data['total_trade_fee'];//未知字段
    	$response_data['to_type'] = 'ecos.ome';
    	$response_data['ship_status'] = get_ship_status($request_data['ship_status']);
    	$response_data['cur_amount'] = $request_data['total_currency_fee'];//未知字段
    	$response_data['modified'] = strtotime($request_data['modified']);
    	$response_data['shipping'] = json_encode(array('shipping_name'=>$request_data['shipping_type'],'cost_protect'=>$request_data['protect_fee'],'is_protect'=>$request_data['is_protect'],'cost_shipping'=>$request_data['shipping_fee']));
    	$response_data['method'] = 'ome.order.add';
    	$response_data['payed'] = $request_data['payed_fee'];
    	$response_data['is_auto_complete'] = $request_data['is_auto_complete'];
    	$response_data['node_id'] = $request_data['from_node_id'];
    //	$response_data['order_objects'] = $request_data['from_node_id'];
    	$response_data['payments'] = '[]';//未知字段
    	$response_data['is_tax'] = 0;//未知字段
    	$response_data['createtime'] = strtotime($request_data['modified']);
    	$response_data['buyer_id'] = $request_data['buyer_id'];
    	
		$request_order = json_decode($request_data['orders']);
		
		$order_objects = array();
		if ($request_order) {
			foreach ($request_order->order as $key=>$value) {
				$tmp = new stdClass;
				$tmp->consign_time = $value->consign_time;
				$tmp->obj_type = $value->type;
				$tmp->name = $value->title;
				$tmp->weight = $value->weight;
				$tmp->pmt_price = $value->discount_fee;//未确定字段
				$tmp->bn = $value->orders_bn;
				$tmp->oid = $value->oid;
				$tmp->amount = $value->total_order_fee;
				$tmp->score = '';//订单积分？
				$tmp->shop_goods_id = $value->iid;//未确定字段
				$tmp->obj_alias = $value->type_alias;
				$tmp->order_status = $value->order_status;
				$tmp->price = $value->price;
				$tmp->quantity = $value->items_num;
				$order_items = array();
				foreach ($value->order_items->item as $key2=>$value2) {
					$items = new stdClass;
					$items->score = $value2->score;
					$items->addon = '';//未确定字段
					$items->name = $value2->name;
					$items->weight = $value2->weight;
					$items->pmt_price = $value2->discount_fee;
					$items->bn = $value2->bn;
					$items->item_status = $value2->item_status;
					$sku_properties = explode(':',$value2->sku_properties);
					$pattr = new stdClass;
					$pattr->value = $sku_properties[0];
					$pattr->label = $sku_properties[1];
					$items->product_attr = array(0=>$pattr);
					$items->item_type = $value2->item_type;
					$items->amount = $value2->total_item_fee;
					$items->shop_goods_id = $value2->iid;//未确定字段
					$items->amount = $value2->total_item_fee;
					$items->sendnum = $value2->sendnum;
					$items->sale_price = $value2->sale_price;
					$items->quantity = $value2->num;
					$items->price = $value2->price;
					$items->shop_product_id = $value2->sku_id;
					$order_items[$key] = $items;
				}
				$tmp->order_items = $order_items;
				$order_objects[$key] = $tmp;
			}
    	}
    	
    	$response_data['order_objects'] = json_encode($order_objects);
    	
    	return array('response_data'=>$response_data,'order_bn'=>$response_data['order_bn'],'from_method'=>$request_data['method'],'node_type'=>$request_data['node_type']);
    //	$CI->load->library('common/httpclient');
    	
    }
    
    
    function result($params){
    	if($params['return_data']){
    		
    		return json_encode(array('succ'));
    	}else{
    		
    	}
    }
    
}

?>