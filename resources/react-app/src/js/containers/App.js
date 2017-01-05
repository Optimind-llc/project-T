import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
// Actions
import { applicationActions } from '../reducers/application';
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

class App extends Component {
  constructor(props, context) {
    super(props, context);
  }

  render() {
    const { children, routes, Application, alerts, actions } = this.props;

    const links = [
      { en: 'dashboard', name: 'マッピング', disable: false},
      { en: 'reference', name: '検査結果検索', disable: false },
      { en: 'report', name: '直レポート印刷', disable: false },
      { en: 'association', name: 'パネルID検索', disable: false }
    ];

    const masterlinks = [
      { en: 'inspector', name: '担当者マスタメンテ', disable: false },
      { en: 'failure', name: '不良区分マスタメンテ', disable: false },
      { en: 'modification', name: '手直区分マスタメンテ', disable: false },
      { en: 'hole', name: '穴あけ加工ポイント登録', disable: true },
    ];

    const nameList = [
      '検査工程　不良マッピングシステム',
      routes[0].name,
      routes[1].name
    ];

    const styles = {
      container: {
        minWidth: 1349,
        backgroundColor: 'rgba(231,236,245,1)',
        height: '100%',
        minHeight: 400
      },
      content: {
        paddingLeft: 160, 
      }
    };

    return (
      <div style={styles.container}>
        <Alert alerts={alerts} deleteSideAlerts={actions.deleteSideAlerts} />
        <Navigation
          vehicle={Application.vehicle}
          links={links}
          masterlinks={masterlinks}
          logedin={Application.master}
          pw={'0000'}
          login={() => actions.login()}
          logout={() => actions.logout()}
          push={actions.push}
        />
        <div style={styles.content}>
          {children}
        </div>
      </div>
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
    Application: state.Application,
    alerts: state.alert,
    routes: ownProps.routes
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign(
    applicationActions,
    { push: push }
  );

  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(App);
