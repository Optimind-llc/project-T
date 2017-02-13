import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
import { vehicles, processes, inspections, inspectionGroups } from '../../../../utils/Processes';
// Actions
import { push } from 'react-router-redux';
import { reportActions } from '../ducks/report';
// Styles
import './report.scss';
// Components
// import Loading from '../../../components/loading/loading';
// import RangeCalendar from '../components/rangeCalendar/rangeCalendar';
// import Mapping from '../../mapping/containers/mapping';

class Report extends Component {
  constructor(props, context) {
    super(props, context);

    const { getReportData } = props.actions;
    const now = moment();
    getReportData(now.format("YYYY-MM-DD"));

    this.state = ({
      modal: false,
      path: '',
      vehicle: null,
      date: now,
      inspectorG: null,
      process: null,
    });
  }

  componentWillUnmount() {
    this.props.actions.clearReportData();
  }

  render() {
    const { date } = this.state;
    const { reportData, actions } = this.props;

    return (
      <div id="950Report">
        <p>Comming soon...</p>
      </div>
    );
  }
}

Report.propTypes = {
  reportData: PropTypes.object.isRequired
};

function mapStateToProps(state, ownProps) {
  return {
    reportData: state.ReportData950A
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({push}, reportActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Report);
