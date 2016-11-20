import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
import { parts, processes, inspections } from '../../../utils/Processes';
import { downloadCsv } from '../../../utils/Export';
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
      vehicle: {value: '680A', label: '680A'},
      partTId: null,
      processId: null,
      itionGId: null,
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

  render() {
    const { SerchedData, FailureData, ModificationData, actions } = this.props;
    const {
      vehicle, partTId, processId, itionGId,
      itorG, judgement, RequiredF, RequiredM, RequiredHM,
      narrowedBy, startDate, endDate, panelId
    } = this.state;

    let table = [];
    if (SerchedData.data != null && !SerchedData.isFetching) {
      let header = ['No.','車種','品番','品名','パネルID','直','検査者','更新者','判定'];
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
      header.push('コメント');
      header.push('検査日');
      header.push('更新日');

      table.push(header);

      let rows = SerchedData.data.parts.map((p,i) => {
        let status = p.status == 1 ? '○' : '×';
        let result = [String(i), p.vehicle, String(p.pn), p.name, p.panelId, p.tyoku, p.createdBy, p.updatedBy, status];

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

        result.push(p.comment);
        result.push(p.createdAt);
        result.push(p.updatedAt);

        return result;
      });

      table = table.concat(rows);
    };

console.log(table);
    return (
      <div id="referenceWrap">
        <div className="serch-wrap bg-white">
          <div className="serch-area">
            <div className="flex-row col-1">
              <div>
                <p>車種*</p>
                <Select
                  name="車種"
                  placeholder="車種を選択"
                  styles={{height: 36}}
                  clearable={false}
                  Searchable={true}
                  value={this.state.vehicle}
                  options={[
                    {label: '680A', value: '680A'},
                    {label: '950A', value: '950A', disabled:true}
                  ]}
                  onChange={value => this.setState({vehicle: value})}
                />
              </div>
              <div>
                <p>部品*</p>
                <Select
                  name="部品"
                  styles={{height: 36}}
                  placeholder={vehicle == null ? '先に車種を選択' :'部品を選択'}
                  disabled={vehicle == null}
                  clearable={false}
                  Searchable={false}
                  scrollMenuIntoView={false}
                  value={this.state.partTId}
                  options={parts}
                  onChange={value => this.setState({
                    partTId: value,
                    processId: null,
                    itionGId: null
                  })}
                />
              </div>
              <div>
                <p>工程*</p>
                <Select
                  name="工程"
                  styles={{height: 36}}
                  placeholder={partTId == null ? '先に部品を選択' :'工程を選択'}
                  disabled={partTId == null}
                  clearable={false}
                  Searchable={true}
                  value={processId}
                  options={partTId ? processes[partTId.value] : null}
                  onChange={value => this.setState({processId: value})}
                />
              </div>
              <div>
                <p>検査*</p>
                <Select
                  name="検査"
                  styles={{height: 36}}
                  placeholder={processId == null ? '先に工程を選択' :'検査を選択'}
                  disabled={processId == null}
                  clearable={false}
                  Searchable={true}
                  value={itionGId}
                  options={processId ? inspections[processId.value] : null}
                  onChange={value => {
                    actions.getFailures(value.value);
                    actions.getModifications(value.value);
                    this.setState({itionGId: value});
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
                          {label: '黒直', value: 'B', disabled: true},
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
                      />
                      <p>〜</p>
                      <CustomCalendar
                        defaultValue={endDate}
                        setState={endDate => this.setState({
                          endDate: endDate
                        })}
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
                    {/*
                    <div>
                      <p>穴手直</p>
                      <Select
                        name="穴手直"
                        placeholder="穴手直を選択"
                        clearable={false}
                        Searchable={false}
                        multi={true}
                        value={RequiredHM}
                        options={hModifications}
                        onChange={value => {console.log(value); this.setState({RequiredHM: value});}}
                      />
                    </div>
                    */}
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
          <div
            className={`serch-btn ${partTId && itionGId && itorG && 'active'}`}
            onClick={() => {
              if (narrowedBy == 'panelId') {
                actions.panelIdSerch(partTId.value, itionGId.value, panelId);
              }
              else if (narrowedBy == 'advanced') {
                console.log(judgement);
                const format = 'YYYY/MM/DD';
                const body = {
                  'tyoku': itorG.value == 'both' ? ['白直', '黄直', '黒直', '不明'] : [itorG.label],
                  'judgement': judgement.value == 'both' ? [1, 0] : [judgement.value],
                  'start': startDate.format(format),
                  'end': endDate.format(format),
                  'f': RequiredF.map(rf => rf.value),
                  'm': RequiredM.map(rm => rm.value)
                };
                actions.advancedSerch(partTId.value, itionGId.value, body);
              }
            }}
          >
            <p>この条件で検索</p>
          </div>
        </div>
        <div className="btn-wrap">
          <button onClick={() => downloadCsv(table)}>aaa</button>
        </div>
        <div className="result-wrap bg-white">
        
          {
            SerchedData.data != null && !SerchedData.isFetching &&
            <CustomTable
              data={SerchedData.data.parts}
              failures={SerchedData.data.f}
              holes={SerchedData.data.h}
              modifications={SerchedData.data.m}
              hModifications={SerchedData.data.hm}
            />
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
