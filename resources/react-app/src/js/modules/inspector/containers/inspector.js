import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
import iconCheck from './check.svg';
// Actions
import { inspectorActions } from '../ducks/inspector';
// Material-ui Components
import { Paper, Dialog, RaisedButton, FlatButton } from 'material-ui';
import { grey50, indigo500 } from 'material-ui/styles/colors';
// Styles
import './inspector.scss';
// Components
import Edit from '../components/edit/edit';
import Create from '../components/create/create';

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
      editting: null,
      createModal: false,
      sort: {
        key: 'yomi',
        asc: false,
        id: 0
      }
    };
  }

  requestInspector() {
    const { yomi, choku, itionG, status, editting } = this.state;
    this.props.actions.getInspectors(yomi, choku.value, itionG.value, status.value);
  }

  updateInspector(id, name, yomi, choku, itionG) {
    const { updateInspector } = this.props.actions;
    updateInspector(id, name, yomi, choku, itionG);
  }

  createInspector(name, yomi, choku, itionG) {
    const { createInspector } = this.props.actions;
    createInspector(name, yomi, choku, itionG);
  }

  componentWillReceiveProps(nextProps) {
    if (!this.props.InspectorData.updated && nextProps.InspectorData.updated) {
      this.requestInspector();
      this.setState({
        editModal: false,
        createModal: false
      });
    }
  }

  sortData(data) {
    const { sort } = this.state;
    data.sort((a,b) => {
      let aaa = 0;
      let bbb = 0;

      if (sort.key == 'yomi') {
        aaa = a[sort.key].toLowerCase();
        bbb = b[sort.key].toLowerCase();
      }
      else if (sort.key == 'chokuName') {
        aaa = a[sort.key].toLowerCase();
        bbb = b[sort.key].toLowerCase();
      }
      else if (sort.key == 'ig') {
        if (a[sort.key].find(ig => ig.id == sort.id)) {
          aaa = a[sort.key].find(ig => ig.id == sort.id).sort;
        }
        else {
          aaa = 10000;
        }
        if (b[sort.key].find(ig => ig.id == sort.id)) {
          bbb = b[sort.key].find(ig => ig.id == sort.id).sort;
        }
        else {
          bbb = 10000;
        }
      }

      if (sort.asc) {
        if(aaa < bbb) return 1;
        else if(aaa > bbb) return -1;
      } else {
        if(aaa < bbb) return -1;
        else if(aaa > bbb) return 1;
      }
      return 0;
    });

    return data;
  }

  render() {
    const { InspectorData } = this.props;
    const { yomi, choku, itionG, status, editModal, editting, createModal, sort } = this.state;

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
                  {label: '成形工程ライン① インナー外観検査', value: 1},
                  {label: '成形工程ライン① アウター外観検査', value: 5},
                  {label: '成形工程ライン② インナー外観検査', value: 2},
                  {label: '成形工程ライン② アウター外観検査', value: 6},
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
          <button
            className="create-btn"
            onClick={() => this.setState({createModal: true})}
          >
            新規登録
          </button>
          <table>
            <thead>
              <tr>
                <th colSpan={1} rowSpan={3}>No.</th>
                <th colSpan={1} rowSpan={3}>名前</th>
                <th
                  colSpan={1}
                  rowSpan={3}
                  className={`clickable ${sort.key == 'yomi' ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'yomi') this.setState({sort: { key: 'yomi', asc: !sort.asc, id: 0 }});
                    else this.setState({sort: { key: 'yomi', asc: false, id: 0 }});
                  }}
                >
                  ヨミ
                </th>
                <th
                  colSpan={1}
                  rowSpan={3}
                  className={`clickable ${sort.key == 'chokuName' ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'chokuName') this.setState({sort: { key: 'chokuName', asc: !sort.asc, id: 0 }});
                    else this.setState({sort: { key: 'chokuName', asc: false, id: 0 }});
                  }}
                >
                  直
                </th>
                <th colSpan={2}>成形工程ライン①</th>
                <th colSpan={2}>成形工程ライン②</th>
                <th colSpan={3}>穴あけ工程</th>
                <th colSpan={5}>接着工程</th>
                <th colSpan={1} rowSpan={3}>iPad<br/>表示</th>
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
                <th
                  colSpan={1}
                  className={`clickable ${(sort.key == 'ig' && sort.id === 1) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'ig') this.setState({sort: { key: 'ig', asc: !sort.asc, id: 1 }});
                    else this.setState({sort: { key: 'ig', asc: false, id: 1 }});
                  }}
                >
                  外観検査
                </th>
                <th
                  colSpan={1}
                  className={`clickable ${(sort.key == 'ig' && sort.id === 5) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'ig') this.setState({sort: { key: 'ig', asc: !sort.asc, id: 5 }});
                    else this.setState({sort: { key: 'ig', asc: false, id: 5 }});
                  }}
                >
                  外観検査
                </th>
                <th
                  colSpan={1}
                  className={`clickable ${(sort.key == 'ig' && sort.id === 2) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'ig') this.setState({sort: { key: 'ig', asc: !sort.asc, id: 2 }});
                    else this.setState({sort: { key: 'ig', asc: false, id: 2 }});
                  }}
                >
                  外観検査
                </th>
                <th
                  colSpan={1}
                  className={`clickable ${(sort.key == 'ig' && sort.id === 6) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'ig') this.setState({sort: { key: 'ig', asc: !sort.asc, id: 6 }});
                    else this.setState({sort: { key: 'ig', asc: false, id: 6 }});
                  }}
                >
                  外観検査
                </th>
                <th
                  colSpan={1}
                  className={`clickable ${(sort.key == 'ig' && sort.id === 15) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'ig') this.setState({sort: { key: 'ig', asc: !sort.asc, id: 15 }});
                    else this.setState({sort: { key: 'ig', asc: false, id: 15 }});
                  }}
                >
                  外観検査
                </th>
                <th
                  colSpan={1}
                  className={`clickable ${(sort.key == 'ig' && sort.id === 4) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'ig') this.setState({sort: { key: 'ig', asc: !sort.asc, id: 4 }});
                    else this.setState({sort: { key: 'ig', asc: false, id: 4 }});
                  }}
                >
                  穴検査
                </th>
                <th
                  colSpan={1}
                  className={`clickable ${(sort.key == 'ig' && sort.id === 8) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'ig') this.setState({sort: { key: 'ig', asc: !sort.asc, id: 8 }});
                    else this.setState({sort: { key: 'ig', asc: false, id: 8 }});
                  }}
                >
                  穴検査
                </th>
                <th
                  colSpan={1}
                  className={`clickable ${(sort.key == 'ig' && sort.id === 16) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'ig') this.setState({sort: { key: 'ig', asc: !sort.asc, id: 16 }});
                    else this.setState({sort: { key: 'ig', asc: false, id: 16 }});
                  }}
                >
                  簡易CF
                </th>
                <th
                  colSpan={1}
                  className={`clickable ${(sort.key == 'ig' && sort.id === 10) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'ig') this.setState({sort: { key: 'ig', asc: !sort.asc, id: 10 }});
                    else this.setState({sort: { key: 'ig', asc: false, id: 10 }});
                  }}
                >
                  止水
                </th>
                <th
                  colSpan={1}
                  className={`clickable ${(sort.key == 'ig' && sort.id === 11) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'ig') this.setState({sort: { key: 'ig', asc: !sort.asc, id: 11 }});
                    else this.setState({sort: { key: 'ig', asc: true, id: 11 }});
                  }}
                >
                  仕上
                </th>
                <th
                  colSpan={1}
                  className={`clickable ${(sort.key == 'ig' && sort.id === 12) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'ig') this.setState({sort: { key: 'ig', asc: !sort.asc, id: 12 }});
                    else this.setState({sort: { key: 'ig', asc: true, id: 12 }});
                  }}
                >
                  検査
                </th>
                <th
                  colSpan={1}
                  className={`clickable ${(sort.key == 'ig' && sort.id === 14) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'ig') this.setState({sort: { key: 'ig', asc: !sort.asc, id: 14 }});
                    else this.setState({sort: { key: 'ig', asc: true, id: 14 }});
                  }}
                >
                  手直
                </th>
              </tr>
            </thead>
            <tbody>
            {
              InspectorData.data && InspectorData.data.length != 0 &&
              this.sortData(InspectorData.data).map((itor, i)=> 
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
                      {
                        itor.status == 1 ?
                        <img
                          className="icon-checked"
                          src={iconCheck}
                          alt="iconCheck"
                          onClick={() => this.props.actions.deactivateInspector(itor.id)}
                        /> :
                        <div
                          className="icon-check"
                          onClick={() => this.props.actions.activateInspector(itor.id)}
                        ></div>
                      }
                      </td>
                      <td>
                        <button
                          className="dark edit"
                          onClick={() => this.setState({
                            editModal: true,
                            editting: itor
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
          }{
            createModal &&
            <Create
              close={() => this.setState({createModal: false})}
              create={(name, yomi, choku, itionG) => this.createInspector(name, yomi, choku, itionG)}
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
