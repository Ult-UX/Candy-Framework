@extends(layout) 

@block(container)
<div class="row">
    <div class="column"><h1>添加文章的测试</h1></div>
</div>
<div class="row">
    <div class="small-9 columns">
        <form action="/dashboard/content/article/add" method="post">
            <input type="text" name="title" value="">
            <textarea rows="20" cols="100" name="text"></textarea>
            <button type="reset" class="alert button">重置</button>
            <button type="submit" class="button">提交</button>
        </form>
    </div>
    <div class="small-3 columns">

    </div>
</div>
@end