import React, { Component, PropTypes } from 'react';
import Select from 'react-select';
// Styles
import './edit.scss';

class Edit extends Component {
  constructor(props, context) {
    super(props, context);
    this.state = {
      max1: props.max1.toFixed(3),
      min1: props.min1.toFixed(3),
      max2: props.max2 !== null ? props.max2.toFixed(3) : null,
      min2: props.min2 !== null ? props.min2.toFixed(3) : null,
    };
  }

  render() {
    const { id, partName, sort } = this.props;
    const { max1, min1, max2, min2 } = this.state;

    return (
      <div>
        <div className="modal">
        </div>
        <div className="edit-wrap">
          <div className="panel-btn" onClick={() => this.props.close()}>
            <span className="panel-btn-close"></span>
          </div>
          <p className="title">公差情報編集</p>
          <p className="explanation">{`${partName}: ${sort}`}</p>
          <div className="edit">
            <div className="max1">
              <p>ライン１最大値</p>
              <input
                type="number"
                step="0.01"
                value={this.state.max1}
                onChange={e => {
                  if (e.target.value.length <= 6) {
                    this.setState({max1: e.target.value});
                  }
                }}
              />
            </div>
            <div className="min1">
              <p>ライン１最小値</p>
              <input
                type="number"
                step="0.01"
                value={this.state.min1}
                onChange={e => {
                  if (e.target.value.length <= 6) {
                    this.setState({min1: e.target.value});
                  }
                }}
              />
            </div>
            {
              this.state.max2 !== null &&
              <div className="max2">
                <p>ライン２最大値</p>
                <input
                  type="number"
                  step="0.01"
                  value={this.state.max2}
                  onChange={e => {
                    if (e.target.value.length <= 6) {
                      this.setState({max2: e.target.value});
                    }
                  }}
                />
              </div>
            }{
              this.state.min2 !== null &&
              <div className="min2">
                <p>ライン２最小値</p>
                <input
                  type="number"
                  step="0.01"
                  value={this.state.min2}
                  onChange={e => {
                    if (e.target.value.length <= 6) {
                      this.setState({min2: e.target.value});
                    }
                  }}
                />
              </div>
            }

          </div>
          <div className="btn-wrap">
            <button onClick={() => this.props.update(id, max1, min1, max2, min2)}>
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
