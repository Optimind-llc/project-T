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
    const { dCombination } = this.props;

    return (
      <div>
        <div className="modal">
        </div>
        <div className="edit-wrap">
          <div className="panel-btn" onClick={() => this.props.close()}>
            <span className="panel-btn-close"></span>
          </div>
          <p className="title">不良区分情報編集</p>
          {
            this.props.message == 'duplicate failure name' &&
            <p className="error-message">同じ名前の不良区分がすでに登録されています</p>
          }{
            this.props.message == 'duplicate failure label' &&
            <p className="error-message">同じ番号の不良区分がすでに登録されています</p>
          }{
            this.props.message == 'success' &&
            <p className="success-message">更新されました</p>
          }
          <div className="edit">
            <div className="name">
              <p>名前</p>
              <input
                type="text"
                value={this.state.name}
                onChange={e => this.setState({name: e.target.value})}
              />
            </div>
            <div className="label">
              <p>番号</p>
              <input
                type="number"
                value={this.state.label}
                onChange={e => this.setState({label: e.target.value})}
              />
            </div>
          </div>
          <div>
            <table>
              <thead>
                <tr>
                  <th colSpan={4} rowSpan={1}>成形</th>
                </tr>
                <tr>
                  <th colSpan={4} rowSpan={1}>外観検査</th>
                </tr>
                <tr>
                  <th colSpan={1} rowSpan={1}>DI</th><th colSpan={1} rowSpan={1}>LF</th><th colSpan={1} rowSpan={1}>LI</th><th colSpan={1} rowSpan={1}>LO</th>
                </tr>
              </thead>
              <tbody>
                <tr className="content">
                {
                  dCombination.filter(dc => dc.p === 'molding').map(dc => 
                    inspections.find(i => i.p == dc.p && i.i == dc.i && i.d == dc.d) ?
                    <td key={dc.p + dc.i + dc.d}>
                      <input
                        type="number"
                        value={inspections.find(i => i.p == dc.p && i.i == dc.i && i.d == dc.d).sort}
                        onChange={e => this.setState({
                            inspections: inspections.map(i =>
                              i.p == dc.p && i.i == dc.i && i.d == dc.d ? Object.assign(i, {sort: e.target.value}) : i
                            )
                        })}
                      />
                      <div className="failure-type-wrap">
                        <input
                          type="checkbox"
                          checked={inspections.find(i => i.p == dc.p && i.i == dc.i && i.d == dc.d).type === 1}
                          onChange={() => this.setState({
                            inspections: inspections.map(i =>
                              i.p == dc.p && i.i == dc.i && i.d == dc.d ? Object.assign(i, {type: i.type === 1 ? 2 : 1}) : i
                            )
                          })}
                        />
                        <p>重要</p>
                      </div>
                      <div
                        className="panel-btn" 
                        onClick={() => this.setState({
                          inspections: inspections.filter(i => i.p !== dc.p || i.i !== dc.i || i.d !== dc.d)
                        })}
                      >
                        <span className="panel-btn-close"></span>
                      </div>
                    </td> :
                    <td key={dc.p + dc.i + dc.d}>
                      <p className="null"></p>
                      <div
                        className="panel-btn"
                        onClick={() => this.setState({
                          inspections: [{p: dc.p, i: dc.i, d: dc.d, sort: 1, type: 2}, ...inspections]
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
            <table>
              <thead>
                <tr>
                  <th colSpan={8} rowSpan={1}>穴あけ</th>
                </tr>
                <tr>
                  <th colSpan={4} rowSpan={1}>洗浄前外観検査</th>
                  <th colSpan={4} rowSpan={1}>洗浄後外観検査</th>
                </tr>
                <tr>
                  <th colSpan={1} rowSpan={1}>DI</th><th colSpan={1} rowSpan={1}>LF</th><th colSpan={1} rowSpan={1}>LI</th><th colSpan={1} rowSpan={1}>LO</th>
                  <th colSpan={1} rowSpan={1}>DI</th><th colSpan={1} rowSpan={1}>LF</th><th colSpan={1} rowSpan={1}>LI</th><th colSpan={1} rowSpan={1}>LO</th>
                </tr>
              </thead>
              <tbody>
                <tr className="content">
                {
                  dCombination.filter(dc => dc.p === 'holing'  && (dc.i === 'maegaikan' || dc.i === 'atogaikan')).map(dc => 
                    inspections.find(i => i.p == dc.p && i.i == dc.i && i.d == dc.d) ?
                    <td key={dc.p + dc.i + dc.d}>
                      <input
                        type="number"
                        value={inspections.find(i => i.p == dc.p && i.i == dc.i && i.d == dc.d).sort}
                        onChange={e => this.setState({
                            inspections: inspections.map(i =>
                              i.p == dc.p && i.i == dc.i && i.d == dc.d ? Object.assign(i, {sort: e.target.value}) : i
                            )
                        })}
                      />
                      <div className="failure-type-wrap">
                        <input
                          type="checkbox"
                          checked={inspections.find(i => i.p == dc.p && i.i == dc.i && i.d == dc.d).type === 1}
                          onChange={() => this.setState({
                            inspections: inspections.map(i =>
                              i.p == dc.p && i.i == dc.i && i.d == dc.d ? Object.assign(i, {type: i.type === 1 ? 2 : 1}) : i
                            )
                          })}
                        />
                        <p>重要</p>
                      </div>
                      <div
                        className="panel-btn" 
                        onClick={() => this.setState({
                          inspections: inspections.filter(i => i.p !== dc.p || i.i !== dc.i || i.d !== dc.d)
                        })}
                      >
                        <span className="panel-btn-close"></span>
                      </div>
                    </td> :
                    <td key={dc.p + dc.i + dc.d}>
                      <p className="null"></p>
                      <div
                        className="panel-btn"
                        onClick={() => this.setState({
                          inspections: [{p: dc.p, i: dc.i, d: dc.d, sort: 1, type: 2}, ...inspections]
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
            <table>
              <thead>
                <tr>
                  <th colSpan={4} rowSpan={1}>穴検査</th>
                  <th colSpan={4} rowSpan={1}>手直</th>
                </tr>
                <tr>
                  <th colSpan={1} rowSpan={1}>DI</th><th colSpan={1} rowSpan={1}>LF</th><th colSpan={1} rowSpan={1}>LI</th><th colSpan={1} rowSpan={1}>LO</th>
                  <th colSpan={1} rowSpan={1}>DI</th><th colSpan={1} rowSpan={1}>LF</th><th colSpan={1} rowSpan={1}>LI</th><th colSpan={1} rowSpan={1}>LO</th>
                </tr>
              </thead>
              <tbody>
                <tr className="content">
                {
                  dCombination.filter(dc => dc.p === 'holing' && (dc.i === 'ana' || dc.i === 'tenaoshi')).map(dc => 
                    inspections.find(i => i.p == dc.p && i.i == dc.i && i.d == dc.d) ?
                    <td key={dc.p + dc.i + dc.d}>
                      <input
                        type="number"
                        value={inspections.find(i => i.p == dc.p && i.i == dc.i && i.d == dc.d).sort}
                        onChange={e => this.setState({
                            inspections: inspections.map(i =>
                              i.p == dc.p && i.i == dc.i && i.d == dc.d ? Object.assign(i, {sort: e.target.value}) : i
                            )
                        })}
                      />
                      <div className="failure-type-wrap">
                        <input
                          type="checkbox"
                          checked={inspections.find(i => i.p == dc.p && i.i == dc.i && i.d == dc.d).type === 1}
                          onChange={() => this.setState({
                            inspections: inspections.map(i =>
                              i.p == dc.p && i.i == dc.i && i.d == dc.d ? Object.assign(i, {type: i.type === 1 ? 2 : 1}) : i
                            )
                          })}
                        />
                        <p>重要</p>
                      </div>
                      <div
                        className="panel-btn" 
                        onClick={() => this.setState({
                          inspections: inspections.filter(i => i.p !== dc.p || i.i !== dc.i || i.d !== dc.d)
                        })}
                      >
                        <span className="panel-btn-close"></span>
                      </div>
                    </td> :
                    <td key={dc.p + dc.i + dc.d}>
                      <p className="null"></p>
                      <div
                        className="panel-btn"
                        onClick={() => this.setState({
                          inspections: [{p: dc.p, i: dc.i, d: dc.d, sort: 1, type: 2}, ...inspections]
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
            <table>
              <thead>
                <tr>
                  <th colSpan={8} rowSpan={1}>かしめ/接着</th>
                </tr>
                <tr>
                  <th colSpan={4} rowSpan={1}>かしめ後検査</th>
                  <th colSpan={1} rowSpan={1}>外周仕上</th>
                  <th colSpan={1} rowSpan={1}>パテ補修</th>
                  <th colSpan={1} rowSpan={1}>水研後</th>
                  <th colSpan={1} rowSpan={1}>塗装後</th>
                </tr>
                <tr>
                  <th colSpan={1} rowSpan={1}>DI</th><th colSpan={1} rowSpan={1}>LF</th><th colSpan={1} rowSpan={1}>LI</th><th colSpan={1} rowSpan={1}>LO</th>
                  <th colSpan={1} rowSpan={1}>LO</th>
                  <th colSpan={1} rowSpan={1}>LO</th>
                  <th colSpan={1} rowSpan={1}>LO</th>
                  <th colSpan={1} rowSpan={1}>LO</th>
                </tr>
              </thead>
              <tbody>
                <tr className="content">
                {
                  dCombination.filter(dc => dc.p === 'jointing'  && (dc.i !== 'setchakugo' && dc.i !== 'gaikan' && dc.i !== 'tenaoshi')).map(dc => 
                    inspections.find(i => i.p == dc.p && i.i == dc.i && i.d == dc.d) ?
                    <td key={dc.p + dc.i + dc.d}>
                      <input
                        type="number"
                        value={inspections.find(i => i.p == dc.p && i.i == dc.i && i.d == dc.d).sort}
                        onChange={e => this.setState({
                            inspections: inspections.map(i =>
                              i.p == dc.p && i.i == dc.i && i.d == dc.d ? Object.assign(i, {sort: e.target.value}) : i
                            )
                        })}
                      />
                      <div className="failure-type-wrap">
                        <input
                          type="checkbox"
                          checked={inspections.find(i => i.p == dc.p && i.i == dc.i && i.d == dc.d).type === 1}
                          onChange={() => this.setState({
                            inspections: inspections.map(i =>
                              i.p == dc.p && i.i == dc.i && i.d == dc.d ? Object.assign(i, {type: i.type === 1 ? 2 : 1}) : i
                            )
                          })}
                        />
                        <p>重要</p>
                      </div>
                      <div
                        className="panel-btn" 
                        onClick={() => this.setState({
                          inspections: inspections.filter(i => i.p !== dc.p || i.i !== dc.i || i.d !== dc.d)
                        })}
                      >
                        <span className="panel-btn-close"></span>
                      </div>
                    </td> :
                    <td key={dc.p + dc.i + dc.d}>
                      <p className="null"></p>
                      <div
                        className="panel-btn"
                        onClick={() => this.setState({
                          inspections: [{p: dc.p, i: dc.i, d: dc.d, sort: 1, type: 2}, ...inspections]
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
            <table>
              <thead>
                <tr>
                  <th colSpan={2} rowSpan={1}>接着後</th>
                  <th colSpan={2} rowSpan={1}>外観検査</th>
                  <th colSpan={2} rowSpan={1}>手直</th>
                </tr>
                <tr>
                  <th colSpan={1} rowSpan={1}>DA</th><th colSpan={1} rowSpan={1}>LA</th>
                  <th colSpan={1} rowSpan={1}>DA</th><th colSpan={1} rowSpan={1}>LA</th>
                  <th colSpan={1} rowSpan={1}>DA</th><th colSpan={1} rowSpan={1}>LA</th>
                </tr>
              </thead>
              <tbody>
                <tr className="content">
                {
                  dCombination.filter(dc => dc.p === 'jointing' && (dc.i === 'setchakugo' || dc.i === 'gaikan' || dc.i === 'tenaoshi')).map(dc => 
                    inspections.find(i => i.p == dc.p && i.i == dc.i && i.d == dc.d) ?
                    <td key={dc.p + dc.i + dc.d}>
                      <input
                        type="number"
                        value={inspections.find(i => i.p == dc.p && i.i == dc.i && i.d == dc.d).sort}
                        onChange={e => this.setState({
                            inspections: inspections.map(i =>
                              i.p == dc.p && i.i == dc.i && i.d == dc.d ? Object.assign(i, {sort: e.target.value}) : i
                            )
                        })}
                      />
                      <div className="failure-type-wrap">
                        <input
                          type="checkbox"
                          checked={inspections.find(i => i.p == dc.p && i.i == dc.i && i.d == dc.d).type === 1}
                          onChange={() => this.setState({
                            inspections: inspections.map(i =>
                              i.p == dc.p && i.i == dc.i && i.d == dc.d ? Object.assign(i, {type: i.type === 1 ? 2 : 1}) : i
                            )
                          })}
                        />
                        <p>重要</p>
                      </div>
                      <div
                        className="panel-btn" 
                        onClick={() => this.setState({
                          inspections: inspections.filter(i => i.p !== dc.p || i.i !== dc.i || i.d !== dc.d)
                        })}
                      >
                        <span className="panel-btn-close"></span>
                      </div>
                    </td> :
                    <td key={dc.p + dc.i + dc.d}>
                      <p className="null"></p>
                      <div
                        className="panel-btn"
                        onClick={() => this.setState({
                          inspections: [{p: dc.p, i: dc.i, d: dc.d, sort: 1, type: 2}, ...inspections]
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
          </div>
          <div className="btn-wrap">
            <button onClick={() => {
              this.props.update(id, name, label, inspections)
            }}>
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
  message: PropTypes.string,
  close: PropTypes.func,
  update: PropTypes.func,
};

export default Edit;
