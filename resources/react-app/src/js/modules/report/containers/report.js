import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
import 'react-select/dist/react-select.css';
// Actions
import { reportActions } from '../ducks/report';
// Components
import Loading from '../../../components/loading/loading';
import MyCalendar from '../components/calender';
import './report.scss';

class Report extends Component {
  constructor(props, context) {
    super(props, context);

    this.state = {
      open: true,
      value: 1,
    };
  }

  serch() {
    const { actions: {getInspectionData} } = this.props;
    getInspectionData();
  }

  render() {
    // const { conference } = this.props;
    const now = moment();
    const styles = {
      customWidth: {
        width: 150,
      },
    };
    const options = {
      vehicle: [
        { value: 1, label: '680A' }
      ],
      inspectorGroup: [
        { value: 1, label: '黄直' },
        { value: 2, label: '白直' }
      ]
    };

    return (
      <div id="reportWrap">
        <div className="bg-white">
          <div>
            <p>車両*</p>
            <Select
              name="vehicle"
              clearable={false}
              Searchable={true}
              value={this.state.value}
              options={options.vehicle}
              onChange={value => this.setState({value: value.value})}
            />
          </div>
          <div>
            <p>日付*</p>
            <MyCalendar defaultValue={now}/>
          </div>
          <div>
            <p>直*</p>
            <Select
              name="vehicle"
              clearable={false}
              Searchable={true}
              value={this.state.value}
              options={options.inspectorGroup}
              onChange={value => this.setState({value: value.value})}
            />
          </div>
          <div>
            <button className="serchBtn">検索</button>
          </div>
        </div>
        <div className="bg-white">

        </div>
      </div>
    );
  }
}

Report.propTypes = {
  // id: PropTypes.string.isRequired,
  // application: PropTypes.object.isRequired,
  // conference: PropTypes.object.isRequired,
};

function mapStateToProps(state, ownProps) {
  return {
    // id: ownProps.params.id,
    // application: state.application,
    // conference: state.conference,
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({}, reportActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Report);
