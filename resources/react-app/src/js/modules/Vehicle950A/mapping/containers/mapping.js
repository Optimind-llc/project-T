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
import './mapping.scss';
// Components
import CustomCalendar from '../components/calendar/calendar';
import MappingBody from '../components/body/mappingBody';

import SearchButton from '../../../../components/buttons/search/searchButton';
import SaveButton from '../../../../components/buttons/save/saveButton';
// import Loading from '../../../components/loading/loading';
// import RangeCalendar from '../components/rangeCalendar/rangeCalendar';

class Mapping extends Component {
  constructor(props, context) {
    super(props, context);
    const { InitialData, MappingData, actions } = props;

    this.state = {
      p: {label: '成型', value: 'molding'},
      i: {label: '外観検査', value: 'gaikan'},
      pt: {label: 'ドアインナL', value: 6714211020},
      narrowedBy: 'realtime',
      choku: {label: '白直', value: ['W']},
      startDate: moment(),
      endDate: moment(),
      panelId: '',
      intervalId: null,
      interval: 30000,
    };
  }

  componentWillUnmount() {
    clearInterval(this.state.intervalId);
    this.props.actions.clearMappingData();
  }

  startInterval() {    
    const intervalId = setInterval(
      () => this.requestMappingData(),
      this.state.interval
    );

    this.setState({intervalId});
  }

  endInterval() {
    clearInterval(this.state.intervalId);
  }

  requestMappingData() {
    const { p, i, pt, narrowedBy, choku, startDate, endDate, panelId } = this.state;
    const { actions } = this.props;

    const format = 'YYYY-MM-DD';

    if (narrowedBy === 'realtime') {
      actions.getMappingDataRealtime(
        p.value,
        i.value,
        pt.value
      );
    }
    else if (narrowedBy === 'date') {
      actions.getMappingDataByDate(
        p.value,
        i.value,
        pt.value,
        choku.value,
        startDate.format(format),
        endDate.format(format)
      );
    }
    else if (narrowedBy === 'panelId') {
      actions.getMappingDataByPanelId(
        p.value,
        i.value,
        pt.value,
        panelId
      );
    }
  }

  render() {
    const { p, i, pt, narrowedBy, choku, startDate, endDate, panelId, intervalId } = this.state;
    const { InitialData, MappingData, actions } = this.props;

    const filterdI = InitialData.combination.filter(c => 
      c.process === p.value
    ).map(c => 
      c.inspection
    ).filter((c, i, self) =>
      self.indexOf(c) === i
    );

    const filterdPt = InitialData.combination.filter(c => 
      c.process === p.value && c.inspection === i.value
    ).map(c => 
      c.part
    ).filter((c, i, self) =>
      self.indexOf(c) === i
    );

    const processes = InitialData.processes.map(p => { return {label: p.name, value: p.en} });

    const inspections = InitialData.inspections.filter(i =>
      filterdI.indexOf(i.en) >= 0
    ).map(i => { return {label: i.name, value: i.en} });

    const partTypes = InitialData.partTypes.filter(pt =>
      filterdPt.indexOf(pt.en) >= 0
    ).map(pt => { return {label: pt.name, value: pt.pn} });

    let defaultActiveTab = 'failure';
    // if (MappingData.data !== null && MappingData.data.holeModificationTypes.length > 0) {
    //   defaultActiveTab = 'hole';
    // }
    // else if (MappingData.data !== null && MappingData.data.inlineTypes.length > 0) {
    //   defaultActiveTab = 'inline';
    // }

    return (
      <div id="mapping-950A-wrap">
        <div className="bg-white mapping-header">
          <div className="select-wrap">
            <div className="row">
              <p>工程*</p>
              <Select
                name="ライン"
                placeholder="全てのライン"
                clearable={false}
                Searchable={true}
                value={p}
                options={processes}
                onChange={p => this.setState({p})}
              />
              <p>検査*</p>
              <Select
                name="車種"
                placeholder="全ての車種"
                clearable={false}
                Searchable={true}
                value={i}
                options={inspections}
                onChange={i => this.setState({i})}
              />
              <p>部品*</p>
              <Select
                name="品番"
                placeholder="品番を選択"
                clearable={false}
                Searchable={true}
                value={pt}
                options={partTypes}
                onChange={pt => this.setState({pt})}
              />
            </div>
            <div className="row">
              <div
                className={`row selectable ${narrowedBy === 'realtime' ? 'selected' : ''}`}
                onClick={() => this.setState({narrowedBy: 'realtime'})}
              >
                <p className="realtime">リアルタイム</p>
              </div>
              <div
                className={`row selectable ${narrowedBy === 'date' ? 'selected' : ''}`}
                onClick={() => this.setState({narrowedBy: 'date'})}
              >
                <p>直</p>
                <Select
                  name="直"
                  className="width140"
                  placeholder="直を選択"
                  clearable={false}
                  Searchable={true}
                  value={choku}
                  options={[
                    {label: '白直', value: ['W']},
                    {label: '黄直', value: ['Y']},
                    {label: '黒直', value: ['B'], disabled: true},
                    {label: '全直', value: ['W', 'Y']}
                  ]}
                  onChange={value => this.setState({choku: value})}
                />
                <p>期間</p>
                <CustomCalendar
                  defaultDate={startDate}
                  changeDate={d => this.setState({startDate: d})}
                  disabled={false}
                />
                <p>〜</p>
                <CustomCalendar
                  defaultDate={endDate}
                  changeDate={d => this.setState({endDate: d})}
                  disabled={false}
                />
              </div>
              <div
                className={`row selectable ${narrowedBy === 'panelId' ? 'selected' : ''}`}
                onClick={() => this.setState({narrowedBy: 'panelId'})}
              >
                <p>パネルID</p>
                <input
                  type="text"
                  value={panelId}
                  style={{width: 140}}
                  onChange={e => this.setState({panelId: e.target.value})}
                />
              </div>
            </div>
          </div>
          <button
            className={`show ${pt === null ? 'disabled' : ''}`}
            onClick={() => {
              this.requestMappingData();
              clearInterval(intervalId);

              if (narrowedBy === 'realtime') {
                this.startInterval();
              }
            }}
          >
            表示
          </button>
        </div>
        {
          MappingData.data !== null &&
          <MappingBody
            data={MappingData.data}
            isFetching={MappingData.isFetching}
            didInvalidate={MappingData.didInvalidate}
            narrowedBy={narrowedBy}
            defaultActive={defaultActiveTab}
          />
        }
      </div>
    );
  }
}

Mapping.propTypes = {
  InitialData: PropTypes.object.isRequired,
  MappingData: PropTypes.object.isRequired
};

function mapStateToProps(state, ownProps) {
  return {
    InitialData: state.Application.vehicle950A,
    MappingData: state.MappingData950A
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({push}, pageActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Mapping);
