import React, { Component, PropTypes } from 'react';
// Utils
import { inspectionGroups } from '../../../../utils/Processes';
// Styles
import './mapping.scss';

class Mapping extends Component {
  constructor(props, context) {
    super(props, context);

    this.state = {
      p: null,
      i: null,
      ji: [16, 10, 11, 12, 14],
      active: 'failure',
      fFilter: [],
      holeStatus: 0,
      hModification: 1,
      cFilter: [],
      inlineStatus: 's1'
    };
  }

  requestMapping(ition) {
    const { requestMapping, mappingPartTypeId, header } = this.props;
    const { p, ji } = this.state;

    let itionGIds = inspectionGroups.filter(ig =>
      ig.vehicle == '680A' &&
      (mappingPartTypeId ? (ig.part == mappingPartTypeId) : false) &&
      (p ? (ig.p == p) : false) &&
      (ition ? (ig.i == ition) : false) &&
      !ig.disabled
    ).map(ig => ig.iG);

    if (p === 'j') {
      if (ition !== 'inline') {
        itionGIds = [16, 10, 11, 12, 14];
      }
    }

    requestMapping(itionGIds);
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

  renderPanelContent() {
    const { data } = this.props;
    const { active, fFilter, holeStatus, cFilter, hModification, inlineStatus } = this.state;

    switch (active) {
      case 'failure':
        return (
          <div className="failure">
          {
            data.map(d =>
              <div className="collection">
                <div>
                  <ul>
                    <li
                      onClick={() => {
                        let newFilter;
                        if ( fFilter.length !== 0) newFilter = [];
                        else newFilter = d.ft.map(ft => ft.id);
                        this.setState({ fFilter: newFilter });
                      }}
                    >
                      <span>{fFilter.length === 0 && <p className="icon-check">{'✔'}︎</p>}</span>
                      <span>不良区分</span>
                    </li>
                    {d.ft.map(ft =>{
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
                          <span>{index === -1 && <p className="icon-check">{'✔'}︎</p>}</span>
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
                      d.ft.map(ft => 
                        <li>
                          {d.failures ? d.failures.filter(f => f.id == ft.id).length : 0}
                        </li>
                      )
                    }
                  </ul>
                </div>
                <p>{inspectionGroups.find(ig => ig.iG == d.family.inspectionGroupId).name}</p>
              </div>
            )
          }
          </div>
        );
      case 'hole':
        return (
          <div className="hole">
          {
            data.map(d =>
              <div className="collection">
                <div>
                  <ul>
                    <li>{'穴'}</li>
                    {d.holes.map(h => <li>{h.l}</li>)}
                  </ul>
                </div>
                <div>
                  <ul>
                    <li
                      onClick={() => this.setState({holeStatus: 0})}
                    >
                      <span>{holeStatus === 0 && <p className="icon-check">{'✔'}︎</p>}</span>
                      {'×'}
                    </li>
                    {d.holes.map(h => {
                      let percentage = 0;
                      const sum = 1;
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
                      <span>{holeStatus === 2 && <p className="icon-check">{'✔'}︎</p>}</span>
                      {'△'}
                    </li>
                    {d.holes.map(h => {
                      let percentage = 0;
                      const sum = 1;
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
                      <span>{holeStatus === 1 && <p className="icon-check">{'✔'}︎</p>}</span>
                      {'○'}
                    </li>
                    {d.holes.map(h => {
                      let percentage = 0;
                      const sum = 1;
                      const sum1 = h.s.filter(s => s.s == 1).length;
                      if (sum !== 0) percentage = Math.round(1000*sum1/sum)/10;

                      return (
                        <li>{`${percentage}%`}<span>({sum1 !== 0 ? sum1 : '-'})</span></li>
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
                    {d.holes.map(h => {
                      const sumM = h.s.filter(s => s.hm !== null).length;

                      return (
                        <li>{sumM !== 0 ? sumM : '-'}</li>
                      )
                    })}
                  </ul>
                </div>
              </div>
            )
          }
          </div>
        )
      case 'comment':
        return (
          <div className="comment">
          {
            data.map(d =>
              <div className="collection">
                <div>
                  <ul>
                    <li
                      onClick={() => {
                        let newFilter;
                        if ( cFilter.length !== 0) newFilter = [];
                        else newFilter = d.mt.map(ft => ft.id);
                        this.setState({ cFilter: newFilter });
                      }}
                    >
                      <span>{cFilter.length === 0 &&<p className="icon-check">{'✔'}︎</p>}</span>
                      <span>手直し区分</span>
                    </li>
                    {d.mt.map(ct =>{
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
                          <span>{index === -1 &&<p className="icon-check">{'✔'}︎</p>}</span>
                          <span>{`${ct.label}. ${ct.name}`}</span>
                        </li>
                      );
                    })}
                  </ul>
                </div>
                <div>
                  <ul className="parts">
                    <li>計</li>
                    {
                      d.mt.map(ct => 
                        <li>
                          {d.modifications ? d.modifications.filter(m => m.id == ct.id).length : 0}
                        </li>
                      )
                    }
                  </ul>
                </div>
                <p>{inspectionGroups.find(ig => ig.iG == d.family.inspectionGroupId).name}</p>
              </div>
            )
          }
          </div>
        );
      case 'hModification':
        return (
          <div className="hModification">
          {
            data.map(d =>
              <div className="collection">
                <div>
                  <ul>
                    <li>{'穴'}</li>
                    {d.holes.map(h => <li>{h.l}</li>)}
                  </ul>
                </div>
                {
                  d.hmt.map(hm => {
                    return (
                      <div>
                        <ul>
                          <li
                            onClick={() => this.setState({hModification: hm.id})}
                          >
                            <span>{hModification == hm.id && <p className="icon-check">{'✔'}︎</p>}</span>
                            {hm.name}
                          </li>
                          {d.holes.map(h => {
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
            )
          }
          </div>
        );
      case 'inline':
        return (
          <div className="inline">
          {
            data[0].inlines !== null && !Array.isArray(data.inlines) &&
            data.map(d =>
              <div className="collection">
                <div>
                  <ul>
                    <li>{'精度検査'}</li>
                    {d.i.map(i => 
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
                    {d.i.map(ii => {
                      let percentage = 0;
                      const sum = d.inlines[ii.id].length;
                      const sum0 = d.inlines[ii.id].filter(i => ii.maxTolerance < i || ii.minTolerance > i).length;
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
                    {d.i.map(ii => {
                      let percentage = 0;
                      const sum = d.inlines[ii.id].length;
                      const sum1 = d.inlines[ii.id].filter(i => ii.maxTolerance >= i && ii.minTolerance <= i).length;
                      if (sum !== 0 && sum1 != 0) percentage = Math.round(1000*sum1/sum)/10;

                      return (
                        <li>{`${percentage}%`}<span>({sum1})</span></li>
                      )
                    })}
                  </ul>
                </div>
              </div>
            )
          }
          </div>
        );
    }
  }

  render() {
    const { data, isFetching, didInvalidate, mappingPartTypeId, header } = this.props;
    const { p, i, ji, active, fFilter, holeStatus, cFilter, hModification } = this.state;

    return (
      <div className="modal">
        <div className="mapping-wrap">
          <div className="panel-btn" onClick={() => this.props.close()}>
            <span className="panel-btn-close"></span>
          </div>
          <div className="mapping-header">
            <p>{header}</p>
          </div>
          <div className="mapping-left-panel">
            <ul>
              <li className="process-name">成形ライン①</li>
              <li
                className={`inspection-name ${inspectionGroups.filter(ig => ig.vehicle == '680A' && ig.part == mappingPartTypeId && ig.p == 'm001' && ig.i == 'gaikan' && !ig.disabled).length !== 0 ? '' : 'disable'} ${(p === 'm001' && i === 'gaikan') ? 'active' : ''}`}
                onClick={() => this.setState({
                  p: 'm001',
                  i: 'gaikan',
                  active: 'failure'
                }, () => this.requestMapping('gaikan'))}
              >
                外観検査
              </li>
              <li
                className={`inspection-name ${inspectionGroups.filter(ig => ig.vehicle == '680A' && ig.part == mappingPartTypeId && ig.p == 'm001' && ig.i == 'inline' && !ig.disabled).length !== 0 ? '' : 'disable'} ${(p === 'm001' && i === 'inline') ? 'active' : ''}`}
                onClick={() => this.setState({
                  p: 'm001',
                  i: 'inline',
                  active: 'inline'
                }, () => this.requestMapping('inline'))}
              >精度検査</li>
            </ul>
            <div className="divider"></div>
            <ul>
              <li className="process-name">成形ライン②</li>
              <li
                className={`inspection-name ${inspectionGroups.filter(ig => ig.vehicle == '680A' && ig.part == mappingPartTypeId && ig.p == 'm002' && ig.i == 'gaikan' && !ig.disabled).length !== 0 ? '' : 'disable'} ${(p === 'm002' && i === 'gaikan') ? 'active' : ''}`}
                onClick={() => this.setState({
                  p: 'm002',
                  i: 'gaikan',
                  active: 'failure'
                }, () => this.requestMapping('gaikan'))}
              >
                外観検査
              </li>
              <li
                className={`inspection-name ${inspectionGroups.filter(ig => ig.vehicle == '680A' && ig.part == mappingPartTypeId && ig.p == 'm002' && ig.i == 'inline' && !ig.disabled).length !== 0 ? '' : 'disable'} ${(p === 'm002' && i === 'inline') ? 'active' : ''}`}
                onClick={() => this.setState({
                  p: 'm002',
                  i: 'inline',
                  active: 'inline'
                }, () => this.requestMapping('inline'))}
              >精度検査</li>
            </ul>
            <div className="divider"></div>
            <ul>
              <li className="process-name">穴あけ</li>
              <li
                className={`inspection-name ${inspectionGroups.filter(ig => ig.vehicle == '680A' && ig.part == mappingPartTypeId && ig.p == 'h' && ig.i == 'gaikan' && !ig.disabled).length !== 0 ? '' : 'disable'} ${(p === 'h' && i === 'gaikan') ? 'active' : ''}`}
                onClick={() => this.setState({
                  p: 'h',
                  i: 'gaikan',
                  active: 'failure'
                }, () => this.requestMapping('gaikan'))}
              >外観検査</li>
              <li
                className={`inspection-name ${inspectionGroups.filter(ig => ig.vehicle == '680A' && ig.part == mappingPartTypeId && ig.p == 'h' && ig.i == 'ana' && !ig.disabled).length !== 0 ? '' : 'disable'} ${(p === 'h' && i === 'ana') ? 'active' : ''}`}
                onClick={() => this.setState({
                  p: 'h',
                  i: 'ana',
                  active: 'hole'
                }, () => this.requestMapping('ana'))}
              >穴検査</li>
            </ul>
            <div className="divider"></div>
            <ul>
              <li className="process-name">接着</li>
              <li
                className={`inspection-name ${inspectionGroups.filter(ig => ig.vehicle == '680A' && ig.part == mappingPartTypeId && ig.p == 'j' && ig.i == 'inline' && !ig.disabled).length !== 0 ? '' : 'disable'} ${(p === 'j' && i === 'inline') ? 'active' : ''}`}
                onClick={() => this.setState({
                  p: 'j',
                  i: 'inline',
                  active: 'inline'
                }, () => this.requestMapping('inline'))}
              >
                精度検査
              </li>
            </ul>
            <ul
              className={`grouped ${(p === 'j' && i !== 'inline') ? 'active' : ''} ${mappingPartTypeId === 7 ? '' : 'disabled'}`}
              onClick={() => {
                  this.setState({
                    p: 'j',
                    i: null,
                    active: 'failure'
                  }, () => this.requestMapping(null));
            }}>
              <li
                className={`inspection-name-jointing`}
                onClick={() => {
                  let newJi = [];
                  if (ji.indexOf(16) >= 0) {
                    ji.splice(ji.indexOf(16), 1);
                    newJi = ji;
                  } else {
                    newJi = [16, ...ji];                  
                  }

                  this.setState({p: 'j', ji: newJi});
                }}
              >
                簡易CF {p === 'j' && i !== 'inline' && ji.indexOf(16) >= 0 && <div className="icon-check red">️</div>}
              </li>
              <li
                className={`inspection-name-jointing ${inspectionGroups.filter(ig => ig.vehicle == '680A' && ig.part == mappingPartTypeId && ig.p == 'j' && ig.i == 'shisui' && !ig.disabled).length !== 0 ? '' : 'disable'} ${(p === 'j' && i === 'shisui') ? 'active' : ''}`}
                onClick={() => {
                  let newJi = [];
                  if (ji.indexOf(10) >= 0) {
                    ji.splice(ji.indexOf(10), 1);
                    newJi = ji;
                  } else {
                    newJi = [10, ...ji]  ;                  
                  }

                  this.setState({p: 'j', ji: newJi});
                }}
              >
                止水 {p === 'j' && i !== 'inline' && ji.indexOf(10) >= 0 && <div className="icon-check yellow">️</div>}
              </li>
              <li
                className={`inspection-name-jointing ${inspectionGroups.filter(ig => ig.vehicle == '680A' && ig.part == mappingPartTypeId && ig.p == 'j' && ig.i == 'shiage' && !ig.disabled).length !== 0 ? '' : 'disable'} ${(p === 'j' && i === 'shiage') ? 'active' : ''}`}
                onClick={() => {
                  let newJi = [];
                  if (ji.indexOf(11) >= 0) {
                    ji.splice(ji.indexOf(11), 1);
                    newJi = ji;
                  } else {
                    newJi = [11, ...ji]  ;                  
                  }

                  this.setState({p: 'j', ji: newJi});
                }}
              >
                仕上 {p === 'j' && i !== 'inline' && ji.indexOf(11) >= 0 && <div className="icon-check blue">️</div>}
              </li>
              <li
                className={`inspection-name-jointing ${inspectionGroups.filter(ig => ig.vehicle == '680A' && ig.part == mappingPartTypeId && ig.p == 'j' && ig.i == 'kensa' && !ig.disabled).length !== 0 ? '' : 'disable'} ${(p === 'j' && i === 'kensa') ? 'active' : ''}`}
                onClick={() => {
                  let newJi = [];
                  if (ji.indexOf(12) >= 0) {
                    ji.splice(ji.indexOf(12), 1);
                    newJi = ji;
                  } else {
                    newJi = [12, ...ji]  ;                  
                  }

                  this.setState({p: 'j', ji: newJi});
                }}
              >
                検査 {p === 'j' && i !== 'inline' && ji.indexOf(12) >= 0 && <div className="icon-check green">️</div>}
              </li>
              <li
                className={`inspection-name-jointing ${inspectionGroups.filter(ig => ig.vehicle == '680A' && ig.part == mappingPartTypeId && ig.p == 'j' && ig.i == 'tenaoshi' && !ig.disabled).length !== 0 ? '' : 'disable'} ${(p === 'j' && i === 'tenaoshi') ? 'active' : ''}`}
                onClick={() => {
                  let newJi = [];
                  if (ji.indexOf(14) >= 0) {
                    ji.splice(ji.indexOf(14), 1);
                    newJi = ji;
                  } else {
                    newJi = [14, ...ji]  ;                  
                  }

                  this.setState({p: 'j', ji: newJi});
                }}
              >
                手直 {p === 'j' && i !== 'inline' && ji.indexOf(14) >= 0 && <div className="icon-check purple">️</div>}
              </li>
            </ul>
          </div>
          {
            data !== null && !isFetching && !didInvalidate &&
            <div className="mapping-right-main">
              <div className="mapping-figure">
              {
                data.filter(d => 
                  p === 'j' ? ji.indexOf(d.family.inspectionGroupId) >= 0 : true
                ).map(d => 
                  <p>{`${inspectionGroups.find(ig => ig.iG == d.family.inspectionGroupId).name}: 判定${d.status === 1 ? '◯' : '×'},${d.family.createdBy}, ${d.family.createdAt}, コメント：${d.family.comment ? d.family.comment : ''}`}</p>
                )
              }
                <div className="figure">
                {
                  data[0].pageTypes.length == 1 ? 
                  <img src={`/img/figures/${data[0].pageTypes[0].path}`}/> :
                  data[0].pageTypes.map(pt =>
                    <img src={`/img/figures/${pt.path}`} className="quarter"/>
                  )
                }
                </div>
                <svg>
                  {
                    active == 'failure' &&
                    data.filter(d => 
                      p === 'j' ? ji.indexOf(d.family.inspectionGroupId) >= 0 : true
                    ).map(d => d.failures.filter(f => fFilter.indexOf(f.id) == -1).map(f => {
                        const point = f.p.split(',');
                        let x = point[0]/2;
                        let y = point[1]/2;

                        if(d.pageTypes.length > 1) {
                          const number = d.pageTypes.find(pt => pt.id == f.pg).n;
                          switch (number) {
                            case 1: x = x/2;            y = y/2;            break;
                            case 2: x = (x + 1740/2)/2; y = y/2;            break;
                            case 3: x = x/2;            y = (y + 1030/2)/2; break;
                            case 4: x = (x + 1740/2)/2; y = (y + 1030/2)/2; break;
                          }
                        }

                        switch (d.family.inspectionGroupId) {
                          case 10:
                            return (
                              <g>
                                <circle cx={x} cy={y} r={5} fill="#C6B700" />
                              </g>
                            );
                            break;
                          case 11:
                            return (
                              <g>
                                <circle cx={x} cy={y} r={5} fill="blue" />
                              </g>
                            );
                            break;
                          case 12:
                            return (
                              <g>
                                <circle cx={x} cy={y} r={5} fill="green" />
                              </g>
                            );
                            break;
                          case 14:
                            return (
                              <g>
                                <circle cx={x} cy={y} r={5} fill="purple" />
                              </g>
                            );
                            break;
                          default:
                            return (
                              <g>
                                <circle cx={x} cy={y} r={5} fill="red" />
                              </g>
                            );
                            break;
                        }
                    }))
                  }{
                    active == 'hole' &&
                    data.map(d => d.holes.map(hole => {
                      const h = this.formatHole(hole);

                      if(d.pageTypes.length > 1) {
                        const number = d.pageTypes.find(pt => pt.id == hole.pg).n;
                        switch (number) {
                          case 1: h.x = (h.x)/2; h.lx = (h.lx)/2; h.y = (h.y)/2; h.ly = (h.ly)/2; break;
                          case 2: h.x = (h.x + 1740/2)/2; h.y = (h.y)/2; h.lx = (h.lx + 1740/2)/2; h.ly = (h.ly)/2; break;
                          case 3: h.x = (h.x)/2; h.y = (h.y + 1030/2)/2; h.lx = (h.lx)/2; h.ly = (h.ly + 1030/2)/2; break;
                          case 4: h.x = (h.x + 1740/2)/2; h.y = (h.y + 1030/2)/2; h.lx = (h.lx + 1740/2)/2; h.ly = (h.ly + 1030/2)/2; break;
                        }
                      }

                      const s = h.s.map(s => s.s);
                      let disable = s.indexOf(holeStatus) === -1;
                      // if (holeStatus === 1) {
                      //   disable = s.length === d.count/d.pageTypes.length;
                      // }

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
                    }))
                  }{
                    active == 'comment' &&
                    data.filter(d => 
                      p === 'j' ? ji.indexOf(d.family.inspectionGroupId) >= 0 : true
                    ).map(d => d.modifications.filter(c => cFilter.indexOf(c.id) == -1).map(m => {
                        const point = m.p.split(',');
                        const x = point[0]/2;
                        const y = point[1]/2;


                        switch (d.family.inspectionGroupId) {
                          case 10:
                            return (
                              <g>
                                <rect x={x-6} y={y-6} width="12" height="12" fill="#C6B700"/>
                                <circle cx={x} cy={y} r={5} fill="#C6B700" />
                              </g>
                            );
                            break;
                          case 11:
                            return (
                              <g>
                                <rect x={x-6} y={y-6} width="12" height="12" fill="blue"/>
                              </g>
                            );
                            break;
                          case 12:
                            return (
                              <g>
                                <rect x={x-6} y={y-6} width="12" height="12" fill="green"/>
                              </g>
                            );
                            break;
                          case 14:
                            return (
                              <g>
                                <rect x={x-6} y={y-6} width="12" height="12" fill="purple"/>
                              </g>
                            );
                            break;
                          default:
                            return (
                              <g>
                                <rect x={x-6} y={y-6} width="12" height="12" fill="red"/>
                              </g>
                            );
                            break;
                        }
                    }))
                  }{
                    active == 'hModification' &&
                    data.map(d => d.holes.map(hole => {
                      const h = this.formatHole(hole);

                      if(d.pageTypes.length > 1) {
                        const number = d.pageTypes.find(pt => pt.id == hole.pg).n;
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
                    }))
                  }{
                    active == 'inline' &&
                    Object.keys(data[0].i).map(id => {
                      return data[0].i.map(i => {
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
              <div className="mapping-right-panel">
                <div className="right-panel-tab">
                  {
                    data[0].holes.length !== 0 &&
                    <button
                      className={active == 'hole' ? '' : 'disable'}
                      onClick={() => this.setState({ active: 'hole'})}
                    >
                      穴検査
                    </button>
                  }{
                    data[0].mt.length !== 0 &&
                    <button
                      className={active == 'comment' ? '' : 'disable'}
                      onClick={() => this.setState({ active: 'comment'})}
                    >
                      手直し
                    </button>
                  }{
                    data[0].hmt.length !== 0 &&
                    <button
                      className={active == 'hModification' ? '' : 'disable'}
                      onClick={() => this.setState({ active: 'hModification'})}
                    >
                      手直し
                    </button>
                  }{
                    data[0].ft.length !== 0 &&
                    <button
                      className={active == 'failure' ? '' : 'disable'}
                      onClick={() => this.setState({ active: 'failure'})}
                    >
                      外観検査
                    </button>
                  }{
                    data[0].i.length !== 0 && data.inlines !== null &&
                    <button
                      className={active == 'inline' ? '' : 'disable'}
                      onClick={() => this.setState({ active: 'inline'})}
                    >
                      精度検査
                    </button>
                  }
                </div>
                <div className="right-panel-content">
                  {this.renderPanelContent()}
                </div>
              </div>
            </div>
          }{
            data == null && p !== null && !isFetching && !didInvalidate &&
            <p className="message">検査結果がありません</p>
          }{
            didInvalidate &&
            <p className="message">検査結果がありません</p>
          }{
            data == null && p == null && !didInvalidate &&
            <p className="message">表示する検査を選択してください</p>
          }
        </div>
      </div>
    );
  }
}

Mapping.propTypes = {
  close: PropTypes.func.isRequired,
  requestMapping: PropTypes.func.isRequired,
  data: PropTypes.object.isRequired,
  isFetching: PropTypes.bool.isRequired,
  didInvalidate: PropTypes.bool.isRequired,
  mappingPartTypeId: PropTypes.number.isRequired,
  header: PropTypes.string.isRequired,
};

export default Mapping;
