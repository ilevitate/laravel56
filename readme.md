# Laravel5.6开发测试

##### 添加了一言API

* 接口地址：`https://v1.hitokoto.cn`
* 请求方式：`GET`
* 请求参数：
    * `c` : 可选，即`Cat`类型，提交不同的参数代表不同的类别，具体如下：L
        * `a`	:   `Anime` - 动画
        * `b`	:   `Comic` – 漫画
        * `c`	:   `Game` – 游戏
        * `d`	:   `Novel` – 小说
        * `e`	:   `Myself` – 原创
        * `f`	:   `Internet` – 来自网络
        * `g`	:   `Other` – 其他
        * 其他不存在参数	任意类型随机取得
    * `encode` : 可选
        * `text` :    返回纯净文本
        * `json` :	返回不进行 `unicode` 转码的 `json` 文本
        * `js`   :	返回指定选择器(默认`.hitokoto`)的同步执行函数。
        * 其他不存在参数	返回 `unicode` 转码的 `json` 文本
    * `charset`: 可选
        * `utf-8`	: 返回 `UTF-8` 编码的内容，支持与异步函数同用。
        * `gbk`	: 返回 `GBK` 编码的内容，不支持与异步函数同用。 
    * `callback` : 可选，回调函数，将返回的内容传参给指定的异步函数。
    
* 返回数据：(默认为`json`格式)
    * `id`	        :    本条一言的id。
    * `hitokoto`    :   一言正文。编码方式unicode。使用utf-8。
    * `type`	    :   类型。请参考第三节参数的表格。
    * `from`	    :   一言的出处。
    * `creator`	    :   添加者。
    * `created_at`	:   添加时间。
    * 注意：如果`encode`参数为`text`，那么输出的只有一言正文。