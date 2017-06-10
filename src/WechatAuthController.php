<?php
/*
 * Stanley Song <sxhuan@gmail.com>
 */

namespace Flarum\Auth\WechatLogin;

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
        return new \Henter\WeChat\OAuth([
            'clientId'        => $this->settings->get('flarum-auth-wechat.app_id'),
            'clientSecret'    => $this->settings->get('flarum-auth-wechat.app_secret'),
        ]);
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
