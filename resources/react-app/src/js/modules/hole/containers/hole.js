import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
// Actions
import { holeActions } from '../ducks/hole';
// Material-ui Components
import { Paper, Dialog, RaisedButton, FlatButton } from 'material-ui';
import { grey50, indigo500 } from 'material-ui/styles/colors';
// Styles
import './hole.scss';
// Components
// import RangeCalendar from '../components/rangeCalendar/rangeCalendar';

class Failure extends Component {
  constructor(props, context) {
    super(props, context);
    this.props.actions.getHoles();

    this.state = {
      editModal: false,
      editting_l: null,
      editting_n: null,
    };
  }

  render() {
    const { AllHoleData } = this.props;
    const { vehicle } = this.state;

    const getDirection = d => {
      switch (d) {
        case 'left': return '左'; break;
        case 'right': return '右'; break;
        case 'top': return '上'; break;
        case 'bottom': return '下'; break;
      }
    } 

    return (
      <div id="inspector">
        <div className="header bg-white">
          <p>不良区分マスタメンテ</p>
        </div>
        <div className="body bg-white">
          <button className="create-btn">新規登録</button>
          <table>
            <thead>
              <tr>
                <th>No.</th>
                <th>図面</th>
                <th>番号</th>
                <th>位置</th>
                <th>色</th>
                <th>形</th>
                <th>線</th>
                <th>ラベル位置</th>
                <th>機能</th>
              </tr>
            </thead>
            <tbody>
            {
              AllHoleData.data && AllHoleData.data.length != 0 &&
              AllHoleData.data.map((h, i)=> 
                {
                  return(
                    <tr className="content">
                      <td>{i+1}</td>
                      <td>{'穴あけインナー Page1'}</td>
                      <td>{h.label}</td>
                      <td>{h.point}</td>
                      <td><p style={{backgroundColor: `#${h.color}`, border: '1px solid #000'}}>{h.color}</p></td>
                      <td>{h.shape == 'circle' ? '円' : '四角'}</td>
                      <td>{h.border == 'solid' ? '実線' : '破線'}</td>
                      <td>{getDirection(h.direction)}</td>
                      <td>
                        <button onClick={() => this.setState({
                          editModal: true,
                          editting_l: f.label,
                          editting_n: f.name,
                        })}>
                          非表示
                        </button>
                        <button onClick={() => this.setState({
                          editModal: true,
                          editting_l: f.label,
                          editting_n: f.name
                        })}>
                          編集
                        </button>
                      </td>
                    </tr>
                  )
                }              
              )
            }{
              AllHoleData.data && AllHoleData.data.length == 0 &&
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
                <p className="title">不良区分情報編集</p>
                <div className="edit">
                  <div className="process-wrap">
                    <p>番号</p>
                    <input
                      type="text"
                      value={this.state.editting_l}
                      onChange={(e) => this.setState({editting_l: e.target.value})}
                    />
                  </div>
                  <div className="inspection-wrap">
                    <p>名前</p>
                    <input
                      type="text"
                      value={this.state.editting_n}
                      onChange={(e) => this.setState({editting_n: e.target.value})}
                    />
                  </div>
                </div>
                <table>
                  <thead>
                    <tr>
                      <th colSpan={2}>成形工程</th>
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
                      <td>{'○'}</td>
                      <td>{'○'}</td>
                      <td>{'○'}</td>
                      <td>{'○'}</td>
                      <td>{'○'}</td>
                      <td>{'○'}</td>
                      <td>{'○'}</td>
                      <td>{'○'}</td>
                      <td>{'○'}</td>
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

Failure.propTypes = {
  AllHoleData: PropTypes.object.isRequired,
};

function mapStateToProps(state, ownProps) {
  return {
    AllHoleData: state.AllHoleData
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({}, holeActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Failure);
