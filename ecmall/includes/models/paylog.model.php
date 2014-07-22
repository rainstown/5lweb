<?php

/* 订单日志 orderlog */
class PaylogModel extends BaseModel
{
    var $table  = 'pay_log';
    var $prikey = 'log_id';
    var $_name  = 'paylog';
    var $_relation  = array(
        // 一个支付日志只能属于一个支付
        'belongs_to_pay' => array(
            'model'         => 'pay',
            'type'          => BELONGS_TO,
            'foreign_key'   => 'pay_id',
            'reverse'       => 'has_paylog',
        ),
    );
}

?>