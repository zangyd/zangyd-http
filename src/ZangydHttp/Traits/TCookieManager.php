<?php
namespace Zangyd\Util\ZangydHttp\Traits;

use Zangyd\Util\ZangydHttp\Cookie\CookieManager;

trait TCookieManager
{
    /**
     * Cookie 管理器
     *
     * @var \Zangyd\Util\ZangydHttp\Cookie\CookieManager
     */
    protected $cookieManager;

    private function initCookieManager()
    {
        $this->cookieManager = new CookieManager();
    }

    /**
     * Get cookie 管理器
     *
     * @return  \Zangyd\Util\ZangydHttp\Cookie\CookieManager
     */ 
    public function getCookieManager()
    {
        return $this->cookieManager;
    }

}