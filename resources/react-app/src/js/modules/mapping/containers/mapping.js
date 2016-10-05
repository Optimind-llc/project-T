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
    getPageData(props.id);

    this.state = {
      intervalId: null,
      interval: 100000,
      innerHeight: window.innerHeight,
      failure: true,
      iFailure: true,
      nFailure: true,
      hole: false,
      holeStatus: 0
    };
  }

  componentDidMount() {
    const { id, actions: {getPageData} } = this.props;
    const { interval } = this.state;
    const intervalId = setInterval(()=> getPageData(id), interval);

    this.setState({intervalId});
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

  renderContent() {
    const { data } = this.props.PageData;
    const { failure, hole, holeStatus, iFailure, nFailure } = this.state;

    if (failure && !hole){
      return (
        <div className="failure">
          <div className="switch">
            <button
              key="iFailure"
              className={iFailure ? 'active' : ''}
              onClick={() => this.setState({ iFailure: !iFailure })}
            >
              {'重要不良'}
            </button>
            <button
              key="nFailure"
              className={nFailure ? 'active' : ''}
              onClick={() => this.setState({ nFailure: !nFailure })}
            >
              {'普通不良'}
            </button>
          </div>
          <div className="collection">
            {
              data.parts.map(part => {
                const failures = data.failures[part.pn];
                console.log(failures);
                return (
                  <div key={part.pn}>
                    <p>{part.name}</p>
                    <ul>
                      <li>
                        <span>{'重要不良'}</span>
                        <span>{failures == undefined ? 0 : failures.filter(f => f.type == '1').length}</span>
                      </li>
                      <li>
                        <span>{'普通不良'}</span>
                        <span>{failures == undefined ? 0 :failures.filter(f => f.type == '2').length}</span>
                      </li>
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
          <div className="switch">
            <button
              key="holeStatus1"
              className={holeStatus == 1 ? 'active' : ''}
              onClick={() => this.setState({ holeStatus: 1 })}
            >
              {'公差内 ○'}
            </button>
            <button
              key="holeStatus2"
              className={holeStatus == 2 ? 'active' : ''}
              onClick={() => this.setState({ holeStatus: 2 })}
            >
              {'穴小 △'}
            </button>
            <button
              key="holeStatus0"
              className={holeStatus == 0 ? 'active' : ''}
              onClick={() => this.setState({ holeStatus: 0 })}
            >
              {'穴大 ×'}
            </button>
          </div>
          <div className="collection">
            {
              data.parts.map(part => {
                const holes = data.holes[part.pn];
                return (
                  <div key={part.pn}>
                    <p>{part.name}</p>
                    <ul>
                      <li>
                        <span>{'公差内 ○'}</span>
                        <span>{this.formatHolesByPart(holes).filter(s => s == 0).length}</span>
                      </li>
                      <li>
                        <span>{'穴小 △'}</span>
                        <span>{this.formatHolesByPart(holes).filter(s => s == 2).length}</span>
                      </li>
                      <li>
                        <span>{'穴大 ×'}</span>
                        <span>{this.formatHolesByPart(holes).filter(s => s == 1).length}</span>
                      </li>
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
    const { isFetching, data } = this.props.PageData;
    const { state } = this;

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
              </h4>
            </div>
            <div className="mapping-body">
              <div className="figure-wrap">
                <ul className="parts-info">
                  {
                    data.parts.map(part =>
                      <li key={part.pn}>
                        <span className="small">品番：</span><span>{part.pn}</span>
                        <span className="small">品名：</span><span>{part.name}</span>
                      </li>
                    )
                  }
                </ul>
                <div className="figure">         
                  <img src={data.path}/>
                  <svg>
                    {state.failure &&
                      Object.keys(data.failures).map(part => {
                        return data.failures[part].filter(f => {
                          if (state.iFailure && state.nFailure) {
                            return true;
                          }
                          else if(!state.iFailure && state.nFailure) {
                            return f.type == "2";
                          }
                          else if(state.iFailure && !state.nFailure) {
                            return f.type == "1";
                          }
                          else {
                            return false;
                          }
                        }).map(f => {
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
                    }
                    {state.hole &&
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
                                  {holes.status.filter(s => s == state.holeStatus).length}
                                </text>
                            </g>
                          )
                        })
                      })
                    }
                  </svg>
                </div>
              </div>
              
              <div className="control-panel">
                <div className="control-tab">
                  <button
                    className={state.failure ? '' : 'disable'}
                    onClick={() => this.setState({
                      failure: true,
                      hole: false,
                    })}
                  >
                    不良検査
                  </button>
                  {
                    !data.hole === undefined &&
                    <button
                      className={state.hole ? '' : 'disable'}
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
  PageData: PropTypes.object.isRequired,
};

function mapStateToProps(state, ownProps) {
  return {
    id: ownProps.params.id,
    PageData: state.PageData,
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({}, pageActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Mapping);
