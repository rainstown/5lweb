<?php echo $this->fetch('header.html'); ?>
<?php echo $this->fetch('curlocal.html'); ?>
<div class="flow_chart">
    <div class="pos_x1 bg_a2" title="选购商品"></div>
    <div class="pos_x2 bg_b2" title="完成订单信息并下单"></div>
    <div class="pos_x3 bg_c1" title="下单完成并支付"></div>
</div>

<div class="content">
    <div class="module_common">
        <div class="step_main">
            <div class="succeed">
                <h4>支付成功。</h4>
                <p>您已成功支付了，请等待卖家给您发货，您想： <a href="<?php echo url('app=member'); ?>">用户中心</a>&nbsp;&nbsp;<a href="<?php echo url('app=buyer_order&act=view&order_id=' . $this->_var['order']['order_id']. ''); ?>">查看订单</a></p>
                <div class="btn" title="商城首页"><a href="<?php echo $this->_var['site_url']; ?>"></a></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<?php echo $this->fetch('footer.html'); ?>