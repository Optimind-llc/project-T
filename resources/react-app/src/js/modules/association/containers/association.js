import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
// Actions
import { partFActions } from '../ducks/partF';
// Material-ui Components
import { Paper, Dialog, RaisedButton, FlatButton } from 'material-ui';
import { grey50, indigo500 } from 'material-ui/styles/colors';
// Styles
import './association.scss';
// Components
import RangeCalendar from '../components/rangeCalendar/rangeCalendar';
import Loading from '../../../components/loading/loading';

class Association extends Component {
  constructor(props, context) {
    super(props, context);

    this.state = {
      date: moment(),
      tyoku: null
    };
  }

  serch() {
    const { actions: {getPartFData} } = this.props;
    const { date, tyoku } = this.state;

    getPartFData(date.format('YYYY-MM-DD'), tyoku.value);
  }

  render() {
    const { PartFData } = this.props;
    const { date } = this.state;

    return (
      <div id="association">
        <div className="serch bg-white">
          <div>
            <RangeCalendar
              defaultValue={date}
              setState={date => this.setState({
                date: date
              })}
            />
          </div>
          <div>
            <Select
              name="直"
              placeholder="直を選択"
              styles={{height: 36}}
              clearable={false}
              Searchable={false}
              value={this.state.tyoku}
              options={[
                {label: '１直', value: 1},
                {label: '２直', value: 2}
              ]}
              onChange={value => this.setState({tyoku: value})}
            />
          </div>
          <div>
            <button
              onClick={() => this.serch()}
            >
              検索
            </button>
          </div>
        </div>
        <div className="result bg-white">
          <table>
            <tbody>
              <tr>
                <td colSpan={1}>No.</td>
                <td colSpan={1} >ASSY</td>
                <td colSpan={1} >インナー</td>
                <td colSpan={5} >アウター</td>
              </tr>
              <tr>
                <td></td>
                <td>バックドアインナASSY</td>
                <td>バックドアインナー</td>
                <td>アッパー</td>
                <td>サイドアッパーRH</td>
                <td>サイドアッパーLH</td>
                <td>サイドロアRH</td>
                <td>サイドロアLH</td>
              </tr>
              <tr>
                <td></td>
                <td>67007 47120 000</td>
                <td>67149 47060 000</td>
                <td>67119 47060 000</td>
                <td>67175 47060 000</td>
                <td>67176 47060 000</td>
                <td>67177 47050 000</td>
                <td>67178 47010 000</td>
              </tr>
              {
                PartFData.data && PartFData.data.length != 0 &&
                PartFData.data.map((f, i)=> 
                  {
                    return(
                      <tr>
                        <td>{i+1}</td>
                        <td>{f.parts['67007'][0].panelId}</td>
                        <td>{f.parts['67149'][0].panelId}</td>
                        <td>{f.parts['67119'][0].panelId}</td>
                        <td>{f.parts['67175'][0].panelId}</td>
                        <td>{f.parts['67176'][0].panelId}</td>
                        <td>{f.parts['67177'][0].panelId}</td>
                        <td>{f.parts['67178'][0].panelId}</td>
                      </tr>
                    )
                  }              
                )
              }{
                PartFData.data && PartFData.data.length == 0 &&
                <td colspan="8">結果なし</td>
              }
            </tbody>
          </table>
        </div>
      </div>
    );
  }
}

Association.propTypes = {
  PartFData: PropTypes.object.isRequired,
};

function mapStateToProps(state, ownProps) {
  return {
    PartFData: state.PartFData,
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({}, partFActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Association);
