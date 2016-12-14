import React, { PropTypes, Component } from 'react';
import { Link } from 'react-router';
import styles from './navigation.scss';
import logo from './toyota-logo.svg';

class Navigation extends Component {
  constructor(props, context) {
    super(props, context);

    this.state = {
      pw: '',
      error: false,
      opened: false
    };
  }

  login() {
    const { pw, login } = this.props;
    this.state.pw === pw ? login() : this.setState({error: true});
  }

  logout() {
    const { logout, push } = this.props;

    logout();
    push('/manager/dashboard');
    this.setState({pw: '', error: false});
  }

  render() {
    const { vehicle, links, masterlinks, logedin } = this.props;
    const { pw, error, opened } = this.state;

    return (
      <div id="navigation">
        <div className="header">
          {/*<img src={logo} alt="logo"/>*/}
          <div
            className={`vehicle-displaying ${opened ? 'focused' : ''}`}
            onClick={() => this.setState({opened: !opened})}
          >
            <p>{vehicle}</p>
          </div>
          {
            opened &&
            <div className="vehicle-hiding">
              <p>{'950A'}</p>
            </div>
          }
        </div>
        <div className="divider"></div>
        <ul>
        {
          links.map((link, i) => 
            <li key={i}>
              <Link
                className={link.disable ? 'disable' : ''}
                activeClassName="active"
                to={`/manager/${link.en}`}
              >
                {link.name}
              </Link>
            </li>
          )
        }
        </ul>
        <div className="divider"></div>
        {
          logedin &&
          <ul>
          {
            masterlinks.map((link, i) => 
              <li key={i}>
                <Link
                  className={link.disable ? 'disable' : ''}
                  activeClassName="active"
                  to={`/manager/${link.en}`}
                >
                  {link.name}
                </Link>
              </li>
            )
          }
          </ul>
        }{
          logedin && <div className="divider"></div>
        }{
          logedin ? 
          <div className="logout-wrap">
            <p>マスタメンテ</p>
            <button
              className="logout-btn"
              onClick={() => this.logout()}
            >
              <p>ログアウト</p>
            </button>
          </div> :
          <div className="login-wrap">
            <p>マスタメンテ</p>
            <input
              className=""
              type="text"
              value={pw}
              onChange={e => this.setState({pw: e.target.value})}
            />
            {
              error &&
              <p className="error-message">パスワードが間違っています</p>
            }
            <button
              className="login-btn"
              onClick={() => this.login()}
            >
              <p>ログイン</p>
            </button>
          </div>
        }
      </div>
    );
  }
}

Navigation.propTypes = {
  vehicle: PropTypes.string.isRequired,
  links: PropTypes.array.isRequired,
  masterlinks: PropTypes.array.isRequired,
  logedin: PropTypes.bool.isRequired,
  pw: PropTypes.string.isRequired,
  login: PropTypes.func.isRequired,
  logout: PropTypes.func.isRequired
};

export default Navigation;
