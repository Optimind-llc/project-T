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
import Edit from '../components/edit/edit';

class Inspector extends Component {
  constructor(props, context) {
    super(props, context);

    let yomi = '';
    let choku = ['W', 'Y', 'B'];
    let itionG = 'all';
    let status = [1];

    this.props.actions.getInspectors(yomi, choku, itionG, status);

    this.state = {
      yomi: yomi,
      choku: {label: '全直', value: choku},
      itionG: {label: '全て', value: itionG},
      status: {label: '表示中', value: status},
      editModal: false,
      editting: null
    };
  }

  requestInspector() {
    const { yomi, choku, itionG, status, editting } = this.state;
    this.props.actions.getInspectors(yomi, choku.value, itionG.value, status.value);
  }

  updateInspector(id, name, yomi, choku, itionG) {
    console.log(id, name, yomi, choku, itionG);
    const { updateInspector, getInspectors } = this.props.actions;

    updateInspector(id, name, yomi, choku, itionG);
  }

  componentWillReceiveProps(nextProps) {
    if (!this.props.InspectorData.updated && nextProps.InspectorData.updated) {
      this.requestInspector();
      this.setState({editModal: false});
    }
  }

  render() {
    const { InspectorData } = this.props;
    const { yomi, choku, itionG, status, editModal, editting } = this.state;

    return (
      <div id="inspector">
        <div className="refine-wrap bg-white">
          <div className="refine">
            <div className="yomi">
              <p>ヨミ</p>
              <input
                type="text"
                value={this.state.yomi}
                onChange={e => this.setState(
                  {yomi: e.target.value},
                  () => this.requestInspector()
                )}
              />
            </div>
            <div className="choku">
              <p>直</p>
              <Select
                name="直"
                placeholder="直を選択"
                styles={{height: 30}}
                clearable={false}
                Searchable={true}
                value={this.state.choku}
                options={[
                  {label: '全直', value: ['W', 'Y', 'B']},
                  {label: '白直', value: ['W']},
                  {label: '黄直', value: ['Y']},
                  {label: '黒直', value: ['B']}
                ]}
                onChange={value => this.setState(
                  {choku: value},
                  () => this.requestInspector()
                )}
              />
            </div>
            <div className="inspection">
              <p>検査</p>
              <Select
                name="検査"
                placeholder="検査を選択"
                styles={{height: 30}}
                clearable={false}
                Searchable={true}
                value={this.state.itionG}
                options={[
                  {label: '全て', value: 'all'},
                  {label: '成型工程ライン① インナー外観検査', value: 1},
                  {label: '成型工程ライン① アウター外観検査', value: 5},
                  {label: '成型工程ライン② インナー外観検査', value: 2},
                  {label: '成型工程ライン② アウター外観検査', value: 6},
                  {label: '穴あけ工程 インナー外観検査', value: 15},
                  {label: '穴あけ工程 インナー穴検査', value: 4},
                  {label: '穴あけ工程 アウター穴検査', value: 8},
                  {label: '接着工程 簡易CF', value: 16},
                  {label: '接着工程 止水', value: 10},
                  {label: '接着工程 仕上', value: 11},
                  {label: '接着工程 検査', value: 12},
                  {label: '接着工程 手直', value: 14}
                ]}
                onChange={value => this.setState(
                  {itionG: value},
                  () => this.requestInspector()
                )}
              />
            </div>
            <div className="status">
              <p>状態</p>
              <Select
                name="状態"
                placeholder="状態を選択"
                styles={{height: 30}}
                clearable={false}
                Searchable={true}
                value={this.state.status}
                options={[
                  {label: '全て', value: [0,1]},
                  {label: '非表示中', value: [0]},
                  {label: '表示中', value: [1]},
                ]}
                onChange={value => this.setState(
                  {status: value},
                  () => this.requestInspector()
                )}
              />
            </div>
          </div>
        </div>
        <div className="body bg-white">
          <button className="create-btn">新規登録</button>
          <table>
            <thead>
              <tr>
                <th colSpan={1} rowSpan={3}>No.</th>
                <th colSpan={1} rowSpan={3}>名前</th>
                <th colSpan={1} rowSpan={3}>ヨミ</th>
                <th colSpan={1} rowSpan={3}>直</th>
                <th colSpan={2}>成型工程ライン①</th>
                <th colSpan={2}>成型工程ライン②</th>
                <th colSpan={3}>穴あけ工程</th>
                <th colSpan={5}>接着工程</th>
                <th colSpan={1} rowSpan={3}>機能</th>
              </tr>
              <tr>
                <th colSpan={1}>インナー</th>
                <th colSpan={1}>アウター</th>
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
                    <tr className="content" key={i}>
                      <td>{i+1}</td>
                      <td>{itor.name}</td>
                      <td>{itor.yomi}</td>
                      <td>{itor.chokuName}</td>
                      <td>{itor.ig.find(ig => ig.id == 1) ? itor.ig.find(ig => ig.id == 1).sort : ''}</td>
                      <td>{itor.ig.find(ig => ig.id == 5) ? itor.ig.find(ig => ig.id == 5).sort : ''}</td>
                      <td>{itor.ig.find(ig => ig.id == 2) ? itor.ig.find(ig => ig.id == 2).sort : ''}</td>
                      <td>{itor.ig.find(ig => ig.id == 6) ? itor.ig.find(ig => ig.id == 6).sort : ''}</td>
                      <td>{itor.ig.find(ig => ig.id == 15) ? itor.ig.find(ig => ig.id == 15).sort : ''}</td>
                      <td>{itor.ig.find(ig => ig.id == 4) ? itor.ig.find(ig => ig.id == 4).sort : ''}</td>
                      <td>{itor.ig.find(ig => ig.id == 8) ? itor.ig.find(ig => ig.id == 8).sort : ''}</td>
                      <td>{itor.ig.find(ig => ig.id == 16) ? itor.ig.find(ig => ig.id == 16).sort : ''}</td>
                      <td>{itor.ig.find(ig => ig.id == 10) ? itor.ig.find(ig => ig.id == 10).sort : ''}</td>
                      <td>{itor.ig.find(ig => ig.id == 11) ? itor.ig.find(ig => ig.id == 11).sort : ''}</td>
                      <td>{itor.ig.find(ig => ig.id == 12) ? itor.ig.find(ig => ig.id == 12).sort : ''}</td>
                      <td>{itor.ig.find(ig => ig.id == 14) ? itor.ig.find(ig => ig.id == 14).sort : ''}</td>
                      <td>
                        <button onClick={() => this.setState({
                          editModal: false
                        })}>
                          非表示
                        </button>
                        <button onClick={() => this.setState({
                          editModal: true,
                          editting: itor
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
                <td colSpan="17">結果なし</td>
              </tr>
            }
            </tbody>
          </table>
          {
            editModal &&
            <Edit
              id={editting.id}
              name={editting.name}
              yomi={editting.yomi}
              choku={{value: editting.chokuCode, label: editting.chokuName}}
              itionG={editting.ig}
              message={InspectorData.message}
              close={() => this.setState({editModal: false})}
              update={(id, name, yomi, choku, itionG) => this.updateInspector(id, name, yomi, choku, itionG)}
            />
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
