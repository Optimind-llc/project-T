import React, { Component, PropTypes } from 'react';
import Calendar from 'rc-calendar';
import moment from 'moment';
import DatePicker from 'rc-calendar/lib/Picker';
import jaJP from './ja_JP';
import 'rc-calendar/assets/index.css';
import './calendar.css';
import TimePickerPanel from 'rc-time-picker/lib/Panel';
import 'rc-time-picker/assets/index.css';

class CustomCalendar extends Component {
  constructor(props, context) {
    super(props, context);
    this.state = {
      format: 'YYYY年MM月DD日',
      disabled: props.disabled
    };
  }

  onChange(value) {
    this.setState({
      value,
    });
    this.props.setState(value);
  }

  onShowDateInputChange(e) {
    this.setState({
      showDateInput: e.target.checked,
    });
  }

  render() {
    const { defaultDate, disabled, changeDate } = this.props;

    function disabledDate(current) {
      if (!current) {
        return false;
      }

      const date = moment();
      return !date.isAfter(current);
    }


    const state = this.state;
    const calendar = (<Calendar
      locale={jaJP}
      style={{ zIndex: 1000 }}
      dateInputPlaceholder="YYYY年MM月DD日で入力"
      formatter={state.format}
      timePicker={null}
      defaultValue={defaultDate}
      showToday={false}
      showDateInput={false}
      disabledDate={disabledDate}
    />);

    return (
        <DatePicker
          animation="slide-up"
          calendar={calendar}
          value={defaultDate}
          onChange={value => changeDate(value)}
          disabled={disabled}
        >
          {
            ({ value }) => {
              return (
                <span tabIndex="0">
                <input
                  placeholder="選択してください"
                  style={{
                    boxSizing: 'border-box',
                    border: '1px solid #ccc',
                    padding: '0 10px',
                    margin: 0,
                    width: 150,
                    height: 30,
                    borderRadius: 4,
                    color: disabled ? '#BBB' : '#000',
                    lineHeight: '34px',
                  }}
                  disabled={disabled}
                  readOnly
                  tabIndex="-1"
                  className="ant-calendar-picker-input ant-input"
                  value={value && value.format(state.format) || ''}
                />
                </span>
              );
            }
          }
        </DatePicker>
    );
  }
};

CustomCalendar.propTypes = {
  defaultDate: PropTypes.object.isRequired,
  disabled: PropTypes.bool.isRequired,
  changeDate: PropTypes.func.isRequired
};

export default CustomCalendar;
