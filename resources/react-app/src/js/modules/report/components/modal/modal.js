import React, { PropTypes, Component } from 'react';
import styles from './modal.scss';

class Modal extends Component {
  render() {
    const { path, close } = this.props;

    return (
      <div className="modal-pdf">
        <object
          data={path}
          width="900"
          height="650"
          hspace="0"
          vspace="0"
          internalinstanceid="15"
          title=""
        >
        </object>
        <div className="panel-btn" onClick={() => close()}>
          <span className="panel-btn-close"></span>
        </div>  
      </div>
    );
  }
}

Modal.propTypes = {
  path: PropTypes.string.isRequired,
  close: PropTypes.func.isRequired
};

export default Modal;
