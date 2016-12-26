import React, { Component, PropTypes } from 'react';
import Select from 'react-select';
// Styles
import './create.scss';

class Create extends Component {
  constructor(props, context) {
    super(props, context);
    this.state = {
      name: '',
      label: '',
      inspections: []
    };
  }

  render() {
    const { name, label, inspections } = this.state;
    const inspectionIds = [1,10,3,11,5,6,7,9];

    return (
      <div>
        <div className="modal">
        </div>
        <div className="edit-wrap">
          <div className="panel-btn" onClick={() => this.props.close()}>
            <span className="panel-btn-close"></span>
          </div>
          <p className="title">新規担当者登録</p>
          <div className="edit">
            <div className="name">
              <p>名前</p>
              <input
                type="text"
                value={this.state.name}
                onChange={e => this.setState({name: e.target.value})}
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
                onChange={e => this.setState({label: e.target.value})}
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
                <th colSpan={1}>成形工程</th>
                <th colSpan={2}>穴あけ工程</th>
                <th colSpan={5}>接着工程</th>
              </tr>
              <tr>
                <th colSpan={1}>外観検査</th>
                <th colSpan={1}>外観検査</th>
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
              {
                inspectionIds.map(iID => 
                  inspections.find(ig => ig.id == iID) ?
                  <td key={iID}>
                    <input
                      type="number"
                      value={inspections.find(ig => ig.id == iID) ? inspections.find(ig => ig.id == iID).sort : null}
                      onChange={e => this.setState({
                        inspections: inspections.map(ig => ig.id == iID ? Object.assign(ig, {sort: e.target.value}) : ig)
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
                        inspections: inspections.filter(ig => ig.id !== iID)
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
            <button onClick={() => this.props.create(name, label, inspections)}>
              登録
            </button>
          </div>
        </div>
      </div>
    );
  }
};

Create.propTypes = {
  close: PropTypes.func,
  create: PropTypes.func,
};

export default Create;
