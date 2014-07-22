<?php echo $this->fetch('member.header.html'); ?>
<script type="text/javascript">
$(function(){
    $('#account_form').validate({
        errorPlacement: function(error, element){
            $(element).next('.field_notice').hide();
            $(element).after(error);
        },
        success       : function(label){
            label.addClass('validate_right').text('OK!');
        },
        rules : {
            payment_password : {
                required   : true,
                minlength  : 6,
                maxlength  : 20
            },
            check_password : {
                required   : true,
                equalTo    : '#password'
            }
        },
        messages : {
            payment_password  : {
                required   : '支付密码',
                minlength  : '输入大于6位的字符'
            },
            check_password : {
                required   : '确认密码',
                equalTo    : '密码必须相等'
            }
        }
    });
});
</script>
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
                <form method="post" id="account_form">
                    <ul>
                         <li><h3>申请账户</h3>
                        <p> </p>
                        </li>
                        
                        <li><h3>支付密码:</h3>
                        <p><input class="text width_normal" id="password" type="password" name="payment_password" /></p>
                        </li>
                        <li><h3>确认密码:</h3>
                        <p><input class="text width_normal" type="password" name="check_password" /></p>
                        </li>
                         <!-- 
                        <li><h3>手机号码:</h3>
                        <p><input class="text width_normal" type="text" name="phone_mob" />  用户接收验证码</p>
                        </li>
                       
                        <li><h3>验证码:</h3>
                        <p><input class="text width_normal" type="text" name="check_code" /></p>
                        </li>
                        -->
                    </ul>
                    <div class="submit">
                              <input class="btn" type="submit" value="提交" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php echo $this->fetch('footer.html'); ?>
