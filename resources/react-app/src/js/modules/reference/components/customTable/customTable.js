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
    const {data, failures, holes, modifications, hModifications} = this.props;
    const { sort } = this.state;
console.log(sort);
    return (
      <table>
        <thead>
          <tr>
            <th rowSpan="2">No.</th>
            <th
              rowSpan="2"
              className={`clickable ${sort.key == 'panelId' ? sort.asc ? 'asc' : 'desc' : ''}`}
              onClick={() => {
                if(sort.key == 'panelId') this.setState({sort: { key: 'panelId', asc: !sort.asc, id: 0 }});
                else this.setState({sort: { key: 'panelId', asc: true, id: 0 }});
              }}
            >
              車種
            </th>
            <th
              rowSpan="2"
              className={`clickable ${sort.key == 'pn' ? sort.asc ? 'asc' : 'desc' : ''}`}
              onClick={() => {
                if(sort.key == 'pn') this.setState({sort: { key: 'pn', asc: !sort.asc, id: 0 }});
                else this.setState({sort: { key: 'pn', asc: true, id: 0 }});
              }}
            >
              品番
            </th>
            <th rowSpan="2" className={`clickable ${sort.key == 'panelId' && sort.order}`}>品名</th>
            <th rowSpan="2" className={`clickable ${sort.key == 'panelId' && sort.order}`}>パネルID</th>
            <th rowSpan="2" className={`clickable ${sort.key == 'panelId' && sort.order}`}>直</th>
            <th rowSpan="2" className={`clickable ${sort.key == 'panelId' && sort.order}`}>検査者</th>
            <th rowSpan="2" className={`clickable ${sort.key == 'panelId' && sort.order}`}>更新者</th>
            <th rowSpan="2" className={`clickable ${sort.key == 'panelId' && sort.order}`}>判定</th>
            {
              holes.length > 0 &&
              <th colSpan={holes.length}>穴</th>
            }{
              hModifications.length > 0 &&
              <th>手直し</th>
            }{
              failures.length > 0 &&
              <th colSpan={failures.length}>外観不良</th>
            }{
              modifications.length > 0 &&
              <th>手直し</th>
            }
            <th rowSpan="2">検査日</th>
            <th rowSpan="2">最終更新日</th>
          </tr>
          <tr>
          {
            holes.length > 0 &&
            holes.map(h =>
              <th className={`clickable ${sort.key == 'panelId' && sort.order}`}>{`${h.label}`}</th>
            )
          }{
            hModifications.length > 0 &&
            <th className={`clickable ${sort.key == 'panelId' && sort.order}`}>手直し</th>
          }{
            failures.length > 0 &&
            failures.map(f =>
              <th className={`clickable ${sort.key == 'panelId' && sort.order}`}>{`${f.label}.${f.name}`}</th>
            )
          }{
            modifications.length > 0 &&
            <th>手直し</th>
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
              <td>{d.status}</td>
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
                failures.length > 0 &&
                failures.map(f => {
                  let sum = 0;
                  if (d.failures[f.id]) {
                    sum =  d.failures[f.id];
                  }
                  return (<td>{sum}</td>);
                })
              }
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
