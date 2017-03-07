import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import { vehicles, processes, inspections, inspectionGroups } from '../../../../utils/Processes';
// Actions
import { push } from 'react-router-redux';
import { reportActions } from '../ducks/report';
// Styles
import './report.scss';
// Components
// import Loading from '../../../components/loading/loading';
// import RangeCalendar from '../components/rangeCalendar/rangeCalendar';
// Components
import Select from 'react-select';
import CustomCalendar from '../components/calendar/calendar';
import ReportBody from '../components/body/reportBody';
import Modal from '../components/modal/modal';

class Report extends Component {
  constructor(props, context) {
    super(props, context);

    const { getReportData } = props.actions;
    const date = moment();
    const choku = {label: '白直', value: ['W']};

    getReportData('molding', date.format('YYYY-MM-DD'), choku.value);

    this.state = ({
      date,
      choku,
      p: {label: '成型', value: 'molding'},
      modal: false,
      path: '',
    });
  }

  componentWillUnmount() {
    this.props.actions.clearReportData();
  }

  serchReport() {
    const { getReportData } = this.props.actions;
    const { p, date, choku } = this.state;

    getReportData(p.value, date.format('YYYY-MM-DD'), choku.value);
  }

  openModal(i, partTypeEn) {
    const { p, date, choku } = this.state;
    const { partTypes } = this.props.InitialData;
    const pn = partTypes.find(pt => pt.en === partTypeEn).pn;

    this.setState({
      modal: true,
      path: `/manager/950A/report/export/${p.value}/${i}/1/${pn}/${date.format("YYYY-MM-DD")}/${choku.value}`
    });
  }

  render() {
    const { date, choku, p } = this.state;
    const { InitialData, ReportData, actions } = this.props;

    const processes = InitialData.processes.map(p => { return {label: p.name, value: p.en} });

    return (
      <div id="report-950A-wrap">
        <div className="bg-white report-header">
          <p>日付*</p>
          <CustomCalendar
            defaultDate={date}
            disabled={false}
            changeDate={date => this.setState({date}, () => this.serchReport())}
          />
          <p>直*</p>
          <Select
            name="直"
            placeholder="直を選択"
            clearable={false}
            Searchable={true}
            value={choku}
            options={[
              {label: '白直', value: 'W'},
              {label: '黄直', value: 'Y'},
              {label: '黒直', value: 'B', disabled: true}
            ]}
            onChange={choku => this.setState({choku}, () => this.serchReport())}
          />
          <p>工程*：</p>
          <Select
            name="ライン"
            placeholder="全てのライン"
            clearable={false}
            Searchable={true}
            value={p}
            options={processes}
            onChange={p => this.setState({p}, () => this.serchReport())}
          />
        </div>
        {
          !InitialData.idFetching && !ReportData.idFetching && ReportData.data !== null &&
          <ReportBody
            p={p.value}
            partTypes={InitialData.partTypes}
            inspections={InitialData.inspections}
            combination={InitialData.combination}
            data={ReportData.data}
            openModal={(i, pt) => this.openModal(i, pt)}
          />
        }{
          this.state.modal &&
          <Modal
            path={this.state.path}
            close={() => this.setState({modal: false})}
          />
        }
      </div>
    );
  }
}

Report.propTypes = {
  InitialData: PropTypes.object.isRequired,
  ReportData: PropTypes.object.isRequired
};

function mapStateToProps(state, ownProps) {
  return {
    InitialData: state.Application.vehicle950A,
    ReportData: state.ReportData950A
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({push}, reportActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Report);
