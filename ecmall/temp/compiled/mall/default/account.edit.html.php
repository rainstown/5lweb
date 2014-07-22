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
            old_payment_password : {
                required : true,
                minlength: 6
            },
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
            old_payment_password : {
                required : '老支付密码',
                minlength  : '输入大于6位的字符'
            },
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
                         <li><h3>修改账户</h3>
                        <p> </p>
                        </li>
                        <li><h3>老支付密码:</h3>
                        <p><input class="text width_normal" type="password" name="old_payment_password" /></p>
                        </li>
                        <li><h3>支付密码:</h3>
                        <p><input class="text width_normal" id="password" type="password" name="payment_password" /></p>
                        </li>
                        <li><h3>确认密码:</h3>
                        <p><input class="text width_normal" type="password" name="check_password" /></p>
                        </li>
                         <!-- 
                        <li><h3>手机号码:</h3>
                        <p><input class="text width_normal" type="text" name="mob" /> 交易时候用于接收验证码。</p>
                        </li>
                        
                        <li><h3>验证码:</h3>
                        <p><input class="text width_normal" type="text" name="check_code" /></p>
                        </li>
                        -->
                    </ul>
                    <div class="submit">
                              <input class="btn" type="submit" value="提交" />
                              <input class="btn" type="button" onclick="javascript:reset_pwd()" value="重置密码"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<script>
    function reset_pwd()
    {
       window.location.href="index.php?app=account&act=reset_pwd"; 
    }
    </script>
<?php echo $this->fetch('footer.html'); ?>
