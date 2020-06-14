<?php
namespace Zangyd\Util\ZangydHttp\Handler;

interface IHandler
{
    /**
     * 发送请求
     * @param \Zangyd\Util\ZangydHttp\Http\Request $request
     * @return void
     */
    public function send($request);

    /**
     * 接收请求
     * @return \Zangyd\Util\ZangydHttp\Http\Response
     */
    public function recv();

    /**
     * 连接 WebSocket
     *
     * @param \Zangyd\Util\ZangydHttp\Http\Request $request
     * @param \Zangyd\Util\ZangydHttp\WebSocket\IWebSocketClient $websocketClient
     * @return \Zangyd\Util\ZangydHttp\WebSocket\IWebSocketClient
     */
    public function websocket($request, $websocketClient = null);

    /**
     * Get cookie 管理器
     *
     * @return  \Zangyd\Util\ZangydHttp\Cookie\CookieManager
     */ 
    public function getCookieManager();

    /**
     * 获取原始处理器对象
     *
     * @return mixed
     */
    public function getHandler();

    /**
     * 批量运行并发请求
     *
     * @param \Zangyd\Util\ZangydHttp\Http\Request[] $requests
     * @param float|null $timeout 超时时间，单位：秒。默认为 null 不限制
     * @return \Zangyd\Util\ZangydHttp\Http\Response[]
     */
    public function coBatch($requests, $timeout = null);

    /**
     * 关闭并释放所有资源
     *
     * @return void
     */
    public function close();

}