import React, { Component, PropTypes } from 'react';
import Select from 'react-select';
// Styles
import './edit.scss';

class Edit extends Component {
  constructor(props, context) {
    super(props, context);
    this.state = {
      id: props.id,
      max1: props.max1,
      min1: props.min1,
      max2: props.max2,
      min2: props.min2,
    };
  }

  render() {
    const { id, max1, min1, max2, min2 } = this.state;

    return (
      <div>
        <div className="modal">
        </div>
        <div className="edit-wrap">
          <div className="panel-btn" onClick={() => this.props.close()}>
            <span className="panel-btn-close"></span>
          </div>
          <p className="title">公差情報編集</p>
          <div className="edit">
            <div className="name">
              <p>ライン１最大値</p>
              <input
                type="number"
                value={this.state.max1}
                onChange={e => {
                  if (e.target.value.length <= 6) {
                    this.setState({name: e.target.value});
                  }
                }}
              />
            </div>
            <div className="name">
              <p>ライン１最小値</p>
              <input
                type="number"
                value={this.state.min1}
                onChange={e => {
                  if (e.target.value.length <= 6) {
                    this.setState({name: e.target.value});
                  }
                }}
              />
            </div>
            {
              this.state.max2 !== null &&
              <div className="name">
                <p>ライン２最大値</p>
                <input
                  type="number"
                  value={this.state.max2}
                  onChange={e => {
                    if (e.target.value.length <= 6) {
                      this.setState({name: e.target.value});
                    }
                  }}
                />
              </div>
            }{
              this.state.min2 !== null &&
              <div className="name">
                <p>ライン２最小値</p>
                <input
                  type="number"
                  value={this.state.min2}
                  onChange={e => {
                    if (e.target.value.length <= 6) {
                      this.setState({name: e.target.value});
                    }
                  }}
                />
              </div>
            }

          </div>
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
  max1: PropTypes.string,
  min1: PropTypes.string,
  max2: PropTypes.string,
  min2: PropTypes.string,

  close: PropTypes.func,
  update: PropTypes.func,
};

export default Edit;
