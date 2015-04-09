<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 订单状态更新
 * @author Administrator
 *
 */

class Store_trade_staus_update {

	public function __construct()
	{
	//	 $data =  $this->_init();
	//	 return $data;
	}
    
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
    												'zip'=>$request_data['receiver_address'],
    												'mobile'=>$request_data['receiver_mobile'],
    												'telephone'=>$request_data['receiver_phone'],
    												'area_city'=>$request_data['receiver_city'],
    												'email'=>$request_data['receiver_email'],
    												'area_state'=>$request_data['receiver_state'],
    												'area_district'=>$request_data['receiver_district'],
    												'name'=>$request_data['receiver_name'],	
    		));
    	
    	$response_data['app_id'] = $request_data['app_id'];
    	$response_data['sign'] = $request_data['sign'];
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
    	$response_data['consigner'] = array();//发货人
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
    	
    	
    	return array('response_data'=>$response_data,'order_bn'=>$response_data['order_bn'],'from_method'=>$request_data['method'],'to_method'=>'ome.order.add','to_sys'=>'erp','from_sys'=>'ecstore');
    //	$CI->load->library('common/httpclient');
    	
    }
    
    
    
    
}

?>