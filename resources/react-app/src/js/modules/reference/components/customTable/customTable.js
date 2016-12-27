import React, { Component, PropTypes } from 'react';
import { Table } from 'reactable';
import moment from 'moment';
import './customTable.scss';

class CustomTable extends Component {
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

  sortData(data) {
    const { sort } = this.state;

    data.sort((a,b) => {
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
      else if (sort.key == 'tyoku' || sort.key == 'createdBy' || sort.key == 'updatedBy') {
        aaa = a[sort.key];
        bbb = b[sort.key];
      }
      else if (sort.key == 'failures' || sort.key == 'modifications' || sort.key == 'hModifications') {
        if (a[sort.key][sort.id]) {
          aaa = a[sort.key][sort.id];
        }
        if (b[sort.key][sort.id]) {
          bbb = b[sort.key][sort.id];
        }
      }
      else if (sort.key == 'holes') {
        aaa = a[sort.key].find(h => h.id == [sort.id]).status;
        bbb = b[sort.key].find(h => h.id == [sort.id]).status;
      }
      else if (sort.key == 'createdAt' || sort.key == 'updatedAt') {
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

    return data;
  }

  render() {
    const { count, data, failures, holes, modifications, hModifications, inlines, download } = this.props;
    const { sort } = this.state;
    const colWidth = {
      number: 36,
      vehicle: 43,
      pn: 49,
      name: 140,
      panelId: 68,
      tyoku: 38,
      createdBy: 80,
      updatedBy: 80,
      status: 47,
      failure: 87,
      modification: 87,
      hole: 46,
      hModification: 87,
      inline: 50,
      comment: 77,
      createdAt: 127,
      updatedAt: 127
    };

    let tableWidth = colWidth.number + colWidth.vehicle + colWidth.pn + colWidth.name + colWidth.panelId + colWidth.tyoku + colWidth.createdBy + colWidth.updatedBy + colWidth.status + colWidth.comment + colWidth.createdAt + colWidth.updatedAt + 18;
    if (failures.length > 0) {
      tableWidth = tableWidth + colWidth.failure*failures.length;
    }
    if (holes.length > 0) {
      tableWidth = tableWidth + colWidth.hole*holes.length;
    }
    if (hModifications.length > 0) {
      tableWidth = tableWidth + colWidth.failure*hModifications.length;
    }
    if (modifications.length > 0) {
      tableWidth = tableWidth + colWidth.modification*modifications.length;
    }
    if (inlines.length > 0) {
      tableWidth = tableWidth + colWidth.inline*inlines.length;
    }

    return (
      <div className="table-wrap">
        {
          count < 1000 &&
          <p className="result-count">{`${count}件中 ${count}件表示`}</p>
        }{
          count >= 1000 &&
          <p className="result-count">{`${count}件中 1000件表示`}</p>
        }
        <button className="download dark" onClick={() => download()}>
          <p>CSVをダウンロード</p>
        </button>
        <table className="reference-result" style={{width: tableWidth}}>
          <thead>
            <tr>
              <th rowSpan="2" style={{width: colWidth.number}}>No.</th>
              <th rowSpan="2" style={{width: colWidth.vehicle}}>車種</th>
              <th rowSpan="2" style={{width: colWidth.pn}}>品番</th>
              <th rowSpan="2" style={{width: colWidth.name}}>品名</th>
              <th
                rowSpan="2"
                style={{width: colWidth.panelId}}
                className={`clickable ${sort.key == 'panelId' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'panelId') this.setState({sort: { key: 'panelId', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'panelId', asc: true, id: 0 }});
                }}
              >
                パネルID</th>
              <th
                rowSpan="2"
                style={{width: colWidth.tyoku}}
                className={`clickable ${sort.key == 'tyoku' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'tyoku') this.setState({sort: { key: 'tyoku', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'tyoku', asc: true, id: 0 }});
                }}
              >
                直
              </th>
              <th
                rowSpan="2"
                style={{width: colWidth.createdBy}}
                className={`clickable ${sort.key == 'createdBy' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'createdBy') this.setState({sort: { key: 'createdBy', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'createdBy', asc: true, id: 0 }});
                }}
              >
                検査者
              </th>
              <th
                rowSpan="2"
                style={{width: colWidth.updatedBy}}
                className={`clickable ${sort.key == 'updatedBy' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'updatedBy') this.setState({sort: { key: 'updatedBy', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'updatedBy', asc: true, id: 0 }});
                }}
              >
                更新者
              </th>
              <th
                rowSpan="2"
                style={{width: colWidth.status}}
                className={`clickable ${sort.key == 'status' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'status') this.setState({sort: { key: 'status', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'status', asc: true, id: 0 }});
                }}
              >
                判定
              </th>
              {
                holes.length > 0 &&
                <th colSpan={holes.length}>穴</th>
              }{
                hModifications.length > 0 &&
                <th colSpan={hModifications.length}>穴手直</th>
              }{
                failures.length > 0 &&
                <th colSpan={failures.length}>外観不良</th>
              }{
                modifications.length > 0 &&
                <th colSpan={modifications.length}>不良手直</th>
              }{
                inlines.length > 0 &&
                <th colSpan={inlines.length}>精度検査</th>
              }
              <th rowSpan="2" style={{width: colWidth.comment}}>コメント</th>
              <th
                rowSpan="2"
                style={{width: colWidth.createdAt}}
                className={`clickable ${sort.key == 'createdAt' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'createdAt') this.setState({sort: { key: 'createdAt', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'createdAt', asc: true, id: 0 }});
                }}
              >
                検査日
              </th>
              <th
                rowSpan="2"
                style={{width: colWidth.updatedAt}}
                className={`clickable ${sort.key == 'updatedAt' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'updatedAt') this.setState({sort: { key: 'updatedAt', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'updatedAt', asc: true, id: 0 }});
                }}
              >
                最終更新日
              </th>
            </tr>
            <tr>
            {
              holes.length > 0 &&
              holes.map(h =>
                <th
                  style={{width: colWidth.hole}}
                  className={`clickable ${(sort.key == 'holes' && sort.id == h.id) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'holes') this.setState({sort: { key: 'holes', asc: !sort.asc, id: h.id }});
                    else this.setState({sort: { key: 'holes', asc: true, id: h.id }});
                  }}
                >
                  {`${h.label}`}
                </th>
              )
            }{
              hModifications.length > 0 &&
              hModifications.map(hm =>
                <th
                  style={{width: colWidth.hModification}}
                  className={`clickable ${(sort.key == 'hModifications' && sort.id == hm.id) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'hModifications') this.setState({sort: { key: 'hModifications', asc: !sort.asc, id: hm.id }});
                    else this.setState({sort: { key: 'hModifications', asc: true, id: hm.id }});
                  }}
                >
                  {`${hm.name}`}
                </th>
              )
            }{
              failures.length > 0 &&
              failures.map(f =>
                <th
                  style={{width: colWidth.failure}}
                  className={`clickable ${(sort.key == 'failures' && sort.id == f.id) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'failures') this.setState({sort: { key: 'failures', asc: !sort.asc, id: f.id }});
                    else this.setState({sort: { key: 'failures', asc: true, id: f.id }});
                  }}
                >
                  {`${f.name}`}
                </th>
              )
            }{
              modifications.length > 0 &&
              modifications.map(m =>
                <th
                  style={{width: colWidth.modification}}
                  className={`clickable ${(sort.key == 'modifications' && sort.id == m.id) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'modifications') this.setState({sort: { key: 'modifications', asc: !sort.asc, id: m.id }});
                    else this.setState({sort: { key: 'modifications', asc: true, id: m.id }});
                  }}
                >
                  {`${m.name}`}
                </th>
              )
            }{
              inlines.length > 0 &&
              inlines.map(i =>
                <th
                  style={{width: colWidth.inline}}
                  className={`clickable ${(sort.key == 'inlines' && sort.id == i.id) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'inlines') this.setState({sort: { key: 'inlines', asc: !sort.asc, id: i.id }});
                    else this.setState({sort: { key: 'inlines', asc: true, id: i.id }});
                  }}
                >
                  {`P-${i.sort}`}
                </th>
              )
            }
            </tr>
          </thead>
          <tbody>
          {
            this.sortData(data).map((d,i) =>
              <tr>
                <td style={{width: colWidth.number}}>{i+1}</td>
                <td style={{width: colWidth.vehicle}}>{d.vehicle}</td>
                <td style={{width: colWidth.pn}}>{d.pn}</td>
                <td style={{width: colWidth.name}}>{d.name}</td>
                <td style={{width: colWidth.panelId}}>{d.panelId}</td>
                <td style={{width: colWidth.tyoku}}>{d.tyoku}</td>
                <td style={{width: colWidth.createdBy}}>{d.createdBy}</td>
                <td style={{width: colWidth.updatedBy}}>{d.updatedBy}</td>
                <td style={{width: colWidth.status}}>{d.status == 1 ? '○' : '×'}</td>
                {
                  holes.length > 0 &&
                  holes.map(h => {
                    let status;
                    if (d.holes.find(ch => ch.id == h.id).status == 0) {status = '×';}
                    else if (d.holes.find(ch => ch.id == h.id).status == 2) {status = '△';}
                    else if (d.holes.find(ch => ch.id == h.id).status == 1) {status = '○';}

                    return (<td style={{width: colWidth.hole}}>{status}</td>);
                  })
                }{
                  hModifications.length > 0 &&
                  hModifications.map(hm => {
                    let sum = 0;
                    if (d.hModifications[hm.id]) {
                      sum =  d.hModifications[hm.id];
                    }
                    return (<td style={{width: colWidth.hModification}}>{sum}</td>);
                  })
                }{
                  failures.length > 0 &&
                  failures.map(f => {
                    let sum = 0;
                    if (d.failures[f.id]) {
                      sum =  d.failures[f.id];
                    }
                    return (<td style={{width: colWidth.failure}}>{sum}</td>);
                  })
                }{
                  modifications.length > 0 &&
                  modifications.map(m => {
                    let sum = 0;
                    if (d.modifications[m.id]) {
                      sum =  d.modifications[m.id];
                    }
                    return (<td style={{width: colWidth.modification}}>{sum}</td>);
                  })
                }{
                  inlines.length > 0 &&
                  inlines.map(i => {
                    let status = true;
                    let target = 0;
                    if (d.inlines[i.id]) {
                      target = d.inlines[i.id];
                      if ( target.status > target.max || target.status < target.min ) {
                        status = false;
                      }
                    }

                    return (<td style={{width: colWidth.inline, color: status ? '#000' : 'red'}}>{target.status}</td>);
                  })
                }
                <td style={{width: colWidth.comment}}>{d.comment ? d.comment.slice(0,5)+'...' : ''}</td>
                <td style={{width: colWidth.createdAt}}>{d.inspectedAt ? d.inspectedAt : d.createdAt}</td>
                <td style={{width: colWidth.updatedAt}}>{d.inspectedAt ? d.inspectedAt : d.updatedAt}</td>
              </tr>
            )
          }{
            count == 0 &&
            <tr>
                <td style={{width: tableWidth, textAlign: 'left'}}>
                  検索結果なし
                </td>
            </tr>
          }
          </tbody>
        </table>
      </div>
    );
  }
};

CustomTable.propTypes = {
  count: PropTypes.object,
  data: PropTypes.object,
  failures: PropTypes.array,
  holes: PropTypes.array,
  modifications: PropTypes.array,
  hModifications: PropTypes.array,
  inlines: PropTypes.array,
  download: PropTypes.func
};

export default CustomTable;
