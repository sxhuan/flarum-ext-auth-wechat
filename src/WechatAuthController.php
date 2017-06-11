<?php
/*
 * Stanley Song <sxhuan@gmail.com>
 */

namespace StanleySong\Auth\Wechat;

use Flarum\Forum\Controller\AuthenticateUserTrait;
use Flarum\Forum\AuthenticationResponseFactory;
use Flarum\Http\Controller\ControllerInterface;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Bus\Dispatcher;
use Henter\WeChat\OAuth;

class WechatAuthController extends ControllerInterface
{
    use AuthenticateUserTrait;

    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @param SettingsRepositoryInterface $settings
     * @param UrlGenerator $url
     * @param Dispatcher $bus
     */
    public function __construct(SettingsRepositoryInterface $settings, UrlGenerator $url, Dispatcher $bus)
    {
        $this->settings = $settings;
        $this->url = $url;
        $this->bus = $bus;
    }

    /**
     * @param Request $request
     * @param array $routeParams
     * @return \Psr\Http\Message\ResponseInterface|RedirectResponse
     */
    public function handle(Request $request, array $routeParams = [])
    {
        $code = $_GET['code'];

        $oauth = new OAuth([
            'AppId'        => $this->settings->get('stanleysong-auth-wechat.app_id'),
            'AppSecret'    => $this->settings->get('stanleysong-auth-wechat.app_secret'),
        ]);

        $callback_url = $this->settings->get('stanleysong-auth-wechat.callback_url');
        $url = $oauth->getAuthorizeURL($callback_url);

        file_put_contents("/var/log/php.info", $url, FILE_APPEND);

        if($access_token = $oauth->getAccessToken('code', $code)){
            $refresh_token = $oauth->getRefreshToken();
            $expires_in = $oauth->getExpiresIn();
            $openid = $oauth->getOpenid();
            $access_token = $oauth->refreshAccessToken($refresh_token);
        }else{
            echo $oauth->error();
        }
        $oauth->setAccessToken($access_token);
        $userinfo = $oauth->api('sns/userinfo', array('openid'=>$openid));
        $username = preg_replace('/[^a-z0-9-_]/i', '', $userinfo->getNickname());
        
        file_put_contents("/var/log/php.info", $userinfo, FILE_APPEND);

        return $this->authenticate(compact('openid'), compact('username'));;
    }
}
