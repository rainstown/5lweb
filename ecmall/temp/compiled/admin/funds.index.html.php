<?php echo $this->fetch('header.html'); ?>
<script type="text/javascript">
$(function(){
    $('#add_time_from').datepicker({dateFormat: 'yy-mm-dd'});
    $('#add_time_to').datepicker({dateFormat: 'yy-mm-dd'});
});
</script>
<div id="rightTop">
    <ul class="subnav">
         <li><span>管理</span></li>
        <li><a class="btn1" href="index.php?app=funds&amp;act=in_funds">充值</a></li>
       
    </ul>
</div>
<div class="mrightTop">
    <div class="fontl">
        <form method="get">
             <div class="left">
                <input type="hidden" name="app" value="funds" />
                <input type="hidden" name="act" value="index" />
                <!--
                <select class="querySelect" name="field"><?php echo $this->html_options(array('options'=>$this->_var['search_options'],'selected'=>$_GET['field'])); ?>
                </select>:<input class="queryInput" type="text" name="search_name" value="<?php echo htmlspecialchars($this->_var['query']['search_name']); ?>" />
                -->
                <select class="querySelect" name="status">
                    <option value="">状态</option>
                    <?php echo $this->html_options(array('options'=>$this->_var['order_status_list'],'selected'=>$this->_var['query']['status'])); ?>
                </select>
                时间从:<input class="queryInput2" type="text" value="<?php echo $this->_var['query']['add_time_from']; ?>" id="add_time_from" name="add_time_from" class="pick_date" />
                至:<input class="queryInput2" type="text" value="<?php echo $this->_var['query']['add_time_to']; ?>" id="add_time_to" name="add_time_to" class="pick_date" />
                金额从:<input class="queryInput2" type="text" value="<?php echo $this->_var['query']['order_amount_from']; ?>" name="order_amount_from" />
                至:<input class="queryInput2" type="text" style="width:60px;" value="<?php echo $this->_var['query']['order_amount_to']; ?>" name="order_amount_to" class="pick_date" />
                <input type="submit" class="formbtn" value="查询" />
            </div>
            <?php if ($this->_var['filtered']): ?>
            <a class="left formbtn1" href="index.php?app=funds">撤销检索</a>
            <?php endif; ?>
        </form>
    </div>
    <div class="fontr">
        <?php if ($this->_var['orders']): ?><?php echo $this->fetch('page.top.html'); ?><?php endif; ?>
    </div>
</div>
<div class="tdare">
    <table width="100%" cellspacing="0" class="dataTable">
        <?php if ($this->_var['orders']): ?>
        <tr class="tatr1">
            <td width="5%"><span ectype="order_by" fieldname="in_out">流向</span></td>
            <td width="10%" class="firstCell"><span ectype="order_by" fieldname="seller_id">资金名称</span></td>
            <td width="10%"><span ectype="order_by" fieldname="order_sn">交易编码</span></td>
            <td width="12%"><span ectype="order_by" fieldname="add_time">交易时间</span></td>
            <td width="10%"><span ectype="order_by" fieldname="user_name">会员账户</span></td>
            <td width="8%"><span ectype="order_by" fieldname="order_amount">金额</span></td>
            <td >备注</td>
            <td width="10%"><span ectype="order_by" fieldname="status">状态</span></td>
            <td width="8%">操作</td>
        </tr>
        <?php endif; ?>
        <?php $_from = $this->_var['orders']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'order');if (count($_from)):
    foreach ($_from AS $this->_var['order']):
?>
        <tr class="tatr2">
            <td class="firstCell"><?php echo call_user_func("funds_in_out",$this->_var['order']['in_out']); ?></td>
            <td class="firstCell"><?php echo htmlspecialchars($this->_var['order']['funds_name']); ?></td>
            <td><?php echo $this->_var['order']['order_sn']; ?></td>
            <td><?php echo local_date("Y-m-d H:i:s",$this->_var['order']['add_time']); ?></td>
            <td><?php echo htmlspecialchars($this->_var['order']['user_name']); ?></td>
            <td><?php echo price_format($this->_var['order']['money']); ?></td>
            <td><?php echo $this->_var['order']['name']; ?> - <?php echo $this->_var['order']['account_name']; ?> - <?php echo $this->_var['order']['account']; ?></td>
            <td><?php echo call_user_func("pay_status",$this->_var['order']['status']); ?></td>
            <td><a href="index.php?app=funds&act=view_funds&funds_id=<?php echo $this->_var['order']['funds_id']; ?>">查看</a></td>
        </tr>
        <?php endforeach; else: ?>
        <tr class="no_data">
            <td colspan="7">没有符合条件的记录</td>
        </tr>
        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </table>
    <div id="dataFuncs">
        <div class="pageLinks">
            <?php if ($this->_var['orders']): ?><?php echo $this->fetch('page.bottom.html'); ?><?php endif; ?>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php echo $this->fetch('footer.html'); ?>
