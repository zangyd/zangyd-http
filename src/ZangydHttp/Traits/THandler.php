<?php
namespace Zangyd\Util\ZangydHttp\Traits;

use Zangyd\Util\ZangydHttp\Http\Psr7\Uri;

trait THandler
{
    /**
     * 处理重定向的 location
     *
     * @param string $location
     * @param \Zangyd\Util\ZangydHttp\Http\Psr7\Uri $currentUri
     * @return \Zangyd\Util\ZangydHttp\Http\Psr7\Uri
     */
    public function parseRedirectLocation($location, $currentUri)
    {
        $locationUri = new Uri($location);
        if('' === $locationUri->getHost())
        {
            if(!isset($location[0]))
            {
                return;
            }
            if('/' === $location[0])
            {
                $uri = $currentUri->withQuery('')->withPath($location);
            }
            else
            {
                $path = dirname($currentUri);
                if('\\' === DIRECTORY_SEPARATOR && false !== strpos($path, DIRECTORY_SEPARATOR))
                {
                    $path = str_replace(DIRECTORY_SEPARATOR, '/', $path);
                }
                $uri = new Uri($path . '/' . $location);
            }
        }
        else
        {
            $uri = $locationUri;
        }
        return $uri;
    }

    /**
     * 检查请求对象
     *
     * @param \Zangyd\Util\ZangydHttp\Http\Request[] $requests
     * @return void
     */
    protected function checkRequests($requests)
    {
        foreach($requests as $request)
        {
            if(!$request instanceof \Zangyd\Util\ZangydHttp\Http\Request)
            {
                throw new \InvalidArgumentException('Request must be instance of \Zangyd\Util\ZangydHttp\Http\Request');
            }
        }
    }

}
