import React, { Component, PropTypes } from 'react';
import Calendar from 'rc-calendar';
import moment from 'moment';
import DatePicker from 'rc-calendar/lib/Picker';
import jaJP from './ja_JP';
import 'rc-calendar/assets/index.css';
import './rangeCalendar.css';
import TimePickerPanel from 'rc-time-picker/lib/Panel';
import 'rc-time-picker/assets/index.css';

class RangeCalendar extends Component {
  constructor(props, context) {
    super(props, context);
    this.state = {
      format: 'YYYY年MM月DD日 HH:mm:ss',
      showTime: false,
      showDateInput: true,
      disabled: false,
      value: props.defaultValue,
    };
  }

  onChange(value) {
    this.setState({
      value,
    });
    this.props.setState(value);
  }

  onShowTimeChange(e) {
    this.setState({
      showTime: e.target.checked,
    });
  }

  onShowDateInputChange(e) {
    this.setState({
      showDateInput: e.target.checked,
    });
  }

  toggleDisabled() {
    this.setState({
      disabled: !this.state.disabled,
    });
  }

  render() {
    const now = moment();
    const defaultCalendarValue = now.clone();
    defaultCalendarValue.add(-1, 'month');

    const timePickerElement = <TimePickerPanel />;

    function disabledDate(current) {
      if (!current) {
        // allow empty select
        return false;
      }
      const date = moment();
      date.hour(0);
      date.minute(0);
      date.second(0);
      return current.date() + 10 < date.date();  // can not select days before today
    }


    const state = this.state;
    const calendar = (<Calendar
      locale={jaJP}
      style={{ zIndex: 1000 }}
      dateInputPlaceholder="please input"
      formatter={state.format}
      timePicker={state.showTime ? timePickerElement : null}
      defaultValue={this.props.defaultCalendarValue}
      showDateInput={state.showDateInput}
      disabledDate={disabledDate}
    />);

    return (
      <div>
        <div style={{
          boxSizing: 'border-box',
          display: 'block',
          lineHeight: 1.5,
        }}
        >
          <DatePicker
            animation="slide-up"
            disabled={state.disabled}
            calendar={calendar}
            value={state.value}
            onChange={(value) => this.onChange(value)}
            disabled={this.props.disabled}
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
                      width: 196,
                      borderRadius: 4,
                      color: '#000',
                      lineHeight: '34px',
                    }}
                    disabled={state.disabled}
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
        </div>
      </div>
    );
  }
};

RangeCalendar.propTypes = {
  defaultValue: PropTypes.object,
  defaultCalendarValue: PropTypes.object,
  setState: PropTypes.func,
  disabled: PropTypes.bool
};

export default RangeCalendar;
