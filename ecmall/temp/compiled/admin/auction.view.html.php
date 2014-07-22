<?php echo $this->fetch('header.html'); ?>
<div id="rightTop">
	<p>拍卖</p>
	<ul class="subnav">
		<?php if ($this->_var['auction']['type'] == 'P'): ?>
		<li><a class="btn1" href="index.php?app=auction&act=personal">管理</a></li>
		<?php else: ?>
		<li><a class="btn1" href="index.php?app=auction">管理</a></li>
		<?php if ($this->_var['auction']['auction_id']): ?>
		<li>
			<a class="btn1" href="index.php?app=auction&amp;act=edit&amp;id=<?php echo $this->_var['auction']['auction_id']; ?>">编辑</a> 
		</li>
		<?php endif; ?>
		<li>
			<a class="btn1" href="index.php?app=auction&amp;act=edit">新增</a>
		</li> 
		<?php endif; ?>
	</ul>
</div>
<div class="info">
	<form method="post" action="<?php echo url('app=auction&act=edit&id=' . $this->_var['auction']['auction_id']. ''); ?>" id="auction_form">
		<input type="hidden" name="auction_id" value="<?php echo $this->_var['auction']['auction_id']; ?>" />
		<table class="infoTable">
			<tr>
				<th class="paddingT15">拍卖会标题:</th>
				<td class="paddingT15 wordSpacing5">
				<?php echo $this->_var['auction']['name']; ?>
				</td>
			</tr>
			<tr>
				<th class="paddingT15">开始时间:</th>
				<td class="paddingT15 wordSpacing5">
					<?php echo $this->_var['auction']['start_time']; ?>
				</td>
			</tr>
			<tr>
				<th class="paddingT15">结束时间:</th>
				<td class="paddingT15 wordSpacing5">
					<?php echo $this->_var['auction']['end_time']; ?>
				</td>
			</tr>
			<?php if ($this->_var['auction']['type'] == 'O'): ?>
			<tr>
				<th class="paddingT15">报名开始时间:</th>
				<td class="paddingT15 wordSpacing5">
					<?php echo $this->_var['auction']['sign_start']; ?>
				</td>
			</tr>
			<tr>
				<th class="paddingT15">报名截止时间:</th>
				<td class="paddingT15 wordSpacing5">
					<?php echo $this->_var['auction']['sign_end']; ?>
				</td>
			</tr>
			<?php endif; ?>
			<tr>
				<th class="paddingT15">状态:</th>
				<td class="paddingT15 wordSpacing5">
					<?php echo $this->_var['auction']['status_name']; ?>
				</td>
			</tr>
			<tr>
				<th class="paddingT15">保证金:</th>
				<td class="paddingT15 wordSpacing5">
					<?php echo $this->_var['auction']['keep_money']; ?>
				</td>
			</tr>
			<tr>
				<th class="paddingT15">商品上架费用(元/每件):</th>
				<td class="paddingT15 wordSpacing5">
					<?php echo $this->_var['auction']['put_money']; ?>
				</td>
			</tr>
			<tr>
				<th class="paddingT15">交易费用(元/每件):</th>
				<td class="paddingT15 wordSpacing5">
					<?php echo $this->_var['auction']['trade_money']; ?>
				</td>
			</tr>
			<tr>
				<th class="paddingT15">买家保证金(%):</th>
				<td class="paddingT15 wordSpacing5">
					<?php echo $this->_var['auction']['keep_percent']; ?>
				</td>
			</tr>
			<tr>
				<th class="paddingT15">描述:</th>
				<td class="paddingT15 wordSpacing5">
					<?php echo nl2br($this->_var['auction']['description']); ?>
				</td>
			</tr>
		</table>
	</form>
</div>
<?php echo $this->fetch('footer.html'); ?>
