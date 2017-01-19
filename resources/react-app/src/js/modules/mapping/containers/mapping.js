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
      holeStatus: 0,
      hModification: 1,
      cFilter: [],
      inlineStatus: 's1'
    };
  }

  componentWillReceiveProps(nextProps) {
    if (this.props.active.time !== nextProps.active.time) {
      this.setState({
        active: nextProps.active.name,
        fFilter: [],
        cFilter: []
      });
    }
  }

  formatHole(hole) {
    const point = hole.p.split(',');
    const x = point[0]/2;
    const y = point[1]/2;
    let lx = x;
    let ly = y;

    switch (hole.d) {
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
    const { active, fFilter, holeStatus, cFilter, hModification, inlineStatus } = this.state;

    switch (active) {
      case 'failure':
        return (
          <div className="failure">
          {
            (this.props.partTId.value === 3 || this.props.partTId.value === 4) &&
            <div className="collection">
              <div>
                <ul>
                  <li
                    onClick={() => {
                      let newFilter;
                      if ( fFilter.length !== 0) newFilter = [];
                      else newFilter = data.ft.map(ft => ft.id);
                      this.setState({ fFilter: newFilter });
                    }}
                  >
                    <span>{fFilter.length === 0 && <p>{'✔'}︎</p>}</span>
                    <span>不良区分</span>
                  </li>
                  {data.ft.map(ft =>{
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
              <div>
                <ul className="parts">
                  <li>LH</li>
                  {
                    data.ft.map(ft => 
                      <li>
                        {data.failures ? data.failures.filter(f => f.id == ft.id && f.pt == 4).length : 0}
                      </li>
                    )
                  }
                </ul>
              </div>
              <div>
                <ul className="parts">
                  <li>RH</li>
                  {
                    data.ft.map(ft => 
                      <li>
                        {data.failures ? data.failures.filter(f => f.id == ft.id && f.pt == 3).length : 0}
                      </li>
                    )
                  }
                </ul>
              </div>
            </div>
          }{
            (this.props.partTId.value === 5 || this.props.partTId.value === 6) &&
            <div className="collection">
              <div>
                <ul>
                  <li
                    onClick={() => {
                      let newFilter;
                      if ( fFilter.length !== 0) newFilter = [];
                      else newFilter = data.ft.map(ft => ft.id);
                      this.setState({ fFilter: newFilter });
                    }}
                  >
                    <span>{fFilter.length === 0 && <p>{'✔'}︎</p>}</span>
                    <span>不良区分</span>
                  </li>
                  {data.ft.map(ft =>{
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
              <div>
                <ul className="parts">
                  <li>LH</li>
                  {
                    data.ft.map(ft => 
                      <li>
                        {data.failures ? data.failures.filter(f => f.id == ft.id && f.pt == 6).length : 0}
                      </li>
                    )
                  }
                </ul>
              </div>
              <div>
                <ul className="parts">
                  <li>RH</li>
                  {
                    data.ft.map(ft => 
                      <li>
                        {data.failures ? data.failures.filter(f => f.id == ft.id && f.pt == 5).length : 0}
                      </li>
                    )
                  }
                </ul>
              </div>
            </div>
          }{
            this.props.partTId.value !== 3 && this.props.partTId.value !== 4 && this.props.partTId.value !== 5 && this.props.partTId.value !== 6 &&
            <div className="collection">
              <div>
                <ul>
                  <li
                    onClick={() => {
                      let newFilter;
                      if ( fFilter.length !== 0) newFilter = [];
                      else newFilter = data.ft.map(ft => ft.id);
                      this.setState({ fFilter: newFilter });
                    }}
                  >
                    <span>{fFilter.length === 0 && <p>{'✔'}︎</p>}</span>
                    <span>不良区分</span>
                  </li>
                  {data.ft.map(ft =>{
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
              <div>
                <ul className="parts">
                  <li>計</li>
                  {
                    data.ft.map(ft => 
                      <li>
                        {data.failures ? data.failures.filter(f => f.id == ft.id).length : 0}
                      </li>
                    )
                  }
                </ul>
              </div>
            </div>
          }
          </div>
        );
      case 'hole':
        return (
          <div className="hole">
            <div className="collection">
              <div>
                <ul>
                  <li>{'穴'}</li>
                  {
                    (this.props.partTId.value === 3 || this.props.partTId.value === 4 || this.props.partTId.value === 5 || this.props.partTId.value === 6) ?
                    data.holes.map(h => {
                      let side = 'L';
                      if (h.pt === 3 || h.pt === 5) {
                        side = 'R';
                      }
                      return (<li>{`${h.l}(${side})`}</li>)
                    }):
                    data.holes.map(h => <li>{h.l}</li>)
                  }
                </ul>
              </div>
              <div>
                <ul>
                  <li
                    onClick={() => this.setState({holeStatus: 0})}
                  >
                    <span>{holeStatus === 0 && <p>{'✔'}︎</p>}</span>
                    {'×'}
                  </li>
                  {data.holes.map(h => {
                    let percentage = 0;
                    const sum = data.count/data.pageTypes.length;
                    const sum0 = h.s.filter(s => s.s == 0).length;
                    if (sum !== 0) percentage = Math.round(1000*sum0/sum)/10;

                    return (
                      <li>{`${percentage}%`}<span>({sum !== 0 ? sum0 : '-'})</span></li>
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
                  {data.holes.map(h => {
                    let percentage = 0;
                    const sum = data.count/data.pageTypes.length;
                    const sum2 = h.s.filter(s => s.s == 2).length;
                    if (sum !== 0) percentage = Math.round(1000*sum2/sum)/10;

                    return (
                      <li>{`${percentage}%`}<span>({sum !== 0 ? sum2 : '-'})</span></li>
                    )
                  })}
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
                  {data.holes.map(h => {
                    let percentage = 0;
                    const sum = data.count/data.pageTypes.length;
                    const sum02 = h.s.length;
                    if (sum !== 0) percentage = Math.round(1000*(sum-sum02)/sum)/10;

                    return (
                      <li>{`${percentage}%`}<span>({sum !== 0 ? (sum-sum02) : '-'})</span></li>
                    )
                  })}
                </ul>
              </div>
              <div>
                <ul>
                  <li
                    onClick={() => this.setState({holeStatus: 3})}
                  >
                    {/*<span>{holeStatus == 's3' && <p>{'✔'}︎</p>}</span>*/}
                    {'手直'}
                  </li>
                  {data.holes.map(h => {
                    // let percentage = 0;
                    // const sum = h.s.length;
                    const sumM = h.s.filter(s => s.hm !== null).length;
                    // if (sum !== 0) percentage = Math.round(1000*sum2/sum)/10;

                    return (
                      <li>{sumM !== 0 ? sumM : '-'}</li>
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
                      else newFilter = data.mt.map(ft => ft.id);
                      this.setState({ cFilter: newFilter });
                    }}
                  >
                    <span>{cFilter.length === 0 &&<p>{'✔'}︎</p>}</span>
                    <span>手直し区分</span>
                  </li>
                  {data.mt.map(ct =>{
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
                      data.mt.map(ct => 
                        <li>
                          {data.modifications ? data.modifications.filter(m => m.id == ct.id).length : 0}
                        </li>
                      )
                    }
                  </ul>
                </div>
              }
            </div>
          </div>
        );
      case 'hModification':
        return (
          <div className="hModification">
            <div className="collection">
              <div>
                <ul>
                  <li>{'穴'}</li>
                  {
                    (this.props.partTId.value === 3 || this.props.partTId.value === 4 || this.props.partTId.value === 5 || this.props.partTId.value === 6) ?
                    data.holes.map(h => {
                      let side = 'L';
                      if (h.pt === 3 || h.pt === 5) {
                        side = 'R';
                      }
                      return (<li>{`${h.l}(${side})`}</li>)
                    }):
                    data.holes.map(h => <li>{h.l}</li>)
                  }
                </ul>
              </div>
              {
                data.hmt.map(hm => {
                  return (
                    <div>
                      <ul>
                        <li
                          onClick={() => this.setState({hModification: hm.id})}
                        >
                          <span>{hModification == hm.id && <p>{'✔'}︎</p>}</span>
                          {hm.name}
                        </li>
                        {data.holes.map(h => {
                          let percentage = 0;
                          const sum = h.s.filter(s => s.hm !== null).length;
                          const modified = h.s.filter(s => s.hm == hm.id).length;
                          if (h.sum !== 0 && modified != 0) percentage = Math.round(1000*modified/sum)/10;

                          return (
                            <li>{modified ? modified : ''}{/*<span>{percentage ? `${percentage}%`: ''}</span>*/}</li>
                          )
                        })}
                      </ul>
                    </div>
                  );
                })
              }
            </div>
          </div>
        );
      case 'inline':
        return (
          <div className="inline">
          {
            data.inlines !== null && !Array.isArray(data.inlines) &&
            <div className="collection">
              <div>
                <ul>
                  <li>{'精度検査'}</li>
                  {data.i.map(i => 
                    <li>{i.sort}</li>
                  )}
                </ul>
              </div>
              <div>
                <ul>
                  <li
                    onClick={() => this.setState({inlineStatus: 's0'})}
                  >
                    {'×'}
                  </li>
                  {data.i.map(ii => {
                    let percentage = 0;
                    const sum = data.inlines[ii.id].length;
                    const sum0 = data.inlines[ii.id].filter(i => ii.maxTolerance < i || ii.minTolerance > i).length;
                    if (sum !== 0 && sum0 != 0) percentage = Math.round(1000*sum0/sum)/10;
                    return (
                      <li>{`${percentage}%`}<span>({sum0})</span></li>
                    )
                  })}
                </ul>
              </div>
              <div>
                <ul>
                  <li
                    onClick={() => this.setState({inlineStatus: 's1'})}
                  >
                    {'○'}
                  </li>
                  {data.i.map(ii => {
                    let percentage = 0;
                    const sum = data.inlines[ii.id].length;
                    const sum1 = data.inlines[ii.id].filter(i => ii.maxTolerance >= i && ii.minTolerance <= i).length;
                    if (sum !== 0 && sum1 != 0) percentage = Math.round(1000*sum1/sum)/10;

                    return (
                      <li>{`${percentage}%`}<span>({sum1})</span></li>
                    )
                  })}
                </ul>
              </div>
            </div>
          }
          </div>
        );
    }
  }

  render() {
    const { isFetching, data } = this.props.PageData;
    const { active, fFilter, holeStatus, cFilter, hModification } = this.state;

    return (
      <div id="mapping-wrap" className="">
        <div className="mapping-body">
          {
            active == 'failure' &&
            <div className="color-label">
              <div>
                <div className="circle-red"></div>
                <p>白直</p>
              </div>
              <div>
                <div className="circle-yellow"></div>
                <p>黄直</p>
              </div>
              <div>
                <div className="circle-blue"></div>
                <p>黒直</p>
              </div>
            </div>
          }{
            active == 'comment' &&
            <div className="color-label">
              <div>
                <div className="rect-red"></div>
                <p>白直</p>
              </div>
              <div>
                <div className="rect-yellow"></div>
                <p>黄直</p>
              </div>
              <div>
                <div className="rect-blue"></div>
                <p>黒直</p>
              </div>
            </div>
          }
          <div className="figure-wrap">
            <div className="figure">
            {
              data.pageTypes.length == 1 ? 
              <img src={`/img/figures/${data.pageTypes[0].path}`}/> :
              data.pageTypes.map(pt =>
                <img src={`/img/figures/${pt.path}`} className="quarter"/>
              )
            }
            </div>
            <svg>
              {
                active == 'failure' &&
                data.failures.filter(f => fFilter.indexOf(f.id) == -1).map(f => {
                  const point = f.p.split(',');
                  let x = point[0]/2;
                  let y = point[1]/2;

                  if(data.pageTypes.length > 1) {
                    const number = data.pageTypes.find(pt => pt.id == f.pg).n;
                    switch (number) {
                      case 1: x = x/2;            y = y/2;            break;
                      case 2: x = (x + 1740/2)/2; y = y/2;            break;
                      case 3: x = x/2;            y = (y + 1030/2)/2; break;
                      case 4: x = (x + 1740/2)/2; y = (y + 1030/2)/2; break;
                    }
                  }

                  switch (f.c) {
                    case '白直':
                      return (
                        <g>
                          <circle cx={x} cy={y} r={5} fill="red" />
                        </g>
                      );
                      break;
                    case '黄直':
                      return (
                        <g>
                          <circle cx={x} cy={y} r={5} fill="#C6B700" />
                        </g>
                      );
                      break;
                    case '黒直':
                      return (
                        <g>
                          <circle cx={x} cy={y} r={5} fill="blue" />
                        </g>
                      );
                      break;
                  }
                })
              }{
                active == 'hole' &&
                data.holes.map(hole => {
                  const h = this.formatHole(hole);

                  if(data.pageTypes.length > 1) {
                    const number = data.pageTypes.find(pt => pt.id == hole.pg).n;
                    switch (number) {
                      case 1: h.x = (h.x)/2; h.lx = (h.lx)/2; h.y = (h.y)/2; h.ly = (h.ly)/2; break;
                      case 2: h.x = (h.x + 1740/2)/2; h.y = (h.y)/2; h.lx = (h.lx + 1740/2)/2; h.ly = (h.ly)/2; break;
                      case 3: h.x = (h.x)/2; h.y = (h.y + 1030/2)/2; h.lx = (h.lx)/2; h.ly = (h.ly + 1030/2)/2; break;
                      case 4: h.x = (h.x + 1740/2)/2; h.y = (h.y + 1030/2)/2; h.lx = (h.lx + 1740/2)/2; h.ly = (h.ly + 1030/2)/2; break;
                    }
                  }

                  const s = h.s.map(s => s.s);
                  let disable = s.indexOf(holeStatus) === -1;
                  if (holeStatus === 1) {
                    disable = s.length === data.count/data.pageTypes.length;
                  }

                  return (
                    <g>
                      <circle cx={h.x} cy={h.y} r={4} fill={disable ? 'rgba(255,0,0,0.4)' : 'rgba(255,0,0,1)'} />
                      <text
                        x={h.lx}
                        y={h.ly}
                        dy="6"
                        fontSize="12"
                        fill={disable ? 'rgba(0,0,0,0.3)' : 'rgba(0,0,0,1)'}
                        textAnchor="middle"
                        fontWeight="bold"
                        >
                          {h.l}
                        </text>
                    </g>
                  )
                })
              }{
                active == 'comment' &&
                data.modifications.filter(c => cFilter.indexOf(c.id) == -1).map(m => {
                  const point = m.p.split(',');
                  const x = point[0]/2;
                  const y = point[1]/2;

                  if (m.c == '白直') {
                    return (
                      <g>
                        <rect x={x-6} y={y-6} width="12" height="12" fill="red"/>
                      </g>
                    ); 
                  } else if (m.c == '黄直'){
                    return (
                      <g>
                        <rect x={x-6} y={y-6} width="12" height="12" fill="#C6B700"/>
                      </g>
                    ); 
                  }else if (m.c == '黒直'){
                    return (
                      <g>
                        <rect x={x-6} y={y-6} width="12" height="12" fill="blue"/>
                      </g>
                    ); 
                  }
                })
              }{
                active == 'hModification' &&
                data.holes.map(hole => {
                  const h = this.formatHole(hole);

                  if(data.pageTypes.length > 1) {
                    const number = data.pageTypes.find(pt => pt.id == hole.pg).n;
                    switch (number) {
                      case 1: h.x = (h.x)/2; h.lx = (h.lx)/2; h.y = (h.y)/2; h.ly = (h.ly)/2; break;
                      case 2: h.x = (h.x + 1740/2)/2; h.y = (h.y)/2; h.lx = (h.lx + 1740/2)/2; h.ly = (h.ly)/2; break;
                      case 3: h.x = (h.x)/2; h.y = (h.y + 1030/2)/2; h.lx = (h.lx)/2; h.ly = (h.ly + 1030/2)/2; break;
                      case 4: h.x = (h.x + 1740/2)/2; h.y = (h.y + 1030/2)/2; h.lx = (h.lx + 1740/2)/2; h.ly = (h.ly + 1030/2)/2; break;
                    }
                  }

                  const modificatedHole = h.s.filter(s => s.hm).map(s => s.hm);
                  const includ = modificatedHole.indexOf(hModification) >= 0;

                  return (
                    <g>
                      <circle cx={h.x} cy={h.y} r={4} fill={includ ? 'rgba(255,0,0,1)' : 'rgba(255,0,0,0.4)'} />
                      <text
                        x={h.lx}
                        y={h.ly}
                        dy="6"
                        fontSize="12"
                        fill={includ ? 'rgba(0,0,0,1)' : 'rgba(0,0,0,0.3)'}
                        textAnchor="middle"
                        fontWeight="bold"
                        >
                          {h.l}
                        </text>
                    </g>
                  )
                })
              }{
                active == 'inline' &&
                Object.keys(data.i).map(id => {
                  return data.i.map(i => {
                    const width = 106;
                    const height = 30;
                    const point = i.point.split(',');
                    const x = point[0]/2;
                    const y = point[1]/2;

                    const labelPoint = i.labelPoint.split(',');
                    const lx = labelPoint[0]/2;
                    const ly = labelPoint[1]/2;
                    return (
                      <g>
                        <circle cx={x} cy={y} r={6} fill="red" />
                        <rect x={lx} y={ly} width={width} height={height} fill="white" stroke="gray"></rect>
                        <line x1={x} y1={y} x2={i.side == 'left' ? lx : lx + width} y2={ly+height/2} stroke="#e74c3c" stroke-width="10" />
                        <text
                          x={lx}
                          y={ly}
                          dx="6"
                          dy="20"
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
                            dx="22"
                            dy="20"
                            fontSize="12"
                            fill="black"
                            text-anchor="middle"
                          >
                            {`(${i.face})`}
                          </text>
                        }{
                          i.position &&
                          <text
                            x={lx}
                            y={ly}
                            dx="52"
                            dy="20"
                            fontSize="8"
                            fill="black"
                            text-anchor="middle"
                          >
                            {i.position}
                          </text>
                        }
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
                data.holes.length !== 0 &&
                <button
                  className={active == 'hole' ? '' : 'disable'}
                  onClick={() => this.setState({ active: 'hole'})}
                >
                  穴検査
                </button>
              }{
                data.mt.length !== 0 &&
                <button
                  className={active == 'comment' ? '' : 'disable'}
                  onClick={() => this.setState({ active: 'comment'})}
                >
                  手直し
                </button>
              }{
                data.hmt.length !== 0 &&
                <button
                  className={active == 'hModification' ? '' : 'disable'}
                  onClick={() => this.setState({ active: 'hModification'})}
                >
                  手直し
                </button>
              }{
                data.ft.length !== 0 &&
                <button
                  className={active == 'failure' ? '' : 'disable'}
                  onClick={() => this.setState({ active: 'failure'})}
                >
                  外観検査
                </button>
              }{
                data.i.length !== 0 && data.inlines !== null &&
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
          !isFetching && data.count == 0 &&
          <div className="cover">
            <p>検査結果が見つかりませんでした</p>
          </div>
        }
        {
          !isFetching && data.message == 'notFound' &&
          <div className="cover">
            <p>検索条件が間違っています</p>
          </div>
        }
        {
          !isFetching && data.message == 'over limit' &&
          <div className="cover">
            <p>検索条件が広すぎます</p>
          </div>
        }
      </div>
    );
  }
}

Mapping.propTypes = {
  PageData: PropTypes.object.isRequired,
  realtime: PropTypes.bool.isRequired,
  active: PropTypes.object.isRequired,
  partTId: PropTypes.number.isRequired
};

export default Mapping;
