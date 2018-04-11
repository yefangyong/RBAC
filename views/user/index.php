<div class="row">
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
        <h5>用户列表</h5>
    </div>
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <a href="<?=\app\services\UrlService::buildUrl("/user/set");?>" class="btn btn-link pull-right">添加用户</a>
    </div>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>姓名</th>
            <th>邮箱</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
       <?php if(isset($user_list) && !empty($user_list)):?>
           <?php foreach ($user_list as $item):?>
       <tr>
           <td><?=$item['name']?></td>
           <td><?=$item['email']?></td>
           <td>
               <a href="<?=\app\services\UrlService::buildUrl("/user/set",[ 'id' => $item['id'] ]);?>">编辑</a>
           </td>
       <tr>
           <?php endforeach;?>
           <?php else:?>
               <tr>
                   <td colspan="3">暂无用户</td>
               </tr>
        <?php endif;?>
        </tbody>
    </table>
</div>