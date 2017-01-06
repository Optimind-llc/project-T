import React, { Component, PropTypes } from 'react';
import Select from 'react-select';
// Styles
import './edit.scss';

class Edit extends Component {
  constructor(props, context) {
    super(props, context);
    this.state = {
      id: props.id,
      name: props.name,
      label: props.label,
      inspections: props.inspections
    };
  }

  render() {
    const { id, name, label, inspections } = this.state;
    const inspectionIds = [11,5,6,7,9];
console.log(this.state);
    return (
      <div>
        <div className="modal">
        </div>
        <div className="edit-wrap">
          <div className="panel-btn" onClick={() => this.props.close()}>
            <span className="panel-btn-close"></span>
          </div>
          <p className="title">担当者情報編集</p>
          <div className="edit">
            <div className="name">
              <p>名前</p>
              <input
                type="text"
                value={this.state.name}
                onChange={e => {
                  if (e.target.value.length <= 6) {
                    this.setState({name: e.target.value});
                  }
                }}
              />
              {
                this.props.message == 'duplicate failure name' &&
                <p className="error-message">同じ名前の不良区分がすでに登録されています</p>
              }
            </div>
            <div className="label">
              <p>番号</p>
              <input
                type="number"
                value={this.state.label}
                onChange={e => {
                  if (e.target.value >= 0 && e.target.value < 100) {
                    this.setState({label: e.target.value});
                  }
                }}
              />
              {
                this.props.message == 'duplicate failure label' &&
                <p className="error-message">同じ番号の不良区分がすでに登録されています</p>
              }
            </div>
          </div>
          {
            this.props.message == 'over limit of failures' &&
            <p className="error-message">{`${this.props.meta.inspection}の不良区分数が上限(${this.props.meta.limit})を超えてしまいます`}</p>
          }
          <table>
            <thead>
              <tr>
                <th colSpan={5}>接着工程</th>
              </tr>
              <tr>
                <th colSpan={1}>簡易CF</th>
                <th colSpan={1}>止水</th>
                <th colSpan={1}>仕上</th>
                <th colSpan={1}>検査</th>
                <th colSpan={1}>手直</th>
              </tr>
            </thead>
            <tbody>
              <tr className="content">
              {
                inspectionIds.map(iID =>
                  inspections.find(i => i.id == iID) ?
                  <td key={iID}>
                    <input
                      type="number"
                      value={inspections.find(i => i.id == iID) ? inspections.find(i => i.id == iID).sort : null}
                      onChange={e => this.setState({
                        inspections: inspections.map(i => i.id == iID ? Object.assing(i, {sort: e.target.value}) : i)
                      })}
                    />
                    <div className="failure-type-wrap">
                      <input
                        type="checkbox"
                        checked={inspections.find(i => i.id == iID) ? inspections.find(i => i.id == iID).type === 1 : null}
                        onChange={() => this.setState({
                          inspections: inspections.map(i => i.id == iID ? Object.assign(i, {type: i.type === 1 ? 2 : 1}) : i)
                        })}
                      />
                      <p>重要</p>
                    </div>
                    <div
                      className="panel-btn"
                      onClick={() => this.setState({
                        inspections: inspections.filter(i => i.id !== iID)
                      })}
                    >
                      <span className="panel-btn-close"></span>
                    </div>
                  </td> :
                  <td key={iID}>
                    <p className="null"></p>
                    <div
                      className="panel-btn"
                      onClick={() => this.setState({
                        inspections: [{id: iID, sort: 1, type: 2}, ...inspections]
                      })}
                    >
                      <span className="panel-btn-add"></span>
                    </div>
                  </td>
                )
              }
              </tr>
            </tbody>
          </table>
          <p className="explanation">※ 数字はiPadでの表示順</p>
          <div className="btn-wrap">
            <button onClick={() => this.props.update(id, name, label, inspections)}>
              保存
            </button>
          </div>
        </div>
      </div>
    );
  }
};

Edit.propTypes = {
  id: PropTypes.number,
  name: PropTypes.string,
  label: PropTypes.number,
  inspections: PropTypes.array,
  message: PropTypes.string,
  meta: PropTypes.object,
  close: PropTypes.func,
  update: PropTypes.func,
};

export default Edit;
