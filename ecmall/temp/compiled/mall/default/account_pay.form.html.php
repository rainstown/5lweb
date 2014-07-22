<?php echo $this->fetch('member.header.html'); ?>
<script src="<?php echo $this->lib_base . "/" . 'mlselection.js'; ?>" charset="utf-8"></script>
<script src="<?php echo $this->lib_base . "/" . 'jquery.plugins/jquery.validate.js'; ?>" charset="utf-8"></script>
<script type="text/javascript">
//<!CDATA[
var SITE_URL = "<?php echo $this->_var['site_url']; ?>";
var REAL_SITE_URL = "<?php echo $this->_var['real_site_url']; ?>";
$(function(){
    regionInit("region");

    $("#apply_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parents('td').next('td');
            error_td.find('.field_notice').hide();
            error_td.find('.fontColor3').hide();
            error_td.append(error);
        },
        success: function(label){
            label.addClass('validate_right').text('OK!');
        },
        onkeyup: false,
        rules: {
            owner_name: {
                required: true
            },
            store_name: {
                required: true,
                remote : {
                    url  : 'index.php?app=apply&act=check_name&ajax=1',
                    type : 'get',
                    data : {
                        store_name : function(){
                            return $('#store_name').val();
                        },
                        store_id : '<?php echo $this->_var['store']['store_id']; ?>'
                    }
                },
                maxlength: 20
            },
            tel: {
                required: true,
                minlength:6,
                checkTel:true
            },
            image_1: {
                accept: "jpg|jpeg|png|gif"
            },
            image_2: {
                accept: "jpg|jpeg|png|gif"
            },
            image_3: {
                accept: "jpg|jpeg|png|gif"
            },
            notice: {
                required : true
            }
        },
        messages: {
            owner_name: {
                required: 'input_owner_name'
            },
            store_name: {
                required: 'input_store_name',
                remote: 'name_exist',
                maxlength: 'note_for_store_name'
            },
            tel: {
                required: 'input_tel',
                minlength: 'phone_tel_error',
                checkTel: 'phone_tel_error'
            },
            image_1: {
                accept: 'select_valid_image'
            },
            image_2: {
                accept: 'select_valid_image'
            },
            image_3: {
                accept: 'select_valid_image'
            },
            notice: {
                required: 'check_notice'
            }
        }
    });
});
//]]>
</script>
<div class="content">
    <div class="particular">
        <div class="particular_wrap">
            <h2>订单详情</h2>
            <div class="box">
                <div class="state">订单状态&nbsp;:&nbsp;<strong><?php echo call_user_func("order_status",$this->_var['order']['status']); ?></strong></div>
                <div class="num">订单号&nbsp;:&nbsp;<?php echo $this->_var['order']['order_sn']; ?></div>
                <div class="time">添加时间&nbsp;:&nbsp;<?php echo local_date("Y-m-d H:i:s",$this->_var['order']['order_add_time']); ?></div>

            </div>
            <h3>订单信息</h3>
            <dl class="info">
                <dt>卖家信息</dt>
                <dd>店铺名称&nbsp;:&nbsp;<?php echo htmlspecialchars($this->_var['order']['store_name']); ?></dd>
                <dd>电话号码&nbsp;:&nbsp;<?php echo (htmlspecialchars($this->_var['order']['tel']) == '') ? '-' : htmlspecialchars($this->_var['order']['tel']); ?></dd>
                <dd>qq&nbsp;:&nbsp;<?php echo (htmlspecialchars($this->_var['order']['im_qq']) == '') ? '-' : htmlspecialchars($this->_var['order']['im_qq']); ?></dd>
                <dd>ww&nbsp;:&nbsp;<?php echo (htmlspecialchars($this->_var['order']['im_ww']) == '') ? '-' : htmlspecialchars($this->_var['order']['im_ww']); ?></dd>
                <dd>所在地区&nbsp;:&nbsp;<?php echo (htmlspecialchars($this->_var['order']['region_name']) == '') ? '-' : htmlspecialchars($this->_var['order']['region_name']); ?></dd>
                <dd>手机号码&nbsp;:&nbsp;<?php echo (htmlspecialchars($this->_var['order']['phone_mob']) == '') ? '-' : htmlspecialchars($this->_var['order']['phone_mob']); ?></dd>
                <dd>msn&nbsp;:&nbsp;<?php echo (htmlspecialchars($this->_var['order']['im_msn']) == '') ? '-' : htmlspecialchars($this->_var['order']['im_msn']); ?></dd>
                <dd>详细地址&nbsp;:&nbsp;<?php echo (htmlspecialchars($this->_var['order']['address']) == '') ? '-' : htmlspecialchars($this->_var['order']['address']); ?></dd>
            </dl>
            <div class="ware_line">
                <div class="ware">
                    <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
                    <div class="ware_list">
                        <div class="ware_pic"><img src="<?php echo $this->_var['goods']['goods_image']; ?>" width="50" height="50"  /></div>
                        <div class="ware_text">
                            <div class="ware_text1">
                                <a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. ''); ?>"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></a>
                                <?php if ($this->_var['group_id']): ?><a target="_blank" href="<?php echo url('app=groupbuy&id=' . $this->_var['group_id']. ''); ?>"><strong class="color8">[团购]</strong></a><?php endif; ?>
                                <br />
                                <span><?php echo htmlspecialchars($this->_var['goods']['specification']); ?></span>
                            </div>
                            <div class="ware_text2">
                                <span>数量&nbsp;:&nbsp;<strong><?php echo $this->_var['goods']['quantity']; ?></strong></span>
                                <span>价格&nbsp;:&nbsp;<strong><?php echo price_format($this->_var['goods']['price']); ?></strong></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    <form method="post" action="index.php?app=account&act=pay_action" enctype="multipart/form-data" id="apply_form">
                     <?php $_from = $this->_var['params']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('_k', 'value');if (count($_from)):
    foreach ($_from AS $this->_var['_k'] => $this->_var['value']):
?>
                        <input type="hidden" name="<?php echo $this->_var['_k']; ?>" value="<?php echo $this->_var['value']; ?>" />
                     <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    <div class="transportation">运费&nbsp;:&nbsp;<span><?php echo price_format($this->_var['order_extm']['shipping_fee']); ?><strong>(<?php echo htmlspecialchars($this->_var['order_extm']['shipping_name']); ?>)</strong></span>优惠打折&nbsp;:&nbsp;<span><?php echo price_format($this->_var['order']['discount']); ?></span>总价&nbsp;:&nbsp;<b><?php echo price_format($this->_var['order']['order_amount']); ?></b></div>
                    <ul class="order_detail_list">
                        <table>
                            <?php if ($this->_var['order']['payment_code']): ?>
                            <tr>
                                <th>支付方式: </th>
                                <td class="width7"><?php echo htmlspecialchars($this->_var['order']['payment_name']); ?></td>
                                <td class="padding3"></td>
                            </tr>
                            
                       <?php if ($this->_var['order']['status'] == ORDER_PENDING): ?>
                             <tr>
                                <th>账户余额: </th>
                                <td class="width7"><b><?php echo price_format($this->_var['account_info']); ?></b></td>
                                <td class="padding3"></td>
                            </tr>
                            <?php if ($this->_var['account_info'] < $this->_var['order']['order_amount']): ?>
                             <tr>
                                <th>注意: </th>
                                <td class="width7">账户余额不足, 请<a href="index.php?app=account&act=funds_in">充值</a>.</td>
                                <td class="padding3">
                                    <a id="payment_has_no_more_money" class="btn1" type="button" dialog_width="400" dialog_title="获取验证码" uri="index.php?app=account&act=payment_has_no_more_money&ajax" ectype="dialog"/></a>
                                    <script>
                                        $(function(){
                                            //$("#payment_has_no_more_money").click();
                                            alert('账户余额不足，请充值');
                                        })
                                    </script>
                                </td>
                            </tr>
                            <?php else: ?>
                            <tr>
                                <th>支付密码: </th>
                                <td class="width7"><input type="password" class="text width2" name="payment_password" value=""/></td>
                                <td class="padding3"><span class="fontColor3">*</span> <span class="field_notice">请输入支付密码</span></td>
                            </tr>
                            <tr>
                                <th>手机验证码: </th>
                                <td class="width7"><input type="check_code" class="text width2" name="check_code" value=""/><input class="btn1" type="button" ectype="dialog" uri="index.php?app=checkmobcode&act=get_pay_code&order_id=<?php echo $this->_var['order']['order_id']; ?>&&ajax" dialog_title="获取验证码" dialog_width="400" value="获取验证码"/></td>
                                <td class="padding3"><span class="fontColor3">*</span> <span class="field_notice">请输入验证码</span></td>
                            </tr>
                            <tr>
                                <td colspan="3"><span class="padding4"><input class="btn" type="submit" value="支付" /></span></td>
                            </tr>
                             <?php endif; ?>
                            <?php endif; ?>
                            
                            
                        </table>
                        <?php endif; ?>
                    </ul>
                </div>
                </form>
            </div>

            <h3>物流信息</h3>
            <div class="goods">
                收货地址&nbsp;:&nbsp;<?php echo htmlspecialchars($this->_var['order_extm']['consignee']); ?><?php if ($this->_var['order_extm']['phone_mob']): ?>, &nbsp;<?php echo $this->_var['order_extm']['phone_mob']; ?><?php endif; ?><?php if ($this->_var['order_extm']['phone_tel']): ?>,&nbsp;<?php echo $this->_var['order_extm']['phone_tel']; ?><?php endif; ?>
                ,&nbsp;<?php echo htmlspecialchars($this->_var['order_extm']['region_name']); ?>&nbsp;<?php echo htmlspecialchars($this->_var['order_extm']['address']); ?>
                <?php if ($this->_var['order_extm']['zipcode']): ?>,&nbsp;<?php echo htmlspecialchars($this->_var['order_extm']['zipcode']); ?><?php endif; ?><br />
                配送方式&nbsp;:&nbsp;<?php echo htmlspecialchars($this->_var['order_extm']['shipping_name']); ?>
                <?php if ($this->_var['order']['invoice_no']): ?>
                <br />
                物流单号&nbsp;:&nbsp;<?php echo htmlspecialchars($this->_var['order']['invoice_no']); ?><!--&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $this->_var['shipping_info']['query_url']; ?>&amp;<?php echo $this->_var['order']['invoice_no']; ?>" target="_blank">query_logistics</a>-->
                <?php endif; ?>
                <?php if ($this->_var['order']['postscript']): ?>
                <br />
                给卖家的附言&nbsp;:&nbsp;<?php echo htmlspecialchars($this->_var['order']['postscript']); ?><br />
                <?php endif; ?>
            </div>

            <div class="particular_bottom"></div>
        </div>

        <div class="clear"></div>
        <div class="adorn_right1"></div>
        <div class="adorn_right2"></div>
        <div class="adorn_right3"></div>
        <div class="adorn_right4"></div>
    </div>
    <div class="clear"></div>
</div>
<?php echo $this->fetch('footer.html'); ?>