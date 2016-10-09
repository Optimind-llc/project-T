import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
// Styles
import './mapping.scss';
// Actions
import { pageActions } from '../ducks/page';
// Material-ui Components
import { Paper, Dialog, RaisedButton, FlatButton } from 'material-ui';
import { grey50, indigo500 } from 'material-ui/styles/colors';
// Components
import Loading from '../../../components/loading/loading';

class Mapping extends Component {
  constructor(props, context) {
    super(props, context);
    const { actions: {getPageData} } = props;

    props.PageData.data = null;
    getPageData(props.id, props.itorG, props.start, props.end, props.panelId);

    this.state = {
      intervalId: null,
      interval: 10000,
      innerHeight: window.innerHeight,
      failure: true,
      iFailure: true,
      nFailure: true,
      dropdown: false,
      fTypeFilter: [],
      fIdFilter: [],
      hole: false,
      holeStatus: 0,
      inline: true
    };
  }

  componentWillReceiveProps(nextProps) {
    const { data, isFetching } = nextProps.PageData;
  }

  componentDidMount() {
    const { id, itorG, start, end, panelId, PageData, actions: {getPageData} } = this.props;
    const { interval } = this.state;

    if (!start && !end) {
      const intervalId = setInterval(()=> getPageData(id, itorG, start, end, panelId), interval);
      this.setState({intervalId});
    }

    if (PageData.data) {

    }
  }

  componentWillUnmount() {
    clearInterval(this.state.intervalId);
  }

  serch(groupId) {
    const { state } = this;
    const { actions: {getPageTData} } = this.props;
    getPageTData(groupId);
  }

  formatHoles(holes) {
    const point = holes[0].point.split(',');
    const x = point[0]/2;
    const y = point[1]/2;
    let lx = x;
    let ly = y;

    switch (holes[0].direction) {
      case 'left':   lx = lx-14; break;
      case 'right':  lx = lx+14; break;
      case 'top':    ly = ly-14; break;
      case 'bottom': ly = ly+14; break;
      default: break;
    }

    return {
      x, y, ly, lx,
      part: holes[0].part,
      label: holes[0].label,
      status: holes.map(h => h.status)
    }
  }

  formatHolesByPart(holes) {
    const ids = Object.keys(holes);
    return status = ids.reduce((arr, id) => arr.concat(holes[id].map(h => h.status)), []);
  }

  renderFilter() {
    const { data, isFetching } = this.props.PageData;
    const { failure, hole, holeStatus, iFailure, nFailure, dropdown, fTypeFilter, fIdFilter, inline } = this.state;

    if(failure && !hole) {
      return (
        <div className="filter-wrap">
          <p>フィルタリング</p>
          <div className="filter">
            <button
              key="iFailure"
              className={fTypeFilter.indexOf('1') === -1 ? 'active' : ''}
              onClick={() => {
                const index = fTypeFilter.indexOf('1');
                index === -1 ? fTypeFilter.push('1') : fTypeFilter.splice(index, 1);
                this.setState({ fTypeFilter });
              }}
            >
              {'重要'}
            </button>
            <button
              key="nFailure"
              className={fTypeFilter.indexOf('2') === -1 ? 'active' : ''}
              onClick={() => {
                const index = fTypeFilter.indexOf('2');
                index === -1 ? fTypeFilter.push('2') : fTypeFilter.splice(index, 1);
                this.setState({ fTypeFilter });
              }}
            >
              {'普通'}
            </button>
          </div>
          {
            data &&
            <div className="filter2">
              <button
                className="dropdown-btn"
                onClick={() => this.setState({
                  dropdown: !dropdown
                })}
              >
                <span>
                  {
                    data.failureTypes.filter(ft => 
                      fTypeFilter.indexOf(String(ft.type)) === -1 && fIdFilter.indexOf(ft.id) === -1
                    ).length
                  }
                </span>
                <span>{`/${data.failureTypes.length}`}</span>
                <span>表示中</span>
                <span>{dropdown? '△隠す' : '▽詳細'}</span>
              </button>
              {
                dropdown &&
                <div className="dropdown-list">
                  {
                    data.failureTypes.filter(ft => 
                      fTypeFilter.indexOf(String(ft.type)) === -1
                    ).map(ft => {
                      const index = fIdFilter.indexOf(ft.id);
                      return (
                        <button
                          key={ft.id}
                          className={index === -1 ? 'active' : ''}
                          onClick={() => {
                            if ( index === -1) fIdFilter.push(ft.id);
                            else fIdFilter.splice(index, 1);
                            this.setState({ fIdFilter });
                          }}
                        >
                            {`${ft.sort}. ${ft.name}`}
                        </button>
                      )
                    })
                  }
                </div>
              }
            </div>
          }
        </div>
      );
    }
    else if (!failure && hole) {
      return (
        <div className="filter-wrap">
          <p>表示切り替え</p>
          <div className="filter">
            <button
              key="holeStatus1"
              className={holeStatus == 1 ? 'active none-event' : ''}
              onClick={() => this.setState({ holeStatus: 1 })}
            >
              {'○'}
            </button>
            <button
              key="holeStatus2"
              className={holeStatus == 2 ? 'active none-event' : ''}
              onClick={() => this.setState({ holeStatus: 2 })}
            >
              {'△'}
            </button>
            <button
              key="holeStatus0"
              className={holeStatus == 0 ? 'active none-event' : ''}
              onClick={() => this.setState({ holeStatus: 0 })}
            >
              {'×'}
            </button>
          </div>
        </div>
      );      
    }
  }

  renderContent() {
    const { data } = this.props.PageData;
    const { failure, hole, holeStatus, iFailure, nFailure } = this.state;

    if (failure && !hole){
      return (
        <div className="failure">
          <div className="collection">
            <div>
              <p>{'不良区分'}</p>
              <ul>
                {data.failureTypes.map(ft =>
                  <li key={ft.id}>{`${ft.sort}. ${ft.name}`}</li>
                )}
              </ul>
            </div>
            {
              data.parts.map(part => {
                const failures = data.failures[part.pn];
                return (
                  <div key={part.pn}>
                    <p>{part.name}</p>
                    <ul>
                      {
                        data.failureTypes.map(ft =>
                          <li>
                            {failures == undefined ? 0 : failures.filter(f => f.sort == ft.sort).length}
                          </li>
                        )
                      }
                    </ul>
                  </div>
                )
              })
            }
          </div>
        </div>
      );
    }
    else if (!failure && hole) {
      return (
        <div className="hole">
          <div className="collection">
            <div>
              <p>{'状態'}</p>
              <ul>
                  <li>{'○'}</li>
                  <li>{'△'}</li>
                  <li>{'×'}</li>
              </ul>
            </div>
            {
              data.parts.map(part => {
                const holes = data.holes[part.pn];
                const all = this.formatHolesByPart(holes).length;
                const s0 = this.formatHolesByPart(holes).filter(s => s == 0).length;
                const s2 = this.formatHolesByPart(holes).filter(s => s == 2).length;
                const s1 = this.formatHolesByPart(holes).filter(s => s == 1).length;

                return (
                  <div key={part.pn}>
                    <p>{part.name}</p>
                    <ul>
                      <li>{s0}<span>{`${s0 == 0 ? 0 : Math.round(1000*s0/all)/10}%`}</span></li>
                      <li>{s2}<span>{`${s2 == 0 ? 0 : Math.round(1000*s2/all)/10}%`}</span></li>
                      <li>{s1}<span>{`${s1 == 0 ? 0 : Math.round(1000*s1/all)/10}%`}</span></li>
                    </ul>
                  </div>
                )
              })
            }
          </div>
        </div>
      )
    }
    else {
      return null;
    }
  }

  render() {
    const { start, end, PageData:{ isFetching, data }} = this.props;
    const { failure, hole, holeStatus, iFailure, nFailure, dropdown, fTypeFilter, fIdFilter, inline } = this.state;

    return (
      <div id="mapping-wrap" className="">
        {
          data !== null &&
          <div>
            <div className="mapping-header">
              <h4>
                <span>{`${data.process}工程`}</span>
                <span>{data.inspection}</span>
                <span>{`${data.line == '1' ? 'ライン①' : data.line == '2' ? 'ライン②' : ''}`}</span>
                <span>{`Page${data.number}`}</span>
                <span>{(start && end) ? `${start} ~ ${end}` : 'リアルタイム更新中'}</span>
              </h4>
            </div>
            <div className="mapping-body">
              <div className="figure-wrap">
                <ul className="parts-info">
                  {
                    data.parts.map(part =>
                      <li key={part.pn}>
                        <span className="small">品番: </span><span>{part.pn}</span>
                        <span className="small">品名: </span><span>{part.name}</span>
                      </li>
                    )
                  }
                </ul>
                <div className="figure">       
                  <img src={data.path}/>
                  <svg>
                    {
                      failure &&
                      Object.keys(data.failures).map(part => {
                        return data.failures[part].filter(f => 
                          fTypeFilter.indexOf(f.type) === -1 && fIdFilter.indexOf(f.id) === -1
                        ).map(f => {
                          const point = f.point.split(',');
                          const x = point[0]/2;
                          const y = point[1]/2;
                          return (
                            <g>
                              <circle cx={x} cy={y} r={3} fill="red" />
                            </g>
                          );
                        })
                      })
                    }{
                      hole &&
                      Object.keys(data.holes).map(part => {
                        return Object.keys(data.holes[part]).map(id => {
                          const holes = this.formatHoles(data.holes[part][id]);
                          return (
                            <g>
                              <circle cx={holes.x} cy={holes.y} r={4} fill="red" />
                              <text
                                x={holes.lx}
                                y={holes.ly}
                                dy="6"
                                fontSize="18"
                                fill="black"
                                textAnchor="middle"
                                fontWeight="bold"
                                >
                                  {holes.status.filter(s => s == holeStatus).length}
                                </text>
                            </g>
                          )
                        })
                      })
                    }{
                      inline &&
                      Object.keys(data.inlines).map(id => {
                        return data.inlines[id].map(i => {
                          const width = 80;
                          const point = i.point.split(',');
                          const x = point[0]/2;
                          const y = point[1]/2;

                          const labelPoint = i.labelPoint.split(',');
                          const lx = labelPoint[0]/2;
                          const ly = labelPoint[1]/2;
                          return (
                            <g>
                              <circle cx={x} cy={y} r={3} fill="red" />
                              <rect x={lx} y={ly} width={width} height="30" fill="red"></rect>
                              <line x1={x} y1={y} x2={i.side == 'left' ? lx : lx + width} y2={ly+15} stroke="#e74c3c" stroke-width="10" />
                            </g>
                          );
                        })
                      })
                    }
                  </svg>
                  {this.renderFilter()}
                </div>
              </div>
              
              <div className="control-panel">
                <div className="control-tab">
                  <button
                    className={failure ? '' : 'disable'}
                    onClick={() => this.setState({
                      failure: true,
                      hole: false,
                    })}
                  >
                    不良検査
                  </button>
                  {
                    data.holes.length !== 0 &&
                    <button
                      className={hole ? '' : 'disable'}
                      onClick={() => this.setState({
                        failure: false,
                        hole: true,
                      })}
                    >
                      穴検査
                    </button>
                  }
                </div>
                <div className="control-content">
                  {this.renderContent()}
                </div>
              </div>
            </div>            

          </div>
        }
      </div>
    );
  }
}

Mapping.propTypes = {
  id: PropTypes.string.isRequired,
  itorG: PropTypes.string.isRequired,
  start: PropTypes.string.isRequired,
  end: PropTypes.string.isRequired,
  PageData: PropTypes.object.isRequired
};

function mapStateToProps(state, ownProps) {
  return {
    id: ownProps.params.id,
    itorG: ownProps.params.itorG,
    start: ownProps.location.query.start,
    end: ownProps.location.query.end,
    panelId: ownProps.location.query.panelId,
    PageData: state.PageData
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({}, pageActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Mapping);
