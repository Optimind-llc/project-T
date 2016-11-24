import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
import { parts, processes, inspections } from '../../../utils/Processes';
// Actions
import { partFActions } from '../ducks/partF';
import { updatePartFActions } from '../ducks/updatePartF';
// Material-ui Components
import { Paper, Dialog, RaisedButton, FlatButton } from 'material-ui';
import { grey50, indigo500 } from 'material-ui/styles/colors';
// Styles
import './association.scss';
// Components
import RangeCalendar from '../components/rangeCalendar/rangeCalendar';
import Loading from '../../../components/loading/loading';

class Association extends Component {
  constructor(props, context) {
    super(props, context);

    this.state = {
      vehicle: {value: '680A', label: '680A'},
      partTId: null,
      panelId: '',
      startDate: moment(),
      endDate: moment(),
      processId: null,
      itionGId: null,
      modal: false,
      editModal: false,
      editting_f: 0,
      editting_1: 0,
      editting_2: 0,
      editting_3: 0,
      editting_4: 0,
      editting_5: 0,
      editting_6: 0
    };
  }

  serch() {
    const { actions: {getPartFData} } = this.props;
    const { startDate, endDate, partTId, panelId } = this.state;

    const start = startDate == null ? null : startDate.format('YYYY-MM-DD-HH');
    const end = endDate == null ? null : endDate.format('YYYY-MM-DD-HH');
    const partTypeId = partTId == null ? null : partTId.value;
    getPartFData(start, end, partTypeId, panelId);
  }

  render() {
    const { PartFData, UpdatePartFData } = this.props;
    const { vehicle, partTId, startDate, endDate, panelId, processId, itionGId, editting } = this.state;

    return (
      <div id="association">
        <div className="serch-wrap-wrap bg-white">
          <div className="serch-wrap">
            <div className="vehicle-wrap">
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
            <div className="parts-wrap">
              <p>部品</p>
              <Select
                name="部品"
                styles={{height: 36}}
                placeholder={vehicle == null ? '先に車種を選択' :'部品を選択'}
                disabled={vehicle == null}
                clearable={false}
                Searchable={false}
                scrollMenuIntoView={false}
                value={this.state.partTId}
                options={[
                  {label: 'バックドアインナー', value: 1},
                  {label: 'アッパー', value: 2},
                  {label: 'サイドアッパーRH', value: 3},
                  {label: 'サイドアッパーLH', value: 4},
                  {label: 'サイドロアRH', value: 5},
                  {label: 'サイドロアLH', value: 6},
                  {label: 'バックドアインナーASSY', value: 7}
                ]}
                onChange={value => this.setState({
                  partTId: value,
                  processId: null,
                  itionGId: null
                })}
              />
            </div>
            <div className="panel-id-wrap">
              <p>パネルID</p>
              <input
                type="text"
                value={panelId}
                onChange={(e) => this.setState({panelId: e.target.value})}
              />
            </div>
            <div className="term-wrap">
              <p>登録日</p>
                <RangeCalendar
                  defaultValue={startDate}
                  setState={startDate => this.setState({
                    startDate: startDate
                  })}
                />
                <p>〜</p>
                <RangeCalendar
                  defaultValue={endDate}
                  setState={endDate => this.setState({
                    endDate: endDate
                  })}
                />
            </div>
          </div>
          <button
            className="serch-btn"
            onClick={() => this.serch()}
          >
            この条件で検索
          </button>
        </div>
        <div className="result bg-white">
          {
            UpdatePartFData.message == 'Already be associated others' &&
            <p>{`${UpdatePartFData.panelId} の更新に失敗しました　すでに他の部品に使用されています。`}</p>
          }{
            UpdatePartFData.message == 'Not be inspected' &&
            <p>{`${UpdatePartFData.panelId} の更新に失敗しました　まだ検査されていない部品です。`}</p>
          }
          <table>
            <thead>
              <tr>
                <th colSpan={1} rowSpan={3}>No.</th>
                <th colSpan={1} rowSpan={3}>登録日</th>
                <th colSpan={1}>インナー</th>
                <th colSpan={5}>アウター</th>
                <th colSpan={1}>ASSY</th>
                <th colSpan={1} rowSpan={3}>機能</th>
              </tr>
              <tr>
                <th>バックドアインナー</th>
                <th>アッパー</th>
                <th>サイドアッパーLH</th>
                <th>サイドアッパーRH</th>
                <th>サイドロアLH</th>
                <th>サイドロアRH</th>
                <th>バックドアインナASSY</th>
              </tr>
              <tr>
                <th>67149 47060 000</th>
                <th>67119 47060 000</th>
                <th>67176 47060 000</th>
                <th>67175 47060 000</th>
                <th>67178 47010 000</th>
                <th>67177 47050 000</th>
                <th>67007 47120 000</th>
              </tr>
            </thead>
            <tbody>
              {
                PartFData.data && PartFData.data.length != 0 &&
                PartFData.data.map((f, i)=> 
                  {
                    return(
                      <tr className="content">
                        <td>{i+1}</td>
                        <td>{f.associatedAt}</td>
                        <td><p onClick={() => this.setState({modal: true})}>{f.parts['67149'][0].panelId}</p></td>
                        <td><p onClick={() => this.setState({modal: true})}>{f.parts['67119'][0].panelId}</p></td>
                        <td><p onClick={() => this.setState({modal: true})}>{f.parts['67176'][0].panelId}</p></td>
                        <td><p onClick={() => this.setState({modal: true})}>{f.parts['67175'][0].panelId}</p></td>
                        <td><p onClick={() => this.setState({modal: true})}>{f.parts['67178'][0].panelId}</p></td>
                        <td><p onClick={() => this.setState({modal: true})}>{f.parts['67177'][0].panelId}</p></td>
                        <td><p onClick={() => this.setState({modal: true})}>{f.parts['67007'][0].panelId}</p></td>
                        <td>
                          <button onClick={() => this.setState({
                            editModal: true,
                            editting_f: f.familyId,
                            editting_1: f.parts[67149][0].panelId,
                            editting_2: f.parts[67119][0].panelId,
                            editting_4: f.parts[67176][0].panelId,
                            editting_3: f.parts[67175][0].panelId,
                            editting_6: f.parts[67178][0].panelId,
                            editting_5: f.parts[67177][0].panelId
                          })}>編集</button>
                        </td>
                      </tr>
                    )
                  }              
                )
              }{
                PartFData.data && PartFData.data.length == 0 &&
                <tr className="content">
                  <td colSpan="10">結果なし</td>
                </tr>
              }
            </tbody>
          </table>
          {
            this.state.modal &&
            <div>
              <div className="modal">
              </div>
              <div className="jump-wrap">
                <div className="panel-btn" onClick={() => this.setState({modal: false})}>
                  <span className="panel-btn-close"></span>
                </div>
                <p>工程と検査を選択してください</p>
                <div className="jump">
                  <div className="process-wrap">
                    <p>工程*</p>
                    <Select
                      name="工程"
                      styles={{height: 36}}
                      placeholder={'工程を選択'}
                      disabled={partTId == null}
                      clearable={false}
                      Searchable={true}
                      value={null}
                      options={null}
                    />
                  </div>
                  <div className="inspection-wrap">
                    <p>検査*</p>
                    <Select
                      name="検査"
                      styles={{height: 36}}
                      placeholder={'先に工程を選択'}
                      disabled={processId == null}
                      clearable={false}
                      Searchable={true}
                      value={null}
                      options={null}
                    />
                  </div>
                </div>
              </div>
            </div>
          }{
            this.state.editModal &&
            <div>
              <div className="modal">
              </div>
              <div className="edit-wrap">
                <div className="edit">
                  <div className="input-wrap">
                    <div className="input">
                      <p className="label">バックドアインナー<br/>67149</p>
                      <input
                        type="text"
                        value={this.state.editting_1}
                        onChange={(e) => this.setState({editting_1: e.target.value})}
                      />
                    </div>
                    <div className="input">
                      <p className="label">アッパー<br/>67119</p>
                      <input
                        type="text"
                        value={this.state.editting_2}
                        onChange={(e) => this.setState({editting_2: e.target.value})}
                      />
                    </div>
                    <div className="input">
                      <p className="label">サイドアッパーLH<br/>67176</p>
                      <input
                        type="text"
                        value={this.state.editting_4}
                        onChange={(e) => this.setState({editting_4: e.target.value})}
                      />
                    </div>
                    <div className="input">
                      <p className="label">サイドアッパーRH<br/>67175</p>
                      <input
                        type="text"
                        value={this.state.editting_3}
                        onChange={(e) => this.setState({editting_3: e.target.value})}
                      />
                    </div>
                    <div className="input">
                      <p className="label">サイドロアLH<br/>67178</p>
                      <input
                        type="text"
                        value={this.state.editting_6}
                        onChange={(e) => this.setState({editting_6: e.target.value})}
                      />
                    </div>
                    <div className="input">
                      <p className="label">サイドロアRH<br/>67177</p>
                      <input
                        type="text"
                        value={this.state.editting_5}
                        onChange={(e) => this.setState({editting_5: e.target.value})}
                      />
                    </div>
                  </div>
                  <div className="btn-wrap">
                    <button onClick={() => {
                      this.setState({editModal: false});
                      this.props.actions.updatePartFamily({
                        "id": this.state.editting_f,
                        "parts": [
                          {
                            "partTypeId": 1,
                            "panelId": this.state.editting_1
                          },{
                            "partTypeId": 2,
                            "panelId": this.state.editting_2
                          },{
                            "partTypeId": 3,
                            "panelId": this.state.editting_3
                          },{
                            "partTypeId": 4,
                            "panelId": this.state.editting_4
                          },{
                            "partTypeId": 5,
                            "panelId": this.state.editting_5
                          },{
                            "partTypeId": 6,
                            "panelId": this.state.editting_6
                          }
                        ]
                      });
                    }}>
                      保存
                    </button>
                    <button onClick={() => this.setState({editModal: false})}>終了</button>
                  </div>
                </div>
              </div>
            </div>
          }
        </div>
      </div>
    );
  }
}

Association.propTypes = {
  PartFData: PropTypes.object.isRequired,
};

function mapStateToProps(state, ownProps) {
  return {
    PartFData: state.PartFData,
    UpdatePartFData: state.UpdatePartFData
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({}, partFActions ,updatePartFActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Association);
