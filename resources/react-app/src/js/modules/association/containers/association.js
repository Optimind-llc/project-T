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
          <div className="column">
            <p className="row aline-right"></p>
            <p className="row">ASSY</p>
            <p className="row border-left">インナー</p>
            <p className="row border-left">アウター</p>
          </div>
          <div className="column">
            <p className="row aline-right"></p>
            <p className="row">バックドアインナASSY</p>
            <p className="row border-left">バックドアインナー</p>
            <p className="row border-left">アッパー</p>
            <p className="row">サイドアッパーRH</p>
            <p className="row">サイドアッパーLH</p>
            <p className="row">サイドロアRH</p>
            <p className="row">サイドロアLH</p>
          </div>
          <div className="column border">
            <p className="row aline-right"></p>
            <p className="row">67007 47120 000</p>
            <p className="row border-left">67149 47060 000</p>
            <p className="row border-left">67119 47060 000</p>
            <p className="row">67175 47060 000</p>
            <p className="row">67176 47060 000</p>
            <p className="row">67177 47050 000</p>
            <p className="row">67178 47010 000</p>
          </div>
          {
            PartFData.data && PartFData.data.length != 0 &&
            PartFData.data.map((f, i)=> 
              {
                return(
                  <div className="column">
                    <p className="row aline-right"><p>{i+1}</p></p>
                    <p className="row">{f.parts['67007'][0].panelId}</p>
                    <p className="row border-left">{f.parts['67149'][0].panelId}</p>
                    <p className="row border-left">{f.parts['67119'][0].panelId}</p>
                    <p className="row">{f.parts['67175'][0].panelId}</p>
                    <p className="row">{f.parts['67176'][0].panelId}</p>
                    <p className="row">{f.parts['67177'][0].panelId}</p>
                    <p className="row">{f.parts['67178'][0].panelId}</p>
                  </div>
                )
              }              
            )
          }{
            PartFData.data && PartFData.data.length == 0 &&
            <p className="no-data">結果なし</p>
          }
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
