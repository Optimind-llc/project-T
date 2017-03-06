import React, { PropTypes, Component } from 'react';
// Styles
import styles from './saveButton.scss';

class SaveButton extends Component {
  constructor(props, context) {
    super(props, context);

    this.state = {
      pw: '',
      error: false,
      opened: false
    };
  }

  render() {
    const { active, saving } = this.props;

    const disabled = !active || saving;
    let word = '保存する';
    if (saving) {word = '保存中...'};
    if (!active) {word = '保存する'};

    return (
      <button
        className={`iconBtn save dark ${disabled ? 'disabled' : ''}`}
      >
        <img src={'/img/icon/search-g.svg'}/>
        <p>{word}</p>
      </button>
    );
  }
}

SaveButton.propTypes = {
  active: PropTypes.bool.isRequired,
  saving: PropTypes.bool.isRequired
};

export default SaveButton;
