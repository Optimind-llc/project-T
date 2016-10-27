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
import MyCalendar from '../components/calendar/calendar';
import './report.scss';
import Modal from '../components/modal/modal';

class Report extends Component {
  constructor(props, context) {
    super(props, context);
    const { data } = props.VeItorGProcData;
    const { getVeItorGProc, getItionG } = props.actions;
    var state = {
      modal: false,
      path: '',
      vehicle: null,
      date: moment(),
      inspectorG: null,
      process: null,
    };

    if (data == null) getVeItorGProc();
    else {
      state.vehicle = data.vehicle[0].c;
      state.inspectorG = data.inspectorG[0].c;
      state.process = data.process[0].id;

      getItionG(state.vehicle, state.date, state.inspectorG, state.process);
    }
    this.state = (state);
  }

  componentWillReceiveProps(nextProps) {
    const { VeItorGProcData, ItionGData, actions } = this.props;
    const { date } = this.state;

    if (VeItorGProcData.isFetching && !nextProps.VeItorGProcData.isFetching) {
      const { data } = nextProps.VeItorGProcData;
      const newState = {
        vehicle: data.vehicle[0].c,
        inspectorG: data.inspectorG[0].c,
        process: data.process[0].id
      };

      this.setState(newState);
      actions.getItionG(newState.vehicle, date, newState.inspectorG, newState.process);
    }
  }

  serch() {
    const { state } = this;
    const { actions: {getItionG} } = this.props;

    if (state.vehicle !== '' && state.date !== '' && state.vehicle !== '' && state.vehicle !== '' ) {
      getItionG(state.vehicle, state.date, state.inspectorG, state.process);
    }
  }

  setVehicle(value) {
    this.setState({vehicle: value}, () => this.serch());
  }

  setDate(value) {
    this.setState({date: value}, () => this.serch());
  }

  setInspectorG(value) {
    this.setState({inspectorG: value}, () => this.serch());
  }

  closeModal() {
    this.setState({modal: false});
  }

  render() {
    const { VeItorGProcData, ItionGData } = this.props;

    return (
      <div id="reportWrap">
        {VeItorGProcData.data !== null &&
          <div className="bg-white serch-flex">
            <div>
              <p>車種*</p>
              <Select
                name="車種"
                placeholder="選択してください"
                clearable={false}
                Searchable={true}
                value={this.state.vehicle}
                options={VeItorGProcData.data.vehicle.map(v => {
                  return { value: v.c, label: v.c }
                })}
                onChange={value => this.setVehicle(value.value)}
              />
            </div>
            <div>
              <p>日付*</p>
              <MyCalendar
                defaultValue={this.state.date}
                setState={date => this.setDate(date)}
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
                options={VeItorGProcData.data.inspectorG.map(i => {
                  return { value: i.c, label: i.n }
                })}
                onChange={value => this.setInspectorG(value.value)}
              />
            </div>
            <div>
              <p>工程*</p>
              <Select
                name="工程"
                placeholder="選択してください"
                clearable={false}
                Searchable={true}
                value={this.state.process}
                options={VeItorGProcData.data.process.map(p => {
                  return { value: p.id, label: p.n }
                })}
                onChange={value => this.setState({
                    process: value.value
                  },() => this.serch()
                )}
              />
            </div>
          </div>
        }{
            this.state.process == 'molding' &&
            <div className="bg-white process-flex">
              <div>
                <p>ライン１</p>
                <div
                  onClick={() => this.setState({
                    modal: true,
                    path: `/manager/pdf/report/1/${this.state.date.format("YYYY-MM-DD")}/${this.state.inspectorG}`
                  })}
                >
                  <p>インナー</p>
                </div>
                <div
                  onClick={() => this.setState({
                    modal: true,
                    path: `/manager/pdf/report/5/${this.state.date.format("YYYY-MM-DD")}/${this.state.inspectorG}`
                  })}
                >
                  <p>アウター</p>
                </div>
              </div>
              <div>
                <p>ライン２</p>
                <div
                  className="disable"
                  onClick={() => this.setState({
                    modal: true,
                    path: `/manager/pdf/report/2/${this.state.date.format("YYYY-MM-DD")}/${this.state.inspectorG}`
                  })}
                >
                  <p>インナー</p>
                </div>
                <div
                  className="disable"
                  onClick={() => this.setState({
                    modal: true,
                    path: `/manager/pdf/report/6/${this.state.date.format("YYYY-MM-DD")}/${this.state.inspectorG}`
                  })}
                >
                  <p>アウター</p>
                </div>
              </div>
              <div>
                <p>精度検査</p>
                <div
                  onClick={() => this.setState({
                    modal: true,
                    path: `/manager/pdf/report/3/${this.state.date.format("YYYY-MM-DD")}/${this.state.inspectorG}`
                  })}
                >
                  <p>インナー</p>
                </div>
              </div>
            </div>
        }{
          this.state.process == 'holing' &&
          <div className="bg-white process-flex">
            <div>
              <p>検査</p>
              <div
                onClick={() => this.setState({
                  modal: true,
                  path: `/manager/pdf/report/4/${this.state.date.format("YYYY-MM-DD")}/${this.state.inspectorG}`
                })}
              >
                <p>インナー</p>
              </div>
              <div
                onClick={() => this.setState({
                  modal: true,
                  path: `/manager/pdf/report/8/${this.state.date.format("YYYY-MM-DD")}/${this.state.inspectorG}`
                })}
              >
                <p>アウター</p>
              </div>
            </div>
          </div>
        }{
          this.state.process == 'jointing' &&
          <div className="bg-white process-flex">
            <div>
              <p>精度検査</p>
              <div
                onClick={() => this.setState({
                  modal: true,
                  path: `/manager/pdf/report/9/${this.state.date.format("YYYY-MM-DD")}/${this.state.inspectorG}`
                })}
              >
                <p>インナーASSY</p>
              </div>
            </div>
            <div>
              <p>止水</p>
              <div
                onClick={() => this.setState({
                  modal: true,
                  path: `/manager/pdf/report/10/${this.state.date.format("YYYY-MM-DD")}/${this.state.inspectorG}`
                })}
              >
                <p>インナーASSY</p>
              </div>
            </div>
            <div>
              <p>仕上</p>
              <div
                onClick={() => this.setState({
                  modal: true,
                  path: `/manager/pdf/report/11/${this.state.date.format("YYYY-MM-DD")}/${this.state.inspectorG}`
                })}
              >
                <p>インナーASSY</p>
              </div>
            </div>
            <div>
              <p>検査</p>
              <div
                onClick={() => this.setState({
                  modal: true,
                  path: `/manager/pdf/report/12/${this.state.date.format("YYYY-MM-DD")}/${this.state.inspectorG}`
                })}
              >
                <p>インナーASSY</p>
              </div>
            </div>
            <div>
              <p>特検</p>
              <div
                onClick={() => this.setState({
                  modal: true,
                  path: `/manager/pdf/report/13/${this.state.date.format("YYYY-MM-DD")}/${this.state.inspectorG}`
                })}
              >
                <p>インナーASSY</p>
              </div>
            </div>
            <div>
              <p>手直し</p>
              <div
                onClick={() => this.setState({
                  modal: true,
                  path: `/manager/pdf/report/14/${this.state.date.format("YYYY-MM-DD")}/${this.state.inspectorG}`
                })}
              >
                <p>インナーASSY</p>
              </div>
            </div>
          </div>
        }{
          this.state.modal &&
          <Modal
            path={this.state.path}
            close={() => this.closeModal()}
          />
        }
      </div>
    );
  }
}

Report.propTypes = {
  VeItorGProcData: PropTypes.object.isRequired,
  ItionGData: PropTypes.object.isRequired,
};

function mapStateToProps(state, ownProps) {
  return {
    VeItorGProcData: state.VeItorGProcData,
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
