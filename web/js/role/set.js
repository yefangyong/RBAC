;//是因为多个js文件压缩合并的时候，可能和上一个js文件代码相冲
var role_set_opts = {
    init:function(){
        this.eventBind();
    },

    eventBind:function () {
      $(".role_set_wrap .save").click(function(){
          var name = $(".role_set_wrap input[name='name']").val();
          if(name.length<1) {
              alert("请输入角色名称");
              return false;
          }
          $.ajax({
              url:'/role/set',
              type:'post',
              data:{
                  id:$(".role_set_wrap input[name='id']").val(),
                  name:name
              },
              dataType:'json',
              success:function (res) {
                  console.log(res);
                  alert(res.msg);
                  if(res.code == 200) {
                      window.location.href = '/role/index'
                  }
              }
          });
      });
    }
};

//页面加载完成之后
$(document).ready(function(){
    role_set_opts.init();
});