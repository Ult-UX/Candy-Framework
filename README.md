# Candy Framework System Statements

* ## Bootstrap/框架引导
 耦合类。

* ## Component/组件
 非耦合类；框架必须的基本构成。
 
 * ### AutoLoader/自动加载

 * ### Config/配置读写

 * ### URI/请求处理

 * ### Error/错误处理

* ## Bundle/包
 非耦合类；功能。

 * ### Input/输入处理

 * ### Parser/模板解析

 * ### SQLBuilder/SQL语句构造器

 * ### Pagination/分页处理

* ## extension/扩展
 耦合类，依赖组件或包；功能扩展。

 * ### URL/URL处理

* ## Framework/框架
 耦合类，依赖组件或包；MVC基础框架。

 * ### Controller/控制器

 * ### Model/模型

 * ### View/视图

* ## Database/数据库

* ## Function/函数
 函数集合包，非类函数仅推荐在视图中使用