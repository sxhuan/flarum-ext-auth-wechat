# flarum-ext-auth-wechat
This plugin is for Flarum using Wechat third-party login.

# Setup
You need to have open platform app id and secrets of wechat (https://open.weixin.qq.com)

Config:
fill app id and secrets and callback url
callback url is your domian with "/auth/wechat"
eg.
www.yourdomain.com/auth/wechat

your authrizon callback domian in https://open.weixin.qq.com  can be www.yourdomain.com


2. fill wechat callback url, it will get back this url while login success

# Notice
As wechat has not provide email, here using a random string as email,
Also if your wechant nickname not using English, will replace to random string
you can change it in the pop window of first time login, otherwise after login
you can go to profile to change it or email.
