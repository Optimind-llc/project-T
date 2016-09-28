import React, { PropTypes, Component } from 'react';
// import styles from './breadcrumbs.css';

class Breadcrumbs extends Component {
  render() {
    const { nameList } = this.props;

    return (
      <div className="breadcrumb">
        <ul className="breadcrumbsTop">
          {
            nameList.map((name, i) => 
              <li key={i}><span className="breadcrumbsItem">{name}</span></li>
            )
          }
          <button className="roundBorberBtn">管理者ログイン</button>
        </ul>
      </div>
    );
  }
}

Breadcrumbs.propTypes = {
  nameList: PropTypes.array.isRequired
};

export default Breadcrumbs;
