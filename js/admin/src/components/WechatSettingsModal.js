import SettingsModal from 'flarum/components/SettingsModal';

export default class WechatSettingsModal extends SettingsModal {
  className() {
    return 'WechatSettingsModal Modal--small';
  }

  title() {
    return app.translator.trans('flarum-auth-wechat.admin.wechat_settings.title');
  }

  form() {
    return [
      <div className="Form-group">
        <label>{app.translator.trans('flarum-auth-wechat.admin.wechat_settings.app_id_label')}</label>
        <input className="FormControl" bidi={this.setting('flarum-auth-wechat.app_id')}/>
      </div>,

      <div className="Form-group">
        <label>{app.translator.trans('flarum-auth-wechat.admin.wechat_settings.app_secret_label')}</label>
        <input className="FormControl" bidi={this.setting('flarum-auth-wechat.app_secret')}/>
      </div>
    ];
  }
}
