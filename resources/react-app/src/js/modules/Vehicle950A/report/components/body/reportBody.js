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

    const styles = {
      molding: {
        gaikan: [],
        inline: [
          { pt: 'luggageInnerARW', value: 78, height: 58 }
        ],
      },
      holing: {
        maegaikan: [],
        atogaikan: [],
        ana: [],
        tenaoshi: []
      },
      jointing: {
        kashimego: [],
        gaishushiage: [
          { pt: 'luggageOuterSTD', value: 68*5 + 10, height: 58 },
          { pt: 'luggageOuterARW', value: 78, height: 58 },
        ],
        pateshufukugo: [
          { pt: 'luggageOuterSTD', value: 68*5 + 10, height: 58 },
          { pt: 'luggageOuterARW', value: 78, height: 58 },
        ],
        suikengo: [
          { pt: 'luggageOuterSTD', value: 68*5 + 10, height: 58 },
          { pt: 'luggageOuterARW', value: 78, height: 58 },
        ],
        tosoukeirego: [
          { pt: 'luggageOuterSTD', value: 68*5 + 10, height: 58 },
          { pt: 'luggageOuterARW', value: 78, height: 58 },
        ],
        inline: [
          { pt: 'doorASSY LH', value: 0, height: 126 },
          { pt: 'doorASSY RH', value: 0, height: 126 },
          { pt: 'luggageASSY STD', value: 0, height: 126 },
          { pt: 'luggageASSY ARW', value: 0, height: 126 }
        ],
        gaikan: [
          { pt: 'doorASSY LH', value: 0, height: 126 },
          { pt: 'doorASSY RH', value: 0, height: 126 },
          { pt: 'luggageASSY STD', value: 0, height: 126 },
          { pt: 'luggageASSY ARW', value: 0, height: 126 }
        ],
        tenaoshi: [
          { pt: 'doorASSY LH', value: 0, height: 126 },
          { pt: 'doorASSY RH', value: 0, height: 126 },
          { pt: 'luggageASSY STD', value: 0, height: 126 },
          { pt: 'luggageASSY ARW', value: 0, height: 126 }
        ]
      },
    };

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
                    style={{
                      marginTop: styles[p][i].find(mt => mt.pt == pt) ? styles[p][i].find(mt => mt.pt == pt).value : 0,
                      height: styles[p][i].find(mt => mt.pt == pt) ? styles[p][i].find(mt => mt.pt == pt).height : 58
                    }}
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
