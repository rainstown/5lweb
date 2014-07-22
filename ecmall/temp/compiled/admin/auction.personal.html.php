<?php echo $this->fetch('header.html'); ?>
<script type="text/javascript">
$(document).ready(function(){
	$('#search_time_from, #search_time_to').datepicker({
		dateFormat : 'yy-mm-dd'
	});
});
</script>
<div id="rightTop">
	<p>个人拍卖</p>
	<!--  
	<ul class="subnav">
		<li><span>管理</span></li>
		<li><a class="btn1" href="index.php?app=auction&amp;act=edit">新增</a></li>
	</ul>
	-->
</div>

<div class="mrightTop">
	<div class="fontl">
		<form method="get">
			<div class="left">
				<input type="hidden" name="app" value="auction" /> 
				<input type="hidden" name="act" value="personal" /> 拍卖会标题: 
				<input class="" type="text" name="name" value="<?php echo htmlspecialchars($_GET['name']); ?>" />
				状态: 
				<select class="querySelect" name="status">
					<option value="">请选择</option> 
					<?php echo $this->html_options(array('options'=>$this->_var['status_options'],'selected'=>$_GET['status'])); ?>
				</select>
				<select class="querySelect" name="search_field">
					<?php echo $this->html_options(array('options'=>$this->_var['search_options'],'selected'=>$_GET['field'])); ?>
                </select>:
               	<input class="queryInput" type="text" value="<?php echo $this->_var['query']['search_time_from']; ?>" id="search_time_from" name="search_time_from" class="pick_date" />
                -&nbsp;&nbsp;&nbsp;<input class="queryInput" type="text" value="<?php echo $this->_var['query']['search_time_to']; ?>" id="search_time_to" name="search_time_to" class="pick_date" />
				<input type="submit" class="formbtn" value="查询" />
			</div>
			<?php if ($this->_var['filtered']): ?>
			<a class="left formbtn1" href="index.php?app=auction&act=personal">撤销检索</a>
			<?php endif; ?>
		</form>
	</div>
	<div class="fontr"><?php echo $this->fetch('page.top.html'); ?></div>
</div>
<div class="tdare">
	<table width="100%" cellspacing="0" class="dataTable">
		<?php if ($this->_var['auctions']): ?>
		<tr class="tatr1">
			<td>拍卖会标题</td>
			<td><span ectype="order_by" fieldname="status">状态</span></td>
			<td><span ectype="order_by" fieldname="start_time">开始时间</span></td>
			<td><span ectype="order_by" fieldname="end_time">结束时间</span></td>
			<td><span ectype="order_by" fieldname="create_time">创建时间</span></td>
			<td>创建者名称</td>
			<td>保证金</td>
			<td>商品上架费用</td>
			<td>交易费用</td>
			<td>买家保证金</td>
			<td class="handler">操作</td>
		</tr>
		<?php endif; ?>
		<?php $_from = $this->_var['auctions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'auction');if (count($_from)):
    foreach ($_from AS $this->_var['auction']):
?>
		<tr class="tatr2">
			<td><?php echo htmlspecialchars($this->_var['auction']['name']); ?></td>
			<td><?php echo htmlspecialchars($this->_var['auction']['status_name']); ?></td>
			<td><?php echo $this->_var['auction']['start_time']; ?></td>
			<td><?php echo $this->_var['auction']['end_time']; ?></td>
			<td><?php echo $this->_var['auction']['create_time']; ?></td>
			<td><?php echo $this->_var['auction']['user_name']; ?></td>
			<td><?php echo $this->_var['auction']['keep_money']; ?></td>
			<td><?php echo $this->_var['auction']['put_money']; ?></td>
			<td><?php echo $this->_var['auction']['trade_money']; ?></td>
			<td><?php echo $this->_var['auction']['keep_percent']; ?>%</td>
			<td>
				<?php if ($this->_var['auction']['can_approve']): ?>
				<a href="index.php?app=auction&amp;act=approve&amp;id=<?php echo $this->_var['auction']['auction_id']; ?>">审核</a>
				<?php else: ?>
				<a href="<?php echo url('app=auction&act=user&id=' . $this->_var['auction']['auction_id']. ''); ?>">查看卖家</a>
				<?php endif; ?>
				<a href="index.php?app=auction&amp;act=view&amp;id=<?php echo $this->_var['auction']['auction_id']; ?>">查看</a>
			</td>
		</tr>
		<?php endforeach; else: ?>
		<tr class="no_data">
			<td colspan="10">没有符合条件的记录</td>
		</tr>
		<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
	</table>
	<?php if ($this->_var['auctions']): ?>
	<div id="dataFuncs">
		<div class="pageLinks"><?php echo $this->fetch('page.bottom.html'); ?></div>
		<div class="clear"></div>
	</div>
	<?php endif; ?>
</div>
<?php echo $this->fetch('footer.html'); ?>
