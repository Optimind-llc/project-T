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
      else if (sort.key == 'failures') {
        if (a[sort.key][sort.id]) {
          aaa = a[sort.key][sort.id];
        }
        if (b[sort.key][sort.id]) {
          bbb = b[sort.key][sort.id];
        }
      }
      else if (sort.key == 'modifications') {
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
    const { count, data, failures, holes, modifications, hModifications, inlines } = this.props;
    const { sort } = this.state;

    return (
      <div>
        {
          count < 1000 &&
          <p className="result-count">{`${count}件中 ${count}件表示`}</p>
        }{
          count >= 1000 &&
          <p className="result-count">{`${count}件中 1000件表示`}</p>
        }
        <button>CSVを出力</button>
        <table className="reference-result">
          <thead>
            <tr>
              <th rowSpan="2" className="number">No.</th>
              <th rowSpan="2" className="vehicle">車種</th>
              <th rowSpan="2" className="pn">品番</th>
              <th rowSpan="2" className="name">品名</th>
              <th
                rowSpan="2"
                className={`panelId clickable ${sort.key == 'panelId' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'panelId') this.setState({sort: { key: 'panelId', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'panelId', asc: true, id: 0 }});
                }}
              >
                パネルID</th>
              <th
                rowSpan="2"
                className={`tyoku clickable ${sort.key == 'tyoku' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'tyoku') this.setState({sort: { key: 'tyoku', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'tyoku', asc: true, id: 0 }});
                }}
              >
                直
              </th>
              <th
                rowSpan="2"
                className={`createdBy clickable ${sort.key == 'createdBy' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'createdBy') this.setState({sort: { key: 'createdBy', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'createdBy', asc: true, id: 0 }});
                }}
              >
                検査者
              </th>
              <th
                rowSpan="2"
                className={`updatedBy clickable ${sort.key == 'updatedBy' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'updatedBy') this.setState({sort: { key: 'updatedBy', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'updatedBy', asc: true, id: 0 }});
                }}
              >
                更新者
              </th>
              <th
                rowSpan="2"
                className={`status clickable ${sort.key == 'status' ? sort.asc ? 'asc' : 'desc' : ''}`}
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
              <th rowSpan="2" className="comment">コメント</th>
              <th
                rowSpan="2"
                className={`createdAt clickable ${sort.key == 'createdAt' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'createdAt') this.setState({sort: { key: 'createdAt', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'createdAt', asc: true, id: 0 }});
                }}
              >
                検査日
              </th>
              <th
                rowSpan="2"
                className={`updatedAt clickable ${sort.key == 'updatedAt' ? sort.asc ? 'asc' : 'desc' : ''}`}
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
                  className={`failure clickable ${(sort.key == 'failures' && sort.id == f.id) ? sort.asc ? 'asc' : 'desc' : ''}`}
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
                  className={`clickable ${(sort.key == 'modifications' && sort.id == m.id) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'modifications') this.setState({sort: { key: 'modifications', asc: !sort.asc, id: m.id }});
                    else this.setState({sort: { key: 'modifications', asc: true, id: m.id }});
                  }}
                >
                  {`${m.label}.${m.name}`}
                </th>
              )
            }{
              inlines.length > 0 &&
              inlines.map(i =>
                <th
                  className={`clickable ${(sort.key == 'inlines' && sort.id == i.id) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'inlines') this.setState({sort: { key: 'inlines', asc: !sort.asc, id: i.id }});
                    else this.setState({sort: { key: 'inlines', asc: true, id: i.id }});
                  }}
                >
                  {`${i.sort}`}
                </th>
              )
            }
            </tr>
          </thead>
          <tbody>
          {
            this.sortData(data).map((d,i) =>
              <tr>
                <td className="number">{i+1}</td>
                <td className="vehicle">{d.vehicle}</td>
                <td className="pn">{d.pn}</td>
                <td className="name">{d.name}</td>
                <td className="panelId">{d.panelId}</td>
                <td className="tyoku">{d.tyoku}</td>
                <td className="createdBy">{d.createdBy}</td>
                <td className="updatedBy">{d.updatedBy}</td>
                <td className="status">{d.status == 1 ? '○' : '×'}</td>
                {
                  holes.length > 0 &&
                  holes.map(h => {
                    let status;
                    if (d.holes.find(ch => ch.id == h.id).status == 0) {status = '×';}
                    else if (d.holes.find(ch => ch.id == h.id).status == 2) {status = '△';}
                    else if (d.holes.find(ch => ch.id == h.id).status == 1) {status = '○';}

                    return (<td>{status}</td>);
                  })
                }{
                  hModifications.length > 0 &&
                  hModifications.map(hm => {
                    let sum = 0;
                    if (d.hModifications[hm.id]) {
                      sum =  d.hModifications[hm.id];
                    }
                    return (<td>{sum}</td>);
                  })
                }{
                  failures.length > 0 &&
                  failures.map(f => {
                    let sum = 0;
                    if (d.failures[f.id]) {
                      sum =  d.failures[f.id];
                    }
                    return (<td className="failure">{sum}</td>);
                  })
                }{
                  modifications.length > 0 &&
                  modifications.map(m => {
                    let sum = 0;
                    if (d.modifications[m.id]) {
                      sum =  d.modifications[m.id];
                    }
                    return (<td>{sum}</td>);
                  })
                }{
                  inlines.length > 0 &&
                  inlines.map(i => {
                    let status = '○';
                    if (d.inlines[i.id]) {
                      let target = d.inlines[i.id];
                      if ( target.status > target.max || target.status < target.min ) {
                        status = '×';
                      }
                    }
                    return (<td>{status}</td>);
                  })
                }
                <td className="comment">{d.comment ? d.comment.slice(0,5)+'...' : ''}</td>
                <td className="createdAt">{d.inspectedAt ? d.inspectedAt : d.createdAt}</td>
                <td className="updatedAt">{d.inspectedAt ? d.inspectedAt : d.updatedAt}</td>
              </tr>
            )
          }{
            count == 0 &&
            <tr>
                <td colSpan={12+holes.length+hModifications.length+failures.length+modifications.length+inlines.length}>
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
  hModifications: PropTypes.array
};

export default CustomTable;
