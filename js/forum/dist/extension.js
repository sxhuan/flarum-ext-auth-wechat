'use strict';

System.register('stanleysong/auth/wechat/components/WechatLogInButton', ['flarum/components/Button'], function (_export, _context) {
  "use strict";

  var Button, WechatLogInButton;
  return {
    setters: [function (_flarumComponentsButton) {
      Button = _flarumComponentsButton.default;
    }],
    execute: function () {
      WechatLogInButton = function (_Button) {
        babelHelpers.inherits(WechatLogInButton, _Button);

        function WechatLogInButton() {
          babelHelpers.classCallCheck(this, WechatLogInButton);
          return babelHelpers.possibleConstructorReturn(this, (WechatLogInButton.__proto__ || Object.getPrototypeOf(WechatLogInButton)).apply(this, arguments));
        }

        babelHelpers.createClass(WechatLogInButton, null, [{
          key: 'initProps',
          value: function initProps(props) {
            props.className = (props.className || '') + ' LogInButton';

            props.onclick = function () {
              var width = 800;
              var height = 400;
              var $window = $(window);

              window.open(app.forum.attribute('baseUrl') + props.path, 'logInPopup', 'width=' + width + ',' + ('height=' + height + ',') + ('top=' + ($window.height() / 2 - height / 2) + ',') + ('left=' + ($window.width() / 2 - width / 2) + ',') + 'status=no,resizable=no');
            };

            babelHelpers.get(WechatLogInButton.__proto__ || Object.getPrototypeOf(WechatLogInButton), 'initProps', this).call(this, props);
          }
        }]);
        return WechatLogInButton;
      }(Button);

      _export('default', WechatLogInButton);
    }
  };
});;
'use strict';

System.register('stanleysong/auth/wechat/main', ['flarum/extend', 'flarum/app', 'flarum/components/LogInButtons', 'stanleysong/auth/wechat/components/WechatLogInButton'], function (_export, _context) {
  "use strict";

  var extend, app, LogInButtons, WechatLogInButton;
  return {
    setters: [function (_flarumExtend) {
      extend = _flarumExtend.extend;
    }, function (_flarumApp) {
      app = _flarumApp.default;
    }, function (_flarumComponentsLogInButtons) {
      LogInButtons = _flarumComponentsLogInButtons.default;
    }, function (_stanleysongAuthWechatComponentsWechatLogInButton) {
      WechatLogInButton = _stanleysongAuthWechatComponentsWechatLogInButton.default;
    }],
    execute: function () {

      app.initializers.add('stanleysong-auth-wechat', function () {
        extend(LogInButtons.prototype, 'items', function (items) {
          items.add('wechat', m(
            WechatLogInButton,
            {
              className: 'Button LogInButton--wechat',
              icon: 'wechat',
              path: '/auth/wechat' },
            app.translator.trans('stanleysong-auth-wechat.forum.log_in.with_wechat_button')
          ));
        });
      });
    }
  };
});