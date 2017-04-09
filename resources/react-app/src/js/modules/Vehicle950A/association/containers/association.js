import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
// import { handleDownload } from '../../../utils/Export';
// Actions
import { partFamilyActions } from '../ducks/partFamily';
// import { updatePartFActions } from '../ducks/updatePartF';
// import { mappingActions } from '../ducks/mapping';
// Styles
import './association.scss';
// Components
import Table from '../components/table/table';
import CustomCalendar from '../components/calendar/calendar';
import Loading from '../../../../components/loading/loading';
// import Mapping from '../components/mapping/mapping';

class Association extends Component {
  constructor(props, context) {
    super(props, context);

    this.state = {
      narrowedBy: 'date',
      type: {label: 'ドアL', value: 'doorL'},
      partType: {label: '全て', value: ['doorInnerL','doorInnerR','reinforceL','reinforceR','luggageInnerSTD','luggageInnerARW','luggageOuterSTD','luggageOuterARW']},
      partTId: null,
      panelId: '',
      startDate: moment(),
      startHour: null,
      endDate: moment(),
      endHour: null,
    };
  }

  serch() {
    const { getPartFamilyByDate, getPartFamilyByPanelId } = this.props.actions;
    const { narrowedBy, type, startDate, startHour, endDate, endHour, partTId, panelId } = this.state;

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

    const pn = partTId == null ? null : partTId.value;

    if (narrowedBy === 'date') {
      getPartFamilyByDate(type.value, start, end);
    }
    else if (narrowedBy === 'panelId') {
      getPartFamilyByPanelId(type.value, pn, panelId);
    }
  }

  render() {
    const { PartFamilyData, UpdatePartFData, MappingData } = this.props;
    const { narrowedBy, type, startDate, startHour, endDate, endHour, partType, partTId, panelId } = this.state;

    // let table = [];
    // if (PartFamilyData.data != null && !PartFamilyData.isFetching) {
    //   let header = ['更新日','バックドアインナー','アッパー','サイドアッパーLH','サイドアッパーRH','サイドロアLH','サイドロアRH'];
    //   table.push(header);

    //   table = table.concat(PartFamilyData.data.families.map(pf => [
    //     pf.associatedAt,
    //     pf.parts['67149'][0].panelId,
    //     pf.parts['67119'] ? pf.parts['67119'][0].panelId : '',
    //     pf.parts['67176'][0].panelId,
    //     pf.parts['67175'][0].panelId,
    //     pf.parts['67178'][0].panelId,
    //     pf.parts['67177'][0].panelId
    //   ]));
    // };

console.log(PartFamilyData);
    return (
      <div id="association-950A-wrap">
        <div className="bg-white">
          <div className="select-wrap">
            <div className="row">
              <div
                className={`row selectable ${narrowedBy === 'date' ? 'selected' : ''}`}
                onClick={() => this.setState({narrowedBy: 'date'})}
              >
                <p>種類</p>
                <Select
                  name="種類"
                  className="width140"
                  placeholder="種類を選択"
                  clearable={false}
                  Searchable={true}
                  value={type}
                  options={[
                    {label: 'ドアL', value: 'doorL'},
                    {label: 'ドアR', value: 'doorR'},
                    {label: 'ラゲージSTD', value: 'luggageSTD'},
                    {label: 'ラゲージARW', value: 'luggageARW'}
                  ]}
                  onChange={type => this.setState({type})}
                />
                <p style={{marginLeft: 12}}>期間</p>
                <CustomCalendar
                  defaultDate={startDate}
                  changeDate={d => this.setState({startDate: d})}
                  disabled={false}
                />
                <Select
                  name="部品"
                  className="width84"
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
                <CustomCalendar
                  defaultDate={endDate}
                  changeDate={d => this.setState({endDate: d})}
                  disabled={false}
                />
                <Select
                  name="部品"
                  className="width84"
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
              <div
                className={`row selectable ${narrowedBy === 'panelId' ? 'selected' : ''}`}
                onClick={() => this.setState({narrowedBy: 'panelId'})}
              >
                <p>部品</p>
                <Select
                  name="種類"
                  className="width200"
                  placeholder="種類を選択"
                  clearable={false}
                  Searchable={true}
                  value={partType}
                  options={[
                    {label: 'ドアインナL', value: ['doorInnerL']},
                    {label: 'ドアインナR', value: ['doorInnerR']},
                    {label: 'リンフォースL', value: ['reinforceL']},
                    {label: 'リンフォースR', value: ['reinforceR']},
                    {label: 'ラゲージインナSTD', value: ['luggageInnerSTD']},
                    {label: 'ラゲージインナARW', value: ['luggageInnerARW']},
                    {label: 'ラゲージインナSTD', value: ['luggageOuterSTD']},
                    {label: 'ラゲージインナARW', value: ['luggageOuterARW']},
                    {label: '全て', value: ['doorInnerL','doorInnerR','reinforceL','reinforceR','luggageInnerSTD','luggageInnerARW','luggageOuterSTD','luggageOuterARW']}
                  ]}
                  onChange={partType => this.setState({partType})}
                />
                <p>パネルID</p>
                <input
                  type="text"
                  value={panelId}
                  style={{width: 120}}
                  onChange={e => this.setState({panelId: e.target.value})}
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

        </div>
        {
          PartFamilyData.isFetching &&
          <p>検索中...</p>
        }{
          PartFamilyData.data != null && !PartFamilyData.isFetching &&
          <div className="result bg-white">
            {
              PartFamilyData.data.count < 100 &&
              <p className="result-count">{`${PartFamilyData.data.count}件中 ${PartFamilyData.data.families.length}件表示`}</p>
            }{
              PartFamilyData.data.count >= 100 &&
              <p className="result-count">{`${PartFamilyData.data.count}件中 100件表示`}</p>
            }
            <button className="download dark" onClick={() => handleDownload(table)}>
              <p>CSVをダウンロード</p>
            </button>
            <Table data={PartFamilyData.data.doorL}/>
            {/*
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
            */}
          </div>
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
    PartFamilyData: state.AssociationData950A,
    UpdatePartFData: state.UpdatePartFData,
    MappingData: state.MappingData,
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({}, partFamilyActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Association);
