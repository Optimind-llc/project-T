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
      hmFilter: []
    };
  }

  renderContent() {
    const { data } = this.props;
    const { active, fFilter, mFilter, hmFilter } = this.state;

    switch (active) {
      case 'f':
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
                data.parts.map((p, i, self) =>
                  <div>
                    <ul className="parts">
                      <li>{self.length === 1 ? '' : i == 0 ? 'L' : 'R'}</li>
                      {
                        data.failureTypes.map(ft => 
                          <li>
                            {
                              p.failures.filter(f =>
                                f.typeId == ft.id
                              ).map(f =>
                                f.mQty ? f.mQty: f.fQty
                              ).reduce((prev, current, i, arr) => {
                                return prev+current;
                              }, 0) + ' (' + p.failures.filter(f =>
                                f.typeId == ft.id
                              ).length + ')'
                            }
                          </li>
                        )
                      }
                    </ul>
                  </div>
                )
              }
            </div>
          </div>
        );
      case 'res':
        return (
          <div className="responsibility">
            <div className="collection">
              <div className="has-check-box">
                <ul>
                  <li
                    onClick={() => {
                      let newFilterK;
                      if ( fFilterK.length !== 0) newFilterK = [];
                      else newFilterK = data.failureTypes.map(ft => ft.id);
                      this.setState({ fFilterK: newFilterK });
                    }}
                  >
                    <span><p>{fFilterK.length === 0 && '✔'}︎</p></span>
                    <span>型保責</span>
                  </li>
                  {data.failureTypes.map(ft =>{
                    const index = fFilterK.indexOf(ft.id);
                    return (
                      <li
                        key={ft.id}
                        className={index === -1 ? 'active' : ''}
                        onClick={() => {
                          if ( index === -1) fFilterK.push(ft.id);
                          else fFilterK.splice(index, 1);
                          this.setState({ fFilterK });
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
                data.parts.map((p, i, self) =>
                  <div className="not-has-check-box">
                    <ul className="parts">
                      <li>{self.length === 1 ? '' : i == 0 ? 'L' : 'R'}</li>
                      {
                        data.failureTypes.map(ft => 
                          <li>
                            {
                              p.failures.filter(f =>
                                f.responsibleFor == 0
                              ).filter(f =>
                                f.typeId == ft.id
                              ).map(f =>
                                f.mQty ? f.mQty: f.fQty
                              ).reduce((prev, current, i, arr) => {
                                return prev+current;
                              }, 0)
                            }
                          </li>
                        )
                      }
                    </ul>
                  </div>
                )
              }
              <div className="has-check-box">
                <ul>
                  <li
                    onClick={() => {
                      let newFilterS;
                      if ( fFilterS.length !== 0) newFilterS = [];
                      else newFilterS = data.failureTypes.map(ft => ft.id);
                      this.setState({ fFilterS: newFilterS });
                    }}
                  >
                    <span><p>{fFilterS.length === 0 && '✔'}︎</p></span>
                    <span>設保責</span>
                  </li>
                  {data.failureTypes.map(ft =>{
                    const index = fFilterS.indexOf(ft.id);
                    return (
                      <li
                        key={ft.id}
                        className={index === -1 ? 'active' : ''}
                        onClick={() => {
                          if ( index === -1) fFilterS.push(ft.id);
                          else fFilterS.splice(index, 1);
                          this.setState({ fFilterS });
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
                data.parts.map((p, i, self) =>
                  <div className="not-has-check-box">
                    <ul className="parts">
                      <li>{self.length === 1 ? '' : i == 0 ? 'L' : 'R'}</li>
                      {
                        data.failureTypes.map(ft => 
                          <li>
                            {
                              p.failures.filter(f =>
                                f.responsibleFor == 1
                              ).filter(f =>
                                f.typeId == ft.id
                              ).map(f =>
                                f.mQty ? f.mQty: f.fQty
                              ).reduce((prev, current, i, arr) => {
                                return prev+current;
                              }, 0)
                            }
                          </li>
                        )
                      }
                    </ul>
                  </div>
                )
              }
              <div className="has-check-box">
                <ul>
                  <li
                    onClick={() => {
                      let newFilterZ;
                      if ( fFilterZ.length !== 0) newFilterZ = [];
                      else newFilterZ = data.failureTypes.map(ft => ft.id);
                      this.setState({ fFilterZ: newFilterZ });
                    }}
                  >
                    <span><p>{fFilterZ.length === 0 && '✔'}︎</p></span>
                    <span>材料責</span>
                  </li>
                  {data.failureTypes.map(ft =>{
                    const index = fFilterZ.indexOf(ft.id);
                    return (
                      <li
                        key={ft.id}
                        className={index === -1 ? 'active' : ''}
                        onClick={() => {
                          if ( index === -1) fFilterZ.push(ft.id);
                          else fFilterZ.splice(index, 1);
                          this.setState({ fFilterZ });
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
                data.parts.map((p, i, self) =>
                  <div className="not-has-check-box">
                    <ul className="parts">
                      <li>{self.length === 1 ? '' : i == 0 ? 'L' : 'R'}</li>
                      {
                        data.failureTypes.map(ft => 
                          <li>
                            {
                              p.failures.filter(f =>
                                f.responsibleFor == 2
                              ).filter(f =>
                                f.typeId == ft.id
                              ).map(f =>
                                f.mQty ? f.mQty: f.fQty
                              ).reduce((prev, current, i, arr) => {
                                return prev+current;
                              }, 0)
                            }
                          </li>
                        )
                      }
                    </ul>
                  </div>
                )
              }
            </div>
          </div>
        )
      case 'ato':
        return (
          <div></div>
        );
    }
  }

  render() {
    const { data, isFetching, didInvalidate, narrowedBy } = this.props;
    const { active, fFilter, mFilter, hmFilter } = this.state;

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
                data.holeTypes.map((h, i, self) => {
                  const split = Math.ceil(Math.sqrt(data.figures.length));
                  const page = data.figures.find(fig => fig.id == h.fig).page;

                  return (
                    <g>
                      <circle
                        cx={h.x/2/split + (870/split)*((page+2)%3)}
                        cy={h.y/2/split + (515/split)*(Math.ceil(page/3)-1)}
                        r={3}
                        fill="red"
                      />
                    </g>
                  );
                })
              }
            </svg>
          </div>
          <div className="control-panel">
            <div className="control-tab">
              <button
                className={active == 'failure' ? '' : 'disable'}
                onClick={() => this.setState({ active: 'failure', fFilter: []})}
              >
                不良検査
              </button>
              <button
                className={active == 'modification' ? '' : 'disable'}
                onClick={() => this.setState({ active: 'modification', mFilter: []})}
              >
                手直検査
              </button>
              <button
                className={active == 'hole' ? '' : 'disable'}
                onClick={() => this.setState({ active: 'hole'})}
              >
                穴検査
              </button>
            </div>
            <div className="control-content">
              {/*this.renderContent()*/}
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
