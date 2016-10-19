import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
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
      vehicle: {value: '680A', label: '680A'},
      partTId: null,
      itionGId: null,
      itorG: null,
      narrowedBy: 'realtime',
      startDate: moment(),
      endDate: moment(),
      panelId: '',
      intervalId: null,
      interval: 20000,
      defaultActive: Symbol('failure')
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
    const { getPageData } = this.props.actions;
    const { state } = this;
    const format = 'YYYY-MM-DD';
    let start, end, panelId;

    clearInterval(state.intervalId);

    switch (state.narrowedBy) {
      case 'realtime':
        start = end = panelId = null;
        const intervalId = setInterval(
          () => getPageData(state.partTId.value, state.itionGId.value, state.itorG.value, null, null, null),
          state.interval
        );
        this.setState({intervalId});
        break;
      case 'term':
        start = state.startDate.format(format);
        end = state.endDate.format(format);
        panelId = null;

        break;
      case 'panelId': start = end = null;
        start = end = null;
        panelId = state.panelId;
        break;
    }

    let defaultActive = '';
    if (state.itionGId.value == 4 || state.itionGId.value == 8) defaultActive = Symbol('hole');
    else if (state.itionGId.value == 3 || state.itionGId.value == 7) defaultActive = Symbol('inline');
    else defaultActive = Symbol('failure');

// console.log(state.itionGId.value, defaultActive, defaultActive.toString());
    this.setState({defaultActive});

    getPageData(state.partTId.value, state.itionGId.value, state.itorG.value, start, end, panelId);
  }

  render() {
    const { vehicle, partTId, itionGId, itorG, narrowedBy, startDate, endDate, panelId, mapping, defaultActive } = this.state;
    const { VehicleData, ItorGData, PageData, actions } = this.props;
    const format = 'YYYY-MM-DD';
    const processes = {
      1: [
        {label: '成形工程ライン１', value: 1},
        {label: '成形工程ライン２', value: 2},
        {label: '成形工程：精度検査', value: 3},
        {label: '穴あけ工程', value: 4}
      ],
      2: [
        {label: '成形工程ライン１', value: 5},
        {label: '成形工程ライン２', value: 6},
        {label: '穴あけ工程', value: 8}
      ],
      3: [
        {label: '成形工程ライン１', value: 5},
        {label: '成形工程ライン２', value: 6},
        {label: '穴あけ工程', value: 8}
      ],
      4: [
        {label: '成形工程ライン１', value: 5},
        {label: '成形工程ライン２', value: 6},
        {label: '穴あけ工程', value: 8}
      ],
      5: [
        {label: '成形工程ライン１', value: 5},
        {label: '成形工程ライン２', value: 6},
        {label: '穴あけ工程', value: 8}
      ],
      6: [
        {label: '成形工程ライン１', value: 5},
        {label: '成形工程ライン２', value: 6},
        {label: '穴あけ工程', value: 8}
      ],
      7: [
        {label: '接着工程：精度検査', value: 9},
        {label: '接着工程：止水', value: 10},
        {label: '接着工程：仕上', value: 11},
        {label: '接着工程：検査', value: 12},
        {label: '接着工程：特検', value: 13},
        {label: '接着工程：手直し', value: 14},
      ],
    };

    return (
      <div id="dashboardWrap">
        <div className="header bg-white">
          <div className="serch-wrap">
            <div className="col-1 flex-row">
              <div>
                <p>車種*</p>
                <Select
                  name="車種"
                  placeholder="車種を選択"
                  styles={{height: 36}}
                  clearable={false}
                  Searchable={true}
                  value={this.state.vehicle}
                  options={[
                    {label: '680A', value: '680A'},
                    {label: '950A', value: '950A', disabled:true}
                  ]}
                  onChange={value => this.setState({vehicle: value})}
                />
              </div>
              <div>
                <p>部品*</p>
                <Select
                  name="部品"
                  styles={{height: 36}}
                  placeholder={vehicle == null ? '先に車種を選択' :'部品を選択'}
                  disabled={vehicle == null}
                  clearable={false}
                  Searchable={false}
                  scrollMenuIntoView={false}
                  value={this.state.partTId}
                  options={[
                    {label: 'バックドアインナー', value: 1},
                    {label: 'アッパー', value: 2},
                    {label: 'サイドアッパーRH', value: 3},
                    {label: 'サイドアッパーLH', value: 4},
                    {label: 'サイドロアRH', value: 5},
                    {label: 'サイドロアLH', value: 6},
                    {label: 'バックドアインナASSY', value: 7}
                  ]}
                  onChange={value => this.setState({
                    partTId: value,
                    itionGId: null
                  })}
                />
              </div>
              <div>
                <p>工程*</p>
                <Select
                  name="工程"
                  styles={{height: 36}}
                  placeholder={partTId == null ? '先に部品を選択' :'工程を選択'}
                  disabled={partTId == null}
                  clearable={false}
                  Searchable={true}
                  value={itionGId}
                  options={partTId ? processes[partTId.value] : null}
                  onChange={value => this.setState({itionGId: value})}
                />
              </div>
              <div>
                <p>直*</p>
                <Select
                  name="直"
                  styles={{height: 36}}
                  placeholder="選択してください"
                  clearable={false}
                  Searchable={true}
                  value={itorG}
                  options={[
                    {label: '黄直', value: 'Y'},
                    {label: '白直', value: 'W'},
                    {label: '両直', value: 'both'}
                  ]}
                  onChange={value => this.setState({itorG: value})}
                />
              </div>
            </div>
            <div className="col-2 flex-row">
              <p>表示方法*</p>
              <div
                className={narrowedBy === 'realtime' ? 'realtime active' : 'realtime'}
                onClick={() => this.setState({narrowedBy: 'realtime'})}
              >
                リアルタイム更新
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
            className={`mapping-btn ${partTId && itionGId && itorG && 'active'}`}
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
  let data;
  if (state.PageData.data && typeof(state.PageData.data.pageTypes) !== 'undefined') {
    let aaa = state.PageData.data.pageTypes.reduce((pre, pt) =>{
      const f = pt.failures.map(f => {
        const point = f.point.split(',');
        let x = point[0]/2;
        let y = point[1]/2;

        switch (pt.pageNum) {
          case 2: x = x + 1740/2; break;
          case 3: y = y + 1030/2; break;
          case 4: x = x + 1740/2; y = y + 1030/2; break;
        }

        return {
          id: f.id,
          point: `${x},${y}`,
          sort: f.sort,
          type: f.type
        }
      });

      return {
        failures: pre.failures.concat(f),
        pages: pre.pages+pt.pages,
        path: pre.path.concat([pt.path])
      }
    }, {
      failures: [],
      pages: 0,
      path: []
    })

    state.PageData.data.commentTypes = [];
    state.PageData.data.comments = [];
    state.PageData.data.holePoints = state.PageData.data.holePoints;
    state.PageData.data.failures = aaa.failures;
    state.PageData.data.pages = aaa.pages;
    state.PageData.data.path = aaa.path;
    state.PageData.data.inlines = [];
  }

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
