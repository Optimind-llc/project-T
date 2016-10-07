import 'babel-polyfill';
import React from 'react';
import { render } from 'react-dom';
import { browserHistory } from 'react-router'
import Root from './js/containers/Root';
import configureStore from './js/store/configureStore';
import { SCHOOL_NAME } from './config/env';
// Styles
import './assets/scss/common.scss';

// baseURLを使うとreact-router-reduxのpushが動かなくなる
// const browserHistory = useRouterHistory(createBrowserHistory)({
//   basename: `/${SCHOOL_NAME}/teacher`
// })

const store = configureStore({}, browserHistory);

render(
  <Root history={browserHistory} store={store}/>,
  document.getElementById('root')
);
