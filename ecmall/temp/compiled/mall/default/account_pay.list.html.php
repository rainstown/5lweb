<?php echo $this->fetch('member.header.html'); ?>
<script type="text/javascript">
$(function(){
    $('#add_time_from').datepicker({dateFormat: 'yy-mm-dd'});
    $('#add_time_to').datepicker({dateFormat: 'yy-mm-dd'});
    $('.checkall_s').click(function(){
        var if_check = $(this).attr('checked');
        $('.checkitem').each(function(){
            if(!this.disabled)
            {
                $(this).attr('checked', if_check);
            }
        });
        $('.checkall_s').attr('checked', if_check);
    });
    $('a[ectype="batchcancel"]').click(function(){
        if($('.checkitem:checked').length == 0){
            return false;
        }
        if($(this).attr('presubmit')){
            if(!eval($(this).attr('presubmit'))){
                return false;
            }
        }
        var items = '';
        $('.checkitem:checked').each(function(){
            items += this.value + ',';
        });
        items = items.substr(0, (items.length - 1));

        var uri = $(this).attr('uri');
        uri = uri + '&' + $(this).attr('name') + '=' + items;
        var id = 'seller_order_cancel_order';
        var title = $(this).attr('dialog_title') ? $(this).attr('dialog_title') : '';
        //var url = $(this).attr('uri');
        var width = '500';
        ajax_form(id, title, uri, width);
    });
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
                <input class="text_normal " type="text" name="pay_name" value="<?php echo htmlspecialchars($this->_var['query']['pay_name']); ?>" />
                <span class="title">交易时间:</span>
                <input class="text_normal width2" type="text" name="add_time_from" id="add_time_from" value="<?php echo $this->_var['query']['add_time_from']; ?>" /> &#8211; <input class="text_normal width2" id="add_time_to" type="text" name="add_time_to" value="<?php echo $this->_var['query']['add_time_to']; ?>" />
               
                
                <input type="hidden" name="app" value="account" />
                <input type="hidden" name="act" value="pay_list" />
                <input type="submit" class="btn" value="搜索" />
                </div>
                <?php if ($this->_var['query']['pay_name'] || $this->_var['query']['add_time_from'] || $this->_var['query']['add_time_to'] || $this->_var['query']['order_sn']): ?>
                    <a class="detlink" href="<?php echo url('app=account&act=pay_list'); ?>">取消检索</a>
                <?php endif; ?>

        </form>

        </div>
         <div class="public_index table">
                <table>
                    <?php if ($this->_var['pays']): ?>
                    <tr class="line tr_bgcolor">
                        <th class="width10" >交易名称</th>
                        <th>交易类型</th>
                        <th>交易时间</th>
                        <th>交易金额</th>
                        <th width="90">交易状态</th>
                        <th width="90"></th>
                    </tr>
                    <?php endif; ?>
                    
                    <?php $_from = $this->_var['pays']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'pay');if (count($_from)):
    foreach ($_from AS $this->_var['pay']):
?>
                    <tr class="color2 line_bottom">
                        <td class="align2"><?php echo htmlspecialchars($this->_var['pay']['pay_name']); ?></td>
                        <td class="align2"><?php echo call_user_func("pay_type",$this->_var['pay']['type']); ?></td>
                        <td class="align2"><?php echo local_date("Y-m-d H:i:s",$this->_var['pay']['create_time']); ?></td>
                        <td class="align2 padding1"><strong><?php if ($this->_var['pay']['buyer_id'] == $this->_var['user_id']): ?> <span class="color3">- <?php echo price_format($this->_var['pay']['money']); ?></span> <?php elseif ($this->_var['pay']['seller_id'] == $this->_var['user_id']): ?> <span class="color4">+ <?php echo price_format($this->_var['pay']['money']); ?></span> <?php endif; ?></strong></td>
                        <td class="align2"><span class="color4"><?php echo call_user_func("pay_status",$this->_var['pay']['status']); ?></span></td>
                        <td class="order_form1">
                            <div class="button_wrap"> 
                                <?php if ($this->_var['pay']['type'] == 'G'): ?>
                                    <?php if ($this->_var['pay']['buyer_id'] == $this->_var['user_id']): ?>
                                        <a class="btn1" href="<?php echo url('app=buyer_order&act=view&order_id=' . $this->_var['pay']['order_id']. ''); ?>" target="_blank">查看详情</a>
                                    <?php else: ?>
                                        <a class="btn1" href="<?php echo url('app=seller_order&act=view&order_id=' . $this->_var['pay']['order_id']. ''); ?>" target="_blank">查看详情</a>
                                     <?php endif; ?>
                               <?php endif; ?>
                                <?php if ($this->_var['pay']['type'] == 'R'): ?>
                                <?php endif; ?>
                                <?php if ($this->_var['pay']['type'] == 'S'): ?>
                                <?php endif; ?>
                            </div></td>
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
