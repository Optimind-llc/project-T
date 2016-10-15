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

    actions.getVehicleData();

    this.state = {
      vehicle: null,
      itorG: null,
      itionG: null,
      page: null,
      narrowedBy: 'realtime',
      startDate: moment(),
      endDate: moment(),
      panelId: ''
    };
  }

  componentWillReceiveProps(nextProps) {
    const { data, isFetching } = nextProps.PageTData;

    if (data) {
      this.setState({
        pageTypeId: data[0].id
      });
    }
  }

  serchItorG() {
    const { getItorGData } = this.props.actions;
    getItorGData();
  }

  serchPageT() {
    const { getPageTData } = this.props.actions;
    const { vehicle, itionG } = this.state;

    getPageTData(vehicle.c, itionG.p, itionG.i, itionG.d, itionG.l);
  }

  render() {
    const { vehicle, itorG, showItionG, itionG, page, narrowedBy, startDate, endDate, panelId } = this.state;
    const { VehicleData, ItorGData, AllItionGData, PageTData, actions } = this.props;

    const format = 'YYYY-MM-DD-HH-mm-ss';
    let url = '';

    if (page) {
      if (narrowedBy == 'realtime') {
        url = `${page.id}/${itorG}`;
      }
      else if (narrowedBy == 'term') {
        url = `${page.id}/${itorG}?start=${startDate.format(format)}&end=${endDate.format(format)}`;
      }
      else if (narrowedBy == 'panelId') {
        url = `${page.id}/${itorG}?panelId=${panelId}`;
      }
    }

    return (
      <div id="dashboardWrap">
        <div className="serch-wrap bg-white">
          <div className="col-1 flex-row">
            <div>
              <p>車種*</p>
              <Select
                name="車種"
                placeholder="選択してください"
                clearable={false}
                Searchable={true}
                value={this.state.vehicle}
                options={VehicleData.data.map(v => {
                  return { value: v.c, label: v.c }
                })}
                onChange={value => this.setState({vehicle: value.value})}
              />
            </div>
            <div>
              <p>部品*</p>
              <Select
                name="部品"
                placeholder="選択してください"
                clearable={false}
                Searchable={true}
                value={this.state.partT}
                options={[
                  {label: 'バックドアインナー', value: 1},
                  {label: 'アッパー', value: 2},
                  {label: 'サイドアッパーRH', value: 3},
                  {label: 'サイドアッパーLH', value: 4},
                  {label: 'サイドロアRH', value: 5},
                  {label: 'サイドロアLH', value: 6},
                  {label: 'バックドアインナASSY', value: 7}
                ]}
                onChange={value => this.setState({partT: value})}
              />
            </div>
            <div>
              <p>工程*</p>
              <Select
                name="工程"
                placeholder="選択してください"
                clearable={false}
                Searchable={true}
                value={{label: '成形工程ライン１', value: 1}}
                options={[
                  {label: '成形工程ライン１', value: 1},
                  {label: '成形工程ライン２', value: 2},
                  {label: '成形工程：精度検査', value: 3},
                  {label: '穴あけ工程', value: 4}
                ]}
                onChange={value => this.setState({ition: value.value})}
              />
            </div>
            <div>
              <p>直*</p>
              <Select
                name="直"
                placeholder="選択してください"
                clearable={false}
                Searchable={true}
                value={{label: '黄直', value: 1}}
                options={[]}
                onChange={value => this.setState({ition: value.value})}
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
                  disabled={narrowedBy !== 'term'}
                  defaultValue={startDate}
                  setState={startDate => this.setState({
                    startDate: startDate
                  })}
                />
                <p>〜</p>
                <RangeCalendar
                  disabled={narrowedBy !== 'term'}
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
            <div
              className="realtime active"
              onClick={() => this.setState({narrowedBy: 'realtime'})}
            >
              表示する
            </div>
          </div>
        </div>
        <Mapping/>
      </div>
    );
  }
}

Dashboard.propTypes = {
  VehicleData: PropTypes.object.isRequired,
  ItorGData: PropTypes.object.isRequired,
  PageTData: PropTypes.object.isRequired,
};

function mapStateToProps(state, ownProps) {
  return {
    VehicleData: state.VehicleData,
    ItorGData: state.ItorGData,
    PageTData: state.PageTData,
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({push},
    vehicleActions,
    itorGActions,
    pageTActions
  );

  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Dashboard);
