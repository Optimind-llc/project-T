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
      label: props.label
    };
  }

  render() {
    const { id, name, label } = this.state;

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
          <div className="btn-wrap">
            <button onClick={() => {
              this.props.update(id, name, label)
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
