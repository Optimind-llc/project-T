import React, { PropTypes, Component } from 'react';
// Styles
import styles from './mappingBody.scss';
// Components
import Loading from '../../../../../components/loading/loading';

class MappingBody extends Component {
  constructor(props, context) {
    super(props, context);

    this.state = {
      active: 'failure',
      fFilter: [],
      mFilter: [],
      hmFilter: [],
      hFilter: 0
    };
  }

  renderContent() {
    const { data } = this.props;
    const { active, fFilter, mFilter, hmFilter, hFilter } = this.state;

    const fIds = Array.prototype.concat.apply([], data.result.map(r => r.fs)).map(ft =>
      ft.id
    );

    const mIds = Array.prototype.concat.apply([], data.result.map(r => r.ms)).map(mt =>
      mt.id
    );

    const hStatus = Array.prototype.concat.apply([], data.result.map(r => r.hs));

    switch (active) {
      case 'failure':
        return (
          <div className="failure">
            <div className="collection">
              <div className="has-check-box">
                <ul>
                  <li
                    onClick={() => {
                      let newFilter;
                      if ( fFilter.length !== 0) newFilter = [];
                      else newFilter = data.failureTypes.map(ft => ft.id);
                      this.setState({ fFilter: newFilter });
                    }}
                  >
                    <span><p>{fFilter.length === 0 && '✔'}︎</p></span>
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
                        <span><p>{index === -1 && '✔'}︎</p></span>
                        <span>{ft.name}</span>
                      </li>
                    );
                  })}
                </ul>
              </div>
              {
                <div>
                  <ul className="parts">
                    <li>{''}</li>
                    {
                      data.failureTypes.map(ft => 
                        <li>
                          {
                            fIds.filter(f => f == ft.id).length
                          }
                        </li>
                      )
                    }
                  </ul>
                </div>
              }
            </div>
          </div>
        );
      case 'modification':
        return (
          <div className="failure">
            <div className="collection">
              <div className="has-check-box">
                <ul>
                  <li
                    onClick={() => {
                      let newFilter;
                      if ( mFilter.length !== 0) newFilter = [];
                      else newFilter = data.failureTypes.map(ft => ft.id);
                      this.setState({ mFilter: newFilter });
                    }}
                  >
                    <span><p>{mFilter.length === 0 && '✔'}︎</p></span>
                    <span>不良区分</span>
                  </li>
                  {data.modificationTypes.map(mt =>{
                    const index = mFilter.indexOf(mt.id);
                    return (
                      <li
                        key={mt.id}
                        className={index === -1 ? 'active' : ''}
                        onClick={() => {
                          if ( index === -1) mFilter.push(mt.id);
                          else mFilter.splice(index, 1);
                          this.setState({ mFilter });
                        }}
                      >
                        <span><p>{index === -1 && '✔'}︎</p></span>
                        <span>{mt.name}</span>
                      </li>
                    );
                  })}
                </ul>
              </div>
              {
                <div>
                  <ul className="parts">
                    <li>{''}</li>
                    {
                      data.modificationTypes.map(mt => 
                        <li>
                          {
                            mIds.filter(m => m == mt.id).length
                          }
                        </li>
                      )
                    }
                  </ul>
                </div>
              }
            </div>
          </div>
        )
      case 'hole':
        return (
          <div className="hole">
            <div className="collection">
              <div className="">
                <ul>
                  <li>{'番号'}</li>
                  {
                    data.holeTypes.map(ht => <li>{ht.l}</li>)
                  }
                </ul>
              </div>
              <div>
                <ul>
                  <li
                    onClick={() => this.setState({ hFilter: 0 })}
                  >
                    <span>{hFilter === 0 && <p>{'✔'}︎</p>}</span>
                    {'×'}
                  </li>
                  {data.holeTypes.map(ht => {
                    let percentage = 0;
                    const sum = data.count;
                    const sum0 = hStatus.filter(h => h.id == ht.id && h.s == 0).length;
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
                    onClick={() => this.setState({ hFilter: 2 })}
                  >
                    <span>{hFilter === 2 && <p>{'✔'}︎</p>}</span>
                    {'△'}
                  </li>
                  {data.holeTypes.map(ht => {
                    let percentage = 0;
                    const sum = data.count;
                    const sum0 = hStatus.filter(h => h.id == ht.id && h.s == 2).length;
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
                    onClick={() => this.setState({ hFilter: 1 })}
                  >
                    <span>{hFilter === 1 && <p>{'✔'}︎</p>}</span>
                    {'○'}
                  </li>
                  {data.holeTypes.map(ht => {
                    let percentage = 0;
                    const sum = data.count;
                    const sum1 = sum - hStatus.filter(h => h.id == ht.id).length;
                    if (sum !== 0) percentage = Math.round(1000*sum1/sum)/10;

                    return (
                      <li>{`${percentage}%`}<span>({sum !== 0 ? sum1 : '-'})</span></li>
                    )
                  })}
                </ul>
              </div>
              <div>
                <ul>
                  <li
                    onClick={() => this.setState({holeStatus: 3})}
                  >
                    {'穴手直'}
                  </li>
                  {data.holeTypes.map(ht => {
                    const sum = hStatus.filter(h => h.id == ht.id && h.hm != -1).length;

                    return (
                      <li>{sum !== 0 ? sum : '-'}</li>
                    )
                  })}
                </ul>
              </div>
            </div>
          </div>
        );
      case 'holeModification':
        return (
          <div className="holeModification">
            <div className="collection">
              <div className="">
                <ul>
                  <li>{'番号'}</li>
                  {
                    data.holeTypes.map(ht => <li>{ht.l}</li>)
                  }
                </ul>
              </div>
              {
                data.holeModificationTypes.map(hmt => {
                  const index = hmFilter.indexOf(hmt.id);
                  return (
                    <div>
                      <ul>
                        <li
                          onClick={() => {
                            if ( index === -1) hmFilter.push(hmt.id);
                            else hmFilter.splice(index, 1);
                            this.setState({ hmFilter });
                          }}
                        >
                          <span>{index === -1 && <p>{'✔'}︎</p>}</span>
                          {hmt.name}
                        </li>
                        {
                          data.holeTypes.map(ht => {
                            const sum = hStatus.filter(h => h.hm === hmt.id).length;
                            return (
                              <li>{sum ? sum : '-'}</li>
                            )
                          }
                        )}
                      </ul>
                    </div>
                  );
                })
              }
            </div>
          </div>
        );
    }
  }

  render() {
    const { data, isFetching, didInvalidate, narrowedBy } = this.props;
    const { active, fFilter, mFilter, hmFilter, hFilter } = this.state;

    const hStatus = Array.prototype.concat.apply([], data.result.map(r => r.hs));

    return (
      <div className="mapping-body-wrap">
        <div className="bg-white mapping-body">
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
          <div className="figure-wrap">
            <div style={{width: 870}}>
              {
                data.figures.map((fig, i, self) =>
                  <div
                    style={{
                      position: 'relative',
                      float: 'left',
                      width: 870/Math.ceil(Math.sqrt(self.length)),
                      height: 515/Math.ceil(Math.sqrt(self.length)),
                      backgroundImage: `url(${fig.path})`,
                      backgroundSize: 'contain',
                      backgroundPosition: 'center top',
                      backgroundRepeat: 'no-repeat'
                    }}
                  >
                  </div>
                )
              }
            </div>
            <svg>
              {
                active === 'failure' &&
                data.result.map((r, i, self) =>
                  r.fs.filter(f =>
                    fFilter.indexOf(f.id) == -1
                  ).map(f => {
                    const split = Math.ceil(Math.sqrt(data.figures.length));
                    const page = data.figures.find(fig => fig.id == f.fig).page;

                    switch (r.c) {
                      case 'W':
                        return (
                          <g>
                            <circle
                              cx={f.x/2 + (870/split)*((page+2)%3)}
                              cy={f.y/2 + (515/split)*(Math.ceil(page/3)-1)}
                              r={5}
                              fill="red"
                            />
                          </g>
                        );
                        break;
                      case 'Y':
                        return (
                          <g>
                            <circle
                              cx={f.x/2 + (870/split)*((page+2)%3)}
                              cy={f.y/2 + (515/split)*(Math.ceil(page/3)-1)}
                              r={5}
                              fill="#C6B700"
                            />
                          </g>
                        );
                        break;
                      case 'B':
                        return (
                          <g>
                            <circle
                              cx={f.x/2 + (870/split)*((page+2)%3)}
                              cy={f.y/2 + (515/split)*(Math.ceil(page/3)-1)}
                              r={5}
                              fill="blue"
                            />
                          </g>
                        );
                        break;
                    }
                  })
                )
              }{
                active === 'modification' &&
                data.result.map((r, i, self) =>
                  r.ms.filter(m =>
                    mFilter.indexOf(m.id) == -1
                  ).map(m => {
                    const split = Math.ceil(Math.sqrt(data.figures.length));
                    const page = data.figures.find(fig => fig.id == m.fig).page;
                    switch (r.c) {
                      case 'W':
                        return (
                          <g>
                            <circle
                              cx={m.x/2 + (870/split)*((page+2)%3)}
                              cy={m.y/2 + (515/split)*(Math.ceil(page/3)-1)}
                              r={5}
                              fill="red"
                            />
                          </g>
                        );
                        break;
                      case 'Y':
                        return (
                          <g>
                            <circle
                              cx={m.x/2 + (870/split)*((page+2)%3)}
                              cy={m.y/2 + (515/split)*(Math.ceil(page/3)-1)}
                              r={5}
                              fill="#C6B700"
                            />
                          </g>
                        );
                        break;
                      case 'B':
                        return (
                          <g>
                            <circle
                              cx={m.x/2 + (870/split)*((page+2)%3)}
                              cy={m.y/2 + (515/split)*(Math.ceil(page/3)-1)}
                              r={5}
                              fill="blue"
                            />
                          </g>
                        );
                        break;
                    }
                  })
                )
              }{
                active === 'hole' &&
                data.holeTypes.map((ht, i, self) => {
                  const split = Math.ceil(Math.sqrt(data.figures.length));
                  const page = data.figures.find(fig => fig.id == ht.fig).page;

                  let disable = false;
                  if (hFilter !== 1) {
                    const count = hStatus.filter(h => h.s === hFilter && h.id === ht.id).length;
                    disable = count === 0;
                  } else {
                    const count = hStatus.filter(h => h.id === ht.id).length;
                    disable = count === data.count;
                  }

                  const x = ht.x/2/split + (870/split)*((page+2)%3);
                  const y = ht.y/2/split + (515/split)*(Math.ceil(page/3)-1);

                  let lx = 0;
                  let ly = 0;
                  switch (ht.d) {
                    case 'left':   lx = x-(34/split); ly = y - 2; break;
                    case 'right':  lx = x+(34/split); ly = y - 2; break;
                    case 'top':    ly = y-(30/split) - 2; lx = x; break;
                    case 'bottom': ly = y+(30/split) - 2; lx = x; break;
                    default: break;
                  }

                  return (
                    <g>
                      <circle
                        cx={x}
                        cy={y}
                        r={disable ? 3 : 4}
                        fill={disable ? 'rgba(255,0,0,0.4)' : 'rgba(255,0,0,1)'} 
                      />
                      <text
                        x={lx}
                        y={ly}
                        dy="6"
                        fontSize="10"
                        fill={disable ? 'rgba(0,0,0,0.3)' : 'rgba(0,0,0,1)'}
                        textAnchor="middle"
                        fontWeight="bold"
                      >
                        {ht.l}
                      </text>
                    </g>
                  );
                })
              }{
                active === 'holeModification' &&
                data.holeTypes.map((ht, i, self) => {
                  const split = Math.ceil(Math.sqrt(data.figures.length));
                  const page = data.figures.find(fig => fig.id == ht.fig).page;

                  let disable = false;
                  const count = hStatus.filter(h => hmFilter.indexOf(h.hm) == -1).length;
                  disable = count === 0;

                  const x = ht.x/2/split + (870/split)*((page+2)%3);
                  const y = ht.y/2/split + (515/split)*(Math.ceil(page/3)-1);
console.log(hmFilter);
                  let lx = 0;
                  let ly = 0;
                  switch (ht.d) {
                    case 'left':   lx = x-(34/split); ly = y - 2; break;
                    case 'right':  lx = x+(34/split); ly = y - 2; break;
                    case 'top':    ly = y-(30/split) - 2; lx = x; break;
                    case 'bottom': ly = y+(30/split) - 2; lx = x; break;
                    default: break;
                  }

                  return (
                    <g>
                      <circle
                        cx={x}
                        cy={y}
                        r={disable ? 3 : 4}
                        fill={disable ? 'rgba(255,0,0,0.4)' : 'rgba(255,0,0,1)'} 
                      />
                      <text
                        x={lx}
                        y={ly}
                        dy="6"
                        fontSize="10"
                        fill={disable ? 'rgba(0,0,0,0.3)' : 'rgba(0,0,0,1)'}
                        textAnchor="middle"
                        fontWeight="bold"
                      >
                        {ht.l}
                      </text>
                    </g>
                  );
                })
              }
            </svg>
          </div>
          <div className="control-panel">
            <div className="control-tab">
              {
                data.failureTypes.length > 0 &&
                <button
                  className={active == 'failure' ? '' : 'disable'}
                  onClick={() => this.setState({ active: 'failure', fFilter: []})}
                >
                  不良検査
                </button>
              }{
                data.modificationTypes.length > 0 &&
                <button
                  className={active == 'modification' ? '' : 'disable'}
                  onClick={() => this.setState({ active: 'modification', mFilter: []})}
                >
                  手直検査
                </button>
              }{
                data.holeModificationTypes.length > 0 &&
                <button
                  className={active == 'hole' ? '' : 'disable'}
                  onClick={() => this.setState({ active: 'hole'})}
                >
                  穴検査
                </button>
              }{
                data.holeModificationTypes.length > 0 &&
                <button
                  className={active == 'holeModification' ? '' : 'disable'}
                  onClick={() => this.setState({ active: 'holeModification'})}
                >
                  穴手直
                </button>
              }
            </div>
            <div className="control-content">
              {this.renderContent()}
            </div>
          </div>
          {
            isFetching && <Loading/>
          }{
            !isFetching && data.failureTypes.length == 0 && narrowedBy !== 'realtime' &&
            <div className="cover">
              <p>検査結果が見つかりませんでした</p>
            </div>
          }{
            didInvalidate && narrowedBy !== 'realtime' &&
            <div className="cover">
              <p>検査結果が見つかりませんでした</p>
            </div>
          }
        </div>
      </div>
    );
  }
}

MappingBody.propTypes = {
  data: PropTypes.object.isRequired,
  isFetching: PropTypes.bool.isRequired,
  didInvalidate: PropTypes.bool.isRequired,
  narrowedBy: PropTypes.string.isRequired
};

export default MappingBody;