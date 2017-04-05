import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
import { handleDownload } from '../../../../utils/Export';
import { vehicles, processes, inspections, inspectionGroups } from '../../../../utils/Processes';

// Actions
import { push } from 'react-router-redux';
import { referenceActions } from '../ducks/reference';
// Styles
import './reference.scss';
// Components
import CustomCalendar from '../components/calendar/calendar';
import ReferenceBody from '../components/referenceBody/referenceBody';
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
      choku: {label: '全直', value: ['W','Y','B','NA']},
      judge: {label: '両方', value: [0, 1, 2]},
      startDate: moment(),
      endDate: moment(),
      requiredF: [],
      requiredM: [],
      panelId: '',
    };
  }

  componentWillUnmount() {
    this.props.actions.clearReferenceData();
  }

  handleDownload() {
    const { ReferenceData } = this.props;

    let table = [];
    if (ReferenceData.data != null && !ReferenceData.isFetching) {
      let header = ['車種','品番','品名','パネルID','直','検査者','更新者','判定'];

      ReferenceData.data.hts.forEach(ht => {
        header.push(String(ht.label));
      });
      ReferenceData.data.hmts.forEach(hmt => {
        header.push(hmt.name);
      });
      ReferenceData.data.fts.forEach(ft => {
        header.push(ft.name);
      });
      ReferenceData.data.mts.forEach(mt => {
        header.push(mt.name);
      });
      ReferenceData.data.its.forEach(it => {
        header.push(String(it.label));
      });

      header.push('コメント');
      header.push('検査日');
      header.push('更新日');

      table.push(header);

      let rows = ReferenceData.data.results.map(r => {
        const status = r.status == 1 ? '○' : '×';
        const uBy = r.uBy ? r.uBy : '';
        let result = [r.v, String(r.pn), r.name, r.panelId, r.choku, r.cBy, uBy, status];

        ReferenceData.data.hts.forEach(ht => {
          let hStatus = '-';
          if (r.hs[ht.id] === 0) {hStatus = '×'}
          if (r.hs[ht.id] === 1) {hStatus = '○'}
          if (r.hs[ht.id] === 2) {hStatus = '△'}

          result.push(hStatus);
        });

        ReferenceData.data.hmts.forEach(hmt => {
          let hmSum = r.hms[hmt.id] ? p.failures[hm.id] : 0;
          result.push(String(hmSum));
        });

        ReferenceData.data.fts.forEach(ft => {
          let fSum = r.fs[ft.id] ? r.fs[ft.id] : 0;
          result.push(String(fSum));
        });

        ReferenceData.data.mts.forEach(mt => {
          let mSum = r.ms[mt.id] ? r.ms[mt.id] : 0;
          result.push(String(mSum));
        });

        ReferenceData.data.its.forEach(it => {
          result.push(String(r.is[it.id]));
        });

        result.push(r.comment ? r.comment : '');
        result.push(r.iAt);
        result.push(r.uAt);

        return result;
      });

      table = table.concat(rows);
    };

    handleDownload(table);
  }

  search() {
    const { advancedSearch, panelIdSearch } = this.props.actions;
    const { p, i, pt, narrowedBy, choku, judge, startDate, endDate, requiredF, requiredM, panelId } = this.state;
    const format = 'YYYY-MM-DD';

    const take = 1000;
    const skip = 0;

    if (narrowedBy === 'advanced') {
      advancedSearch(
        p.value,
        i.value,
        pt.value,
        choku.value,
        judge.value,
        startDate.format(format),
        endDate.format(format),
        requiredF.map(f => f.value),
        requiredM.map(m => m.value),
        take,
        skip
      );
    }
    else if (narrowedBy === 'panelId') {
      panelIdSearch(
        p.value,
        i.value,
        pt.value,
        panelId,
        take,
        skip
      );
    }
  }

  additionalSearch(skip) {
    const { advancedAdditionalSearch, panelIdSearch } = this.props.actions;
    const { p, i, pt, narrowedBy, choku, judge, startDate, endDate, requiredF, requiredM, panelId } = this.state;
    const format = 'YYYY-MM-DD';
    const take = 100;

    if (narrowedBy === 'advanced') {
      advancedAdditionalSearch(
        p.value,
        i.value,
        pt.value,
        choku.value,
        judge.value,
        startDate.format(format),
        endDate.format(format),
        requiredF.map(f => f.value),
        requiredM.map(m => m.value),
        take,
        skip
      );
    }
    else if (narrowedBy === 'panelId') {
      panelIdSearch(
        p.value,
        i.value,
        pt.value,
        panelId,
        take,
        skip
      );
    }
  }

  render() {
    const { InitialData, ReferenceData, actions } = this.props;
    const { p, i, pt, narrowedBy, choku, judge, startDate, endDate, requiredF, requiredM, panelId } = this.state;

    const processes = InitialData.processes.map(p => { return {label: p.name, value: p.en} });

    const filterdI = InitialData.combination.filter(c => 
      c.process === p.value
    ).map(c => 
      c.inspection
    ).filter((c, i, self) =>
      self.indexOf(c) === i
    );

    const inspections = InitialData.inspections.filter(i =>
      filterdI.indexOf(i.en) >= 0
    ).map(i => { return {label: i.name, value: i.en} });

    const partTypes = InitialData.combination.filter(c => 
      c.process === p.value && c.inspection === i.value
    ).map(c => {
      return { label: c.partName, value: c.pn };
    });

    const chokus = InitialData.chokus.slice().reverse().reduce((pre, cur, i, self) => {
      pre.unshift({ label: cur.name, value: [cur.code], disabled: cur.status === 0 });
      return pre;
    }, [{ label: '全直', value: ['W','Y','B','NA'] }]);

    const failureTypes = InitialData.failureTypes.map(ft => {
      return {label: ft.name, value: ft.id};
    });
    const modificationTypes = InitialData.modificationTypes.map(mt => {
      return {label: mt.name, value: mt.id};
    });

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
                    placeholder="絞り込む不良区分を選択"
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
                    placeholder="絞り込む手直区分を選択"
                    className="width454"
                    clearable={true}
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
                <p>パネルID<span className="small">4文字以上</span></p>
                <input
                  className="panelId"
                  type="text"
                  value={panelId}
                  onChange={e => this.setState({panelId: e.target.value.replace(/[^A-Za-z0-9]+/i,'').toUpperCase().substr(0,8)})}
                />
              </div>
            </div>
          </div>
          <button
            className={`serch dark ${narrowedBy === 'panelId' && (panelId.length < 4 || panelId.length > 8) ? 'disabled' : ''}`}
            onClick={() => this.search()}
          >
            <p>この条件で検索</p>
          </button>
        </div>
        <div className="bg-white reference-result">
          {
            ReferenceData.isFetching && 
            <p className="message">検索中...</p>
          }{
            !ReferenceData.isFetching && ReferenceData.data != null && ReferenceData.data.results.length === 0 &&
            <p className="message">検索結果なし</p>
          }{
            !ReferenceData.isFetching && ReferenceData.data != null && ReferenceData.data.results.length > 0 &&
            <ReferenceBody
              count={ReferenceData.data.count}
              results={ReferenceData.data.results}
              fts={ReferenceData.data.fts}
              mts={ReferenceData.data.mts}
              hts={ReferenceData.data.hts}
              hmts={ReferenceData.data.hmts}
              its={ReferenceData.data.its}
              download={() => this.handleDownload()}
              additionalSearch={(skip) => this.additionalSearch(skip)}
            />
          }
        </div>
      </div>
    );
  }
}

Reference.propTypes = {
  InitialData: PropTypes.object.isRequired,
  ReferenceData: PropTypes.object.isRequired,
};

function mapStateToProps(state, ownProps) {
  return {
    InitialData: state.Application.vehicle950A,
    ReferenceData: state.ReferenceData950A,
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({push}, referenceActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Reference);
