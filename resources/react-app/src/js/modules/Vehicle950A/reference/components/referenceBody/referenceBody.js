import React, { Component, PropTypes } from 'react';
import moment from 'moment';
import './referenceBody.scss';

class ReferenceBody extends Component {
  constructor(props, context) {
    super(props, context);
    this.state = {
      sort: {
        key: 'createdAt',
        asc: false,
        id: 0
      }
    };
  }

  sortResults(results) {
    const { sort } = this.state;

    return results.slice().sort((a, b) => {
      let aaa = 0;
      let bbb = 0;

      if (sort.key == 'panelId') {
        aaa = a[sort.key].toLowerCase();
        bbb = b[sort.key].toLowerCase();
      }
      else if (sort.key == 'status') {
        aaa = a[sort.key];
        bbb = b[sort.key];
      }
      else if (sort.key == 'choku' || sort.key == 'cBy' || sort.key == 'uBy') {
        aaa = a[sort.key];
        bbb = b[sort.key];
      }
      else if (sort.key == 'fs' || sort.key == 'ms' || sort.key == 'hms') {
        if (a[sort.key][sort.id]) {
          aaa = a[sort.key][sort.id];
        }
        if (b[sort.key][sort.id]) {
          bbb = b[sort.key][sort.id];
        }
      }
      else if (sort.key == 'hs') {
        aaa = a[sort.key][sort.id];
        bbb = b[sort.key][sort.id];
      }
      else if (sort.key == 'iAt' || sort.key == 'uAt') {
        aaa = moment(a[sort.key], 'YYYY-MM-DD HH:mm:ss 11:08:40').unix();
        bbb = moment(b[sort.key], 'YYYY-MM-DD HH:mm:ss 11:08:40').unix();
      }

      if (sort.asc) {
        if(aaa < bbb) return 1;
        else if(aaa > bbb) return -1;
      } else {
        if(aaa < bbb) return -1;
        else if(aaa > bbb) return 1;
      }
      return 0;
    });
  }

  render() {
    const { sort } = this.state;
    const { count, results ,fts ,mts ,hts ,hmts ,its, download } = this.props;

    const CW = {
      num: 36,
      v: 43,
      pn: 80,
      name: 140,
      panelId: 68,
      choku: 38,
      status: 47,
      label: 87,
      hole: 46,
      inline: 60,
      comment: 77,
      by: 80,
      at: 127
    };

    let tableWidth = CW.num + CW.v + CW.pn + CW.name + CW.panelId + CW.choku + CW.by + CW.by + CW.status + CW.comment + CW.at + CW.at + 18;
    tableWidth = tableWidth + CW.label*fts.length;
    tableWidth = tableWidth + CW.label*mts.length;
    tableWidth = tableWidth + CW.label*hmts.length;

    if (hts.length > 0) {
      tableWidth = tableWidth + CW.hole*hts.length;
    }
    if (its.length > 0) {
      tableWidth = tableWidth + CW.inline*its.length;
    }

    return (
      <div className="table-wrap">
        {
          count < 100 &&
          <p className="result-count">{`${count}件中 ${count}件表示`}</p>
        }{
          count >= 100 &&
          <p className="result-count">{`${count}件中 100件表示`}</p>
        }
        {/*<button className="download dark" onClick={() => download()}>
          <p>CSVをダウンロード</p>
        </button>*/}
        <table className="reference-result" style={{width: tableWidth}}>
          <thead>
            <tr>
              <th rowSpan="2" style={{width: CW.num}}>No.</th>
              <th rowSpan="2" style={{width: CW.v}}>車種</th>
              <th rowSpan="2" style={{width: CW.pn}}>品番</th>
              <th rowSpan="2" style={{width: CW.name}}>品名</th>
              <th
                rowSpan="2"
                style={{width: CW.panelId}}
                className={`clickable ${sort.key == 'panelId' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'panelId') this.setState({sort: { key: 'panelId', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'panelId', asc: true, id: 0 }});
                }}
              >
                パネルID</th>
              <th
                rowSpan="2"
                style={{width: CW.choku}}
                className={`clickable ${sort.key == 'choku' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'choku') this.setState({sort: { key: 'choku', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'choku', asc: true, id: 0 }});
                }}
              >
                直
              </th>
              <th
                rowSpan="2"
                style={{width: CW.by}}
                className={`clickable ${sort.key == 'cBy' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'cBy') this.setState({sort: { key: 'cBy', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'cBy', asc: true, id: 0 }});
                }}
              >
                検査者
              </th>
              <th
                rowSpan="2"
                style={{width: CW.by}}
                className={`clickable ${sort.key == 'uBy' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'uBy') this.setState({sort: { key: 'uBy', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'uBy', asc: true, id: 0 }});
                }}
              >
                更新者
              </th>
              <th
                rowSpan="2"
                style={{width: CW.status}}
                className={`clickable ${sort.key == 'status' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'status') this.setState({sort: { key: 'status', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'status', asc: true, id: 0 }});
                }}
              >
                判定
              </th>
              {
                hts.length > 0 &&
                <th colSpan={hts.length}>穴</th>
              }{
                hmts.length > 0 &&
                <th colSpan={hmts.length}>穴手直</th>
              }{
                fts.length > 0 &&
                <th colSpan={fts.length}>外観不良</th>
              }{
                mts.length > 0 &&
                <th colSpan={mts.length}>不良手直</th>
              }{
                its.length > 0 &&
                <th colSpan={its.length}>精度検査</th>
              }
              <th rowSpan="2" style={{width: CW.comment}}>コメント</th>
              <th
                rowSpan="2"
                style={{width: CW.at}}
                className={`clickable ${sort.key == 'iAt' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'iAt') this.setState({sort: { key: 'iAt', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'iAt', asc: true, id: 0 }});
                }}
              >
                検査日
              </th>
              <th
                rowSpan="2"
                style={{width: CW.at}}
                className={`clickable ${sort.key == 'uAt' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'uAt') this.setState({sort: { key: 'uAt', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'uAt', asc: true, id: 0 }});
                }}
              >
                最終更新日
              </th>
            </tr>
            <tr>
            {
              hts.length > 0 &&
              hts.map(ht =>
                <th
                  style={{width: CW.hole}}
                  className={`clickable ${(sort.key == 'hs' && sort.id == ht.id) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'hs') this.setState({sort: { key: 'hs', asc: !sort.asc, id: ht.id }});
                    else this.setState({sort: { key: 'hs', asc: true, id: ht.id }});
                  }}
                >
                  {`${ht.label}`}
                </th>
              )
            }{
              hmts.length > 0 &&
              hmts.map(hmt =>
                <th
                  style={{width: CW.label}}
                  className={`clickable ${(sort.key == 'hms' && sort.id == hmt.id) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'hms') this.setState({sort: { key: 'hms', asc: !sort.asc, id: hmt.id }});
                    else this.setState({sort: { key: 'hms', asc: true, id: hmt.id }});
                  }}
                >
                  {`${hmt.name}`}
                </th>
              )
            }{
              fts.length > 0 &&
              fts.map(ft =>
                <th
                  style={{width: CW.label}}
                  className={`clickable ${(sort.key == 'fs' && sort.id == ft.id) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'fs') this.setState({sort: { key: 'fs', asc: !sort.asc, id: ft.id }});
                    else this.setState({sort: { key: 'fs', asc: true, id: ft.id }});
                  }}
                >
                  {`${ft.name}`}
                </th>
              )
            }{
              mts.length > 0 &&
              mts.map(mt =>
                <th
                  style={{width: CW.label}}
                  className={`clickable ${(sort.key == 'ms' && sort.id == mt.id) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'ms') this.setState({sort: { key: 'ms', asc: !sort.asc, id: mt.id }});
                    else this.setState({sort: { key: 'ms', asc: true, id: mt.id }});
                  }}
                >
                  {`${mt.name}`}
                </th>
              )
            }{
              its.length > 0 &&
              its.map(it =>
                <th
                  style={{width: CW.inline}}
                  className={`clickable ${(sort.key == 'is' && sort.id == it.id) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'is') this.setState({sort: { key: 'is', asc: !sort.asc, id: it.id }});
                    else this.setState({sort: { key: 'is', asc: true, id: it.id }});
                  }}
                >
                  {`P-${it.label}`}
                </th>
              )
            }
            </tr>
          </thead>
          <tbody>
          {
            this.sortResults(results).map((r, i) =>
              <tr>
                <td style={{width: CW.num}}>{i+1}</td>
                <td style={{width: CW.v}}>{r.v}</td>
                <td style={{width: CW.pn}}>{r.pn}</td>
                <td style={{width: CW.name}}>{r.name}</td>
                <td style={{width: CW.panelId}}>{r.panelId}</td>
                <td style={{width: CW.choku}}>{r.choku}</td>
                <td style={{width: CW.by}}>{r.cBy}</td>
                <td style={{width: CW.by}}>{r.uBy}</td>
                <td style={{width: CW.status}}>{r.status == 1 ? '○' : '×'}</td>
                {
                  hts.length > 0 &&
                  hts.map(ht => {
                    let status;
                    if (r.hs[ht.id] === 0) {status = '×';}
                    else if (r.hs[ht.id] === 2) {status = '△';}
                    else if (r.hs[ht.id] === 1) {status = '○';}
                    else {status = '-';}

                    return (<td style={{width: CW.hole}}>{status}</td>);
                  })
                }{
                  hmts.length > 0 &&
                  hmts.map(hm => {
                    let sum = 0;
                    if (r.hms[hm.id]) {
                      sum =  r.hms[hm.id];
                    }
                    return (<td style={{width: CW.label}}>{sum}</td>);
                  })
                }{
                  fts.length > 0 &&
                  fts.map(ft => {
                    let sum = 0;
                    if (r.fs[ft.id]) {
                      sum =  r.fs[ft.id];
                    }
                    return (<td style={{width: CW.label}}>{sum}</td>);
                  })
                }{
                  mts.length > 0 &&
                  mts.map(mt => {
                    let sum = 0;
                    if (r.ms[mt.id]) {
                      sum =  r.ms[mt.id];
                    }
                    return (<td style={{width: CW.label}}>{sum}</td>);
                  })
                }{
                  its.length > 0 &&
                  its.map(it => {
                    let status = true;
                    let target = 0;
                    if (r.is[it.id]) {
                      target = r.is[it.id];
                      console.log(target)
                      if ( target > it.max || target < it.min ) {
                        status = false;
                      }
                    }

                    return (<td style={{width: CW.inline, color: status ? '#000' : 'red'}}>{target}</td>);
                  })
                }
                <td style={{width: CW.comment}}>{r.comment ? r.comment.slice(0,5)+'..' : ''}</td>
                <td style={{width: CW.at}}>{r.iAt}</td>
                <td style={{width: CW.at}}>{r.uAt}</td>
              </tr>
            )
          }
          </tbody>
        </table>
      </div>
    );
  }
};

ReferenceBody.propTypes = {
  count: PropTypes.number.isRequired,
  results: PropTypes.array.isRequired,
  fts: PropTypes.array.isRequired,
  mts: PropTypes.array.isRequired,
  hts: PropTypes.array.isRequired,
  hmts: PropTypes.array.isRequired,
  its: PropTypes.array.isRequired,
  download: PropTypes.func.isRequired
};

export default ReferenceBody;
