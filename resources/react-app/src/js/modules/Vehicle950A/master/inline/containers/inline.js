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

    let partTypePns = {
      label: '全て',
      value: [6714111020, 6715111020, 6714211020, 6715211020, 6441211010, 6441211020, 6701511020, 6701611020, 6440111010, 6440111020],
    };

    this.props.actions.getInlines(partTypePns.value);

    this.state = {
      partTypePns: partTypePns,
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
    const { partTypePns } = this.state;

    getInlines(partTypePns.value);
  }

  updateInline(id, max, min, max2, min2) {
    const { updateInline } = this.props.actions;
    updateInline(id, max, min, max2, min2);
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
      <div id="inline">
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
                value={this.state.partTypePns}
                options={[
                  {label: '全て', value: [6714111020, 6715111020, 6714211020, 6715211020, 6441211010, 6441211020, 6701511020, 6701611020, 6440111010, 6440111020]},
                  {label: 'ドアインナL', value: [6714211020]},
                  {label: 'ドアインナR', value: [6714111020]},
                  {label: 'リンフォースL', value: [6715211020]},
                  {label: 'リンフォースR', value: [6715111020]},
                  {label: 'ラゲージインナSTD', value: [6441211010]},
                  {label: 'ラゲージインナARW', value: [6441211020]},
                  {label: 'ドアASSY LH', value: [6701611020]},
                  {label: 'ドアASSY RH', value: [6701511020]},
                  {label: 'ラゲージASSY STD', value: [  6440111010]},
                  {label: 'ラゲージASSY ARW', value: [6440111020]},
                ]}
                onChange={value => this.setState(
                  {partTypePns: value},
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
                  rowSpan={1}
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
                  rowSpan={1}
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
                  rowSpan={1}
                >
                  部品名
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
                <th colSpan={1} rowSpan={1}>機能</th>
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
                      <td>{`${inline.label} ${inline.position}`}</td>
                      <td>{inline.partName}</td>
                      <td>{inline.max.toFixed(3)}</td>
                      <td>{inline.min.toFixed(3)}</td>
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
              sort={`${editting.label} ${editting.position}`}
              partName={editting.partName}
              max={editting.max}
              min={editting.min}

              close={() => this.setState({editModal: false})}
              update={(id, max, min) => this.updateInline(id, max, min)}
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
