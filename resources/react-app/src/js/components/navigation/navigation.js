import React, { PropTypes, Component } from 'react';
// import styles from './breadcrumbs.css';

class Breadcrumbs extends Component {
  render() {
    const { nameList } = this.props;

    return (
      <p>a</p>
    );
  }
}

Breadcrumbs.propTypes = {
  nameList: PropTypes.array.isRequired
};

export default Breadcrumbs;
