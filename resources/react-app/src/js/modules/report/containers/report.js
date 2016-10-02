import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
import 'react-select/dist/react-select.css';
// Actions
import { reportActions } from '../ducks/report';
import { getItionGActions } from '../ducks/inspectionGroup';
// Components
import Loading from '../../../components/loading/loading';
import MyCalendar from '../components/calender';
import './report.scss';

class Report extends Component {
  constructor(props, context) {
    super(props, context);
    const { getVeItorG, getItionG } = props.actions;
    getVeItorG();

    this.state = {
      vehicle: null,
      date: moment(),
      inspectorG: null,
    };
  }

  componentWillReceiveProps(nextProps) {
    const { VeItorGData, ItionGData, actions } = this.props;
    const { vehicle, date, inspectorG } = this.state;

    if (nextProps.VeItorGData.data !== null) {
      const { vehicle, inspectorG } = nextProps.VeItorGData.data
      this.setState({
        vehicle: vehicle[0].c,
        inspectorG: inspectorG[0].c
      })

      if (VeItorGData.data == null) {
        actions.getItionG(vehicle[0].c, date.format("YYYY-MM-DD"), inspectorG[0].c);
      }
    }
  }

  serch() {
    const { actions: {getInspectionData} } = this.props;
    getInspectionData();
  }

  render() {
    const { VeItorGData, ItionGData } = this.props;

    return (
      <div id="reportWrap">
        {VeItorGData.data !== null &&
          <div className="bg-white">
            <div>
              <p>車種*</p>
              <Select
                name="車種"
                placeholder="選択してください"
                clearable={false}
                Searchable={true}
                value={this.state.vehicle}
                options={VeItorGData.data.vehicle.map(v => {
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
                defaultValue={this.state.date}
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
                options={VeItorGData.data.inspectorG.map(i => {
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
  VeItorGData: PropTypes.object.isRequired,
  ItionGData: PropTypes.object.isRequired,
};

function mapStateToProps(state, ownProps) {
  return {
    // id: ownProps.params.id,
    VeItorGData: state.VeItorGData,
    ItionGData: state.ItionGData,
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({},
    reportActions,
    getItionGActions
  );
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Report);
