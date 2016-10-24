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
      active: props.active.name,
      fFilter: [],
      holeStatus: 's1',
      cFilter: []
    };
  }

  componentWillReceiveProps(nextProps) {
    if (this.props.PageData.isFetching && !nextProps.PageData.isFetching) {
      this.setState({
        fFilter: [],
        cFilter: []
      });
    }

    if (this.props.active.time !== nextProps.active.time) {
      this.setState({active: nextProps.active.name});
    }
  }

  formatHole(hole) {
    const point = hole.point.split(',');
    const x = point[0]/2;
    const y = point[1]/2;
    let lx = x;
    let ly = y;

    switch (hole.direction) {
      case 'left':   lx = lx-22; break;
      case 'right':  lx = lx+22; break;
      case 'top':    ly = ly-22; break;
      case 'bottom': ly = ly+22; break;
      default: break;
    }

    return { x, y, ly, lx, ...hole}
  }

  renderContent() {
    const { data } = this.props.PageData;
    const { active, fFilter, holeStatus, cFilter } = this.state;

    switch (active) {
      case 'failure':
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
                        <span>{`${ft.label}. ${ft.name}`}</span>
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
                          {data.failures == undefined ? 0 : data.failures.filter(f => f.label == ft.label).length}
                        </li>
                      )
                    }
                  </ul>
                </div>
              }
            </div>
          </div>
        );
      case 'hole':
        return (
          <div className="hole">
            <div className="collection">
              <div>
                <ul>
                  <li>{'穴'}</li>
                  {data.holePoints.map(h => <li>{h.id}</li>)}
                </ul>
              </div>
              <div>
                <ul>
                  <li
                    onClick={() => this.setState({holeStatus: 's1'})}
                  >
                    <span>{holeStatus == 's1' && <p>{'✔'}︎</p>}</span>
                    {'○'}
                  </li>
                  {data.holePoints.map(h => {
                    let percentage = 0;
                    if (h.sum !== 0 && h.s1 != 0) percentage = Math.round(1000*h.s1/h.sum)/10;
                    return (
                      <li>{h.s1 ? h.s1 : '-'}<span>{`${percentage}%`}</span></li>
                    )
                  })}
                </ul>
              </div>
              <div>
                <ul>
                  <li
                    onClick={() => this.setState({holeStatus: 's2'})}
                  >
                    <span>{holeStatus == 's2' && <p>{'✔'}︎</p>}</span>
                    {'△'}
                  </li>
                  {data.holePoints.map(h => {
                    let percentage = 0;
                    if (h.sum !== 0 && h.s2 != 0) percentage = Math.round(1000*h.s2/h.sum)/10;
                    return (
                      <li>{h.s2 ? h.s2 : '-'}<span>{`${percentage}%`}</span></li>
                    )
                  })}
                </ul>
              </div>
              <div>
                <ul>
                  <li
                    onClick={() => this.setState({holeStatus: 's0'})}
                  >
                    <span>{holeStatus == 's0' && <p>{'✔'}︎</p>}</span>{'×'}
                  </li>
                  {data.holePoints.map(h => {
                    let percentage = 0;
                    if (h.sum !== 0 && h.s0 != 0) percentage = Math.round(1000*h.s0/h.sum)/10;
                    return (
                      <li>{h.s0 ? h.s0 : '-'}<span>{`${percentage}%`}</span></li>
                    )
                  })}
                </ul>
              </div>
            </div>
          </div>
        )
      case 'comment':
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
                        <span>{`${ct.label}. ${ct.name}`}</span>
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
      case 'inline':
        return (
          <div className="inline">
            <div className="collection">
              <div>
                <ul>
                  {
                    Object.keys(data.inlines).map(id =>
                      <li>{data.inlines[id][0].label}</li>
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
    const { active, fFilter, holeStatus, cFilter } = this.state;

    return (
      <div id="mapping-wrap" className="">
        <div className="mapping-body">
          {
            active == 'failure' &&
            <div className="color-label">
              <div>
                <div className="circle-white"></div>
                <p>白直</p>
              </div>
              <div>
                <div className="circle-yellow"></div>
                <p>黄直</p>
              </div>
            </div>
          }{
            active == 'comment' &&
            <div className="color-label">
              <div>
                <div className="rect-white"></div>
                <p>白直</p>
              </div>
              <div>
                <div className="rect-yellow"></div>
                <p>黄直</p>
              </div>
            </div>
          }
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
                active == 'failure' &&
                data.failures.filter(f => fFilter.indexOf(f.id) == -1).map(f => {
                  const point = f.point.split(',');
                  const x = point[0]/2;
                  const y = point[1]/2;
                  if (Array.isArray(data.path)) {
                    if (f.choku == '白直') {
                      return (
                        <g>
                          <circle cx={x} cy={y} r={6} fill="black" />
                          <circle cx={x} cy={y} r={4} fill="white" />
                        </g>
                      );
                    } else {
                      return (
                        <g>
                          <circle cx={x} cy={y} r={6} fill="#C6B700" />
                        </g>
                      );
                    }
                  }
                  else {
                    if (f.choku == '白直') {
                      return (
                        <g>
                          <circle cx={x} cy={y} r={6} fill="black" />
                          <circle cx={x} cy={y} r={4} fill="white" />
                        </g>
                      );
                    } else {
                      return (
                        <g>
                          <circle cx={x} cy={y} r={6} fill="#C6B700" />
                        </g>
                      );
                    }
                  }
                })
              }{
                active == 'hole' &&
                data.holePoints.map(hole => {
                  const h = this.formatHole(hole);
                  if(Array.isArray(data.path)) {
                    switch (h.pageNum) {
                      case 1: h.x = (h.x)/2; h.lx = (h.lx)/2; h.y = (h.y)/2; h.ly = (h.ly)/2; break;
                      case 2: h.x = (h.x + 1740/2)/2; h.y = (h.y)/2; h.lx = (h.lx + 1740/2)/2; h.ly = (h.ly)/2; break;
                      case 3: h.x = (h.x)/2; h.y = (h.y + 1030/2)/2; h.lx = (h.lx)/2; h.ly = (h.ly + 1030/2)/2; break;
                      case 4: h.x = (h.x + 1740/2)/2; h.y = (h.y + 1030/2)/2; h.lx = (h.lx + 1740/2)/2; h.ly = (h.ly + 1030/2)/2; break;
                    }
                  }
                  return (
                    <g>
                      <circle cx={h.x} cy={h.y} r={4} fill="red" />
                      <text
                        x={h.lx}
                        y={h.ly}
                        dy="6"
                        fontSize="14"
                        fill="black"
                        textAnchor="middle"
                        fontWeight="bold"
                        >
                          {h[holeStatus]}
                        </text>
                    </g>
                  )
                })
              }{
                active == 'comment' &&
                data.comments.filter(c => cFilter.indexOf(c.id) == -1).map(c => {
                  const point = c.point.split(',');
                  const x = point[0]/2;
                  const y = point[1]/2;
                  if (c.choku == '白直') {
                    return (
                      <g>
                        <rect x={x-7} y={y-7} width="14" height="14" fill="white"/>
                        <rect x={x-6} y={y-6} width="12" height="12" fill="black"/>
                        <rect x={x-4} y={y-4} width="8" height="8" fill="white"/>
                      </g>
                    ); 
                  } else {
                    return (
                      <g>
                        <rect x={x-6} y={y-6} width="12" height="12" fill="#C6B700"/>
                      </g>
                    ); 
                  }
                })
              }{
                active == 'inline' &&
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
                          {i.label}
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
              {
                data.holePoints.length !== 0 &&
                <button
                  className={active == 'hole' ? '' : 'disable'}
                  onClick={() => this.setState({ active: 'hole'})}
                >
                  穴検査
                </button>
              }{
                data.commentTypes.length !== 0 &&
                <button
                  className={active == 'comment' ? '' : 'disable'}
                  onClick={() => this.setState({ active: 'comment'})}
                >
                  手直し検査
                </button>
              }
              <button
                className={active == 'failure' ? '' : 'disable'}
                onClick={() => this.setState({ active: 'failure'})}
              >
                不良検査
              </button>
              {
                data.inlines.length !== 0 &&
                <button
                  className={active == 'inline' ? '' : 'disable'}
                  onClick={() => this.setState({ active: 'inline'})}
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
  realtime: PropTypes.bool.isRequired,
  active: PropTypes.object.isRequired
};

export default Mapping;
