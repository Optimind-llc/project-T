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
import './reference.scss';
// Components
import CustomCalendar from '../components/calendar/calendar';
// import Loading from '../../../components/loading/loading';
// import RangeCalendar from '../components/rangeCalendar/rangeCalendar';
// import Mapping from '../../mapping/containers/mapping';

class Reference extends Component {
  constructor(props, context) {
    super(props, context);
    const { MappingData, actions } = props;

    this.state = {
      p: {label: '成型', value: 'molding'},
      i: {label: '外観検査', value: 'gaikan'},
      pt: {label: 'ドアインナL', value: 6714211020},
      narrowedBy: 'advanced',
      choku: {label: '全直', value: ['W','Y','B']},
      judge: {label: '両方', value: [0, 1, 2]},
      startDate: moment(),
      endDate: moment(),
      requiredF: [],
      requiredM: [],
      panelId: '',
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
    const { p, i, pt, narrowedBy, choku, judge, startDate, endDate, requiredF, requiredM, panelId } = this.state;
    const { InitialData, actions } = this.props;

    const processes = InitialData.processes.map(p => { return {label: p.name, value: p.en} });

    const filterdI = InitialData.combination2.filter(c => 
      c.process === p.value
    ).map(c => 
      c.inspection
    ).filter((c, i, self) =>
      self.indexOf(c) === i
    );

    const inspections = InitialData.inspections.filter(i =>
      filterdI.indexOf(i.en) >= 0
    ).map(i => { return {label: i.name, value: i.en} });

    const partTypes = InitialData.combination2.filter(c => 
      c.process === p.value && c.inspection === i.value
    ).map(c => {
      return { label: c.label, value: c.parts };
    });

    const chokus = InitialData.chokus.slice().reverse().reduce((pre, cur, i, self) => {
      pre.unshift({ label: cur.name, value: [cur.code], disabled: cur.status === 0 });
      return pre;
    }, [{ label: '全直', value: ['W','Y','B'] }]);

    const failureTypes = InitialData.failureTypes.map(ft => {
      return {label: ft.name, value: ft.id};
    });
    const modificationTypes = InitialData.modificationTypes.map(mt => {
      return {label: mt.name, value: mt.id};
    });

    const SerchedData = {};

    return (
      <div id="reference-950A-wrap">
        <div className="bg-white reference-header">
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
                className={`column selectable ${narrowedBy === 'advanced' ? 'selected' : ''}`}
                onClick={() => this.setState({narrowedBy: 'advanced'})}
              >
                <div className="row">
                  <p>直*</p>
                  <Select
                    name="直"
                    className="width140"
                    placeholder="直を選択"
                    clearable={false}
                    Searchable={true}
                    value={choku}
                    options={chokus}
                    onChange={choku => this.setState({choku})}
                  />
                  <p>判定*</p>
                  <Select
                    name="判定"
                    placeholder="判定を選択"
                    className="width140"
                    clearable={false}
                    Searchable={true}
                    value={judge}
                    options={[
                      {label: '○', value: [1]},
                      {label: '×', value: [0]},
                      {label: '△', value: [2]},
                      {label: '両方', value: [0,1,2]}
                    ]}
                    onChange={judge => this.setState({judge})}
                  />
                  <p>期間*</p>
                  <CustomCalendar
                    defaultDate={startDate}
                    changeDate={startDate => this.setState({startDate})}
                    disabled={false}
                  />
                  <p>〜</p>
                  <CustomCalendar
                    defaultDate={endDate}
                    changeDate={endDate => this.setState({endDate})}
                    disabled={false}
                  />
                </div>
                <div className="row">
                  <p>不良</p>
                  <Select
                    name="不良"
                    placeholder="不良区分を選択"
                    className="width454"
                    clearable={true}
                    Searchable={false}
                    multi={true}
                    value={requiredF}
                    options={failureTypes}
                    onChange={f => {
                      this.setState({requiredF: f, requiredM: []});
                    }}
                  />
                  <p>手直</p>
                  <Select
                    name="手直"
                    placeholder="手直区分を選択"
                    className="width454"
                    clearable={false}
                    Searchable={false}
                    multi={true}
                    value={requiredM}
                    options={modificationTypes}
                    onChange={m => {
                      this.setState({requiredF: [], requiredM: m});
                    }}
                  />
                </div>
              </div>
              <div
                className={`column selectable ${narrowedBy === 'panelId' ? 'selected' : ''}`}
                onClick={() => this.setState({narrowedBy: 'panelId'})}
              >
                <p>パネルID</p>
                <input
                  type="text"
                  value={panelId}
                  onChange={e => this.setState({panelId: e.target.value})}
                />
              </div>
            </div>
          </div>
          <button
            className="serch dark"
            onClick={() => this.serch()}
          >
            <p>この条件で検索</p>
          </button>
        </div>
        <div className="bg-white reference-result">
          {
            SerchedData.isFetching &&
            <p>検索中...</p>
          }{
            SerchedData.data != null && !SerchedData.isFetching &&
            <CustomTable
              count={SerchedData.data.count}
              data={SerchedData.data.parts}
              failures={SerchedData.data.f}
              holes={SerchedData.data.h}
              modifications={SerchedData.data.m}
              hModifications={SerchedData.data.hm}
              inlines={SerchedData.data.i}
              download={() => handleDownload(table)}
            />
          }
        </div>

      </div>
    );
  }
}

Reference.propTypes = {
  InitialData: PropTypes.object.isRequired,
};

function mapStateToProps(state, ownProps) {
  return {
    InitialData: state.Application.vehicle950A,
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({push}, pageActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Reference);
