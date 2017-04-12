import React, { Component, PropTypes } from 'react';
// Styles
import './edit.scss';

class Edit extends Component {
  constructor(props, context) {
    super(props, context);

    function toObject(arr) {
      var rv = {};
      for (var i = 0; i < arr.length; ++i)
        rv[arr[i].pn] = arr[i].panelId;
      return rv;
    }

    const sorted = this.props.partsData.parts.sort((a, b) =>
      a.sort > b.sort ? 1 : -1
    );

    console.log(sorted);

    this.state = {
      id: this.props.partsData.id,
      parts: toObject(sorted)
    }
  }

  render() {
    const { id, parts } = this.state;
    const { partTypes, updatePartFamily, closeModal, errorParts } = this.props;

    const validated = Object.keys(parts).map(pn => 
      Math.abs(parts[pn].length - 8)
    ).reduce((a, b) =>
      a + b
    ) === 0;

    return (
      <div>
        <div className="modal"></div>
        <div className="edit-wrap">
          <div className="edit">
            <div className="message-wrap">
            {
              errorParts &&
              errorParts.map(p =>
                <p>{`${partTypes.find(pt => pt.pn === p.pn).name} : ${p.panelId} の更新に失敗しました。すでに他の部品に使用されています。`}</p>
              )
            }
            </div>
            <div className="input-wrap">
            {
              Object.keys(parts).map(pn =>
                <div className="input">
                  <p className="label">{`${partTypes.find(pt => pt.pn == pn).name}:${pn}`}</p>
                  <input
                    type="text"
                    value={parts[pn]}
                    onChange={e => {
                      parts[pn] = e.target.value;
                      this.setState({parts: parts});
                    }}
                  />
                  {
                    parts[pn].length != 8 &&
                    <p className="validation_msg">8桁で入力してください</p>
                  }
                </div>
              )
            }
            </div>
            <div className="btn-wrap">
              <button
                className={validated ? '' : 'disabled'}
                onClick={() => updatePartFamily(id, parts)}
              >
                保存
              </button>
              <button onClick={() => closeModal()}>終了</button>
            </div>
          </div>
        </div>
      </div>
    );
  }
}

Edit.propTypes = {
  partTypes: PropTypes.array.isRequired,
  partsData: PropTypes.object.isRequired,
  updatePartFamily: PropTypes.func.isRequired,
  closeModal: PropTypes.func.isRequired,
  errorParts: PropTypes.object.isRequired
};

export default Edit;

