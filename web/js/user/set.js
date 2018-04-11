;
var user_set_opts = {
    init:function () {
        this.bindEvent();
    },
    bindEvent:function () {
        $(".user_set_wrap .save").click(function () {
            var btn_target = $(this);
            if(btn_target.hasClass('disabled')) {
                alert('请不要重复提交哦');
                return false
            }
            var name = $(".user_set_wrap input[name='name']").val();
            var email = $(".user_set_wrap input[name='email']").val();
            if(name.length < 1) {
                alert('请输入合法的用户名');
                return false;
            }
            if(email.length < 1){
                alert("请输入合法的邮箱");
                return false;
            }
           //将选中的角色传递到后端
            var role_ids = [];
            $(".user_set_wrap input[name='role_ids[]']").each(function () {
                if($(this).prop('checked')) {
                    role_ids.push($(this).val());
                }
            });
            console.log(role_ids);

            btn_target.addClass("disabled");
            $.ajax({
                url:'/user/set',
                type:'POST',
                data:{
                    id:$(".user_set_wrap input[name='id']").val(),
                    name:name,
                    email:email,
                    role_ids:role_ids
                },
                dataType:'json',
                success:function (res) {
                    btn_target.removeClass("disabled");
                    alert(res.msg);
                    if(res.code == 200){
                        window.location.href="/user/index"
                    }
                 }

            });

        });
    }
}

$(document).ready(function () {
    user_set_opts.init();
})