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
    const { getVEandITORGdata } = props.actions;
    getVEandITORGdata();

    this.state = {
      open: true,
      vehicle: null,
      inspectorG: null,
      date: null,
    };
  }

  componentWillReceiveProps(nextProps) {
    if (nextProps.VEandITORGdata.data !== null) {
      const { vehicle, inspectorG } = nextProps.VEandITORGdata.data
      this.setState({
        vehicle: vehicle[0].c,
        inspectorG: inspectorG[0].c
      })
    }
  }

  serch() {
    const { actions: {getInspectionData} } = this.props;
    getInspectionData();
  }

  render() {
    const { VEandITORGdata } = this.props;
    const now = moment();

    return (
      <div id="reportWrap">
        {VEandITORGdata.data !== null &&
          <div className="bg-white">
            <div>
              <p>車種*</p>
              <Select
                name="車種"
                placeholder="選択してください"
                clearable={false}
                Searchable={true}
                value={this.state.vehicle}
                options={VEandITORGdata.data.vehicle.map(v => {
                  return { value: v.c, label: v.c }
                })}
                onChange={value => this.setState({
                  vehicle: value.value
                })}
              />
            </div>
            <div>
              <p>日付*</p>
              <MyCalendar
                defaultValue={now}
                setState={(date) => this.setState({date})}
              />
            </div>
            <div>
              <p>直*</p>
              <Select
                name="直"
                placeholder="選択してください"
                clearable={false}
                Searchable={true}
                value={this.state.inspectorG}
                options={VEandITORGdata.data.inspectorG.map(i => {
                  return { value: i.c, label: i.n }
                })}
                onChange={value => this.setState({
                  inspectorG: value.value
                })}
              />
            </div>
            <div>
              <button
                className="serchBtn"
                onClick={() => this.serch()}
              >
                検索
              </button>
            </div>
          </div>
        }
        <div className="bg-white">

        </div>
      </div>
    );
  }
}

Report.propTypes = {
  // id: PropTypes.string.isRequired,
  VEandITORGdata: PropTypes.object.isRequired,
  // conference: PropTypes.object.isRequired,
};

function mapStateToProps(state, ownProps) {
  return {
    // id: ownProps.params.id,
    VEandITORGdata: state.VEandITORGdata,
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
