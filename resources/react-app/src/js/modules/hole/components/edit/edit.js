import React, { Component, PropTypes } from 'react';
import Select from 'react-select';
// Styles
import './edit.scss';

class Edit extends Component {
  constructor(props, context) {
    super(props, context);

    let direction;
    switch (props.hole.direction) {
      case 'left':   direction = {label: '左', value: 'left'}; break;
      case 'right':  direction = {label: '右', value: 'right'}; break;
      case 'top':    direction = {label: '上', value: 'top'}; break;
      case 'bottom': direction = {label: '下', value: 'bottom'}; break;
      default: break;
    }

    let shape;
    switch (props.hole.shape) {
      case 'circle': shape = {label: '円', value: 'circle'}; break;
      case 'square': shape = {label: '四角', value: 'square'}; break;
      default: break;
    }

    let border;
    switch (props.hole.border) {
      case 'solid':  border = {label: '実線', value: 'solid'}; break;
      case 'dotted': border = {label: '破線', value: 'dotted'}; break;
      default: break;
    }

    this.state = {
      id: props.hole.id,
      label: props.hole.label,
      point: props.hole.point,
      direction: direction,
      shape: shape,
      border: border,
      color: props.hole.color,
      partName: props.hole.partName
    };
  }

  render() {
    const { message, close, update } = this.props;
    const { label, point, direction, shape, border, color, partName } = this.state;

    const labelColore = [
      '000000', '021F57', '4A90E2', '7ED321', '9B9B9B', 'BD10E0',
      'D0021B', 'F5A623', 'F8E71C', 'FD6ACB', 'FFFFFF'
    ];
console.log(color)
    return (
      <div>
        <div className="modal"></div>
        <div className="edit-wrap">
          <div className="panel-btn" onClick={() => this.props.close()}>
            <span className="panel-btn-close"></span>
          </div>
          <p className="title">不良区分情報編集</p>
          <div className="edit">
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
            <div className="point">
              <p>穴位置</p>
              <input
                type="text"
                readOnly="readonly"
                value={this.state.point}
              />
            </div>
            <div className="direction">
              <p>ラベル位置</p>
              <Select
                name="ラベル位置"
                clearable={false}
                Searchable={false}
                value={this.state.direction}
                options={[
                  {label: '左', value: 'left'},
                  {label: '右', value: 'right'},
                  {label: '上', value: 'top'},
                  {label: '下', value: 'bottom'}
                ]}
                onChange={value => this.setState({direction: value})}
              />
            </div>
            <div className="shape">
              <p>ラベル形</p>
              <Select
                name="ラベル形"
                clearable={false}
                Searchable={false}
                value={this.state.shape}
                options={[
                  {label: '円', value: 'circle'},
                  {label: '四角', value: 'square'}
                ]}
                onChange={value => this.setState({shape: value})}
              />
            </div>
            <div className="border">
              <p>ラベル枠線</p>
              <Select
                name="ラベル枠線"
                clearable={false}
                Searchable={false}
                value={this.state.border}
                options={[
                  {label: '実線', value: 'solid'},
                  {label: '破線', value: 'dotted'}
                ]}
                onChange={value => this.setState({border: value})}
              />
            </div>
            <div className="color">
              <p>ラベル枠線</p>
              <div className="select-color-wrap">
                {labelColore.map(c => {
                  const size = c === color ? 20 : 12;
                  return(
                    <div
                      style={{
                      backgroundColor: `#${c}`,
                      width: size,
                      height: size,
                      border: '1px solid #000',
                      borderRadius: size/2,
                      }}
                      onClick={() => this.setState({})}
                    >
                    </div>
                  )
                })}
              </div>
            </div>
          </div>
          <p className="explanation">※ 数字はiPadでの表示順</p>
          <div className="btn-wrap">
            <button onClick={() => {
              update(id, name, label, inspections)
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
  hole: PropTypes.number.isRequired,
  message: PropTypes.string.isRequired,
  meta: PropTypes.object.isRequired,
  close: PropTypes.func.isRequired,
  update: PropTypes.func.isRequired
};

export default Edit;
