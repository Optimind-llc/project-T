import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
import { parts, processes, inspections } from '../../../utils/Processes';
// Actions
import { partFActions } from '../ducks/partF';
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
      editModal: false
    };
  }

  serch() {
    const { actions: {getPartFData} } = this.props;
    const { startDate, endDate, partTId, panelId } = this.state;

    const partTypeId = partTId == null ? null : partTId.value;
    getPartFData(startDate.format('YYYY-MM-DD-HH'), endDate.format('YYYY-MM-DD-HH'), partTypeId, panelId);
  }

  render() {
    const { PartFData } = this.props;
    const { vehicle, partTId, startDate, endDate, panelId, processId, itionGId } = this.state;

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
          <table>
            <tbody>
              <tr>
                <td colSpan={1} rowSpan={3}>No.</td>
                <td colSpan={1} rowSpan={3}>登録日</td>
                <td colSpan={1}>インナー</td>
                <td colSpan={5}>アウター</td>
                <td colSpan={1}>ASSY</td>
                <td colSpan={1} rowSpan={3}>機能</td>
              </tr>
              <tr>
                <td>バックドアインナー</td>
                <td>アッパー</td>
                <td>サイドアッパーRH</td>
                <td>サイドアッパーLH</td>
                <td>サイドロアRH</td>
                <td>サイドロアLH</td>
                <td>バックドアインナASSY</td>
              </tr>
              <tr>
                <td>67149 47060 000</td>
                <td>67119 47060 000</td>
                <td>67175 47060 000</td>
                <td>67176 47060 000</td>
                <td>67177 47050 000</td>
                <td>67178 47010 000</td>
                <td>67007 47120 000</td>
              </tr>
              {
                PartFData.data && PartFData.data.length != 0 &&
                PartFData.data.map((f, i)=> 
                  {
                    return(
                      <tr className="content">
                        <td>{i+1}</td>
                        <td>{f.associatedAt}</td>
                        <td onClick={() => this.setState({modal: true})}>{f.parts['67007'][0].panelId}</td>
                        <td onClick={() => this.setState({modal: true})}>{f.parts['67149'][0].panelId}</td>
                        <td onClick={() => this.setState({modal: true})}>{f.parts['67119'][0].panelId}</td>
                        <td onClick={() => this.setState({modal: true})}>{f.parts['67175'][0].panelId}</td>
                        <td onClick={() => this.setState({modal: true})}>{f.parts['67176'][0].panelId}</td>
                        <td onClick={() => this.setState({modal: true})}>{f.parts['67177'][0].panelId}</td>
                        <td onClick={() => this.setState({modal: true})}>{f.parts['67178'][0].panelId}</td>
                        <td onClick={() => this.setState({editModal: true})}>
                          <button>編集</button>
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
                      <p className="label">バックドアインナー 67149</p>
                      <input></input>
                    </div>
                    <div className="input">
                      <p className="label">アッパー 67119</p>
                      <input></input>
                    </div>
                    <div className="input">
                      <p className="label">サイドアッパーRH 67175</p>
                      <input></input>
                    </div>
                    <div className="input">
                      <p className="label">サイドアッパーLH 67176</p>
                      <input></input>
                    </div>
                    <div className="input">
                      <p className="label">サイドロアRH 67177</p>
                      <input></input>
                    </div>
                    <div className="input">
                      <p className="label">サイドロアLH 67178</p>
                      <input></input>
                    </div>
                  </div>
                  <div className="btn-wrap">
                    <button onClick={() => this.setState({editModal: false})}>保存</button>
                    <button onClick={() => this.setState({editModal: false})}>キャンセル</button>
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
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({}, partFActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Association);
