import React, { Component, PropTypes } from 'react';
import Select from 'react-select';
// Styles
import './create.scss';

class Create extends Component {
  constructor(props, context) {
    super(props, context);
    this.state = {
      name: '',
      yomi: '',
      choku: {label: '白直', value: 'W'},
      inspections: []
    };
  }

  render() {
    const { name, yomi, choku, inspections } = this.state;
    const { dCombination } = this.props;

    return (
      <div>
        <div className="modal">
        </div>
        <div className="edit-wrap">
          <div className="panel-btn" onClick={() => this.props.close()}>
            <span className="panel-btn-close"></span>
          </div>
          <p className="title">新規検査者登録</p>
          {
            this.props.message == 'duplicate failure name' &&
            <p className="error-message">同じ名前の検査者がすでに登録されています</p>
          }{
            this.props.message == 'duplicate failure label' &&
            <p className="error-message">同じ番号の検査者がすでに登録されています</p>
          }{
            this.props.message == 'success' &&
            <p className="error-message">作成されました</p>
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
              <p>ヨミ</p>
              <input
                type="text"
                value={this.state.yomi}
                onChange={e => this.setState({yomi: e.target.value})}
              />
            </div>
            <div className="choku" style={{width: 200}}>
              <p>直</p>
              <Select
                name="直"
                placeholder="直を選択"
                styles={{height: 30}}
                clearable={false}
                Searchable={true}
                value={this.state.choku}
                options={[
                  {label: '白直', value: 'W'},
                  {label: '黄直', value: 'Y'},
                  {label: '黒直', value: 'B'}
                ]}
                onChange={choku => this.setState({choku})}
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
                  <th colSpan={1} rowSpan={1}>D</th><th colSpan={1} rowSpan={1}>L</th>
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
                      <input className="visibility-hidden" type="number"/>
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
                  <th colSpan={2} rowSpan={1}>洗浄前外観検査</th>
                  <th colSpan={2} rowSpan={1}>洗浄後外観検査</th>
                  <th colSpan={2} rowSpan={1}>穴検査</th>
                  <th colSpan={2} rowSpan={1}>手直</th>
                </tr>
                <tr>
                  <th colSpan={1} rowSpan={1}>D</th><th colSpan={1} rowSpan={1}>L</th>
                  <th colSpan={1} rowSpan={1}>D</th><th colSpan={1} rowSpan={1}>L</th>
                  <th colSpan={1} rowSpan={1}>D</th><th colSpan={1} rowSpan={1}>L</th>
                  <th colSpan={1} rowSpan={1}>D</th><th colSpan={1} rowSpan={1}>L</th>
                </tr>
              </thead>
              <tbody>
                <tr className="content">
                {
                  dCombination.filter(dc => dc.p === 'holing').map(dc => 
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
                      <input className="visibility-hidden" type="number"/>
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
                  <th colSpan={7} rowSpan={1}>かしめ/接着</th>
                </tr>
                <tr>
                  <th colSpan={2} rowSpan={1}>かしめ後検査</th>
                  <th colSpan={1} rowSpan={1}>外周仕上</th>
                  <th colSpan={1} rowSpan={1}>パテ補修</th>
                  <th colSpan={1} rowSpan={1}>水研後</th>
                  <th colSpan={1} rowSpan={1}>塗装後</th>
                </tr>
                <tr>
                  <th colSpan={1} rowSpan={1}>D</th><th colSpan={1} rowSpan={1}>L</th>
                  <th colSpan={1} rowSpan={1}>L</th>
                  <th colSpan={1} rowSpan={1}>L</th>
                  <th colSpan={1} rowSpan={1}>L</th>
                  <th colSpan={1} rowSpan={1}>L</th>
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
                      <input className="visibility-hidden" type="number"/>
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
                      <input className="visibility-hidden" type="number"/>
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
            <button onClick={() => this.props.create(name, yomi, choku.value, inspections)}>
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
  dCombination: PropTypes.array,
  message: PropTypes.string
};

export default Create;
