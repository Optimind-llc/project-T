import React, { Component, PropTypes } from 'react';
// Styles
import './table.scss';

class Mapping extends Component {
  constructor(props, context) {
    super(props, context);

    this.state = {
    }
  }

  render() {
    const { data, partNames, handleDownload, handleEdit, handleMapping } = this.props;

    return (
      <div className="result-table">
        <p className="result-count">{`${data.length}件表示`}</p>
        {/*<button className="download dark" onClick={() => handleDownload(table)}>
          <p>CSVをダウンロード</p>
        </button>*/}
        <table>
          <thead>
            <tr>
              <th colSpan={1} rowSpan={2} width="30">No.</th>
              <th colSpan={1} rowSpan={2} width="120">更新日</th>
              {
                partNames.map(pn =>
                  <th colSpan={1}>{pn}</th>
                )
              }
              <th colSpan={1} rowSpan={2} width="100">機能</th>
            </tr>
            <tr>
              <th>6714211020</th>
              <th>6715211020</th>
              <th>6701611020</th>
            </tr>
          </thead>
          <tbody>
            {
              data && data.length != 0 &&
              data.map((f, i) =>
                {
                  return(
                    <tr className="content">
                      <td width="30">{i+1}</td>
                      <td width="120">{f.updatedAt}</td>
                      {
                        f.parts.sort((a, b) =>
                          a.sort > b.sort ? 1 : -1
                        ).map(p =>
                          <td>
                            {/*<p onClick={() => handleMapping(p.pn, p.id, 'molding', 'gaikan')}>
                              {p.panelId}
                            </p>*/}
                            <p>
                              {p.panelId}
                            </p>
                          </td>
                        )
                      }
                      <td width="100">
                        <button
                          className="dark edit"
                          onClick={() => handleEdit(f.id)}
                        >
                          <p>編集</p>
                        </button>
                      </td>
                    </tr>
                  )
                }
              )
            }{
              data && data.length == 0 &&
              <tr className="content">
                <td colSpan="10">検査結果なし</td>
              </tr>
            }
          </tbody>
        </table>
      </div>
    );
  }
}

Mapping.propTypes = {
  data: PropTypes.object.isRequired,
  partNames: PropTypes.array.isRequired,
  handleDownload: PropTypes.func.isRequired,
  handleEdit: PropTypes.func.isRequired,
  handleMapping: PropTypes.func.isRequired
};

export default Mapping;
