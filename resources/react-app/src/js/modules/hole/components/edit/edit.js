import React, { Component, PropTypes } from 'react';
import Select from 'react-select';
// Styles
import './edit.scss';

class Edit extends Component {
  constructor(props, context) {
    super(props, context);

    let direction;
    switch (props.hole.direction) {
      case 'left':   direction = {label: '左', value: 'left', x: -22, y: 0}; break;
      case 'right':  direction = {label: '右', value: 'right', x: 22, y: 0}; break;
      case 'top':    direction = {label: '上', value: 'top', x: 0, y: -22}; break;
      case 'bottom': direction = {label: '下', value: 'bottom', x: 0, y: 22}; break;
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
      label: props.hole.label,
      point: props.hole.point.split(','),
      direction: direction,
      shape: shape,
      border: border,
      color: props.hole.color,
      partName: props.hole.partName
    };
  }

  updateHole() {
    const { hole, updateHole } = this.props;
    const { label, point, direction, shape, border, color } = this.state;

    updateHole(hole.id, label, point, direction.value, shape.value, border.value, color);
  }

  getTextColor(color) {
    const cR = parseInt(color.slice(0,2), 16);
    const cG = parseInt(color.slice(2,4), 16);
    const cB = parseInt(color.slice(4,6), 16);

    return (0.3*cR + 0.6*cG + 0.1*cB > 127) ? "#000000" : "#FFFFFF";
  }

  render() {
    const { hole, path, message, close, update } = this.props;
    const { label, point, direction, shape, border, color, partName, offsetX, offsetY } = this.state;

    const labelColore = [
      '000000', '021F57', '4A90E2', '7ED321', '9B9B9B', 'BD10E0',
      'D0021B', 'F5A623', 'F8E71C', 'FD6ACB', 'FFFFFF'
    ];

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
                  {label: '左', value: 'left', x: -22, y: 0},
                  {label: '右', value: 'right', x: 22, y: 0},
                  {label: '上', value: 'top', x: 0, y: -22},
                  {label: '下', value: 'bottom', x: 0, y: 22}
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
              <p>ラベル色</p>
              <div className="select-color-wrap">
                {labelColore.map(c => {
                  const size = c === color ? 20 : 12;
                  return(
                    <div
                      key={c}
                      style={{
                      backgroundColor: `#${c}`,
                      width: size,
                      height: size,
                      border: '2px solid #000',
                      borderRadius: size/2,
                      }}
                      onClick={() => this.setState({color: c})}
                    >
                    </div>
                  )
                })}
              </div>
            </div>
          </div>
          <div className="figure-wrap">
            <img src={path} width={1740/2}/>
            <svg onClick={e => this.setState({point: [(e.clientX - 325)*2, (e.clientY - 100)*2]})}>
              <circle cx={point[0]/2} cy={point[1]/2} r={4} fill="red"/>
              {
                shape.value === 'square' &&
                <g>
                  <rect x={(point[0]/2 + direction.x)-10} y={(point[1]/2 + direction.y)-9} width="20" height="18" fill={`#${color}`} stroke="#000"/>
                  {
                    border.value === 'dotted' &&
                    <rect
                      x={(point[0]/2 + direction.x) - 10}
                      y={(point[1]/2 + direction.y) - 9}
                      width={20}
                      height={18}
                      fill="none"
                      fillRule="evenodd"
                      strokeWidth={2}
                      stroke="#FFF"
                      strokeDasharray={3}
                    />
                  }
                  <text
                    x={point[0]/2 + direction.x}
                    y={point[1]/2 + direction.y}
                    dy="4"
                    fontSize="10"
                    fill={this.getTextColor(color)}
                    textAnchor="middle"
                    fontWeight="bold"
                    >
                      {label}
                    </text>
                </g>
              }{
                shape.value === 'circle' &&
                <g>
                  <circle cx={point[0]/2 + direction.x} cy={point[1]/2 + direction.y} r={10} fill={`#${color}`} stroke="#000"/>
                  {
                    border.value === 'dotted' &&
                    <ellipse
                      cx={point[0]/2 + direction.x}
                      cy={point[1]/2 + direction.y}
                      rx={10}
                      ry={10}
                      fill="none"
                      fillRule="evenodd"
                      stroke="#FFF"
                      strokeWidth={1}
                      strokeDasharray={3}
                    />
                  }
                  <text
                    x={point[0]/2 + direction.x}
                    y={point[1]/2 + direction.y}
                    dy="4"
                    fontSize="10"
                    fill={this.getTextColor(color)}
                    textAnchor="middle"
                    fontWeight="bold"
                    >
                      {label}
                  </text>
                </g>
              }
            </svg>
          </div>
          <div className="btn-wrap">
            <button onClick={() => {this.updateHole()}}>
              保存
            </button>
          </div>
        </div>
      </div>
    );
  }
};

Edit.propTypes = {
  hole: PropTypes.object.isRequired,
  path: PropTypes.string.isRequired,
  message: PropTypes.string.isRequired,
  meta: PropTypes.object.isRequired,
  close: PropTypes.func.isRequired,
  update: PropTypes.func.isRequired
};

export default Edit;
