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
import Association from '../modules/association/containers/association';
import Inspector from '../modules/inspector/containers/inspector';
import Failure from '../modules/failure/containers/failure';
import Modification from '../modules/modification/containers/modification';
import Hole from '../modules/hole/containers/hole';

import Dashboard950A from '../modules/Vehicle950A/dashboard/containers/dashboard';
import Mapping950A from '../modules/Vehicle950A/mapping/containers/mapping';
import Report950A from '../modules/Vehicle950A/report/containers/report';

class Root extends Component {
  render() {
    const { history, store } = this.props;
    return (
      <Provider store={store}>
        <Router history={history}>
          <Route name="閲覧" path="manager" component={App}>
            <Route name="マッピング" path="dashboard" component={Dashboard}/>
            <Route name="検査結果照会" path="reference" component={Reference}/>
            <Route name="直レポート印刷" path="report" component={Report}/>
            <Route name="小部品ID紐付" path="association" component={Association}/>
            <Route name="担当者マスタメンテ" path="inspector" component={Inspector}/>
            <Route name="不良区分マスタメンテ" path="failure" component={Failure}/>
            <Route name="手直区分マスタメンテ" path="modification" component={Modification}/>
            <Route name="穴あけ加工ポイント登録" path="hole" component={Hole}/>
            <Route name="950A" path="950A">
              <Route name="ダッシュボード" path="dashboard" component={Dashboard950A}/>
              <Route name="マッピング" path="mapping" component={Mapping950A}/>
              <Route name="検査結果照会" path="reference" component={Dashboard950A}/>
              <Route name="直レポート" path="report" component={Report950A}/>
              <Route name="マッピング" path="association" component={Dashboard950A}/>
              <Route path="maintenance">
                <Route name="マッピング" path="worker" component={Dashboard950A}/>
                <Route name="マッピング" path="failure" component={Dashboard950A}/>
                <Route name="マッピング" path="modification" component={Dashboard950A}/>
                <Route name="マッピング" path="holeModification" component={Dashboard950A}/>
                <Route name="マッピング" path="hole" component={Dashboard950A}/>
                <Route name="マッピング" path="inline" component={Dashboard950A}/>
              </Route>
            </Route>
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
