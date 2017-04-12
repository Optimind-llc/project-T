import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
// import { handleDownload } from '../../../utils/Export';
// Actions
import { partFamilyActions } from '../ducks/partFamily';
import { mappingActions } from '../ducks/mapping';
// Styles
import './association.scss';
// Components
import Table from '../components/table/table';
import Edit from '../components/edit/edit';
import Mapping from '../components/mapping/mapping';
import CustomCalendar from '../components/calendar/calendar';
import Loading from '../../../../components/loading/loading';

class Association extends Component {
  constructor(props, context) {
    super(props, context);

    this.state = {
      narrowedBy: 'date',
      type: {label: 'ドアL', value: 'doorL'},
      partType: {label: '全て', value: [6714211020, 6714111020, 6715211020, 6715111020, 6441211010, 6441211020, 6441111010, 6441111020]},
      panelId: '',
      startDate: moment(),
      startHour: null,
      endDate: moment(),
      endHour: null,
      editModal: false,
      toBeEditted: null,
      mappingModal: false
    };
  }

  serch() {
    const { getPartFamilyByDate, getPartFamilyByPanelId } = this.props.actions;
    const { narrowedBy, type, startDate, startHour, endDate, endHour, partType, panelId } = this.state;

    let start;
    if (startHour == null) {
      start = startDate == null ? null : `${startDate.format('YYYY-MM-DD')}-00`;
    }
    else {
      start = startDate == null ? null : `${startDate.format('YYYY-MM-DD')}-${startHour.value}`;
    }

    let end;
    if (endHour == null) {
      end = endDate == null ? null : `${endDate.format('YYYY-MM-DD')}-23`;
    }
    else {
      end = endDate == null ? null : `${endDate.format('YYYY-MM-DD')}-${endHour.value}`;
    }

    if (narrowedBy === 'date') {
      getPartFamilyByDate(type.value, start, end);
    }
    else if (narrowedBy === 'panelId') {
      getPartFamilyByPanelId(partType.value, panelId);
    }
  }

  handleDownload() {
    // let table = [];
    // if (PartFamilyData.data != null && !PartFamilyData.isFetching) {
    //   let header = ['更新日','バックドアインナー','アッパー','サイドアッパーLH','サイドアッパーRH','サイドロアLH','サイドロアRH'];
    //   table.push(header);

    //   table = table.concat(PartFamilyData.data.families.map(pf => [
    //     pf.associatedAt,
    //     pf.parts['67149'][0].panelId,
    //     pf.parts['67119'] ? pf.parts['67119'][0].panelId : '',
    //     pf.parts['67176'][0].panelId,
    //     pf.parts['67175'][0].panelId,
    //     pf.parts['67178'][0].panelId,
    //     pf.parts['67177'][0].panelId
    //   ]));
    // };
  }

  handleMapping(pn, id, p, i) {
    const { getMappingData } = this.props.actions;
    getMappingData(pn, id, p, i);

    this.setState({mappingModal: true})
  }

  render() {
    const { PartFamilyData, UpdatePartFData, MappingData, PartTypes, combination, inspections, actions } = this.props;
    const { narrowedBy, type, startDate, startHour, endDate, endHour, partType, panelId, editModal, toBeEditted, mappingModal } = this.state;

    return (
      <div id="association-950A-wrap">
        <div className="bg-white">
          <div className="select-wrap">
            <div className="row">
              <div
                className={`row selectable ${narrowedBy === 'date' ? 'selected' : ''}`}
                onClick={() => this.setState({narrowedBy: 'date'})}
              >
                <p>種類</p>
                <Select
                  name="種類"
                  className="width140"
                  placeholder="種類を選択"
                  clearable={false}
                  Searchable={true}
                  value={type}
                  options={[
                    {label: 'ドアL', value: 'doorL'},
                    {label: 'ドアR', value: 'doorR'},
                    {label: 'ラゲージSTD', value: 'luggageSTD'},
                    {label: 'ラゲージARW', value: 'luggageARW'}
                  ]}
                  onChange={type => this.setState({type})}
                />
                <p style={{marginLeft: 12}}>期間</p>
                <CustomCalendar
                  defaultDate={startDate}
                  changeDate={d => this.setState({startDate: d})}
                  disabled={false}
                />
                <Select
                  name="部品"
                  className="width84"
                  placeholder={'終日'}
                  disabled={false}
                  clearable={true}
                  Searchable={false}
                  scrollMenuIntoView={false}
                  value={this.state.startHour}
                  options={[
                    {label: '0時', value: 0},
                    {label: '1時', value: 1},
                    {label: '2時', value: 2},
                    {label: '3時', value: 3},
                    {label: '4時', value: 4},
                    {label: '5時', value: 5},
                    {label: '6時', value: 6},
                    {label: '7時', value: 7},
                    {label: '8時', value: 8},
                    {label: '9時', value: 9},
                    {label: '10時', value: 10},
                    {label: '11時', value: 11},
                    {label: '12時', value: 12},
                    {label: '13時', value: 13},
                    {label: '14時', value: 14},
                    {label: '15時', value: 15},
                    {label: '16時', value: 16},
                    {label: '17時', value: 17},
                    {label: '18時', value: 18},
                    {label: '19時', value: 19},
                    {label: '20時', value: 20},
                    {label: '21時', value: 21},
                    {label: '22時', value: 22},
                    {label: '23時', value: 23}
                  ]}
                  onChange={value => this.setState({
                    startHour: value
                  })}
                />
                <p>〜</p>
                <CustomCalendar
                  defaultDate={endDate}
                  changeDate={d => this.setState({endDate: d})}
                  disabled={false}
                />
                <Select
                  name="部品"
                  className="width84"
                  placeholder={'終日'}
                  disabled={false}
                  clearable={true}
                  Searchable={false}
                  scrollMenuIntoView={false}
                  value={this.state.endHour}
                  options={[
                    {label: '0時', value: 0},
                    {label: '1時', value: 1},
                    {label: '2時', value: 2},
                    {label: '3時', value: 3},
                    {label: '4時', value: 4},
                    {label: '5時', value: 5},
                    {label: '6時', value: 6},
                    {label: '7時', value: 7},
                    {label: '8時', value: 8},
                    {label: '9時', value: 9},
                    {label: '10時', value: 10},
                    {label: '11時', value: 11},
                    {label: '12時', value: 12},
                    {label: '13時', value: 13},
                    {label: '14時', value: 14},
                    {label: '15時', value: 15},
                    {label: '16時', value: 16},
                    {label: '17時', value: 17},
                    {label: '18時', value: 18},
                    {label: '19時', value: 19},
                    {label: '20時', value: 20},
                    {label: '21時', value: 21},
                    {label: '22時', value: 22},
                    {label: '23時', value: 23}
                  ]}
                  onChange={value => this.setState({
                    endHour: value
                  })}
                />
              </div>
              <div
                className={`row selectable ${narrowedBy === 'panelId' ? 'selected' : ''}`}
                onClick={() => this.setState({narrowedBy: 'panelId'})}
              >
                <p>部品</p>
                <Select
                  name="種類"
                  className="width200"
                  placeholder="種類を選択"
                  clearable={false}
                  Searchable={true}
                  value={partType}
                  options={[
                    {label: 'ドアインナL', value: [6714211020]},
                    {label: 'ドアインナR', value: [6714111020]},
                    {label: 'リンフォースL', value: [6715211020]},
                    {label: 'リンフォースR', value: [6715111020]},
                    {label: 'ラゲージインナSTD', value: [6441211010]},
                    {label: 'ラゲージインナARW', value: [6441211020]},
                    {label: 'ラゲージインナSTD', value: [6441111010]},
                    {label: 'ラゲージインナARW', value: [6441111020]},
                    {label: '全て', value: [6714211020, 6714111020, 6715211020, 6715111020, 6441211010, 6441211020, 6441111010, 6441111020]}
                  ]}
                  onChange={partType => this.setState({partType})}
                />
                <p>パネルID</p>
                <input
                  type="text"
                  value={panelId}
                  style={{width: 120}}
                  onChange={e => this.setState({panelId: e.target.value})}
                />
              </div>
            </div>
            <button
              className="serch dark"
              onClick={() => this.serch()}
            >
              <p>この条件で検索</p>
            </button>
          </div>
        </div>
        {
          PartFamilyData.isFetching &&
          <p>検索中...</p>
        }{
          PartFamilyData.data != null && !PartFamilyData.isFetching &&
          <div className="result bg-white">
            {
              PartFamilyData.data.doorL &&
              <Table
                data={PartFamilyData.data.doorL}
                partNames={['ドアインナL','リンフォースL','ドアASSY LH']}
                partPns={[6714211020,6715211020,6701611020]}
                handleDownload={() => handleDownload('doorL')}
                handleEdit={id => this.setState({
                  editModal: true,
                  toBeEditted: PartFamilyData.data.doorL.find(f => f.id === id),
                })}
                handleMapping={(pn, id, p, i) => this.handleMapping(pn, id, p, i)}
              />
            }{
              PartFamilyData.data.doorR &&
              <Table
                data={PartFamilyData.data.doorR}
                partNames={['ドアインナR','リンフォースR','ドアASSY RH']}
                partPns={[6714111020,6715111020,6701511020]}
                handleDownload={() => handleDownload('doorR')}
                handleEdit={id => this.setState({
                  editModal: true,
                  toBeEditted: PartFamilyData.data.doorR.find(f => f.id === id),
                })}
                handleMapping={(pn, id, p, i) => this.handleMapping(pn, id, p, i)}
              />
            }{
              PartFamilyData.data.luggageSTD &&
              <Table
                data={PartFamilyData.data.luggageSTD}
                partNames={['ラゲージインナSTD','ラゲージアウタSTD','ラゲージASSY STD']}
                partPns={[6441211010,6441111010,6440111010]}
                handleDownload={() => handleDownload('luggageSTD')}
                handleEdit={id => this.setState({
                  editModal: true,
                  toBeEditted: PartFamilyData.data.luggageSTD.find(f => f.id === id),
                })}
                handleMapping={(pn, id, p, i) => this.handleMapping(pn, id, p, i)}
              />
            }{
              PartFamilyData.data.luggageARW &&
              <Table
                data={PartFamilyData.data.luggageARW}
                partNames={['ラゲージインナARW','ラゲージアウタARW','ラゲージASSY ARW']}
                partPns={[6441211020,6441111020,6440111020]}
                handleDownload={() => handleDownload('luggageARW')}
                handleEdit={id => this.setState({
                  editModal: true,
                  toBeEditted: PartFamilyData.data.luggageARW.find(f => f.id === id),
                })}
                handleMapping={(pn, id, p, i) => this.handleMapping(pn, id, p, i)}
              />
            }
          </div>
        }{
          editModal &&
          <Edit
            partTypes={PartTypes}
            partsData={toBeEditted}
            updatePartFamily={(id, parts) => actions.updatePartFamily(id, parts)}
            closeModal={() => {
              this.setState({editModal: false});
              actions.clearErrorPart();
              this.serch();
            }}
            errorParts={PartFamilyData.errorParts}
          />
        }{
          mappingModal && MappingData.data &&
          <div className="mapping-wrap">
            <div
              className="panel-btn"
              onClick={() => {
                this.setState({mappingModal: false});
                actions.clearMappingData();
              }}>
              <span className="panel-btn-close"></span>
            </div>
            <div className="mapping-header">
              <p>{'header'}</p>
            </div>
            <div className="mapping-left-panel">
              <ul>
                <li className="process-name">成形工程</li>
                {
                  combination.filter(c => c.process === 'molding' && c.pn == MappingData.data.pn).map(c =>
                    <li
                      className="inspection-name"
                      onClick={() => this.setState({
                        p: 'molding',
                        i: c.i,
                        active: 'failure'
                      }, () => this.handleMapping(MappingData.data.pn, 1, 'molding', c.i))}
                    >
                      {inspections.find(i => i.en === c.inspection).name}
                    </li>
                  )
                }
              </ul>
              <div className="divider"></div>
              <ul>
                <li className="process-name">穴あけ工程</li>
                {
                  combination.filter(c => c.process === 'holing' && c.pn == MappingData.data.pn).map(c =>
                    <li
                      className="inspection-name"
                      onClick={() => this.setState({
                        p: 'holing',
                        i: c.i,
                        active: 'failure'
                      }, () => this.handleMapping(MappingData.data.pn, 1, 'holing', c.i))}
                    >
                      {inspections.find(i => i.en === c.inspection).name}
                    </li>
                  )
                }
              </ul>
              <div className="divider"></div>
              <ul>
                <li className="process-name">かしめ/接着工程</li>
                {
                  combination.filter(c => c.process === 'jointing' && c.pn == MappingData.data.pn).map(c =>
                    <li
                      className="inspection-name"
                      onClick={() => this.setState({
                        p: 'jointing',
                        i: c.i,
                        active: 'failure'
                      }, () => this.handleMapping(MappingData.data.pn, 1, 'jointing', c.i))}
                    >
                      {inspections.find(i => i.en === c.inspection).name}
                    </li>
                  )
                }
              </ul>
            </div>
            <Mapping
              data={MappingData.data}
            />
          </div>
        }
      </div>
    );
  }
}

Association.propTypes = {
  PartFData: PropTypes.object.isRequired,
};

function mapStateToProps(state, ownProps) {
  return {
    PartFamilyData: state.AssociationData950A,
    UpdatePartFData: state.UpdatePartFData,
    MappingData: state.AssociationMappingData950A,
    PartTypes: state.Application.vehicle950A.partTypes,
    inspections: state.Application.vehicle950A.inspections,
    combination: state.Application.vehicle950A.combination,
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({}, partFamilyActions, mappingActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Association);
