<?php

/* 充值提现  */
class RechangerModel extends BaseModel
{
    var $table  = 'rechanger';
    var $alias  = 'rechanger_alias';
    var $prikey = 'rech_id';
    var $_name  = 'rechanger';
    var $_relation  = array(
       
        // 一个充值有多个充值日志
        'has_rechangerlog' => array(
            'model'         => 'rechangerlog',
            'type'          => HAS_MANY,
            'foreign_key'   => 'rech_id',
            'dependent'     => true
        ),
       
        //一个支付属于一个人
        'belongs_to_user'  => array(
            'type'          => BELONGS_TO,
            'reverse'       => 'has_rechanger',
            'model'         => 'member',
        ),
    );

    /**
     *    修改订单中商品的库存，可以是减少也可以是加回
     *
     *    @author    Garbin
     *    @param     string $action     [+:加回， -:减少]
     *    @param     int    $order_id   订单ID
     *    @return    bool
     */
    function change_stock($action, $order_id)
    {
        if (!in_array($action, array('+', '-')))
        {
            $this->_error('undefined_action');

            return false;
        }
        if (!$order_id)
        {
            $this->_error('no_such_order');

            return false;
        }

        /* 获取订单商品列表 */
        $model_ordergoods =& m('ordergoods');
        $order_goods = $model_ordergoods->find("order_id={$order_id}");
        if (empty($order_goods))
        {
            $this->_error('goods_empty');

            return false;
        }

        $model_goodsspec =& m('goodsspec');
        $model_goods =& m('goods');

        /* 依次改变库存 */
        foreach ($order_goods as $rec_id => $goods)
        {
            $model_goodsspec->edit($goods['spec_id'], "stock=stock {$action} {$goods['quantity']}");
            $model_goods->clear_cache($goods['goods_id']);
        }

        /* 操作成功 */
        return true;
    }
}

?>
