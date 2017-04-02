import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
import { vehicles, parts, processes, inspections, inspectionGroups } from '../../../utils/Processes';
import { downloadCsv, handleDownload } from '../../../utils/Export';
// Actions
import { push } from 'react-router-redux';
import { serchActions } from '../ducks/serch';
import { failureActions } from '../ducks/failure';
import { modificationActions } from '../ducks/modification';
// Material-ui Components
import { Paper, Dialog, RaisedButton, FlatButton } from 'material-ui';
import { grey50, indigo500 } from 'material-ui/styles/colors';
// Styles
import './reference.scss';
// Components
import Loading from '../../../components/loading/loading';
import CustomCalendar from '../components/customCalendar/customCalendar';
import CustomTable from '../components/customTable/customTable';

class Reference extends Component {
  constructor(props, context) {
    super(props, context);

    this.state = {
      vehicle: { label: '680A', value: '680A' },
      partTId: null,
      processId: null,
      inspectionId: null,
      itorG: {label: '全直', value: 'both'},
      judgement: {label: '両方', value: 'both'},
      RequiredF: [],
      RequiredM: [],
      RequiredHM: [],
      narrowedBy: 'advanced',
      startDate: moment(),
      endDate: moment(),
      panelId: ''
    }
  }

  getInspectionGroup() {
    const { vehicle, partTId, processId, inspectionId } = this.state;

    const filteredInspectionGroup = inspectionGroups.filter(ig =>
      ig.vehicle == vehicle.value &&
      (partTId ? (ig.part == partTId.value) : false) &&
      (processId ? (ig.p == processId.value) : false) &&
      (inspectionId ? (ig.i == inspectionId.value) : false) &&
      !ig.disabled
    );

    let inspectionGroupId = 0;
    if (filteredInspectionGroup.length > 0) {
      inspectionGroupId = filteredInspectionGroup[0].iG;
    }

    return inspectionGroupId;
  }

  render() {
    const { SerchedData, FailureData, ModificationData, actions } = this.props;
    const {
      vehicle, partTId, processId, inspectionId,
      itorG, judgement, RequiredF, RequiredM, RequiredHM,
      narrowedBy, startDate, endDate, panelId
    } = this.state;

    let table = [];
    if (SerchedData.data != null && !SerchedData.isFetching) {
      let header = ['車種','品番','品名','パネルID','直','検査者','更新者','判定'];
      if (SerchedData.data.h.length > 0) {
        SerchedData.data.h.forEach(h => {
          header.push(String(h.label));
        })
      }
      if (SerchedData.data.hm.length > 0) {
        SerchedData.data.hm.forEach(hm => {
          header.push(hm.name);
        })
      }
      if (SerchedData.data.f.length > 0) {
        SerchedData.data.f.forEach(f => {
          header.push(f.name);
        })
      }
      if (SerchedData.data.m.length > 0) {
        SerchedData.data.m.forEach(m => {
          header.push(m.name);
        })
      }
      if (SerchedData.data.i.length > 0) {
        SerchedData.data.i.forEach(i => {
          header.push(String(i.sort));
        })
      }

      header.push('コメント');
      header.push('検査日');
      header.push('更新日');

      table.push(header);

      let rows = SerchedData.data.parts.map((p,i) => {
        let status = p.status == 1 ? '○' : '×';
        let result = [p.vehicle, String(p.pn), p.name, p.panelId, p.tyoku, p.createdBy, p.updatedBy, status];

        if (SerchedData.data.h.length > 0) {
          SerchedData.data.h.forEach(h => {
            let status = p.holes.find(hole => hole.id == h.id).status;
            if (status == 0) {status = '×'}
            if (status == 1) {status = '○'}
            if (status == 2) {status = '△'}
            result.push(status);
          })
        }

        if (SerchedData.data.hm.length > 0) {
          SerchedData.data.hm.forEach(hm => {
            let sum = p.hModifications[hm.id] ? p.failures[hm.id] : 0;
            result.push(String(sum));
          })
        }

        if (SerchedData.data.f.length > 0) {
          SerchedData.data.f.forEach(f => {
            let sum = p.failures[f.id] ? p.failures[f.id] : 0;
            result.push(String(sum));
          })
        }

        if (SerchedData.data.m.length > 0) {
          SerchedData.data.m.forEach(m => {
            let sum = p.modifications[m.id] ? p.modifications[m.id] : 0;
            result.push(String(sum));
          })
        }

        if (SerchedData.data.i.length > 0) {
          SerchedData.data.i.forEach(i => {
            const value = String(p.inlines[i.id].status);
            // let value = p.inlines[i.id].status;
            // let status = '○';
            // if ( value.status > value.max || value.status < value.min ) {status = '×';}
            // result.push(status);
            result.push(value);
          })
        }

        result.push(p.comment ? p.comment : '');
        result.push(p.inspectedAt ? p.inspectedAt : p.createdAt);
        result.push(p.inspectedAt ? p.inspectedAt : p.updatedAt);

        return result;
      });

      table = table.concat(rows);
    };

    const filteredProcess = inspectionGroups.filter(ig =>
      ig.vehicle == vehicle.value &&
      (partTId ? (ig.part == partTId.value) : false) &&
      !ig.disabled
    ).map(ig =>
      ig.p
    ).filter((x, i, self) =>
      self.indexOf(x) === i
    );

    const filteredInspection = inspectionGroups.filter(ig =>
      ig.vehicle == vehicle.value &&
      (partTId ? (ig.part == partTId.value) : false) &&
      (processId ? (ig.p == processId.value) : false) &&
      !ig.disabled
    ).map(ig =>
      ig.i
    ).filter((x, i, self) =>
      self.indexOf(x) === i
    );

    const filteredInspectionGroup = inspectionGroups.filter(ig =>
      ig.vehicle == vehicle.value &&
      (partTId ? (ig.part == partTId.value) : false) &&
      (processId ? (ig.p == processId.value) : false) &&
      (inspectionId ? (ig.i == inspectionId.value) : false) &&
      !ig.disabled
    );

    let inspectionGroupId = 0;
    if (filteredInspectionGroup.length > 0) {
      inspectionGroupId = filteredInspectionGroup[0].iG;
    }

    return (
      <div id="referenceWrap">
        <div className="serch-wrap bg-white">
          <div className="serch-area">
            <div className="flex-row col-1">
              <div className="part-wrap">
                <p>部品</p>
                <Select
                  name="部品"
                  styles={{height: 30}}
                  placeholder={vehicle == null ? '先に車種を選択' :'部品を選択'}
                  disabled={vehicle == null}
                  clearable={false}
                  Searchable={false}
                  scrollMenuIntoView={false}
                  value={this.state.partTId}
                  options={parts}
                  onChange={value => this.setState({partTId: value})}
                />
              </div>
              <div className="process-wrap">
                <p>工程</p>
                <Select
                  name="工程"
                  styles={{height: 30}}
                  placeholder={partTId == null ? '先に部品を選択' :'工程を選択'}
                  disabled={partTId == null}
                  clearable={false}
                  Searchable={true}
                  value={processId}
                  options={processes.filter(p => filteredProcess.indexOf(p.value) >= 0)}
                  onChange={value => this.setState({processId: value})}
                />
              </div>
              <div className="inspection-wrap">
                <p>検査</p>
                <Select
                  name="検査"
                  styles={{height: 30}}
                  placeholder={processId == null ? '先に工程を選択' :'検査を選択'}
                  disabled={processId == null}
                  clearable={false}
                  Searchable={true}
                  value={inspectionId}
                  options={inspections.filter(i => filteredInspection.indexOf(i.value) >= 0)}
                  onChange={value => {
                    this.setState({inspectionId: value}, () => {
                      const id = this.getInspectionGroup();
                      console.log(id)
                      if (id != 0) {
                        actions.getFailures(id);
                        actions.getModifications(id);
                      }
                    });
                  }}
                />
              </div>
            </div>
            <div className="flex-row col-2">
              <div
                className={narrowedBy === 'advanced' ? "advanced-wrap active" : "advanced-wrap"}
                onClick={() => this.setState({narrowedBy: 'advanced'})}
              >
                <div
                  className={narrowedBy === 'term' ? 'term-wrap active' : 'term-wrap'}
                  onClick={() => this.setState({narrowedBy: 'term'})}
                >
                  <div className="row-1">
                    <div>
                      <p>直*</p>
                      <Select
                        name="直"
                        placeholder="直を選択"
                        clearable={false}
                        Searchable={true}
                        value={itorG}
                        options={[
                          {label: '黄直', value: 'Y'},
                          {label: '白直', value: 'W'},
                          {label: '黒直', value: 'B'},
                          {label: '全直', value: 'both'}
                        ]}
                        onChange={value => this.setState({itorG: value})}
                      />
                    </div>
                    <div>
                      <p>判定*</p>
                      <Select
                        name="判定"
                        placeholder="直を選択"
                        clearable={false}
                        Searchable={true}
                        value={judgement}
                        options={[
                          {label: '○', value: 1},
                          {label: '×', value: 0},
                          {label: '両方', value: 'both'}
                        ]}
                        onChange={value => this.setState({judgement: value})}
                      />
                    </div>
                    <div>
                      <p>期間*</p>
                      <CustomCalendar
                        defaultValue={startDate}
                        setState={startDate => this.setState({
                          startDate: startDate
                        })}
                        disabled={narrowedBy == 'advanced' ? false : true}
                      />
                      <p>〜</p>
                      <CustomCalendar
                        defaultValue={endDate}
                        setState={endDate => this.setState({
                          endDate: endDate
                        })}
                        disabled={narrowedBy == 'advanced' ? false : true}
                      />
                    </div>
                  </div>
                  <div className="row-2">
                    <div>
                      <p>不良</p>
                      <Select
                        name="直"
                        placeholder="不良を選択"
                        clearable={false}
                        Searchable={false}
                        multi={true}
                        value={RequiredF}
                        options={FailureData.data ? FailureData.data.map(f => {return {label: f.name, value: f.id};}) : []}
                        onChange={value => {
                          this.setState({RequiredF: value, RequiredM: []});
                        }}
                      />
                    </div>
                    <div>
                      <p>手直</p>
                      <Select
                        name="手直"
                        placeholder="手直を選択"
                        clearable={false}
                        Searchable={false}
                        multi={true}
                        value={RequiredM}
                        options={ModificationData.data ? ModificationData.data.map(m => {return {label: m.name, value: m.id};}) : []}
                        onChange={value => {
                          this.setState({RequiredM: value, RequiredF: []});
                        }}
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div
                className={narrowedBy === 'panelId' ? "panel-id-wrap active" : "panel-id-wrap"}
                onClick={() => this.setState({narrowedBy: 'panelId'})}
              >
                <p>パネルID指定</p>
                <input
                  type="text"
                  value={panelId}
                  onChange={(e) => this.setState({panelId: e.target.value})}
                />
              </div>
            </div>
          </div>
          <button
            className={`serch dark ${!(partTId && inspectionId && itorG && inspectionGroupId != 0) && 'disabled'}`}
            onClick={() => {
              if (inspectionGroupId != 0) {
                if (narrowedBy == 'panelId') {
                  actions.panelIdSerch(partTId.value, inspectionGroupId, panelId);
                }
                else if (narrowedBy == 'advanced') {
                  const format = 'YYYY/MM/DD';
                  const body = {
                    'tyoku': itorG.value == 'both' ? ['白直', '黄直', '黒直'] : [itorG.label],
                    'judgement': judgement.value == 'both' ? [1, 0] : [judgement.value],
                    'start': startDate.format(format),
                    'end': endDate.format(format),
                    'f': RequiredF.map(rf => rf.value),
                    'm': RequiredM.map(rm => rm.value)
                  };
                  actions.advancedSerch(partTId.value, inspectionGroupId, body);
                }
              }
            }}
          >
            <p>この条件で検索</p>
          </button>
        </div>
        <div className="result-wrap bg-white">
          {
            SerchedData.isFetching &&
            <p>検索中...</p>
          }{
            SerchedData.data != null && !SerchedData.isFetching &&
            <CustomTable
              igId={SerchedData.data.igId}
              count={SerchedData.data.count}
              data={SerchedData.data.parts}
              failures={SerchedData.data.f}
              holes={SerchedData.data.h}
              modifications={SerchedData.data.m}
              hModifications={SerchedData.data.hm}
              inlines={SerchedData.data.i}
              download={() => handleDownload(table)}
            />
          }{
            inspectionGroupId == 0 &&　inspectionId != null &&
            <p>検索条件が間違っています</p>
          }
        </div>
      </div>
    );
  }
}

Reference.propTypes = {
  // id: PropTypes.string.isRequired,
  // application: PropTypes.object.isRequired,
  // conference: PropTypes.object.isRequired,
};

function mapStateToProps(state, ownProps) {
  return {
    SerchedData: state.SerchedData,
    FailureData: state.FailureData,
    ModificationData: state.ModificationData
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({push},
    serchActions,
    failureActions,
    modificationActions
  );

  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Reference);
