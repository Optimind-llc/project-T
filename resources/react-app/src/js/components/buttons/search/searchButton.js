import React, { PropTypes, Component } from 'react';
// Styles
import styles from './searchButton.scss';

class SearchButton extends Component {
  constructor(props, context) {
    super(props, context);

    this.state = {
      pw: '',
      error: false,
      opened: false
    };
  }

  render() {
    const { active, searching, onClick } = this.props;

    const disabled = !active || searching;
    let word = 'この条件で検索';
    if (searching) {word = '検査中...'};
    if (!active) {word = '検索条件が不正'};

    return (
      <button
        className={`iconBtn search dark ${disabled ? 'disabled' : ''}`}
        onClick={() => onClick()}
      >
        <img src={`/img/icon/${disabled ? 'search-g' : 'search-w'}.svg`}/>
        <p>{word}</p>
      </button>
    );
  }
}

SearchButton.propTypes = {
  active: PropTypes.bool.isRequired,
  searching: PropTypes.bool.isRequired
};

export default SearchButton;
