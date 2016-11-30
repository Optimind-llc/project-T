import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
// Actions
import { inspectorActions } from '../ducks/inspector';
// Material-ui Components
import { Paper, Dialog, RaisedButton, FlatButton } from 'material-ui';
import { grey50, indigo500 } from 'material-ui/styles/colors';
// Styles
import './inspector.scss';
// Components
// import RangeCalendar from '../components/rangeCalendar/rangeCalendar';

class Inspector extends Component {
  constructor(props, context) {
    super(props, context);
    this.props.actions.getInspectors();

    this.state = {
      editModal: false,
      editting_n: null,
      editting_c: null,
    };
  }

  render() {
    const { InspectorData } = this.props;
    const { vehicle } = this.state;

    return (
      <div id="inspector">
        <div className="header bg-white">
          <p>担当者マスタメンテ</p>
        </div>
        <div className="body bg-white">
          <button className="create-btn">新規登録</button>
          <table>
            <thead>
              <tr>
                <th colSpan={1} rowSpan={3}>No.</th>
                <th colSpan={1} rowSpan={3}>名前</th>
                <th colSpan={1} rowSpan={3}>直</th>
                <th colSpan={2}>成型工程</th>
                <th colSpan={3}>穴あけ工程</th>
                <th colSpan={5}>接着工程</th>
                <th colSpan={1} rowSpan={3}>機能</th>
              </tr>
              <tr>
                <th colSpan={1}>インナー</th>
                <th colSpan={1}>アウター</th>
                <th colSpan={2}>インナー</th>
                <th colSpan={1}>アウター</th>
                <th colSpan={5}>インナーASSY</th>
              </tr>
              <tr>
                <th colSpan={1}>外観検査</th>
                <th colSpan={1}>外観検査</th>
                <th colSpan={1}>外観検査</th>
                <th colSpan={1}>穴検査</th>
                <th colSpan={1}>穴検査</th>
                <th colSpan={1}>簡易CF</th>
                <th colSpan={1}>止水</th>
                <th colSpan={1}>仕上</th>
                <th colSpan={1}>検査</th>
                <th colSpan={1}>手直</th>
              </tr>
            </thead>
            <tbody>
            {
              InspectorData.data && InspectorData.data.length != 0 &&
              InspectorData.data.map((itor, i)=> 
                {
                  return(
                    <tr className="content">
                      <td>{i+1}</td>
                      <td>{itor.name}</td>
                      <td>{itor.chokuName}</td>
                      <td>{'○'}</td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>
                        <button onClick={() => this.setState({
                          editModal: true,
                          editting_n: itor.name,
                          editting_c: {value: itor.chokuCode, label: itor.chokuName}
                        })}>
                          非表示
                        </button>
                        <button onClick={() => this.setState({
                          editModal: true,
                          editting_n: itor.name,
                          editting_c: {value: itor.chokuCode, label: itor.chokuName}
                        })}>
                          編集
                        </button>
                      </td>
                    </tr>
                  )
                }              
              )
            }{
              InspectorData.data && InspectorData.data.length == 0 &&
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
                <div className="panel-btn" onClick={() => this.setState({editModal: false})}>
                  <span className="panel-btn-close"></span>
                </div>
                <p className="title">担当者情報編集</p>
                <div className="edit">
                  <div className="process-wrap">
                    <p>名前</p>
                    <input
                      type="text"
                      value={this.state.editting_n}
                      onChange={(e) => this.setState({editting_n: e.target.value})}
                    />
                  </div>
                  <div className="inspection-wrap">
                    <p>直</p>
                    <Select
                      name="検査"
                      styles={{height: 36}}
                      placeholder={''}
                      disabled={false}
                      clearable={false}
                      Searchable={true}
                      value={this.state.editting_c}
                      options={[
                        {value: 'Y', label: '黄直'},
                        {value: 'W', label: '白直'},
                        {value: 'B', label: '黒直'}
                      ]}
                    />
                  </div>
                </div>
                <table>
                  <thead>
                    <tr>
                      <th colSpan={2}>成型工程</th>
                      <th colSpan={3}>穴あけ工程</th>
                      <th colSpan={5}>接着工程</th>
                    </tr>
                    <tr>
                      <th colSpan={1}>インナー</th>
                      <th colSpan={1}>アウター</th>
                      <th colSpan={2}>インナー</th>
                      <th colSpan={1}>アウター</th>
                      <th colSpan={5}>インナーASSY</th>
                    </tr>
                    <tr>
                      <th colSpan={1}>外観検査</th>
                      <th colSpan={1}>外観検査</th>
                      <th colSpan={1}>外観検査</th>
                      <th colSpan={1}>穴検査</th>
                      <th colSpan={1}>穴検査</th>
                      <th colSpan={1}>簡易CF</th>
                      <th colSpan={1}>止水</th>
                      <th colSpan={1}>仕上</th>
                      <th colSpan={1}>検査</th>
                      <th colSpan={1}>手直</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr className="content">
                      <td>{'○'}</td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </tbody>
                </table>
                <div className="btn-wrap">
                  <button>
                    保存
                  </button>
                  <button>
                    終了
                  </button>
                </div>
              </div>
            </div>
          }
        </div>
      </div>
    );
  }
}

Inspector.propTypes = {
  InspectorData: PropTypes.object.isRequired,
};

function mapStateToProps(state, ownProps) {
  return {
    InspectorData: state.InspectorData
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({}, inspectorActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Inspector);
