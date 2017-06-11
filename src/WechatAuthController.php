<?php
/*
 * Stanley Song <sxhuan@gmail.com>
 */

namespace StanleySong\Auth\Wechat;

use Flarum\Forum\AuthenticationResponseFactory;
use Flarum\Forum\Controller\AbstractOAuth2Controller;
use Flarum\Settings\SettingsRepositoryInterface;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Henter\WeChat\OAuth;

class WechatAuthController extends AbstractOAuth2Controller
{
    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    /**
     * @param AuthenticationResponseFactory $authResponse
     * @param SettingsRepositoryInterface $settings
     */
    public function __construct(AuthenticationResponseFactory $authResponse, SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
        $this->authResponse = $authResponse;
    }

    /**
     * {@inheritdoc}
     */
    protected function getProvider($redirectUri)
    {
        $code = $_GET['code'];

        $oauth = new OAuth([
            'AppId'        => $this->settings->get('stanleysong-auth-wechat.app_id'),
            'AppSecret'    => $this->settings->get('stanleysong-auth-wechat.app_secret'),
        ]);

        file_put_contents("php.log", "appid: "."\n".print_r($this->settings->get('stanleysong-auth-wechat.app_id'), true)."\n", FILE_APPEND);
        file_put_contents("php.log", "appkey: "."\n".print_r($this->settings->get('stanleysong-auth-wechat.app_secret'), true)."\n", FILE_APPEND);
        file_put_contents("php.log", "callback: "."\n".print_r($this->settings->get('stanleysong-auth-wechat.callback_url'), true)."\n", FILE_APPEND);

        $callback_url = $this->settings->get('stanleysong-auth-wechat.callback_url');
        $url = $oauth->getWeChatAuthorizeURL($callback_url);

        file_put_contents("php.log", "url: "."\n".print_r($url, true)."\n", FILE_APPEND);

        if($access_token = $oauth->getAccessToken('code', $code)){
            $refresh_token = $oauth->getRefreshToken();
            $expires_in = $oauth->getExpiresIn();
            $openid = $oauth->getOpenid();
            $access_token = $oauth->refreshAccessToken($refresh_token);
        }else{
            echo $oauth->error();
        }
        $oauth->setAccessToken($access_token);
        $userinfo = $oauth->api('sns/userinfo', array('openid'=>$oauth->getOpenid()));
        $username = preg_replace('/[^a-z0-9-_]/i', '', $userinfo->getNickname());

        return $this->authenticate(compact('openid'), compact('username'));;
    }

    /**
     * {@inheritdoc}
     */
    protected function getAuthorizationUrlOptions()
    {
        return ['scope' => ['email']];
    }

    /**
     * {@inheritdoc}
     */
    protected function getIdentification(ResourceOwnerInterface $resourceOwner)
    {
        return [
            'email' => $resourceOwner->getEmail() ?: $this->getEmailFromApi()
        ];
    }
    /**
     * {@inheritdoc}
     */
    protected function getSuggestions(ResourceOwnerInterface $resourceOwner)
    {
        return [
            'username' => $resourceOwner->getNickname(),
            'avatarUrl' => array_get($resourceOwner->toArray(), 'avatar_url')
        ];
    }
    protected function getEmailFromApi()
    {
        $url = $this->provider->apiDomain.'/user/emails';
        $emails = $this->provider->getResponse(
            $this->provider->getAuthenticatedRequest('GET', $url, $this->token)
        );
        foreach ($emails as $email) {
            if ($email['primary'] && $email['verified']) {
                return $email['email'];
            }
        }
    }
}
