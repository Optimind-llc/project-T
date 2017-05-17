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
          <p className="title">新規不良区分登録</p>
          {
            this.props.message == 'duplicate failure name' &&
            <p className="error-message">同じ名前の不良区分がすでに登録されています</p>
          }{
            this.props.message == 'duplicate failure label' &&
            <p className="error-message">同じ番号の不良区分がすでに登録されています</p>
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
              <p>番号</p>
              <input
                type="number"
                value={this.state.label}
                onChange={e => this.setState({label: e.target.value})}
              />
            </div>
          </div>
          <div className="btn-wrap">
            <button onClick={() => this.props.create(name, label)}>
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
