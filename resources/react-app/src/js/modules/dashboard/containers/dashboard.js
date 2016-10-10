import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
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
        {
          vehicle &&
          <div className="header bg-white">
            <h4>
              <span>車種</span>
              <span>{vehicle.c}</span>
              <span onClick={() => this.setState({vehicle: null, itorG: null, itionG: null, page: null})}>変更する</span>
            </h4>
            {
              itorG &&
              <h4>
                <span>直</span>
                <span>{itorG == 'Y' ? '黄直' : itorG == 'W' ? '白直' : '両直'}</span>
                <span onClick={() => this.setState({itorG: null, itionG: null, page: null})}>変更する</span>
              </h4>
            }{
              itionG &&
              <h4>
                <span>区分</span>
                <span className={itionG.p}>{itionG.string}</span>
                <span onClick={() => this.setState({itionG: null, page: null})}>変更する</span>
              </h4>
            }{
              page &&
              <h4>
                <span>ページ</span>
                <span>{page.number}</span>
                <span onClick={() => this.setState({page: null})}>変更する</span>
              </h4>
            }
          </div>
        }{
          VehicleData.data && !vehicle &&
          <div className="select-panel step1 bg-white">
            <p>車種を選択してください</p>
            <div>
              {
                VehicleData.data.map(v => 
                  <button
                    key={v.c}
                    className="gray"
                    onClick={() => {
                      this.setState({vehicle: v});
                      this.serchItorG();
                    }}
                  >
                    {v.c}
                  </button>
                )
              }
              <button
                className="gray disabled"
              >
                {'950A'}
              </button>
            </div>
          </div>
        }{
          ItorGData.data && vehicle && !itorG &&
          <div className="select-panel step2 bg-white">
            <p>直を選択してください</p>
            <div>
              <button
                className="yellow"
                onClick={() => this.setState({itorG: 'Y'})}
              >
                黄直のみ
              </button>
              <button
                className="white"
                onClick={() => this.setState({itorG: 'W'})}
              >
                白直のみ
              </button>
              <button
                className="yellow-white"
                onClick={() => this.setState({itorG: 'both'})}
              >
                両直とも
              </button>
            </div>
          </div>
        }{
          vehicle && itorG && !itionG &&
          <div className="select-panel step3 bg-white">
            <p>区分を選択してください</p>
            <div>
              <div className="process molding">
                <p className="molding">成型</p>
                <div>
                  <div className="inspection check">
                    <p className="molding">検査ライン①</p>
                    <div>
                      <button
                        className="molding"
                        onClick={() => this.setState({itionG: {
                          p: 'molding',
                          i: 'check',
                          d: 'inner',
                          l: 1,
                          string: '成型工程　ライン①　検査　インナー'
                        }}, () => this.serchPageT())}
                      >
                        インナー
                      </button>
                      <button
                        className="molding"
                        onClick={() => this.setState({itionG: {
                          p: 'molding',
                          i: 'check',
                          d: 'small',
                          l: 1,
                          string: '成型工程　ライン①　検査　アウター'
                        }}, () => this.serchPageT())}
                      >
                        アウター
                      </button>
                    </div>
                  </div>
                  <div className="inspection check">
                    <p className="molding">検査ライン②</p>
                    <div>
                      <button
                        className="molding"
                        onClick={() => this.setState({itionG: {
                          p: 'molding',
                          i: 'check',
                          d: 'inner',
                          l: 2,
                          string: '成型工程　ライン②　検査　インナー'
                        }}, () => this.serchPageT())}
                      >
                        インナー
                      </button>
                      <button
                        className="molding"
                        onClick={() => this.setState({itionG: {
                          p: 'molding',
                          i: 'check',
                          d: 'small',
                          l: 2,
                          string: '成型工程　ライン②　検査　アウター'
                        }}, () => this.serchPageT())}
                      >
                        アウター
                      </button>
                    </div>
                  </div>
                  <div className="inspection check">
                    <p className="molding">精度検査</p>
                    <div>
                      <button
                        className="molding"
                        onClick={() => this.setState({itionG: {
                          p: 'molding',
                          i: 'inline',
                          d: 'inner',
                          string: '成型工程　精度検査　インナー'
                        }}, () => this.serchPageT())}
                      >
                        インナー
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <div className="process holing">
                <p className="holing">穴あけ</p>
                <div>
                  <div className="inspection check">
                    <p className="holing">検査</p>
                    <div>
                      <button
                        className="holing"
                        onClick={() => this.setState({itionG: {
                          p: 'holing',
                          i: 'check',
                          d: 'inner',
                          string: '穴あけ工程　検査　インナー'
                        }}, () => this.serchPageT())}
                      >
                        インナー
                      </button>
                      <button
                        className="holing"
                        onClick={() => this.setState({itionG: {
                          p: 'holing',
                          i: 'check',
                          d: 'small',
                          string: '穴あけ工程　検査　アウター'
                        }}, () => this.serchPageT())}
                      >
                        アウター
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <div className="process jointing">
                <p className="jointing">接着</p>
                <div>
                  <div className="inspection check">
                    <p className="jointing">精度検査</p>
                    <div>
                      <button
                        className="jointing"
                        onClick={() => this.setState({itionG: {
                          p: 'jointing',
                          i: 'inline',
                          d: 'inner_assy',
                          string: '接着工程　精度検査　インナーASSY'
                        }}, () => this.serchPageT())}
                      >
                        ASSY
                      </button>
                    </div>
                  </div>
                  <div className="inspection check">
                    <p className="jointing">止水</p>
                    <div>
                      <button
                        className="jointing"
                        onClick={() => this.setState({itionG: {
                          p: 'jointing',
                          i: 'water_stop',
                          d: 'inner_assy',
                          string: '接着工程　止水　インナーASSY'
                        }}, () => this.serchPageT())}
                      >
                        ASSY
                      </button>
                    </div>
                  </div>
                  <div className="inspection check">
                    <p className="jointing">仕上</p>
                    <div>
                      <button
                        className="jointing"
                        onClick={() => this.setState({itionG: {
                          p: 'jointing',
                          i: 'finish',
                          d: 'inner_assy',
                          string: '接着工程　仕上　インナーASSY'
                        }}, () => this.serchPageT())}
                      >
                        ASSY
                      </button>
                    </div>
                  </div>
                  <div className="inspection check">
                    <p className="jointing">点検</p>
                    <div>
                      <button
                        className="jointing"
                        onClick={() => this.setState({itionG: {
                          p: 'jointing',
                          i: 'check',
                          d: 'inner_assy',
                          string: '接着工程　点検　インナーASSY'
                        }}, () => this.serchPageT())}
                      >
                        ASSY
                      </button>
                    </div>
                  </div>
                  <div className="inspection check">
                    <p className="jointing">特検</p>
                    <div>
                      <button
                        className="jointing"
                        onClick={() => this.setState({itionG: {
                          p: 'jointing',
                          i: 'special_check',
                          d: 'inner_assy',
                          string: '接着工程　特検　インナーASSY'
                        }}, () => this.serchPageT())}
                      >
                        ASSY
                      </button>
                    </div>
                  </div>
                  <div className="inspection check">
                    <p className="jointing">手直し</p>
                    <div>
                      <button
                        className="jointing"
                        onClick={() => this.setState({itionG: {
                          p: 'jointing',
                          i: 'adjust',
                          d: 'inner_assy',
                          string: '接着工程　手直し　インナーASSY'
                        }}, () => this.serchPageT())}
                      >
                        ASSY
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        }{
          PageTData.data && !PageTData.isFetching && itionG && !page &&
          <div className="select-panel step4 bg-white">
            {
              PageTData.data.map(p =>
                <div
                  className="page-wrap"
                  onClick={() => this.setState({page: p})}
                >
                  <p><span>Page </span>{p.number}</p>
                  <figure><img src={p.path}/></figure>
                </div>
             )
            }
          </div>
        }{
          PageTData.data && page && 
          <div className="select-panel step5 bg-white">
            <button
              className={narrowedBy === 'realtime' ? "active" : ""}
              onClick={() => this.setState({narrowedBy: 'realtime'})}
            >
              リアルタイム更新（現直＋前直）
            </button>
            <div
              className={narrowedBy === 'term' ? "term-wrap active" : "term-wrap"}
              onClick={() => this.setState({narrowedBy: 'term'})}
            >
              <p>日時を指定</p>
              <div>
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
            </div>
            <div
              className={narrowedBy === 'panelId' ? "panel-id-wrap active" : "panel-id-wrap"}
              onClick={() => this.setState({narrowedBy: 'panelId'})}
            >
              <p>パネルIDを指定</p>
              <input
                type="text"
                value={panelId}
                onChange={(e) => this.setState({panelId: e.target.value})}
              />
            </div>
          </div>
        }{
          page &&
          <button
            className="mapping-btn"
            onClick={() => actions.push(`/manager/mapping/${url}`)}
          >
            この条件でマッピング
          </button>
        }
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
