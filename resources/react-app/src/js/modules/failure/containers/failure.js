import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
// Images
import iconCheck from '../../../../assets/img/icon/check.svg';
// Actions
import { failureActions } from '../ducks/failure';
// Material-ui Components
import { Paper, Dialog, RaisedButton, FlatButton } from 'material-ui';
import { grey50, indigo500 } from 'material-ui/styles/colors';
// Styles
import './failure.scss';
// Components
import Edit from '../components/edit/edit';
import Create from '../components/create/create';

class Failure extends Component {
  constructor(props, context) {
    super(props, context);

    let name = '';
    let inspection = 'all';
    let status = [1];

    this.props.actions.getFailures(name, inspection, status);

    this.state = {
      name: name,
      inspection: {label: '全て', value: inspection},
      status: {label: '表示中', value: status},
      editModal: false,
      editting: null,
      createModal: false,
      sort: {
        key: 'label',
        asc: false,
        id: 0
      }
    };
  }

  requestFailure() {
    const { getFailures } = this.props.actions;
    const { name, inspection, status } = this.state;

    getFailures(name, inspection.value, status.value);
  }

  updateFailure(id, name, label, is) {
    const { updateFailure } = this.props.actions;
    updateFailure(id, name, label, is);
  }

  createFailure(name, label, is) {
    const { createFailure } = this.props.actions;
    createFailure(name, label, is);
  }

  componentWillReceiveProps(nextProps) {
    if (!this.props.maintFailureData.updated && nextProps.maintFailureData.updated) {
      this.requestFailure();
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

      if (sort.key == 'name') {
        aaa = a[sort.key].toLowerCase();
        bbb = b[sort.key].toLowerCase();
      }
      else if (sort.key == 'label') {
        aaa = a[sort.key];
        bbb = b[sort.key];
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
    const { maintFailureData } = this.props;
    const { name, choku, inspection, status, editModal, editting, createModal, sort } = this.state;

    return (
      <div id="failure">
        <div className="refine-wrap bg-white">
          <div className="refine">
            <div className="name">
              <p>不良名</p>
              <input
                type="text"
                value={this.state.name}
                onChange={e => this.setState(
                  {name: e.target.value},
                  () => this.requestFailure()
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
                value={this.state.inspection}
                options={[
                  {label: '全て', value: 'all'},
                  {label: '成形工程 外観検査', value: 1},
                  {label: '穴あけ工程 外観検査', value: 10},
                  {label: '穴あけ工程 穴検査', value: 3},
                  {label: '接着工程 簡易CF', value: 11},
                  {label: '接着工程 止水', value: 5},
                  {label: '接着工程 仕上', value: 6},
                  {label: '接着工程 検査', value: 7},
                  {label: '接着工程 手直', value: 9}
                ]}
                onChange={value => this.setState(
                  {inspection: value},
                  () => this.requestFailure()
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
                  () => this.requestFailure()
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
                <th colSpan={1} rowSpan={3}>不良名</th>
                <th
                  colSpan={1}
                  rowSpan={3}
                  className={`clickable ${sort.key == 'chokuName' ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'chokuName') this.setState({sort: { key: 'chokuName', asc: !sort.asc, id: 0 }});
                    else this.setState({sort: { key: 'chokuName', asc: false, id: 0 }});
                  }}
                >
                  番号
                </th>
                <th colSpan={1}>成形工程</th>
                <th colSpan={2}>穴あけ工程</th>
                <th colSpan={5}>接着工程</th>
                <th colSpan={1} rowSpan={3}>iPad<br/>表示</th>
                <th colSpan={1} rowSpan={3}>機能</th>
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
              maintFailureData.data && maintFailureData.data.length != 0 &&
              this.sortData(maintFailureData.data).map((f, i)=> 
                {
                  return(
                    <tr className="content" key={i}>
                      <td>{i+1}</td>
                      <td>{f.name}</td>
                      <td>{f.label}</td>
                      <td>
                      {
                        f.inspections.find(i => i.id == 1) ?
                        <div className="failure-status">
                          <div className={`failure-type ${f.inspections.find(i => i.id == 1).type === 1 ? 'important' : ''}`}>{f.inspections.find(i => i.id == 1).type === 1 ? '重要' : '普通'}</div>
                          <div className="failure-sort">{`表示順：${f.inspections.find(i => i.id == 1).sort}`}</div>
                        </div> :
                        <div></div>
                      }
                      </td>
                      <td>
                      {
                        f.inspections.find(i => i.id == 10) ?
                        <div className="failure-status">
                          <div className={`failure-type ${f.inspections.find(i => i.id == 10).type === 1 ? 'important' : ''}`}>{f.inspections.find(i => i.id == 10).type === 1 ? '重要' : '普通'}</div>
                          <div className="failure-sort">{`表示順：${f.inspections.find(i => i.id == 10).sort}`}</div>
                        </div> :
                        <div></div>
                      }
                      </td>
                      <td>
                      {
                        f.inspections.find(i => i.id == 3) ?
                        <div className="failure-status">
                          <div className={`failure-type ${f.inspections.find(i => i.id == 3).type === 1 ? 'important' : ''}`}>{f.inspections.find(i => i.id == 3).type === 1 ? '重要' : '普通'}</div>
                          <div className="failure-sort">{`表示順：${f.inspections.find(i => i.id == 3).sort}`}</div>
                        </div> :
                        <div></div>
                      }
                      </td>
                      <td>
                      {
                        f.inspections.find(i => i.id == 11) ?
                        <div className="failure-status">
                          <div className={`failure-type ${f.inspections.find(i => i.id == 11).type === 1 ? 'important' : ''}`}>{f.inspections.find(i => i.id == 11).type === 1 ? '重要' : '普通'}</div>
                          <div className="failure-sort">{`表示順：${f.inspections.find(i => i.id == 11).sort}`}</div>
                        </div> :
                        <div></div>
                      }
                      </td>
                      <td>
                      {
                        f.inspections.find(i => i.id == 5) ?
                        <div className="failure-status">
                          <div className={`failure-type ${f.inspections.find(i => i.id == 5).type === 1 ? 'important' : ''}`}>{f.inspections.find(i => i.id == 5).type === 1 ? '重要' : '普通'}</div>
                          <div className="failure-sort">{`表示順：${f.inspections.find(i => i.id == 5).sort}`}</div>
                        </div> :
                        <div></div>
                      }
                      </td>
                      <td>
                      {
                        f.inspections.find(i => i.id == 6) ?
                        <div className="failure-status">
                          <div className={`failure-type ${f.inspections.find(i => i.id == 6).type === 1 ? 'important' : ''}`}>{f.inspections.find(i => i.id == 6).type === 1 ? '重要' : '普通'}</div>
                          <div className="failure-sort">{`表示順：${f.inspections.find(i => i.id == 6).sort}`}</div>
                        </div> :
                        <div></div>
                      }
                      </td>
                      <td>
                      {
                        f.inspections.find(i => i.id == 7) ?
                        <div className="failure-status">
                          <div className={`failure-type ${f.inspections.find(i => i.id == 7).type === 1 ? 'important' : ''}`}>{f.inspections.find(i => i.id == 7).type === 1 ? '重要' : '普通'}</div>
                          <div className="failure-sort">{`表示順：${f.inspections.find(i => i.id == 7).sort}`}</div>
                        </div> :
                        <div></div>
                      }
                      </td>
                      <td>
                      {
                        f.inspections.find(i => i.id == 9) ?
                        <div className="failure-status">
                          <div className={`failure-type ${f.inspections.find(i => i.id == 9).type === 1 ? 'important' : ''}`}>{f.inspections.find(i => i.id == 9).type === 1 ? '重要' : '普通'}</div>
                          <div className="failure-sort">{`表示順：${f.inspections.find(i => i.id == 9).sort}`}</div>
                        </div> :
                        <div></div>
                      }
                      </td>
                      <td>
                      {
                        f.status == 1 ?
                        <img
                          className="icon-checked"
                          src={iconCheck}
                          alt="iconCheck"
                          onClick={() => this.props.actions.deactivateFailure(f.id)}
                        /> :
                        <div
                          className="icon-check"
                          onClick={() => this.props.actions.activateFailure(f.id)}
                        ></div>
                      }
                      </td>
                      <td>
                        <button
                          className="dark edit"
                          onClick={() => this.setState({
                            editModal: true,
                            editting: f
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
              maintFailureData.data && maintFailureData.data.length == 0 &&
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
              label={editting.label}
              inspections={editting.inspections}
              message={maintFailureData.message}
              meta={maintFailureData.meta}
              close={() => this.setState({editModal: false})}
              update={(id, name, label, is) => this.updateFailure(id, name, label, is)}
            />
          }{
            createModal &&
            <Create
              close={() => this.setState({createModal: false})}
              create={(name, label, is) => this.createFailure(name, label, is)}
            />
          }
        </div>
      </div>
    );
  }
}

Failure.propTypes = {
  maintFailureData: PropTypes.object.isRequired
};

function mapStateToProps(state, ownProps) {
  return {
    maintFailureData: state.maintFailureData
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({}, failureActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Failure);
