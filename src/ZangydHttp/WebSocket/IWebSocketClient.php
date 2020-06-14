<?php
namespace Zangyd\Util\ZangydHttp\WebSocket;

interface IWebSocketClient
{
    /**
     * 初始化
     *
     * @param \Zangyd\Util\ZangydHttp\Handler\IHandler $httpHandler
     * @param \Zangyd\Util\ZangydHttp\Http\Request $request
     * @param \Zangyd\Util\ZangydHttp\Http\Response $response
     * @return void
     */
    public function init($httpHandler, $request, $response);

    /**
     * 获取 Http Handler
     *
     * @return  \Zangyd\Util\ZangydHttp\Handler\IHandler
     */ 
    public function getHttpHandler();

    /**
     * 获取 Http Request
     *
     * @return \Zangyd\Util\ZangydHttp\Http\Request
     */
    public function getHttpRequest();

    /**
     * 获取 Http Response
     *
     * @return \Zangyd\Util\ZangydHttp\Http\Response
     */
    public function getHttpResponse();

    /**
     * 连接
     *
     * @return bool
     */
    public function connect();

    /**
     * 关闭连接
     *
     * @return void
     */
    public function close();

    /**
     * 发送数据
     *
     * @param mixed $data
     * @return bool
     */
    public function send($data);

    /**
     * 接收数据
     *
     * @param double|null $timeout 超时时间，单位：秒。默认为 null 不限制
     * @return mixed
     */
    public function recv($timeout = null);

    /**
     * 是否已连接
     *
     * @return boolean
     */
    public function isConnected();

}