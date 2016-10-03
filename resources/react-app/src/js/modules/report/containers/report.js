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
        }
        <div className="bg-white process-flex">
          {
            !ItionGData.isFetching && ItionGData.data !== null && ItionGData.data.map(i =>
              <div key={i.en}>
                <p>{i.name}</p>
                {
                  i.groups.map(g =>
                    <div
                      key={g.id}
                      className={g.families.length == 0 ? 'disable' : ''}
                      onClick={() => this.setState({
                        modal: true,
                        path: "/pdf/template/molding-inner.pdf"
                      })}
                    >
                      <p>{`${g.division.name} ライン${g.line == '1' ? '①' : '②'}`}</p>
                      <p>{g.families.length}<span>件</span></p>
                    </div>
                  )
                }
              </div>
            )
          }
        </div>
        {
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
