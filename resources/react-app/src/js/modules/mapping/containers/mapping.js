import React, { Component, PropTypes } from 'react';
import moment from 'moment';
// Styles
import './mapping.scss';
// Material-ui Components
import { Paper, Dialog, RaisedButton, FlatButton } from 'material-ui';
import { grey50, indigo500 } from 'material-ui/styles/colors';
// Components
import Loading from '../../../components/loading/loading';

class Mapping extends Component {
  constructor(props, context) {
    super(props, context);

    this.state = {
      failure: true,
      hole: false,
      comment: false,
      inline: false,
      fFilter: [],
      holeStatus: 1,
      cFilter: []
    };
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

  renderContent() {
    const { data } = this.props.PageData;
    const { failure, hole, comment, inline, fFilter, holeStatus, cFilter } = this.state;

    if (failure){
      return (
        <div className="failure">
          <div className="collection">
            <div>
              <ul>
                <li
                  onClick={() => {
                    let newFilter;
                    if ( fFilter.length !== 0) newFilter = [];
                    else newFilter = data.failureTypes.map(ft => ft.id);
                    this.setState({ fFilter: newFilter });
                  }}
                >
                  <span>{fFilter.length === 0 && <p>{'✔'}︎</p>}</span>
                  <span>不良区分</span>
                </li>
                {data.failureTypes.map(ft =>{
                  const index = fFilter.indexOf(ft.id);
                  return (
                    <li
                      key={ft.id}
                      className={index === -1 ? 'active' : ''}
                      onClick={() => {
                        if ( index === -1) fFilter.push(ft.id);
                        else fFilter.splice(index, 1);
                        this.setState({ fFilter });
                      }}
                    >
                      <span>{index === -1 && <p>{'✔'}︎</p>}</span>
                      <span>{`${ft.sort}. ${ft.name}`}</span>
                    </li>
                  );
                })}
              </ul>
            </div>
            {
              <div>
                <ul className="parts">
                  <li>計</li>
                  {
                    data.failureTypes.map(ft => 
                      <li>
                        {data.failures == undefined ? 0 : data.failures.filter(f => f.sort == ft.sort).length}
                      </li>
                    )
                  }
                </ul>
              </div>
            }
          </div>
        </div>
      );
    }
    else if (hole) {
      return (
        <div className="hole">
          <div className="collection">
            <div>
              <ul>
                <li>{'穴'}</li>
                {Object.keys(data.holes).map(id => <li>{id}</li>)}
              </ul>
            </div>
            <div>
              <ul>
                <li
                  onClick={() => this.setState({holeStatus: 1})}
                >
                  <span>{holeStatus === 1 && <p>{'✔'}︎</p>}</span>
                  {'○'}
                </li>
                {Object.keys(data.holes).map(id => {
                  const all = data.holes[id].length;
                  const s1 = data.holes[id].filter(s => s.status == 1).length;
                  return (
                    <li>{s1}<span>{`${s1 == 0 ? 0 : Math.round(1000*s1/all)/10}%`}</span></li>
                  )
                })}
              </ul>
            </div>
            <div>
              <ul>
                <li
                  onClick={() => this.setState({holeStatus: 2})}
                >
                  <span>{holeStatus === 2 && <p>{'✔'}︎</p>}</span>
                  {'△'}
                </li>
                {Object.keys(data.holes).map(id => {
                  const all = data.holes[id].length;
                  const s2 = data.holes[id].filter(s => s.status == 2).length;
                  return (
                    <li>{s2}<span>{`${s2 == 0 ? 0 : Math.round(1000*s2/all)/10}%`}</span></li>
                  )
                })}
              </ul>
            </div>
            <div>
              <ul>
                <li
                  onClick={() => this.setState({holeStatus: 0})}
                >
                  <span>{holeStatus === 0 && <p>{'✔'}︎</p>}</span>{'×'}
                </li>
                {Object.keys(data.holes).map(id => {
                  const all = data.holes[id].length;
                  const s0 = data.holes[id].filter(s => s.status == 0).length;
                  return (
                    <li>{s0}<span>{`${s0 == 0 ? 0 : Math.round(1000*s0/all)/10}%`}</span></li>
                  )
                })}
              </ul>
            </div>
          </div>
        </div>
      )
    }
    else if (comment){
      return (
        <div className="comment">
          <div className="collection">
            <div>
              <ul>
                <li
                  onClick={() => {
                    let newFilter;
                    if ( cFilter.length !== 0) newFilter = [];
                    else newFilter = data.commentTypes.map(ft => ft.id);
                    this.setState({ cFilter: newFilter });
                  }}
                >
                  <span>{cFilter.length === 0 &&<p>{'✔'}︎</p>}</span>
                  <span>手直し区分</span>
                </li>
                {data.commentTypes.map(ct =>{
                  const index = cFilter.indexOf(ct.id);
                  return (
                    <li
                      key={ct.id}
                      className={index === -1 ? 'active' : ''}
                      onClick={() => {
                        if ( index === -1) cFilter.push(ct.id);
                        else cFilter.splice(index, 1);
                        this.setState({ cFilter });
                      }}
                    >
                      <span>{index === -1 &&<p>{'✔'}︎</p>}</span>
                      <span>{`${ct.sort}. ${ct.message}`}</span>
                    </li>
                  );
                })}
              </ul>
            </div>
            {
              <div>
                <ul className="parts">
                  <li>計</li>
                  {
                    data.commentTypes.map(ct => 
                      <li>
                        {data.comments == undefined ? 0 : data.comments.filter(c => c.id == ct.id).length}
                      </li>
                    )
                  }
                </ul>
              </div>
            }
          </div>
        </div>
      );
    }
    else if (inline) {
      return (
        <div className="inline">
          <div className="collection">
            <div>
              <ul>
                {
                  Object.keys(data.inlines).map(id =>
                    <li>{data.inlines[id][0].sort}</li>
                  )
                }
              </ul>
            </div>
            <div>
              <ul>
                {
                  Object.keys(data.inlines).map(id =>
                    <li>{data.inlines[id][0].tolerance}</li>
                  )
                }
              </ul>
            </div>
          </div>
        </div>
      );
    }
  }

  render() {
    const { isFetching, data } = this.props.PageData;
    const { failure, hole, comment, inline, fFilter, holeStatus, cFilter } = this.state;

    return (
      <div id="mapping-wrap" className="">
        <div className="mapping-body">
          <div className="figure-wrap">
            <div className="figure">
              {
                Array.isArray(data.path) ?
                data.path.map(path =>
                  <img src={path} className="quarter"/>
                ):
                <img src={data.path}/>
              }
            </div>
            <svg>
              {
                failure &&
                data.failures.filter(f => fFilter.indexOf(f.id) == -1).map(f => {
                  const point = f.point.split(',');
                  const x = point[0]/2;
                  const y = point[1]/2;
                  return (
                    <g>
                      <circle cx={x} cy={y} r={6} fill="red" />
                    </g>
                  );
                })
              }{
                hole &&
                Object.keys(data.holes).map(id => {
                  const holes = this.formatHoles(data.holes[id]);
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
              }{
                comment &&
                data.comments.filter(c => cFilter.indexOf(c.id) == -1).map(c => {
                  const point = c.point.split(',');
                  const x = point[0]/2;
                  const y = point[1]/2;
                  return (
                    <g>
                      <circle cx={x} cy={y} r={6} fill="blue" />
                    </g>
                  );
                })
              }{
                inline &&
                Object.keys(data.inlines).map(id => {
                  return data.inlines[id].map(i => {
                    const width = 120;
                    const point = i.point.split(',');
                    const x = point[0]/2;
                    const y = point[1]/2;

                    const labelPoint = i.labelPoint.split(',');
                    const lx = labelPoint[0]/2;
                    const ly = labelPoint[1]/2;
                    return (
                      <g>
                        <circle cx={x} cy={y} r={6} fill="red" />
                        <rect x={lx} y={ly} width={width} height="30" fill="white" stroke="gray"></rect>
                        <line x1={x} y1={y} x2={i.side == 'left' ? lx : lx + width} y2={ly+15} stroke="#e74c3c" stroke-width="10" />
                        <text
                          x={lx}
                          y={ly}
                          dx="4"
                          dy="14"
                          fontSize="12"
                          fill="black"
                          fontWeight="bold"
                          text-anchor="middle"
                        >
                          {i.sort}
                        </text>
                        {
                          i.face &&
                          <text
                            x={lx}
                            y={ly}
                            dx="4"
                            dy="26"
                            fontSize="10"
                            fill="black"
                            text-anchor="middle"
                          >
                            {i.face}
                          </text>
                        }
                        <text
                          x={lx}
                          y={ly}
                          dx="24"
                          dy="12"
                          fontSize="10"
                          fill="black"
                        >
                          {`結果：${i.status}`}
                        </text>
                        <text
                          x={lx}
                          y={ly}
                          dx="24"
                          dy="24"
                          fontSize="10"
                          fill="black"
                        >
                          {`公差：${i.tolerance}`}
                        </text>
                      </g>
                    );
                  })
                })
              }
            </svg>
          </div>
          <div className="control-panel">
            <div className="control-tab">
              <button
                className={failure ? '' : 'disable'}
                onClick={() => this.setState({
                  failure: true,
                  hole: false,
                  comment: false,
                  inline: false
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
                    comment: false,
                    inline: false
                  })}
                >
                  穴検査
                </button>
              }{
                data.commentTypes.length !== 0 &&
                <button
                  className={comment ? '' : 'disable'}
                  onClick={() => this.setState({
                    failure: false,
                    hole: false,
                    comment: true,
                    inline: false
                  })}
                >
                  手直し検査
                </button>
              }{
                data.inlines.length !== 0 &&
                <button
                  className={inline ? '' : 'disable'}
                  onClick={() => this.setState({
                    failure: false,
                    hole: false,
                    comment: false,
                    inline: true
                  })}
                >
                  精度検査
                </button>
              }
            </div>
            <div className="control-content">
              {this.renderContent()}
            </div>
          </div>
        </div>
        {
          isFetching && <Loading/>
        }
        {
          !isFetching && data.pages == 0 && !this.props.realtime &&
          <div className="cover">
            <p>見つかりませんでした</p>
          </div>
        }
      </div>
    );
  }
}

Mapping.propTypes = {
  PageData: PropTypes.object.isRequired,
  realtime: PropTypes.bool.isRequired
};

export default Mapping;
