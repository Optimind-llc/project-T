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
      { en: 'dashboard', name: 'マッピング' },
      { en: 'reference', name: '検査結果照会' },
      { en: 'report', name: '直レポート印刷' }
    ]

    const nameList = [
      '検査工程　不良マッピングシステム',
      routes[0].name,
      routes[1].name
    ];

    const styles = {
      container: {
        height: '100%',
        backgroundColor: 'rgba(231,236,245,1)',
        paddingBottom: 40,
        minHeight: 400
      },
      nav: {
        zIndex: 1000,
        position: 'fixed',
        top: 0,
        left: 0,
        width: 180,
        height: '100%',
      },
      content: {
        paddingLeft: 180, 
        height: '100%',
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
