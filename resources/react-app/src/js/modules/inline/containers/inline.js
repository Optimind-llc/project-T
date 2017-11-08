import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
// Actions
import { inlineActions } from '../ducks/inline';
// Styles
import './inline.scss';
// Components
import Edit from '../components/edit/edit';

class Inline extends Component {
  constructor(props, context) {
    super(props, context);

    let partTypeIds = [1,7];

    this.props.actions.getInlines(partTypeIds);

    this.state = {
      partTypeIds: partTypeIds,
      editModal: false,
      editting: null,
      sort: {
        key: 'id',
        asc: false,
        id: 0
      }
    };
  }

  requestInlines() {
    const { getInlines } = this.props.actions;
    const { partTypeIds } = this.state;

    getInlines(partTypeIds);
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
    if (!this.props.maintInlineData.updated && nextProps.maintInlineData.updated) {
      this.requestInlines();
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

      if (sort.key == 'id' || sort.key == 'sort') {
        aaa = a[sort.key];
        bbb = b[sort.key];
      }
      else if (sort.key == 'partName') {
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

    return data;
  }

  render() {
    const { maintInlineData } = this.props;
    const { yomi, choku, itionG, status, editModal, editting, createModal, sort } = this.state;

    return (
      <div id="inspector">
        <div className="refine-wrap bg-white">
          <div className="refine">
            <div className="choku">
              <p>部品</p>
              <Select
                name="部品"
                placeholder="部品を選択"
                styles={{height: 30}}
                clearable={false}
                Searchable={true}
                value={this.state.partTypeIds}
                options={[
                  {label: '全て', value: [1,7]},
                  {label: 'バックドアインナ', value: [1]},
                  {label: 'バックドアインナASSY', value: [7]},
                ]}
                onChange={value => this.setState(
                  {partTypeIds: value},
                  () => this.requestInlines()
                )}
              />
            </div>
          </div>
        </div>
        <div className="body bg-white">
          <table>
            <thead>
              <tr>
                <th
                  colSpan={1}
                  rowSpan={3}
                  className={`clickable ${sort.key == 'id' ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'id') this.setState({sort: { key: 'id', asc: !sort.asc, id: 0 }});
                    else this.setState({sort: { key: 'id', asc: false, id: 0 }});
                  }}
                >
                  No.
                </th>
                <th
                  colSpan={1}
                  rowSpan={3}
                  className={`clickable ${sort.key == 'sort' ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'sort') this.setState({sort: { key: 'sort', asc: !sort.asc, id: 0 }});
                    else this.setState({sort: { key: 'sort', asc: false, id: 0 }});
                  }}
                >
                  ラベル
                </th>
                <th
                  colSpan={1}
                  rowSpan={3}
                >
                  部品名
                </th>
                <th colSpan={2}>ライン①</th>
                <th colSpan={2}>ライン②</th>
                <th colSpan={1} rowSpan={3}>機能</th>
              </tr>
              <tr>
                <th
                  colSpan={1}
                >
                  Max
                </th>
                <th
                  colSpan={1}
                >
                  Min
                </th>
                <th
                  colSpan={1}
                >
                  Max
                </th>
                <th
                  colSpan={1}
                >
                  Min
                </th>
              </tr>
            </thead>
            <tbody>
            {
              maintInlineData.data && maintInlineData.data.length != 0 &&
              this.sortData(maintInlineData.data).map((inline, i)=> 
                {
                  return(
                    <tr className="content" key={i}>
                      <td>{inline.id}</td>
                      <td>{inline.sort}</td>
                      <td>{inline.partName}</td>
                      <td>{inline.max1.toFixed(3)}</td>
                      <td>{inline.min1.toFixed(3)}</td>
                      <td>{inline.max2 !== null ? inline.max2.toFixed(3) : ''}</td>
                      <td>{inline.min2 !== null ? inline.min2.toFixed(3) : ''}</td>
                      <td>
                        <button
                          className="dark edit"
                          onClick={() => this.setState({
                            editModal: true,
                            editting: inline
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
              maintInlineData.data && maintInlineData.data.length == 0 &&
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
              sort={editting.sort}
              partNmae={editting.partNmae}
              max1={editting.max1}
              min1={editting.min1}
              max2={editting.max2}
              min2={editting.min2}

              close={() => this.setState({editModal: false})}
              update={(id, name, yomi, choku, itionG) => this.updateInspector(id, name, yomi, choku, itionG)}
            />
          }
        </div>
      </div>
    );
  }
}

Inline.propTypes = {
  maintInlineData: PropTypes.object.isRequired,
};

function mapStateToProps(state, ownProps) {
  return {
    maintInlineData: state.maintInlineData
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({}, inlineActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Inline);
