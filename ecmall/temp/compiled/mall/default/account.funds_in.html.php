<?php echo $this->fetch('member.header.html'); ?>
<script type="text/javascript">
$(function(){
    $('#funds_time_from').datepicker({dateFormat: 'yy-mm-dd'});
    $('#funds_time_to').datepicker({dateFormat: 'yy-mm-dd'});
});
</script>
<div class="content">
    <?php echo $this->fetch('member.menu.html'); ?>
    <div id="right"><?php echo $this->fetch('member.submenu.html'); ?>
        <div class="wrap">
            <div class="scarch_order">
                <form method="get">
                <div style="float:left;">
                <span class="title">交易名称:</span>
                <input class="text_normal " type="text" name="funds_name" value="<?php echo htmlspecialchars($this->_var['query']['funds_name']); ?>" />
                <span class="title">操作时间:</span>
                <input class="text_normal width2" type="text" name="add_time_from" id="funds_time_from" value="<?php echo $this->_var['query']['add_time_from']; ?>" /> &#8211; <input class="text_normal width2" id="funds_time_to" type="text" name="add_time_to" value="<?php echo $this->_var['query']['add_time_to']; ?>" />
                <input type="hidden" name="app" value="account" />
                <input type="hidden" name="act" value="funds_in" />
                <input type="submit" class="btn" value="搜索" />
                </div>
                <?php if ($this->_var['query']['funds_name'] || $this->_var['query']['add_time_from'] || $this->_var['query']['add_time_to']): ?>
                    <a class="detlink" href="<?php echo url('app=account&act=funds_in'); ?>">取消检索</a>
                <?php endif; ?>

        </form>

        </div>
         <div class="public_index table">
                <table>
                    <?php if ($this->_var['pays']): ?>
                    <tr class="line tr_bgcolor">
                        <th>订单号</th>
                        <th>充值类型</th>
                        <th>交易名称</th>
                        <th>操作时间</th>
                        <th>金额</th>
                        <th  class="width10" >备注</th>
                        <th width="90">状态</th>
                    </tr>
                    <?php endif; ?>
                    
                    <?php $_from = $this->_var['pays']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'pay');if (count($_from)):
    foreach ($_from AS $this->_var['pay']):
?>
                    <tr class="color2 line_bottom">
                        <td class="align2"><?php echo $this->_var['pay']['order_sn']; ?></td>
                        <td class="align2"><?php echo call_user_func("funds_type",$this->_var['pay']['type']); ?></td>
                        <td class="align2"><?php echo htmlspecialchars($this->_var['pay']['funds_name']); ?></td>
                        <td class="align2"><?php echo local_date("y-m-d H:i",$this->_var['pay']['create_time']); ?></td>
                        <td class="align2 padding1"><span class="color4">+ <?php echo price_format($this->_var['pay']['money']); ?></span> </strong></td>
                        
                        <td class="align2"><?php echo htmlspecialchars($this->_var['pay']['name']); ?> <?php echo htmlspecialchars($this->_var['pay']['remark']); ?></span</td>
                        <td class="align2"><span class="color4"><?php echo call_user_func("pay_status",$this->_var['pay']['status']); ?></span></td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td class="member_no_records" colspan="8">没有符合条件的记录</td></tr>
                    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    <?php if ($this->_var['pays']): ?>
                    <tr><th > </th>
                    <th class="align1" colspan="6">
                             
                            <p class="position2">
                                <?php echo $this->fetch('member.page.bottom.html'); ?>
                            </p>
                        </th>
                    </tr>
                    <?php endif; ?>
                    </table>
                    <iframe name="seller_order" style="display:none;"></iframe>
        </div>
    <div class="wrap_bottom"></div>
    </div>
</div>
</div>
<div class="clear"></div>
</div>
<?php echo $this->fetch('footer.html'); ?>
