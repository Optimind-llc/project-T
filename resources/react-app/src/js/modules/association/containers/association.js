import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
import { parts, processes, inspections } from '../../../utils/Processes';
import { handleDownload } from '../../../utils/Export';
// Actions
import { partFActions } from '../ducks/partF';
import { updatePartFActions } from '../ducks/updatePartF';
import { mappingActions } from '../ducks/mapping';
// Material-ui Components
import { Paper, Dialog, RaisedButton, FlatButton } from 'material-ui';
import { grey50, indigo500 } from 'material-ui/styles/colors';
// Styles
import './association.scss';
// Components
import RangeCalendar from '../components/rangeCalendar/rangeCalendar';
import Loading from '../../../components/loading/loading';
import Mapping from '../components/mapping/mapping';

class Association extends Component {
  constructor(props, context) {
    super(props, context);

    this.state = {
      vehicle: {value: '680A', label: '680A'},
      partTId: null,
      panelId: '',
      startDate: null,
      startHour: null,
      endDate: null,
      endHour: null,
      processId: null,
      itionGId: null,
      mappingModal: false,
      mappingId: 0,
      mappingPartTypeId: 0,
      header: '',
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
    const { startDate, startHour, endDate, endHour, partTId, panelId } = this.state;

    let start;
    if (startHour == null) {
      start = startDate == null ? null : `${startDate.format('YYYY-MM-DD')}-00`;
    }
    else {
      start = startDate == null ? null : `${startDate.format('YYYY-MM-DD')}-${startHour.value}`;
    }


    let end;
    if (endHour == null) {
      end = endDate == null ? null : `${endDate.format('YYYY-MM-DD')}-23`;
    }
    else {
      end = endDate == null ? null : `${endDate.format('YYYY-MM-DD')}-${endHour.value}`;
    }

    const partTypeId = partTId == null ? null : partTId.value;
    getPartFData(start, end, partTypeId, panelId);
  }

  requestMapping(itionGId) {
    const { getMappingData } = this.props.actions;
    const { mappingId } = this.state;

    getMappingData(mappingId, itionGId);
  }

  render() {
    const { PartFData, UpdatePartFData, MappingData } = this.props;
    const { vehicle, partTId, startDate, endDate, panelId, processId, itionGId, editting, mappingPartTypeId, header } = this.state;

    let table = [];
    if (PartFData.data != null && !PartFData.isFetching) {
      let header = ['更新日','バックドアインナー','アッパー','サイドアッパーLH','サイドアッパーRH','サイドロアLH','サイドロアRH'];
      table.push(header);

      table = table.concat(PartFData.data.map(pf => [
        pf.associatedAt,
        pf.parts['67149'][0].panelId,
        pf.parts['67119'] ? pf.parts['67119'][0].panelId : '',
        pf.parts['67176'][0].panelId,
        pf.parts['67175'][0].panelId,
        pf.parts['67178'][0].panelId,
        pf.parts['67177'][0].panelId
      ]));
    };

    return (
      <div id="association">
        <div className="serch-wrap-wrap bg-white">
          <div className="serch-wrap">
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
              <p>ID</p>
              <input
                type="text"
                value={panelId}
                onChange={(e) => this.setState({panelId: e.target.value})}
              />
            </div>
            <div className="term-wrap">
              <p>時間</p>
                <RangeCalendar
                  defaultValue={startDate}
                  setState={startDate => this.setState({
                    startDate: startDate
                  })}
                />
                <Select
                  name="部品"
                  styles={{height: 36}}
                  placeholder={'終日'}
                  disabled={false}
                  clearable={true}
                  Searchable={false}
                  scrollMenuIntoView={false}
                  value={this.state.startHour}
                  options={[
                    {label: '0時', value: 0},
                    {label: '1時', value: 1},
                    {label: '2時', value: 2},
                    {label: '3時', value: 3},
                    {label: '4時', value: 4},
                    {label: '5時', value: 5},
                    {label: '6時', value: 6},
                    {label: '7時', value: 7},
                    {label: '8時', value: 8},
                    {label: '9時', value: 9},
                    {label: '10時', value: 10},
                    {label: '11時', value: 11},
                    {label: '12時', value: 12},
                    {label: '13時', value: 13},
                    {label: '14時', value: 14},
                    {label: '15時', value: 15},
                    {label: '16時', value: 16},
                    {label: '17時', value: 17},
                    {label: '18時', value: 18},
                    {label: '19時', value: 19},
                    {label: '20時', value: 20},
                    {label: '21時', value: 21},
                    {label: '22時', value: 22},
                    {label: '23時', value: 23}
                  ]}
                  onChange={value => this.setState({
                    startHour: value
                  })}
                />
                <p>〜</p>
                <RangeCalendar
                  defaultValue={endDate}
                  setState={endDate => this.setState({
                    endDate: endDate
                  })}
                />
                <Select
                  name="部品"
                  styles={{height: 36}}
                  placeholder={'終日'}
                  disabled={false}
                  clearable={true}
                  Searchable={false}
                  scrollMenuIntoView={false}
                  value={this.state.endHour}
                  options={[
                    {label: '0時', value: 0},
                    {label: '1時', value: 1},
                    {label: '2時', value: 2},
                    {label: '3時', value: 3},
                    {label: '4時', value: 4},
                    {label: '5時', value: 5},
                    {label: '6時', value: 6},
                    {label: '7時', value: 7},
                    {label: '8時', value: 8},
                    {label: '9時', value: 9},
                    {label: '10時', value: 10},
                    {label: '11時', value: 11},
                    {label: '12時', value: 12},
                    {label: '13時', value: 13},
                    {label: '14時', value: 14},
                    {label: '15時', value: 15},
                    {label: '16時', value: 16},
                    {label: '17時', value: 17},
                    {label: '18時', value: 18},
                    {label: '19時', value: 19},
                    {label: '20時', value: 20},
                    {label: '21時', value: 21},
                    {label: '22時', value: 22},
                    {label: '23時', value: 23}
                  ]}
                  onChange={value => this.setState({
                    endHour: value
                  })}
                />
            </div>
          </div>
          <button
            className="serch dark"
            onClick={() => this.serch()}
          >
            <p>この条件で検索</p>
          </button>
        </div>
        <div className="result bg-white">
          <button className="download dark" onClick={() => handleDownload(table)}>
            <p>CSVをダウンロード</p>
          </button>
          <table>
            <thead>
              <tr>
                <th colSpan={1} rowSpan={3}>No.</th>
                <th colSpan={1} rowSpan={3}>更新日</th>
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
                        <td>
                          <p onClick={() => {
                            this.setState({
                              mappingModal: true,
                              mappingId: f.parts['67149'][0].id,
                              mappingPartTypeId: 1,
                              header: `67149 バックドアインナー　パネルID: ${f.parts['67149'][0].panelId}`
                            });
                          }}>
                            {f.parts['67149'][0].panelId}
                          </p>
                        </td>
                        <td>
                          {
                            f.parts['67119'] &&
                            <p onClick={() => {
                              this.setState({
                                mappingModal: true,
                                mappingId: f.parts['67119'][0].id,
                                mappingPartTypeId: 2,
                                header: `67119 アッパー　パネルID: ${f.parts['67119'][0].panelId}`
                              });
                            }}>
                              {f.parts['67119'][0].panelId}
                            </p>
                          }
                        </td>
                        <td>
                          <p onClick={() => {
                            this.setState({
                              mappingModal: true,
                              mappingId: f.parts['67176'][0].id,
                              mappingPartTypeId: 4,
                              header: `67176 サイドアッパーLH　パネルID: ${f.parts['67176'][0].panelId}`
                            });
                          }}>
                            {f.parts['67176'][0].panelId}
                          </p>
                        </td>
                        <td>
                          <p onClick={() => {
                            this.setState({
                              mappingModal: true,
                              mappingId: f.parts['67175'][0].id,
                              mappingPartTypeId: 3,
                              header: `67175 サイドアッパーRH　パネルID: ${f.parts['67175'][0].panelId}`
                            });
                          }}>
                            {f.parts['67175'][0].panelId}
                          </p>
                        </td>
                        <td>
                          <p onClick={() => {
                            this.setState({
                              mappingModal: true,
                              mappingId: f.parts['67178'][0].id,
                              mappingPartTypeId: 6,
                              header: `67178 サイドロアLH　パネルID: ${f.parts['67178'][0].panelId}`
                            });
                          }}>
                            {f.parts['67178'][0].panelId}
                          </p>
                        </td>
                        <td>
                          <p onClick={() => {
                            this.setState({
                              mappingModal: true,
                              mappingId: f.parts['67177'][0].id,
                              mappingPartTypeId: 5,
                              header: `67177 サイドロアRH　パネルID: ${f.parts['67177'][0].panelId}`
                            });
                          }}>
                            {f.parts['67177'][0].panelId}
                          </p>
                        </td>
                        <td>
                          <p onClick={() => {
                            this.setState({
                              mappingModal: true,
                              mappingId: f.parts['67007'][0].id,
                              mappingPartTypeId: 7,
                              header: `67007 バックドアインナーASSY　パネルID: ${f.parts['67007'][0].panelId}`
                            });
                          }}>
                            {f.parts['67007'][0].panelId}
                          </p>
                        </td>
                        <td>
                          <button
                            className="dark edit"
                            onClick={() => this.setState({
                              editModal: true,
                              editting_f: f.familyId,
                              editting_1: f.parts[67149][0].panelId,
                              editting_2: f.parts[67119] ? f.parts[67119][0].panelId : '',
                              editting_4: f.parts[67176][0].panelId,
                              editting_3: f.parts[67175][0].panelId,
                              editting_6: f.parts[67178][0].panelId,
                              editting_5: f.parts[67177][0].panelId
                            })}
                          >
                            <p>編集</p>
                          </button>
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
            this.state.editModal &&
            <div>
              <div className="modal">
              </div>
              <div className="edit-wrap">
                <div className="edit">
                  <div className="message-wrap">
                  {
                    UpdatePartFData.message == 'Already be associated others' &&
                    UpdatePartFData.parts.map(p =>
                      <p>{`${p.pn} : ${p.name} : ${p.panelId} の更新に失敗しました　すでに他の部品に使用されています。`}</p>
                    )
                  }{
                    UpdatePartFData.message == 'success' &&
                    <p>更新しました</p>
                  }
                  </div>
                  <div className="input-wrap">
                    <div className="input">
                      <p className="label">バックドアインナー<br/>67149</p>
                      <input
                        type="text"
                        value={this.state.editting_1}
                        onChange={(e) => this.setState({editting_1: e.target.value})}
                      />
                      {
                        this.state.editting_1.length != 8 &&
                        <p className="validation_msg">8桁で入力してください</p>
                      }
                    </div>
                    <div className="input">
                      <p className="label">アッパー<br/>67119</p>
                      <input
                        type="text"
                        placeholder="QRコード無し"
                        value={this.state.editting_2}
                        onChange={(e) => this.setState({editting_2: e.target.value})}
                      />
                      {
                        this.state.editting_2.length !== 0 && this.state.editting_2.length != 8 &&
                        <p className="validation_msg">8桁で入力してください</p>
                      }
                    </div>
                    <div className="input">
                      <p className="label">サイドアッパーLH<br/>67176</p>
                      <input
                        type="text"
                        value={this.state.editting_4}
                        onChange={(e) => this.setState({editting_4: e.target.value})}
                      />
                      {
                        this.state.editting_4.length != 8 &&
                        <p className="validation_msg">8桁で入力してください</p>
                      }
                    </div>
                    <div className="input">
                      <p className="label">サイドアッパーRH<br/>67175</p>
                      <input
                        type="text"
                        value={this.state.editting_3}
                        onChange={(e) => this.setState({editting_3: e.target.value})}
                      />
                      {
                        this.state.editting_3.length != 8 &&
                        <p className="validation_msg">8桁で入力してください</p>
                      }
                    </div>
                    <div className="input">
                      <p className="label">サイドロアLH<br/>67178</p>
                      <input
                        type="text"
                        value={this.state.editting_6}
                        onChange={(e) => this.setState({editting_6: e.target.value})}
                      />
                      {
                        this.state.editting_6.length != 8 &&
                        <p className="validation_msg">8桁で入力してください</p>
                      }
                    </div>
                    <div className="input">
                      <p className="label">サイドロアRH<br/>67177</p>
                      <input
                        type="text"
                        value={this.state.editting_5}
                        onChange={(e) => this.setState({editting_5: e.target.value})}
                      />
                      {
                        this.state.editting_5.length != 8 &&
                        <p className="validation_msg">8桁で入力してください</p>
                      }
                    </div>
                  </div>
                  <div className="btn-wrap">
                    <button
                      className={(this.state.editting_1.length === 8 && (this.state.editting_2.length === 0 || this.state.editting_2.length === 8) && this.state.editting_3.length === 8 && this.state.editting_4.length === 8 && this.state.editting_5.length === 8 && this.state.editting_6.length === 8) ? '' : 'disabled'}
                      onClick={() => {
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
                      }}
                    >
                      保存
                    </button>
                    <button onClick={() => {
                      this.serch()
                      this.props.actions.clearMessage();
                      this.setState({editModal: false});
                    }}>終了</button>
                  </div>
                </div>
              </div>
            </div>
          }
        </div>
        {
          this.state.mappingModal &&
          <Mapping
            close={() => {
              this.setState({mappingModal: false});
              this.props.actions.clearMappingData();
            }}
            header={header}
            requestMapping={(itionGid) => this.requestMapping(itionGid)}
            data={MappingData.data}
            isFetching={MappingData.isFetching}
            didInvalidate={MappingData.didInvalidate}
            mappingPartTypeId={mappingPartTypeId}
          />
        }
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
    UpdatePartFData: state.UpdatePartFData,
    MappingData: state.MappingData,
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({}, partFActions ,updatePartFActions, mappingActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Association);
