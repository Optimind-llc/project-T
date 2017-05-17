import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
// Actions
import { push } from 'react-router-redux';
import { maintFailureActions } from '../ducks/maintFailure';
// Styles
import './failure.scss';
// import iconCheck from '../../../../../../assets/img/icon/check.png';
// Components
import Edit from '../components/edit/edit';
import Create from '../components/create/create';

class Failure extends Component {
  constructor(props, context) {
    super(props, context);
    const { Inspections, MappingData, actions } = props;

    let name = '';
    let inspection = 'all';

    actions.requestFailures();

    this.state = {
      name: name,
      status: {label: '表示中', value: [1]},
      editModal: false,
      editting: null,
      createModal: false,
      sort: {
        key: 'label',
        asc: false,
        id: 0
      }
    };  }

  componentWillUnmount() {
   clearInterval(this.state.intervalId); 
  }

  sortData(data) {
    const { sort } = this.state;
    return data.slice().sort((a,b) => {
      let aaa = 0;
      let bbb = 0;

      if (sort.key == 'label') {
        aaa = a[sort.key];
        bbb = b[sort.key];
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
  }

  render() {
    const { name, status, sort, editModal, editting, createModal } = this.state;
    const { FailureTypes, actions } = this.props;

    return (
      <div id="press-maint-failureType-wrap">
        {/*<div className="filter-wrap bg-white">
          <div className="name">
            <p>不良名</p>
            <input
              type="text"
              value={name}
              onChange={e => this.setState(
                {name: e.target.value},
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
              value={status}
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
        </div>*/}
        <div className="result-wrap bg-white">
          {
            FailureTypes.message === 'over limit' &&
            <p className="error-message-over-limit">不良区分の表示上限16を超えています</p>
          }
          <button
            className="create-btn"
            onClick={() => this.setState({createModal: true})}
          >
            新規登録
          </button>
          <table>
            <thead>
              <tr>
                <th colSpan={1}>No.</th>
                <th colSpan={1}>不良名</th>
                <th colSpan={1}>表示番号</th>
                <th colSpan={1}>iPad表示</th>
                <th colSpan={1}>機能</th>
              </tr>
            </thead>
            <tbody>
            {
              FailureTypes.data && FailureTypes.data.length !== 0 &&
              this.sortData(FailureTypes.data).map((f, i)=> 
                {
                  return(
                    <tr className="content" key={i}>
                      <td>{i+1}</td>
                      <td>{f.name}</td>
                      <td>{f.label}</td>
                      <td>
                      {
                        f.status == 1 ?
                        <img
                          className="icon-checked"
                          src="/img/icon/check.png"
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
              FailureTypes.data && FailureTypes.data.length == 0 &&
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
              message={FailureTypes.message}
              close={() => {
                actions.clearMessage();
                this.setState({editModal: false});
              }}
              update={(id, name, label) => actions.updateFailure(id, name, label)}
            />
          }{
            createModal &&
            <Create
              message={FailureTypes.message}
              close={() => {
                actions.clearMessage();
                this.setState({createModal: false});
              }}
              create={(name, label) => actions.createFailure(name, label)}
            />
          }
        </div>
      </div>
    );
  }
}

Failure.propTypes = {
  FailureTypes: PropTypes.array.isRequired
};

function mapStateToProps(state, ownProps) {
  return {
    FailureTypes: state.PressMaintFailureType
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({push}, maintFailureActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Failure);
