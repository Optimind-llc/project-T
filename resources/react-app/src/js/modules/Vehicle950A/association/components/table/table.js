import React, { Component, PropTypes } from 'react';
// Styles
// import './table.scss';

class Mapping extends Component {
  constructor(props, context) {
    super(props, context);

    this.state = {
    }
  }

  render() {
    const { data } = this.props;
    data[0].parts.map(p => console.log(p.panelId));

    return (
      <table>
        <thead>
          <tr>
            <th colSpan={1} rowSpan={2}>No.</th>
            <th colSpan={1} rowSpan={2}>更新日</th>
            <th colSpan={1}>ドアインナL</th>
            <th colSpan={1}>リンフォースL</th>
            <th colSpan={1}>ドアASSY LH</th>
            <th colSpan={1} rowSpan={2}>機能</th>
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
                    <td>{i+1}</td>
                    <td>{f.updatedAt}</td>
                    {
                      f.parts.map(p =>
                        <td>
                          <p onClick={() => {
                            this.setState({
                              mappingModal: true,
                              mappingId: p.id,
                              mappingPartTypeId: p.pt,
                              header: `67149 バックドアインナー　パネルID: ${p.panelId}`
                            });
                          }}>
                            {p.panelId}
                          </p>
                        </td>
                      )
                    }
                    <td>
                      <button
                        className="dark edit"
                        onClick={() => this.setState({
                          editModal: true,
                          editting_f: f.familyId,
                          editting_1: f.parts[67149][0].panelId,
                          editting_2: f.parts[67119] ? f.parts[67119][0].panelId : '',
                          editting_4: f.parts[67176][0].panelId,
                          editting_3: f.parts[67175][0].panelId,
                          editting_6: f.parts[67178][0].panelId,
                          editting_5: f.parts[67177][0].panelId
                        })}
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
    );
  }
}

Mapping.propTypes = {
  data: PropTypes.object.isRequired,
};

export default Mapping;