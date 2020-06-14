<?php
namespace Zangyd\Util\ZangydHttp\Co;

use Zangyd\Util\ZangydHttp;

abstract class Batch
{
    /**
     * 批量运行并发请求
     *
     * @param \Zangyd\Util\ZangydHttp\Http\Request[]|\Zangyd\Util\HttpRequest[] $requests
     * @param float|null $timeout 超时时间，单位：秒。默认为 null 不限制
     * @param string|null $handlerClass
     * @return \Zangyd\Util\ZangydHttp\Http\Response[]
     */
    public static function run($requests, $timeout = null, $handlerClass = null)
    {
        foreach($requests as &$request)
        {
            if($request instanceof \Zangyd\Util\HttpRequest)
            {
                $request = $request->buildRequest();
            }
            else if(!$request instanceof \Zangyd\Util\ZangydHttp\Http\Request)
            {
                throw new \InvalidArgumentException('Request must be instance of \Zangyd\Util\ZangydHttp\Http\Request or \Zangyd\Util\HttpRequest');
            }
        }
        if(null === $handlerClass)
        {
            $handler = ZangydHttp::getHandler();
        }
        else
        {
            $handler = new $handlerClass;
        }
        /** @var \Zangyd\Util\ZangydHttp\Handler\IHandler $handler */
        return $handler->coBatch($requests, $timeout);
    }

}
