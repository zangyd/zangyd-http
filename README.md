# ZangydHttp 的请求构建器

类：`Zangyd\Util\HttpRequest`

```php
use Zangyd\Util\HttpRequest;
$httpRequest = new HttpRequest;
```

## 属性

### 是否验证证书

```php
$httpRequest->isVerifyCA = false;
```

### 是否启用重定向

```php
$httpRequest->followLocation = true;
```

### 最大重定向次数

```php
$httpRequest->maxRedirects = 10;
```

### Http 协议版本

```php
$httpRequest->protocolVersion = '1.1';
```

## 方法

### Http 请求方法

```php
get($url = null, $requestBody = null)
post($url = null, $requestBody = null, $contentType = null)
head($url = null, $requestBody = null)
put($url = null, $requestBody = null, $contentType = null)
patch($url = null, $requestBody = null, $contentType = null)
delete($url = null, $requestBody = null, $contentType = null)
```

发送请求，所有请求的老祖宗：

```php
send($url = null, $requestBody = null, $method = null, $contentType = null)
```

- `$url` 请求地址，如果为null则取url属性值
- `$requestBody` 发送内容，可以是字符串、数组，如果为空则取content属性值
- `$method` 请求方法，GET、POST等
- `$contentType` 内容类型，支持null/json，为null时不处理

```php
$response = $httpRequest->get('https://www.imiphp.com');
```

### 下载文件

```php
download($fileName, $url = null, $requestBody = null, $method = 'GET')
```

- `$fileName` 保存路径，如果以 .* 结尾，则根据 Content-Type 自动决定扩展名
- `$url` 下载文件地址
- `$requestBody` 发送内容，可以是字符串、数组，如果为空则取content属性值
- `$method` 请求方法，GET、POST等，一般用GET

```php
$httpRequest->download(__DIR__. '/a.html', 'https://www.imiphp.com');
```

### WebSocket

返回 `WebSocket` 客户端对象

```php
websocket($url = null): \Zangyd\Util\ZangydHttp\WebSocket\IWebSocketClient
<?php
    require dirname(__DIR__) . '/vendor/autoload.php';

	use Zangyd\Util\ZangydHttp;
	use Zangyd\Util\HttpRequest;

ZangydHttp::setDefaultHandler(\Zangyd\Util\ZangydHttp\Handler\Swoole::class);
go(function(){    
    // 该测试地址随时可能过期    
    $url = 'ws://123.207.136.134:9010/ajaxchattest';    
    $http = new HttpRequest;    
    $http->header('Origin', 'http://coolaf.com');    
    $client = $http->websocket($url);    
    if(!$client->isConnected())    
    {        
        throw new \RuntimeException('Connect failed');    
    }    
    $time = time() . '';    
    var_dump('time:', $time);    
    $client->send($time);    
    $recv = $client->recv();    
    var_dump('recv:', $recv);    
    $client->close();
});
```

### Http2

发送 Http2 请求不获取响应数据

```php
sendHttp2WithoutRecv($url = null, $requestBody = null, $method = 'GET', $contentType = null)
<?php
    /** * 简单用法示例 */
    require dirname(__DIR__) . '/vendor/autoload.php';

	use Zangyd\Util\HttpRequest;
	use Zangyd\Util\ZangydHttp;
	use Zangyd\Util\ZangydHttp\Handler\Swoole;ZangydHttp::setDefaultHandler(Swoole::class);

	go(function(){    
        $http = new HttpRequest;    
        $http->protocolVersion = '2.0';    
        $http->sendHttp2WithoutRecv('https://wiki.swoole.com/', null, 'GET');
    });
```

### 请求地址

```php
$httpRequest->url('https://www.imiphp.com');
```

### 请求方法

```php
$httpRequest->method('POST');
```

### 主体内容/参数

方法名：`requestBody`

别名：`content`、`params`

```php
// POST 参数
$httpRequest->requestBody([    
    'id'    => 123456,    
    'name'  => 'aaa',
]);

// JSON 参数
$httpRequest->requestBody(json_encode([    
    'id'    => 123456,    
    'name'  => 'aaa',
]));

// 同时支持POST参数、上传文件
$httpRequest->requestBody([    
    'id'    =>    123456,    
    // 显示的文件名；文件类型，可以为null；文件真实路径    
    'file'  => new UploadedFile('1.txt', MediaType::TEXT_PLAIN, __FILE__),
]);
```

### 请求头

```php
// 批量设置
$httpRequest->headers([    'k1' => 'v1',    'k2' => 'v2',]);// 单个设置$httpRequest->header('k', 'v');
```

常用请求头快捷方法：`accept`、`acceptLanguage`、`acceptEncoding`、`acceptRanges`、`cacheControl`、`contentType`、`range`、`referer`、`userAgent`、`ua` (`userAgent`的别名)

### Cookie

```php
// 批量设置
$httpRequest->cookies([    'k1' => 'v1',    'k2' => 'v2',]);// 单个设置$httpRequest->cookie('k', 'v');
```

### 失败重试

设置失败重试次数，状态码为5XX或者0才需要重试

```php
$httpRequest->retry(3); // 失败重试 3 次
```

### 网络代理

代理设置：`proxy($server, $port, $type = 'http', $auth = 'basic')`

- `$type`-代理类型，支持：http、socks4、socks4a、socks5
- `$auth`-代理认证方式，支持：basic、ntlm。一般默认basic

代理认证：`proxyAuth($username, $password)`

```php
$httpRequest->proxy('123.123.123.123', 8080)            
    ->proxyAuth('zangyd', 'zhen-shuai');
```

### 超时设置

```php
timeout($timeout = null, $connectTimeout = null)
```

- `$timeout`-总超时时间，单位：毫秒
- `$connectTimeout`-连接超时时间，单位：毫秒

```php
$httpRequest->time(30000); // 超时时间设为 30 秒
```

### 限速

仅 `Curl` 请求器可用

```php
limitRate($download = 0, $upload = 0)
```

- `$download`-下载速度，为0则不限制，单位：字节
- `$upload`-上传速度，为0则不限制，单位：字节

```php
$httpRequest->limitRate(10 * 1024 * 1024, 1 * 1024 * 1024); // 下载限速 10 MB，上传限速 1 MB
```

### 网页认证

```php
userPwd($username, $password)
$httpRequest->userPwd('username', 'password');
```

### 保存文件

保存至文件的设置：`saveFile($filePath, $fileMode = 'w+')`

获取文件保存路径：`getSavePath()`

```php
$httpRequest->saveFile(__DIR__ . '/1.txt');$filepath = $httpRequest->getSavePath();echo $filepath;
```

### ssl 相关 (https)

设置SSL证书 `sslCert($path, $type = null, $password = null)`

- `$path`-一个包含 PEM 格式证书的文件名
- `$type`-证书类型，支持的格式有”PEM”(默认值),“DER”和”ENG”
- `$password`-使用证书需要的密码

设置SSL私钥 `sslKey($path, $type = null, $password = null)`

- `$path`-包含 SSL 私钥的文件名
- `$type`-certType规定的私钥的加密类型，支持的密钥类型为”PEM”(默认值)、”DER”和”ENG”
- `$password`-SSL私钥的密码

代码示例：

```php
// 鉴于大家绝大部分情况下用 https，都不会选择验证，否则会有很多奇怪的问题，所以默认是 false
// 这边我们需要手动启用下
$httpRequest->isVerifyCA = true;
```

### 其它可选属性

```php
// 批量设置
$httpRequest->options([    
    'k1' => 'v1',    
    'k2' => 'v2',
]);
// 单个设置$httpRequest->option('k', 'v');
```

YurunHttp 的请求响应类，结果类。除了遵循 PSR-7 规范，另外还增加了一些人性化的方法。

类：`Yurun\Util\YurunHttp\Http\Response`

```
use Yurun\Util\HttpRequest;$http = new HttpRequest;$response = $http->get('http://www.baidu.com');
```

# 请求响应类

## 方法

### 响应内容（页面内容）

```php
$content = $response->body();
```

#### 自动编码转换

```php
// gb2312 转 UTF-8
$content = $response->body('gb2312', 'UTF-8');
```

#### json

```php
// 返回对象
$data = $response->json();

// 返回数组
$data = $response->json(true);

// gb2312 转 UTF-8
$data = $response->json(true, 'gb2312', 'UTF-8');
```

#### jsonp

获取 `jsonp` 格式内容，去除方法名，像 `json` 一样转为数据

```php
// 返回对象
$data = $response->jsonp();

// 返回数组
$data = $response->jsonp(true);

// gb2312 转 UTF-8
$data = $response->jsonp(true, 'gb2312', 'UTF-8');
```

#### xml

```php
// 返回 \SimpleXMLElement 对象
$data = $response->xml();

// 返回数组
$data = $response->xml(true);

// gb2312 转 UTF-8
$data = $response->xml(false, 'gb2312', 'UTF-8');
```

### 响应头

获取单个响应头：

```php
var_dump($response->getHeaderLine('Content-Type'));
```

响应头是否存在：

```php
var_dump($response->hasHeader('Content-Type'));
```

获取所有响应头：

```php
var_dump($response->getHeaders());
```

### Cookie

获取 Cookie 值：

```php
// 通常用法
var_dump($response->getCookie('a'));
// 设置默认值为 
testvar_dump($response->getCookie('a', 'test'));
```

获取所有 Cookie：

```php
var_dump($response->getCookieParams());
```

获取所有cookie原始参数，包含expires、path、domain等：

```php
var_dump($response->getCookieOriginParams());
```

获取cookie原始参数值，包含expires、path、domain等：

```php
// 通常用法
var_dump($response->getCookieOrigin('a'));
// 设置默认值为 
testvar_dump($response->getCookieOrigin('a', 'test'));
```

### 状态码

两个用法一致：

```php
var_dump($response->httpCode());
var_dump($response->getStatusCode());
```

### 获取请求耗时

两个用法一致：

```php
var_dump($response->totalTime());
var_dump($response->getTotalTime());
```

### 错误相关

```php
// 错误代码-两个用法一致：
var_dump($response->errno());
var_dump($response->getErrno());

// 错误信息-两个用法一致：
var_dump($response->error());
var_dump($response->getError());
```

### 获取请求体

```php
/** @var \Yurun\Util\YurunHttp\Http\Request $request */
$request = $response->getRequest();
```

### 获取 http2 streamId

```php
var_dump($response->getStreamId());
```