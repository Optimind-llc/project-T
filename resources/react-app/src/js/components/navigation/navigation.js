import React, { PropTypes, Component } from 'react';
import { Link } from 'react-router';
import styles from './navigation.scss';
import logo from './toyota-logo.svg';

class Navigation extends Component {
  render() {
    const { links } = this.props;

    return (
      <div id="navigation">
        <div className="header">
          <img src={logo} alt="logo"/>
        </div>
        <ul>
          {
            links.map((link, i) => 
              <li>
                <Link activeClassName="active" key={i} to={`/manager/${link.en}`}>{link.name}</Link>
              </li>
            )
          }
        </ul>
      </div>
    );
  }
}

Navigation.propTypes = {
  links: PropTypes.array.isRequired
};

export default Navigation;
