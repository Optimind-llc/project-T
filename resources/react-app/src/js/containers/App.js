import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
// Actions
import * as InitializeActions from '../actions/initialize';
import { push } from 'react-router-redux';
// Material-ui Components
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import { Paper, IconButton, IconMenu, MenuItem } from 'material-ui';
import MoreVertIcon from 'material-ui/svg-icons/navigation/more-vert';
import NavigationClose from 'material-ui/svg-icons/navigation/close';
import SocialPublic from 'material-ui/svg-icons/social/public';
// Components
import Alert from '../components/alert/alert';
import Navigation from '../components/navigation/navigation';
import Breadcrumbs from '../components/breadcrumbs/breadcrumbs';

class App extends Component {
  constructor(props, context) {
    super(props, context);
  }

  render() {
    const { children, routes, alerts, actions } = this.props;

    const links = [
      { en: 'dashboard', name: 'マッピング', disable: false},
      { en: 'reference', name: '検査結果検索', disable: false },
      { en: 'report', name: '直レポート印刷', disable: false },
      { en: 'association', name: 'パネルID検索', disable: false },
      { en: 'inspector', name: '担当者マスタメンテ', disable: false },
      { en: 'master', name: '品番マスタメンテ', disable: true },
      { en: 'hole', name: '穴あけ加工ポイント登録', disable: true },
      { en: 'failure', name: '不良区分マスタメンテ', disable: true },
      { en: 'master', name: '手直し区分マスタメンテ', disable: true }
    ];

    const nameList = [
      '検査工程　不良マッピングシステム',
      routes[0].name,
      routes[1].name
    ];

    const styles = {
      container: {
        minWidth: 1280,
        backgroundColor: 'rgba(231,236,245,1)',
        paddingBottom: 10,
        minHeight: '100%'
      },
      nav: {
        zIndex: 1000,
        position: 'fixed',
        top: 0,
        left: 0,
        width: 160,
        height: '100%',
      },
      content: {
        paddingLeft: 160, 
      }
    };

    return (
      <MuiThemeProvider>
        <div style={styles.container}>
          <Alert alerts={alerts} deleteSideAlerts={actions.deleteSideAlerts} />
          <Paper
            zDepth={2}
            rounded={false}
            style={styles.nav}
          >
            <Navigation links={links}/>
          </Paper>
          <div style={styles.content}>
            {/*<Breadcrumbs nameList={nameList}/>*/}
            {children}
          </div>
        </div>
      </MuiThemeProvider>
    );
  }
}

App.propTypes = {
  children: PropTypes.element.isRequired,
  routes: PropTypes.array.isRequired,
  alerts: PropTypes.array,
  actions: PropTypes.object.isRequired
};

function mapStateToProps(state, ownProps) {
  return {
    routes: ownProps.routes,
    alerts: state.alert,
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign(
    InitializeActions,
    { push: push }
  );

  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(App);
