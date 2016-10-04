import React, { Component, PropTypes } from 'react';
import { Router, Route, IndexRoute, Redirect } from 'react-router';
import { Provider, connect } from 'react-redux';
//import DevTools from './DevTools';
import injectTapEventPlugin from 'react-tap-event-plugin';
injectTapEventPlugin();
//Components
import App from './App';
import Dashboard from '../modules/dashboard/containers/dashboard';
import Mapping from '../modules/mapping/containers/mapping';
import Reference from '../modules/reference/containers/reference';
import Report from '../modules/report/containers/report';

class Root extends Component {
  render() {
    const { history, store } = this.props;
    return (
      <Provider store={store}>
        <Router history={history}>
          <Route name="閲覧" path="manager" component={App}>
            <Route name="ダッシュボード" path="dashboard" component={Dashboard}/>
            <Route name="マッピング" path="mapping/:id" component={Mapping}/>
            <Route name="検査結果照会" path="reference" component={Reference}/>
            <Route name="直レポート印刷" path="report" component={Report}/>
          </Route>
        </Router>
        {/*<DevTools/>*/}
      </Provider>
    );
  }
}

Root.propTypes = {};

function mapStateToProps(state) {
  return {};
}

export default connect(mapStateToProps)(Root);

            //<Route path="dashboard" component={Dashboard}/>
            //<Route path="mapping" component={Mapping}/>
