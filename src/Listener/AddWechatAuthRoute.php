<?php

/*
 * Stanley Song <sxhuan@gmail.com>
 */

namespace Flarum\Auth\Wechat\Listener;

use Flarum\Event\ConfigureForumRoutes;
use Illuminate\Contracts\Events\Dispatcher;

class AddWechatAuthRoute
{
    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(ConfigureForumRoutes::class, [$this, 'configureForumRoutes']);
    }

    /**
     * @param ConfigureForumRoutes $event
     */
    public function configureForumRoutes(ConfigureForumRoutes $event)
    {
        $event->get('/auth/wechat', 'auth.wechat', 'Flarum\Auth\Wechat\WechatAuthController');
    }
}
