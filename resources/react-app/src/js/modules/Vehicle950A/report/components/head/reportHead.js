import React, { PropTypes, Component } from 'react';
// Styles
import styles from './reportHead.scss';
// Components
import Select from 'react-select';
import CustomCalendar from '../calendar/calendar';

class reportHead extends Component {
  constructor(props, context) {
    super(props, context);
  }

  render() {
    const { defaultDate, changeDate, defaultChoku, changeChoku } = this.props;
    return (
      <div className="bg-white report-header">
        <p>日付*</p>
        <CustomCalendar
          defaultDate={defaultDate}
          disabled={false}
          changeDate={d => changeDate(d)}
        />
        <p>直*</p>
        <Select
          name="直"
          placeholder="直を選択"
          clearable={false}
          Searchable={true}
          value={defaultChoku}
          options={[
            {label: '白直', value: ['W']},
            {label: '黄直', value: ['Y']},
            // {label: '黒直', value: ['B'], disabled: true},
            // {label: '両直', value: ['W', 'Y', 'B']}
          ]}
          onChange={value => changeChoku(value)}
        />
      </div>
    );
  }
}

reportHead.propTypes = {
  defaultDate: PropTypes.object.isRequired,
  changeDate: PropTypes.func.isRequired,
  defaultChoku: PropTypes.object.isRequired,
  changeChoku: PropTypes.func.isRequired,
};

export default reportHead;
