import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
// Config
import { SCHOOL_NAME } from '../../../config/env';
// Actions
import * as DashboardActions from '../../actions/dashboard';
// Components
import { Paper, Dialog, RaisedButton, FlatButton } from 'material-ui';
import { grey50, indigo500 } from 'material-ui/styles/colors';
import Loading from '../Common/Loading';

class Dashboard extends Component {
  constructor(props, context) {
    super(props, context);
    const { fetchConference, createAuditor } = props.actions;

    fetchConference(props.id);

    if (props.application.auditorCode === null) {
      createAuditor();
    }
    this.state = {
      open: true,
    };
  }

  sendReaction(type) {
    const { id, application: {auditorCode}, actions: {sendReaction} } = this.props;
    sendReaction({
      conference: id,
      token: auditorCode,
      type
    });
  }

  render() {
    const { conference } = this.props;
    const style = {
      minHeight: window.innerHeight,
      background: 'rgb(17,25,142)',
    };
    const actions = [
      <FlatButton
        label="Enter"
        primary={true}
        disabled={conference.didInvalidate || conference.isFetching || (conference.conference !== null && conference.conference.status == 0)}
        onClick={() => {
          this.setState({open: false});
          this.sendReaction(0);
        }}
      />
    ];

    return (
      <div>aaa</div>
    );
  }
}

Dashboard.propTypes = {
  id: PropTypes.string.isRequired,
  application: PropTypes.object.isRequired,
  conference: PropTypes.object.isRequired,
};

function mapStateToProps(state, ownProps) {
  return {
    id: ownProps.params.id,
    application: state.application,
    conference: state.conference,
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({}, DashboardActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Dashboard);
