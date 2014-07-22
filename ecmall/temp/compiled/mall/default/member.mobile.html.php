<?php echo $this->fetch('member.header.html'); ?>

<style>
.borline td {padding:10px 0px;}
.ware_list th {text-align:left;}
.bgwhite {background: #FFFFFF;}
</style>
<div class="content">
    <?php echo $this->fetch('member.menu.html'); ?>
    <div id="right">
        <?php echo $this->fetch('member.submenu.html'); ?>
        <div class="eject_con bgwhite">
            <div class="add">
                <form method="post" id="email_form">
                    <ul>
                        
                        <li>
                            <?php if ($this->_var['mobile']): ?>
                            您已经绑定手机号：<?php echo $this->_var['mobile']; ?><a href="index.php?app=member&act=mobile_edit" >【<font color="#0000FF">点击此处</font>】</a>可修改
                        <?php else: ?>
                        您未绑定手机号：<?php echo $this->_var['mobile']; ?><a href="index.php?app=member&act=mobile_edit">【<font color="#0000FF">点击此处</font>】</a>可绑定手机号
                        <?php endif; ?>
                        </li>
                    </ul>
                    
                </form>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php echo $this->fetch('footer.html'); ?>
