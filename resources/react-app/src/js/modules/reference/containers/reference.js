import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
import { parts, processes, inspections } from '../../../utils/Processes';
// Actions
import { referenceActions } from '../ducks/reference';
// Material-ui Components
import { Paper, Dialog, RaisedButton, FlatButton } from 'material-ui';
import { grey50, indigo500 } from 'material-ui/styles/colors';
// Styles
import './reference.scss';
// Components
import Loading from '../../../components/loading/loading';
import CustomCalendar from '../components/customCalendar/customCalendar';

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
    const {
      vehicle, partTId, processId, itionGId,
      itorG, judgement, RequiredF, RequiredM, RequiredHM,
      narrowedBy, startDate, endDate, panelId
    } = this.state;

const failures = [
  {label: 'キズ', value: 1},
  {label: '凸', value: 2},
  {label: '凹', value: 3},
  {label: 'その他', value: 4}
];
const modifications = [
  {label: '削り', value: 1},
  {label: '除去', value: 2},
  {label: '樹脂盛り', value: 3},
  {label: 'その他', value: 4}
];
const hModifications = [
  {label: '穴径修正', value: 1},
  {label: 'トリム部修正', value: 2},
  {label: 'その他', value: 3}
];

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
                  onChange={value => this.setState({itionGId: value})}
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
                        options={failures}
                        onChange={value => {console.log(value); this.setState({RequiredF: value});}}
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
                        options={modifications}
                        onChange={value => {console.log(value); this.setState({RequiredM: value});}}
                      />
                    </div>
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
            onClick={() => this.showMapping()}
          >
            <p>この条件で検索</p>
          </div>
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
    // id: ownProps.params.id,
    // application: state.application,
    // conference: state.conference,
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({}, referenceActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Reference);
