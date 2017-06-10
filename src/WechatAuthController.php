<?php
/*
 * Stanley Song <sxhuan@gmail.com>
 */

namespace StanleySong\Auth\Wechat;

use Flarum\Forum\AuthenticationResponseFactory;
use Flarum\Forum\Controller\AbstractOAuth2Controller;
use Flarum\Settings\SettingsRepositoryInterface;
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

        file_put_contents("/var/log/php.info", $userinfo, FILE_APPEND);

        return $userinfo;
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
            'email' => $resourceOwner->getEmail()
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getSuggestions(ResourceOwnerInterface $resourceOwner)
    {
        return [
            'username' => $resourceOwner->getName(),
            'avatarUrl' => $resourceOwner->getPictureUrl()
        ];
    }
}
