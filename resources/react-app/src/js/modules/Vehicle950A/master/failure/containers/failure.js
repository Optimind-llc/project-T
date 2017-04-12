import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
// Actions
import { push } from 'react-router-redux';
// import { maintFailureActions } from '../ducks/maintFailure';
// Styles
import './failure.scss';
// Components
// import Loading from '../../../components/loading/loading';
// import RangeCalendar from '../components/rangeCalendar/rangeCalendar';
// import Mapping from '../../mapping/containers/mapping';

class Failure extends Component {
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
      <div id="maint-failure-950A-wrap">
        <p>Comming soon...</p>
      </div>
    );
  }
}

Failure.propTypes = {
  MappingData: PropTypes.object.isRequired
};

function mapStateToProps(state, ownProps) {
  return {
    MappingData: state.V950MappingData
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({push}, maintFailureActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Failure);
