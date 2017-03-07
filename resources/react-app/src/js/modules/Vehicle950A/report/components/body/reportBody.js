import React, { PropTypes, Component } from 'react';
// Styles
import styles from './reportBody.scss';
// Components

class reportBody extends Component {
  constructor(props, context) {
    super(props, context);
  }

  render() {
    const { p, inspections, partTypes, combination, data, openModal } = this.props;

    const fillterdInspections = combination.filter(
      c => c.process === p
    ).map(c => 
      c.inspection
    ).filter((c, i, self) =>
      self.indexOf(c) === i
    );

    return (
      <div className="bg-white report-body">
        {
          fillterdInspections.map((i, ii) =>
            <div key={ii} className="inspection-wrap">
              <p>{inspections.find(ins => ins.en === i).name}</p>
              {
                combination.filter(
                  c => c.process === p && c.inspection === i
                ).map(c => 
                  c.part
                ).filter((c, i, self) =>
                  self.indexOf(c) === i
                ).map((pt, pti) =>
                  <div
                    key={pti}
                    className={`report-panel ${data.filter(d => d.process === p && d.inspection === i && d.partEn === pt).length > 0 ? '' : 'disabled'}`}
                    onClick={() => openModal(i, pt)}
                  >
                    <p className="report-line-name">{partTypes.find(partType => partType.en === pt).name}</p>
                  </div>
                )
              }
            </div>
          )
        }
      </div>
    );
  }
}

reportBody.propTypes = {
  p: PropTypes.string,
  inspections: PropTypes.array.isRequired,
  partTypes: PropTypes.array.isRequired,
  combination: PropTypes.array.isRequired,
  data: PropTypes.object.isRequired,
  openModal: PropTypes.func.isRequired
};

export default reportBody;
