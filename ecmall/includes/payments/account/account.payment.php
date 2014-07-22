<?php

/**
 *    虚拟账户支付插件
 *
 *    @author    rainstown
 *    @usage    none
 */

class AccountPayment extends BasePayment
{
    /* 网关 */
    var $_gateway   =   '/index.php';
    var $_code      =   'account';
    var $_trance_key = 'TEST1001';
    /**
     *    获取支付表单
     *
     *    @author    Garbin
     *    @param     array $order_info  待支付的订单信息，必须包含总费用及唯一外部交易号
     *    @return    array
     */
    function get_payform($order_info)
    {
        /* 版本号 */
        $version = '2';

        /* 任务代码，定值：12 */
        $cmdno = '12';

        /* 编码标准 */
        if (!defined('CHARSET'))
        {
            $encode_type = 2;
        }
        else
        {
            if (CHARSET == 'utf-8')
            {
                $encode_type = 2;
            }
            else
            {
                $encode_type = 1;
            }
        }

         /* 商品名称 */
        $mch_id = $order_info['order_id'];
        
        /* 商品名称 */
        $mch_name = $this->_get_subject($order_info);

        /* 总金额 */
        $mch_price = floatval($order_info['order_amount']) * 100;


        /* 交易说明 */
        $mch_desc = $this->_get_subject($order_info);
        $need_buyerinfo = '2' ;


        /* 生成一个随机扰码 */
        $rand_num = rand(1,9);
        for ($i = 1; $i < 10; $i++)
        {
            $rand_num .= rand(0,9);
        }

        /* 获得订单的流水号，补零到10位 */
        $mch_vno = $this->_get_trade_sn($order_info);

        /* 返回的路径 */
        $mch_returl = $this->_create_notify_url($order_info['order_id']);
        $show_url   = $this->_create_return_url($order_info['order_id']);
        $attach = $rand_num;
        /* 数字签名 */
        $sign_text = "attach=" . $attach . "&mch_id=" . $mch_id . "&mch_desc=" . $mch_desc . "&mch_name=" . $mch_name . "&mch_price=" . $mch_price ."&mch_returl=" . $mch_returl . "&mch_vno=" . $mch_vno . "&show_url=" . $show_url . "&version=" . $version . "&key=".$this->_trance_key;
       // echo $sign_text;
       // exit;
        $sign =md5($sign_text);

        /* 交易参数 */
        $parameter = array(
            'app'               => 'account',   //模块 
            'act'               => 'pay_order',   //action
            'attach'            => $attach,
            'mch_id'            => $mch_id,                 //订单ID
            'mch_desc'          => $mch_desc,
            'mch_name'          => $mch_name,               //订单编号
            'mch_price'         => $mch_price,                  // 订单金额
            'mch_returl'        => $mch_returl,                 // 接收财付通返回结果的URL
            'mch_vno'           => $mch_vno,             // 交易号(订单号)，由商户网站产生(建议顺序累加)
            'show_url'          => $show_url,
            'transport_desc'    => $transport_desc,
            'transport_fee'     => $transport_fee,
            'version'           => $version,                    //版本号 2
            'sign'              => $sign,                       // MD5签名
        );

        return $this->_create_payform('GET', $parameter);
    }

    /**
     *    返回通知结果 -- 不做任何操作，在支付端已全部操作过;
     *
     *    @author    Garbin
     *    @param     array $order_info
     *    @param     bool  $strict
     *    @return    array 返回结果
     *               false 失败时返回
     *   
     */
    function verify_notify($order_info, $strict = false)
    {
        /*取返回参数*/
        

        return array(
            'target'    =>  ORDER_ACCEPTED,
        );
    }
    
    /**
     *    获取外部交易号 覆盖基类
     *
     *    @author    huibiaoli
     *    @param     array $order_info
     *    @return    string
     */
    function _get_trade_sn($order_info)
    {
        if (!$order_info['out_trade_sn'] || $order_info['pay_alter'])
        {
            $out_trade_sn = $this->_gen_trade_sn();
        }
        else
        {
            $out_trade_sn = $order_info['out_trade_sn'];
        }
        
        /* 将此数据写入订单中 */
        $model_order =& m('order');
        $model_order->edit(intval($order_info['order_id']), array('out_trade_sn' => $out_trade_sn, 'pay_alter' => 0));
        return $out_trade_sn;
    }
    
    /**
     *    生成外部交易号
     *
     *    @author    huibiaoli
     *    @return    string
     */
    function _gen_trade_sn()
    {
        /* 选择一个随机的方案 */
        mt_srand((double) microtime() * 1000000);
        $timestamp = gmtime();
        $y = date('y', $timestamp);
        $z = date('z', $timestamp);
        $out_trade_sn = $y . str_pad($z, 3, '0', STR_PAD_LEFT) . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);

        $model_order =& m('order');
        $orders = $model_order->find('out_trade_sn=' . $out_trade_sn);
        if (empty($orders))
        {
            /* 否则就使用这个交易号 */
            return $out_trade_sn;
        }

        /* 如果有重复的，则重新生成 */
        return $this->_gen_trade_sn();
    }
}

?>