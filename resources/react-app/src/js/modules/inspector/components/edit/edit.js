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
      yomi: props.yomi,
      choku: props.choku,
      itionG: props.itionG
    };
  }

  render() {
    const { id, name, yomi, choku, itionG } = this.state;
    const inspectionGroupIds = [1,5,2,6,15,4,8,16,10,11,12,14];

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
                this.props.message == 'duplicate inspector name' &&
                <p className="error-message">同じ名前の人がすでに登録されています</p>
              }
            </div>
            <div className="yomi">
              <p>ヨミ</p>
              <input
                type="text"
                value={this.state.yomi}
                onChange={e => this.setState({yomi: e.target.value})}
              />
            </div>
            <div className="choku">
              <p>直</p>
              <Select
                name="直"
                styles={{height: 36}}
                placeholder={''}
                disabled={false}
                clearable={false}
                Searchable={true}
                value={this.state.choku}
                options={[
                  {value: 'Y', label: '黄直'},
                  {value: 'W', label: '白直'},
                  {value: 'B', label: '黒直'}
                ]}
                onChange={value => this.setState({choku: value})}
              />
            </div>
          </div>
          <table>
            <thead>
              <tr>
                <th colSpan={2}>成形工程ライン①</th>
                <th colSpan={2}>成形工程ライン②</th>
                <th colSpan={3}>穴あけ工程</th>
                <th colSpan={5}>接着工程</th>
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
              <tr className="content">
              {
                inspectionGroupIds.map(igID => 
                  itionG.find(ig => ig.id == igID) ?
                  <td key={igID}>
                    <input
                      type="number"
                      value={itionG.find(ig => ig.id == igID) ? itionG.find(ig => ig.id == igID).sort : null}
                      onChange={e => this.setState({
                        itionG: itionG.map(ig => ig.id == igID ? Object.assign(ig, {sort: e.target.value}) : ig)
                      })}
                    />
                    <div
                      className="panel-btn" 
                      onClick={() => this.setState({
                        itionG: itionG.filter(ig => ig.id !== igID)
                      })}
                    >
                      <span className="panel-btn-close"></span>
                    </div>
                  </td> :
                  <td key={igID}>
                    <p></p>
                    <div
                      className="panel-btn"
                      onClick={() => this.setState({
                        itionG: [{id: igID, sort: 1}, ...itionG]
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
            <button onClick={() => this.props.update(id, name, yomi, choku.value, itionG)}>
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
  yomi: PropTypes.string,
  choku: PropTypes.object,
  itionG: PropTypes.array,
  message: PropTypes.string,
  close: PropTypes.func,
  update: PropTypes.func,
};

export default Edit;
