<?php
namespace Zangyd\Util;

use Swoole\Coroutine;
use Zangyd\Util\ZangydHttp\Handler\IHandler;

abstract class ZangydHttp
{
    /**
     * 默认处理器类
     * 
     * @var string|null
     */
    private static $defaultHandler = null;

    /**
     * 属性
     *
     * @var array
     */
    private static $attributes = [];

    /**
     * 版本号
     */
    const VERSION = '4.2';

    /**
     * 设置默认处理器类
     * @param string|null $class
     * @return void
     */
    public static function setDefaultHandler($class)
    {
        static::$defaultHandler = $class;
    }

    /**
     * 获取默认处理器类
     * @return string|null
     */
    public static function getDefaultHandler()
    {
        return static::$defaultHandler;
    }

    /**
     * 获取处理器类
     *
     * @return \Zangyd\Util\ZangydHttp\Handler\IHandler
     */
    public static function getHandler()
    {
        if(static::$defaultHandler)
        {
            $class = static::$defaultHandler;
        }
        else if(defined('SWOOLE_VERSION') && Coroutine::getuid() > -1)
        {
            $class = \Zangyd\Util\ZangydHttp\Handler\Swoole::class;
        }
        else
        {
            $class = \Zangyd\Util\ZangydHttp\Handler\Curl::class;
        }
        return new $class;
    }

    /**
     * 发送请求并获取结果
     * @param \Zangyd\Util\ZangydHttp\Http\Request $request
     * @param \Zangyd\Util\ZangydHttp\Handler\IHandler|string $handlerClass
     * @return \Zangyd\Util\ZangydHttp\Http\Response
     */
    public static function send($request, $handlerClass = null)
    {
        if($handlerClass instanceof IHandler)
        {
            $handler = $handlerClass;
        }
        else if(null === $handlerClass)
        {
            $handler = static::getHandler();
        }
        else
        {
            $handler = new $handlerClass();
        }
        $time = microtime(true);
        foreach(static::$attributes as $name => $value)
        {
            if(null === $request->getAttribute($name))
            {
                $request = $request->withAttribute($name, $value);
            }
        }
        $handler->send($request);
        $response = $handler->recv();
        if(!$response)
        {
            return $response;
        }
        $response = $response->withTotalTime(microtime(true) - $time);
        return $response;
    }

    /**
     * 发起 WebSocket 连接
     *
     * @param \Zangyd\Util\ZangydHttp\Http\Request $request
     * @param \Zangyd\Util\ZangydHttp\Handler\IHandler|string $handlerClass
     * @return \Zangyd\Util\ZangydHttp\WebSocket\IWebSocketClient
     */
    public static function websocket($request, $handlerClass = null)
    {
        if($handlerClass instanceof IHandler)
        {
            $handler = $handlerClass;
        }
        else if(null === $handlerClass)
        {
            $handler = static::getHandler();
        }
        else
        {
            $handler = new $handlerClass();
        }
        $time = microtime(true);
        foreach(static::$attributes as $name => $value)
        {
            if(null === $request->getAttribute($name))
            {
                $request = $request->withAttribute($name, $value);
            }
        }
        $websocketClient = $handler->websocket($request);
        $response = $websocketClient->getHttpResponse()->withTotalTime(microtime(true) - $time);
        $websocketClient->init($handler, $request, $response);
        return $websocketClient;
    }

    /**
     * 获取所有全局属性
     * @return array
     */
    public static function getAttributes()
    {
        return static::$attributes;
    }

    /**
     * 获取全局属性值
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public static function getAttribute($name, $default = null)
    {
        if(array_key_exists($name, static::$attributes))
        {
            return static::$attributes[$name];
        }
        else
        {
            return $default;
        }
    }

    /**
     * 设置全局属性值
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public static function setAttribute($name, $value)
    {
        static::$attributes[$name] = $value;
    }

}