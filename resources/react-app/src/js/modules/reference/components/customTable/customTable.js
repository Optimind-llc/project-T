import React, { Component, PropTypes } from 'react';
import { Table } from 'reactable';
import './customTable.scss';

class CustomTable extends Component {
  constructor(props, context) {
    super(props, context);
    this.state = {
      sort: {
        key: 'panelId',
        asc: true,
        id: 0
      }
    };
  }

  render() {
    const { data, failures, holes, modifications, hModifications } = this.props;
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

      if (sort.asc) {
        if(aaa < bbb) return 1;
        else if(aaa > bbb) return -1;
      } else {
        if(aaa < bbb) return -1;
        else if(aaa > bbb) return 1;
      }
      return 0;
    });

    return (
      <table>
        <thead>
          <tr>
            <th rowSpan="2">No.</th>
            <th rowSpan="2">車種</th>
            <th rowSpan="2">品番</th>
            <th rowSpan="2">品名</th>
            <th
              rowSpan="2"
              className={`clickable ${sort.key == 'panelId' ? sort.asc ? 'asc' : 'desc' : ''}`}
              onClick={() => {
                if(sort.key == 'panelId') this.setState({sort: { key: 'panelId', asc: !sort.asc, id: 0 }});
                else this.setState({sort: { key: 'panelId', asc: true, id: 0 }});
              }}
            >
              パネルID</th>
            <th
              rowSpan="2"
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
            }
            <th rowSpan="2">コメント</th>
            <th rowSpan="2">検査日</th>
            <th rowSpan="2">最終更新日</th>
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
                {`${hm.label}.${hm.name}`}
              </th>
            )
          }{
            failures.length > 0 &&
            failures.map(f =>
              <th
                className={`clickable ${(sort.key == 'failures' && sort.id == f.id) ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'failures') this.setState({sort: { key: 'failures', asc: !sort.asc, id: f.id }});
                  else this.setState({sort: { key: 'failures', asc: true, id: f.id }});
                }}
              >
                {`${f.label}.${f.name}`}
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
          }
          </tr>
        </thead>
        <tbody>
        {
          data.map((d,i) =>
            <tr>
              <td>{i+1}</td>
              <td>{d.vehicle}</td>
              <td>{d.pn}</td>
              <td>{d.name}</td>
              <td>{d.panelId}</td>
              <td>{d.tyoku}</td>
              <td>{d.createdBy}</td>
              <td>{d.updatedBy}</td>
              <td>{d.status == 1 ? '○' : '×'}</td>
              {
                holes.length > 0 &&
                d.holes.map(h => {
                  let status;
                  if (h.status == 0) {status = '×';}
                  else if (h.status == 2) {status = '△';}
                  else if (h.status == 1) {status = '○';}

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
                  return (<td>{sum}</td>);
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
              }
              <td>{d.comment ? d.comment.slice(0,5)+'...' : ''}</td>
              <td>{d.createdAt}</td>
              <td>{d.updatedAt}</td>
            </tr>
          )
        }
        </tbody>
      </table>
    );
  }
};

CustomTable.propTypes = {
  data: PropTypes.object,
  failures: PropTypes.array,
  holes: PropTypes.array,
  modifications: PropTypes.array,
  hModifications: PropTypes.array
};

export default CustomTable;
