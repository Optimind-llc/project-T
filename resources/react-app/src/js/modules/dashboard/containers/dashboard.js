import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
import { vehicles, parts, processes, inspections, inspectionGroups } from '../../../utils/Processes';
// Actions
import { push } from 'react-router-redux';
import { vehicleActions } from '../ducks/vehicle';
import { itorGActions } from '../ducks/itorG';
import { pageTActions } from '../ducks/pageT';
import { pageActions } from '../ducks/page';
// Material-ui Components
import { Paper, Dialog, RaisedButton, FlatButton } from 'material-ui';
import { grey50, indigo500 } from 'material-ui/styles/colors';
// Styles
import './dashboard.scss';
// Components
import Loading from '../../../components/loading/loading';
import RangeCalendar from '../components/rangeCalendar/rangeCalendar';
import Mapping from '../../mapping/containers/mapping';

class Dashboard extends Component {
  constructor(props, context) {
    super(props, context);
    const { PageTData, actions } = props;

    this.state = {
      vehicle: { label: '680A', value: '680A' },
      partTId: null,
      processId: null,
      inspectionId: null,
      itorG: null,
      narrowedBy: 'realtime',
      startDate: moment(),
      endDate: moment(),
      panelId: '',
      intervalId: null,
      interval: 30000,
      defaultActive: {
        name: 'failure',
        time: new Date().getTime()
      }
    };
  }

  componentWillUnmount() {
   clearInterval(this.state.intervalId); 
  }

  endInterval() {
    clearInterval(this.state.intervalId);
  }

  serchItorG() {
    const { getItorGData } = this.props.actions;
    getItorGData();
  }

  showMapping() {
    const { panelIdMapping, advancedMapping } = this.props.actions;
    const { state } = this;
    const format = 'YYYY-MM-DD';
    let start, end, panelId;

    clearInterval(state.intervalId);

    const filteredInspectionGroup = inspectionGroups.filter(ig =>
      ig.vehicle == state.vehicle.value &&
      (state.partTId ? (ig.part == state.partTId.value) : false) &&
      (state.processId ? (ig.p == state.processId.value) : false) &&
      (state.inspectionId ? (ig.i == state.inspectionId.value) : false) &&
      !ig.disabled
    );

    let inspectionGroupId = 0;
    if (filteredInspectionGroup.length > 0) {
      inspectionGroupId = filteredInspectionGroup[0].iG;
    }

    switch (state.narrowedBy) {
      case 'realtime':
        start = end = panelId = null;

        const intervalId = setInterval(
          () => advancedMapping(state.partTId.value, inspectionGroupId, state.itorG.value, null, null),
          state.interval
        );
        this.setState({intervalId});
        break;
      case 'term':
        start = state.startDate.format(format);
        end = state.endDate.format(format);
        panelId = null;

        break;
      case 'panelId':
        start = end = null;
        panelId = state.panelId;
        break;
    }

    let time = new Date().getTime();
    let defaultActive = {
      name: 'failure',
      time
    };

    if (inspectionGroupId == 4 || inspectionGroupId == 8) name = 'hole';
    else if (inspectionGroupId == 3 || inspectionGroupId == 9) name = 'inline';
    else if (inspectionGroupId == 16 || inspectionGroupId == 10 || inspectionGroupId == 11 || inspectionGroupId == 12 || inspectionGroupId == 14) name = 'comment';
    else name = 'failure';
    this.setState({ defaultActive: { name, time }});

    if (state.narrowedBy == 'panelId') {
      panelIdMapping(state.partTId.value, inspectionGroupId, state.itorG.value, panelId);
    }
    else {
      advancedMapping(state.partTId.value, inspectionGroupId, state.itorG.value, start, end, panelId);
    }

  }

  render() {
    const { vehicle, partTId, processId, inspectionId, itorG, narrowedBy, startDate, endDate, panelId, mapping, defaultActive } = this.state;
    const { VehicleData, ItorGData, PageData, actions } = this.props;
    const format = 'YYYY-MM-DD';

    const filteredProcess = inspectionGroups.filter(ig =>
      ig.vehicle == vehicle.value &&
      (partTId ? (ig.part == partTId.value) : false) &&
      !ig.disabled
    ).map(ig =>
      ig.p
    ).filter((x, i, self) =>
      self.indexOf(x) === i
    );

    const filteredInspection = inspectionGroups.filter(ig =>
      ig.vehicle == vehicle.value &&
      (partTId ? (ig.part == partTId.value) : false) &&
      (processId ? (ig.p == processId.value) : false) &&
      !ig.disabled
    ).map(ig =>
      ig.i
    ).filter((x, i, self) =>
      self.indexOf(x) === i
    );

    return (
      <div id="dashboardWrap">
        <div className="header bg-white">
          <div className="serch-wrap">
            <div className="col-1 flex-row">
              <div className="part">
                <p>部品</p>
                <Select
                  name="部品"
                  styles={{height: 36}}
                  placeholder={vehicle == null ? '先に車種を選択' :'部品を選択'}
                  disabled={vehicle == null}
                  clearable={false}
                  Searchable={false}
                  scrollMenuIntoView={false}
                  value={this.state.partTId}
                  options={parts}
                  onChange={value => this.setState({
                    partTId: value
                  })}
                />
              </div>
              <div className="process">
                <p>工程</p>
                <Select
                  name="工程"
                  styles={{height: 36}}
                  placeholder={partTId == null ? '先に部品を選択' :'工程を選択'}
                  disabled={partTId == null}
                  clearable={false}
                  Searchable={true}
                  value={processId}
                  options={processes.filter(p => filteredProcess.indexOf(p.value) >= 0)}
                  onChange={value => this.setState({processId: value})}
                />
              </div>
              <div className="inspection">
                <p>検査</p>
                <Select
                  name="検査"
                  styles={{height: 36}}
                  placeholder={processId == null ? '先に工程を選択' :'検査を選択'}
                  disabled={processId == null}
                  clearable={false}
                  Searchable={true}
                  value={inspectionId}
                  options={inspections.filter(i => filteredInspection.indexOf(i.value) >= 0)}
                  onChange={value => this.setState({inspectionId: value})}
                />
              </div>
              <div className="choku">
                <p>直</p>
                <Select
                  name="直"
                  styles={{height: 36}}
                  placeholder="直を選択"
                  clearable={false}
                  Searchable={true}
                  value={itorG}
                  options={[
                    {label: '黄直', value: 'Y'},
                    {label: '白直', value: 'W'},
                    {label: '黒直', value: 'B'},
                    {label: '全直', value: 'both'}
                  ]}
                  onChange={value => this.setState({itorG: value})}
                />
              </div>
            </div>
            <div className="col-2 flex-row">
              <div
                className={narrowedBy === 'realtime' ? 'realtime active' : 'realtime'}
                onClick={() => this.setState({narrowedBy: 'realtime'})}
              >
                <p>リアルタイム更新</p>
              </div>
              <div
                className={narrowedBy === 'term' ? 'term-wrap active' : 'term-wrap'}
                onClick={() => this.setState({narrowedBy: 'term'})}
              >
                <p>期間：</p>
                <RangeCalendar
                  defaultValue={startDate}
                  setState={startDate => this.setState({
                    startDate: startDate
                  })}
                />
                <p>〜</p>
                <RangeCalendar
                  defaultValue={endDate}
                  setState={endDate => this.setState({
                    endDate: endDate
                  })}
                />
              </div>
              <div
                className={narrowedBy === 'panelId' ? "panel-id-wrap active" : "panel-id-wrap"}
                onClick={() => this.setState({narrowedBy: 'panelId'})}
              >
                <p>パネルID：</p>
                <input
                  type="text"
                  value={panelId}
                  onChange={(e) => this.setState({panelId: e.target.value})}
                />
              </div>
            </div>
          </div>
          <div
            className={`mapping-btn ${partTId && inspectionId && itorG && 'active'}`}
            onClick={() => this.showMapping()}
          >
            <p>表示</p>
          </div>
        </div>
        {
          PageData.data &&
          <Mapping
            PageData={PageData}
            realtime={narrowedBy == 'realtime'}
            active={defaultActive}
            partTId={partTId}
          />
        }
      </div>
    );
  }
}

Dashboard.propTypes = {
  VehicleData: PropTypes.object.isRequired,
  ItorGData: PropTypes.object.isRequired,
  PageTData: PropTypes.object.isRequired,
  PageData: PropTypes.object.isRequired
};

function mapStateToProps(state, ownProps) {
  return {
    VehicleData: state.VehicleData,
    ItorGData: state.ItorGData,
    PageTData: state.PageTData,
    PageData: state.PageData
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({push},
    vehicleActions,
    itorGActions,
    pageTActions,
    pageActions
  );

  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Dashboard);
