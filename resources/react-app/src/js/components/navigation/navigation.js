import React, { PropTypes, Component } from 'react';
import styles from './navigation.css';
import logo from './toyota-logo.svg';

class Navigation extends Component {
  render() {
    const { nameList } = this.props;

    return (
      <div>
        <img src={logo} width="38" height="38" alt="React"/>
      </div>
    );
  }
}

Navigation.propTypes = {
  nameList: PropTypes.array.isRequired
};

export default Navigation;
