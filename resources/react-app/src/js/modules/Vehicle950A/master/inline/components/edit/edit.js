import React, { Component, PropTypes } from 'react';
import Select from 'react-select';
// Styles
import './edit.scss';

class Edit extends Component {
  constructor(props, context) {
    super(props, context);
    this.state = {
      max: props.max.toFixed(3),
      min: props.min.toFixed(3),
    };
  }

  render() {
    const { id, partName, sort } = this.props;
    const { max, min } = this.state;

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
            <div className="max">
              <p>最大値</p>
              <input
                type="number"
                step="0.01"
                value={this.state.max}
                onChange={e => {
                  if (e.target.value.length <= 6) {
                    this.setState({max: e.target.value});
                  }
                }}
              />
            </div>
            <div className="min">
              <p>最小値</p>
              <input
                type="number"
                step="0.01"
                value={this.state.min}
                onChange={e => {
                  if (e.target.value.length <= 6) {
                    this.setState({min: e.target.value});
                  }
                }}
              />
            </div>
          </div>
          <div className="btn-wrap">
            <button onClick={() => this.props.update(id, max, min)}>
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
  max: PropTypes.string,
  min: PropTypes.string,

  close: PropTypes.func,
  update: PropTypes.func,
};

export default Edit;
