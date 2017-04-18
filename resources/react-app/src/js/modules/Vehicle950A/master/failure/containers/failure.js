import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
// Actions
import { push } from 'react-router-redux';
import { maintFailureActions } from '../ducks/maintFailure';
// Styles
import './failure.scss';
// Components
// import Loading from '../../../components/loading/loading';
// import RangeCalendar from '../components/rangeCalendar/rangeCalendar';
// import Mapping from '../../mapping/containers/mapping';

class Failure extends Component {
  constructor(props, context) {
    super(props, context);
    const { Inspections, MappingData, actions } = props;

    let name = '';
    let inspection = 'all';

    actions.requestFailures(name, Inspections.map(i => i.en), division);

    this.state = {
      name: name,
      inspection: {label: '全て', value: inspection},
      editModal: false,
      editting: null,
      createModal: false,
      sort: {
        key: 'label',
        asc: false,
        id: 0
      }
    };  }

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
        <div className="refine-wrap bg-white">
          <div className="refine">
            <div className="name">
              <p>不良名</p>
              <input
                type="text"
                value={this.state.name}
                onChange={e => this.setState(
                  {name: e.target.value},
                  () => this.requestFailure()
                )}
              />
            </div>
            <div className="inspection">
              <p>検査</p>
              <Select
                name="検査"
                placeholder="検査を選択"
                styles={{height: 30}}
                clearable={false}
                Searchable={true}
                value={this.state.inspection}
                options={[
                  {label: '全て', value: 'all'},
                  {label: '成形工程 外観検査', value: 1},
                  {label: '穴あけ工程 外観検査', value: 10},
                  {label: '穴あけ工程 穴検査', value: 3},
                  {label: '接着工程 簡易CF', value: 11},
                  {label: '接着工程 止水', value: 5},
                  {label: '接着工程 仕上', value: 6},
                  {label: '接着工程 検査', value: 7},
                  {label: '接着工程 手直', value: 9}
                ]}
                onChange={value => this.setState(
                  {inspection: value},
                  () => this.requestFailure()
                )}
              />
            </div>
            <div className="status">
              <p>状態</p>
              <Select
                name="状態"
                placeholder="状態を選択"
                styles={{height: 30}}
                clearable={false}
                Searchable={true}
                value={this.state.status}
                options={[
                  {label: '全て', value: [0,1]},
                  {label: '非表示中', value: [0]},
                  {label: '表示中', value: [1]},
                ]}
                onChange={value => this.setState(
                  {status: value},
                  () => this.requestFailure()
                )}
              />
            </div>
          </div>
        </div>
      </div>
    );
  }
}

Failure.propTypes = {
  MappingData: PropTypes.object.isRequired
};

function mapStateToProps(state, ownProps) {
  return {
    Inspections: state.Application.vehicle950A.inspections,
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
