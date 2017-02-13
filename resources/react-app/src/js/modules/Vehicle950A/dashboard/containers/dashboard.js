import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
import { vehicles, processes, inspections, inspectionGroups } from '../../../../utils/Processes';
// Actions
import { push } from 'react-router-redux';
import { pageActions } from '../ducks/mapping';
// Styles
import './dashboard.scss';
// Components
// import Loading from '../../../components/loading/loading';
// import RangeCalendar from '../components/rangeCalendar/rangeCalendar';
// import Mapping from '../../mapping/containers/mapping';

class Dashboard extends Component {
  constructor(props, context) {
    super(props, context);
    const { MappingData, actions } = props;

    this.state = {
      vehicle: { label: '680A', value: '680A' }
    };
  }

  componentWillUnmount() {
   clearInterval(this.state.intervalId); 
  }

  endInterval() {
    clearInterval(this.state.intervalId);
  }

  serchItorG() {
    const { getItorGData } = this.props.actions;
    getItorGData();
  }


  render() {
    const { vehicle } = this.state;
    const { MappingData, actions } = this.props;

    return (
      <div id="950Dashboard">
        <p>Comming soon...</p>
      </div>
    );
  }
}

Dashboard.propTypes = {
  MappingData: PropTypes.object.isRequired
};

function mapStateToProps(state, ownProps) {
  return {
    MappingData: state.V950MappingData
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({push}, pageActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Dashboard);
