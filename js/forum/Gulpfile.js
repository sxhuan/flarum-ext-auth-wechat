var gulp = require('flarum-gulp');

gulp({
  modules: {
    'flarum/auth/wechat': 'src/**/*.js'
  }
});
