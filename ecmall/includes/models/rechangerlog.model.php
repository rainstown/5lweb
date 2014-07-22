<?php

/* 订单日志 orderlog */
class RechangerlogModel extends BaseModel
{
    var $table  = 'rechanger_log';
    var $prikey = 'log_id';
    var $_name  = 'rechlog';
    var $_relation  = array(
        // 一个支付日志只能属于一个支付
        'belongs_to_rechanger' => array(
            'model'         => 'rechanger',
            'type'          => BELONGS_TO,
            'foreign_key'   => 'rech_id',
            'reverse'       => 'has_rechangerlog',
        ),
    );
}

?>