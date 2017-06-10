import { extend } from 'flarum/extend';
import app from 'flarum/app';
import LogInButtons from 'flarum/components/LogInButtons';
import WechatLogInButton from 'stanleysong/auth/wechat/components/WechatLogInButton';

app.initializers.add('stanleysong-auth-wechat', () => {
  extend(LogInButtons.prototype, 'items', function(items) {
    items.add('wechat',
      <WechatLogInButton
        className="Button LogInButton--wechat"
        icon="wechat"
        path="/auth/wechat">
        {app.translator.trans('stanleysong-auth-wechat.forum.log_in.with_wechat_button')}
      </WechatLogInButton>
    );
  });
});