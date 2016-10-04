import React, { PropTypes, Component } from 'react';
import styles from './realTime.scss';

class RealTime extends Component {
  render() {
    const { path, close } = this.props;

    return (
      <div className="modal-realTime">
        <imag src={path}>
        </imag>
        <div className="panel-btn" onClick={() => close()}>
          <span className="panel-btn-close"></span>
        </div>  
      </div>
    );
  }
}

RealTime.propTypes = {
  path: PropTypes.string.isRequired,
  close: PropTypes.func.isRequired
};

export default RealTime;
