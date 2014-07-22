<?php echo $this->fetch('header.html'); ?>

<div id="rightTop">
	<p><?php echo $this->_var['auction_name']; ?>拍卖会卖家</p>
	<ul class="subnav">
		<li><span><?php echo $this->_var['auction_name']; ?>拍卖会卖家</span></li>
	</ul>
</div>

<div class="mrightTop">
	<div class="fontl">
	</div>
	<div class="fontr"><?php echo $this->fetch('page.top.html'); ?></div>
</div>
<div class="tdare">
	<table width="100%" cellspacing="0" class="dataTable">
		<?php if ($this->_var['user_list']): ?>
		<tr class="tatr1">
			<td>用户名</td>
			<td class="handler" style="width: 120px;">操作</td>
		</tr>
		<?php endif; ?>
		<?php $_from = $this->_var['user_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'user');if (count($_from)):
    foreach ($_from AS $this->_var['user']):
?>
		<tr class="tatr2">
			<td><?php echo htmlspecialchars($this->_var['user']['user_name']); ?></td>
			<td>
				<?php if ($this->_var['user']['back_money']): ?>
				<?php else: ?>
				<a href="index.php?app=auction&act=refund&id=<?php echo $this->_var['user']['user_id']; ?>&auction_id=<?php echo $_GET['id']; ?>">退保证金</a>
				<?php endif; ?>
			</td>
		</tr>
		<?php endforeach; else: ?>
		<tr class="no_data">
			<td colspan="10">没有符合条件的记录</td>
		</tr>
		<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
	</table>
	<?php if ($this->_var['user_list']): ?>
	<div id="dataFuncs">
		<div class="pageLinks"><?php echo $this->fetch('page.bottom.html'); ?></div>
		<div class="clear"></div>
	</div>
	<?php endif; ?>
</div>
<?php echo $this->fetch('footer.html'); ?>
