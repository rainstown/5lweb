<?php echo $this->fetch('header.html'); ?>

<?php echo $this->fetch('top.html'); ?>
<div id="content">
    <div id="left">
        <?php echo $this->fetch('left.html'); ?>
    </div>

    <div id="right">
        <?php echo $this->fetch('auction_goods_info.html'); ?>

        <a name="module"></a>

        <div class="option_box">
            <div class="default"><?php echo html_filter($this->_var['goods']['description']); ?></div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="clear"></div>
</div>

<?php echo $this->fetch('footer.html'); ?>
