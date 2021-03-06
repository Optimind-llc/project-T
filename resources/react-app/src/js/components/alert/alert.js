import React, { PropTypes, Component } from 'react';
import { TransitionMotion, spring, presets } from 'react-motion';
import styles from './alert.scss';

class Alert extends Component {
  getDefaultValue() {
    const { alerts } = this.props;
    return alerts.map(alert => ({
      ...alert,
      style: {
        height: 0,
        opacity: 1,
        padding: 0,
        marginBottom: 0,
      }
    }));
  }

  getEndValue() {
    const { alerts } = this.props;
    return alerts.map(alert => ({...alert, style: {
      height: spring(50, presets.gentle),
      opacity: spring(1, presets.gentle),
      padding: spring(15, presets.gentle),
      marginBottom: spring(15, presets.gentle),
    }}));
  }

  willEnter() {
    return {
      height: 0,
      opacity: 1,
      padding: 0,
      marginBottom: 0
    };
  }

  willLeave() {
    return {
      height: spring(0),
      opacity: spring(0),
      padding: spring(0),
      marginBottom: spring(0)
    };
  }

  handleClick(key) {
    const { deleteSideAlerts } = this.props;
    deleteSideAlerts([key]);
  }

  render() {
    const { alerts } = this.props;

    return (
      <div className="alerts-wrap">
      {alerts.length > 0 &&
      <TransitionMotion
        defaultStyles={this.getDefaultValue.bind(this)()}
        styles={this.getEndValue.bind(this)()}
        willLeave={this.willLeave.bind(this)}
        willEnter={this.willEnter.bind(this)}>
        {alerts =>
          <div id="alerts-wrap">
            {alerts.map(({ key, data: {status, message}, style }) => {
              return (
                <div className={styles.alert} style={style} key={key}>
                  <p>{message}</p>
                  <span className={styles.close} title={key} onClick={this.handleClick.bind(this, key)}>×</span>
                </div>
              );
            })}
          </div>
        }
      </TransitionMotion>}
      </div>
    );
  }
}

Alert.propTypes = {
  alerts: PropTypes.array.isRequired,
  deleteSideAlerts: PropTypes.func
};

export default Alert;
