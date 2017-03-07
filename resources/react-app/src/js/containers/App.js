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
    const { get950AInitial } = this.props.actions;

    if (props.routes[1].name == '950A') {
      props.actions.changeVehicle('950A');
    }

    get950AInitial();
  }

  render() {
    const { children, routes, Application, alerts, actions } = this.props;

    const links = {
      '680A': [
        { en: 'dashboard', name: 'マッピング', disable: false},
        { en: 'reference', name: '検査結果検索', disable: false },
        { en: 'report', name: '直レポート印刷', disable: false },
        { en: 'association', name: 'パネルID検索', disable: false }
      ],
      '950A': [
        { en: '950A/mapping', name: 'マッピング', disable: false},
        { en: '950A/reference', name: '検査結果検索', disable: true },
        { en: '950A/report', name: '直レポート印刷', disable: false },
        { en: '950A/association', name: 'パネルID検索', disable: false }
      ]
    };

    const masterlinks = {
      '680A': [
        { en: 'inspector', name: '担当者マスタメンテ', disable: false },
        { en: 'failure', name: '不良区分マスタメンテ', disable: false },
        { en: 'modification', name: '手直区分マスタメンテ', disable: false },
        { en: 'hole', name: '穴あけポイントメンテ', disable: false }
      ],
      '950A': [
        { en: '950A/maintenance/worker', name: '担当者マスタ', disable: false },
        { en: '950A/maintenance/failure', name: '不良区分マスタ', disable: false },
        { en: '950A/maintenance/modification', name: '手直区分マスタ', disable: false },
        { en: '950A/maintenance/holeModification', name: '穴手直区分マスタ', disable: false },
        { en: '950A/maintenance/hole', name: '穴あけポイント', disable: false },
        { en: '950A/maintenance/inline', name: '精度ポイント', disable: false }
      ]
    };

    const styles = {
      container: {
        minWidth: 1349,
        backgroundColor: 'rgba(231,236,245,1)',
        height: '100%',
        minHeight: 600
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
          links={links[Application.vehicle]}
          masterlinks={masterlinks[Application.vehicle]}
          logedin={Application.master}
          pw={'0000'}
          login={() => actions.login()}
          logout={() => actions.logout()}
          changeVehicle={(v) => this.props.actions.changeVehicle(v)}
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
