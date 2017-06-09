import app from 'flarum/app';

import WechatSettingsModal from 'flarum/auth/wechat/components/WechatSettingsModal';

app.initializers.add('flarum-auth-wechat', () => {
  app.extensionSettings['flarum-auth-wechat'] = () => app.modal.show(new WechatSettingsModal());
});
