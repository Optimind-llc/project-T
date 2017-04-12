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
    const { PartFamilyData, UpdatePartFData, MappingData, PartTypes, actions } = this.props;
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
            <div className="mapping-header">
              <p>{'header'}</p>
            </div>
            <div className="mapping-left-panel">
              <ul>
                
                <li className="process-name">成形ライン①</li>
                <li
                  className={`inspection-name ${inspectionGroups.filter(ig => ig.vehicle == '680A' && ig.part == mappingPartTypeId && ig.p == 'm001' && ig.i == 'gaikan' && !ig.disabled).length !== 0 ? '' : 'disable'} ${(p === 'm001' && i === 'gaikan') ? 'active' : ''}`}
                  onClick={() => this.setState({
                    p: 'm001',
                    i: 'gaikan',
                    active: 'failure'
                  }, () => this.requestMapping('gaikan'))}
                >
                  外観検査
                </li>
                <li
                  className={`inspection-name ${inspectionGroups.filter(ig => ig.vehicle == '680A' && ig.part == mappingPartTypeId && ig.p == 'm001' && ig.i == 'inline' && !ig.disabled).length !== 0 ? '' : 'disable'} ${(p === 'm001' && i === 'inline') ? 'active' : ''}`}
                  onClick={() => this.setState({
                    p: 'm001',
                    i: 'inline',
                    active: 'inline'
                  }, () => this.requestMapping('inline'))}
                >精度検査</li>
              </ul>
              <div className="divider"></div>
              <ul>
                <li className="process-name">成形ライン②</li>
                <li
                  className={`inspection-name ${inspectionGroups.filter(ig => ig.vehicle == '680A' && ig.part == mappingPartTypeId && ig.p == 'm002' && ig.i == 'gaikan' && !ig.disabled).length !== 0 ? '' : 'disable'} ${(p === 'm002' && i === 'gaikan') ? 'active' : ''}`}
                  onClick={() => this.setState({
                    p: 'm002',
                    i: 'gaikan',
                    active: 'failure'
                  }, () => this.requestMapping('gaikan'))}
                >
                  外観検査
                </li>
                <li
                  className={`inspection-name ${inspectionGroups.filter(ig => ig.vehicle == '680A' && ig.part == mappingPartTypeId && ig.p == 'm002' && ig.i == 'inline' && !ig.disabled).length !== 0 ? '' : 'disable'} ${(p === 'm002' && i === 'inline') ? 'active' : ''}`}
                  onClick={() => this.setState({
                    p: 'm002',
                    i: 'inline',
                    active: 'inline'
                  }, () => this.requestMapping('inline'))}
                >精度検査</li>
              </ul>
              <div className="divider"></div>
              <ul>
                <li className="process-name">穴あけ</li>
                <li
                  className={`inspection-name ${inspectionGroups.filter(ig => ig.vehicle == '680A' && ig.part == mappingPartTypeId && ig.p == 'h' && ig.i == 'gaikan' && !ig.disabled).length !== 0 ? '' : 'disable'} ${(p === 'h' && i === 'gaikan') ? 'active' : ''}`}
                  onClick={() => this.setState({
                    p: 'h',
                    i: 'gaikan',
                    active: 'failure'
                  }, () => this.requestMapping('gaikan'))}
                >外観検査</li>
                <li
                  className={`inspection-name ${inspectionGroups.filter(ig => ig.vehicle == '680A' && ig.part == mappingPartTypeId && ig.p == 'h' && ig.i == 'ana' && !ig.disabled).length !== 0 ? '' : 'disable'} ${(p === 'h' && i === 'ana') ? 'active' : ''}`}
                  onClick={() => this.setState({
                    p: 'h',
                    i: 'ana',
                    active: 'hole'
                  }, () => this.requestMapping('ana'))}
                >穴検査</li>
              </ul>
              <div className="divider"></div>
              <ul>
                <li className="process-name">接着</li>
                <li
                  className={`inspection-name ${inspectionGroups.filter(ig => ig.vehicle == '680A' && ig.part == mappingPartTypeId && ig.p == 'j' && ig.i == 'inline' && !ig.disabled).length !== 0 ? '' : 'disable'} ${(p === 'j' && i === 'inline') ? 'active' : ''}`}
                  onClick={() => this.setState({
                    p: 'j',
                    i: 'inline',
                    active: 'inline'
                  }, () => this.requestMapping('inline'))}
                >
                  精度検査
                </li>
              </ul>
              <ul
                className={`grouped ${(p === 'j' && i !== 'inline') ? 'active' : ''} ${mappingPartTypeId === 7 ? '' : 'disabled'}`}
                onClick={() => {
                    this.setState({
                      p: 'j',
                      i: null,
                      active: 'failure'
                    }, () => this.requestMapping(null));
              }}>
                <li
                  className={`inspection-name-jointing`}
                  onClick={() => {
                    let newJi = [];
                    if (ji.indexOf(16) >= 0) {
                      ji.splice(ji.indexOf(16), 1);
                      newJi = ji;
                    } else {
                      newJi = [16, ...ji];                  
                    }

                    this.setState({p: 'j', ji: newJi});
                  }}
                >
                  簡易CF {p === 'j' && i !== 'inline' && ji.indexOf(16) >= 0 && <div className="icon-check red">️</div>}
                </li>
                <li
                  className={`inspection-name-jointing ${inspectionGroups.filter(ig => ig.vehicle == '680A' && ig.part == mappingPartTypeId && ig.p == 'j' && ig.i == 'shisui' && !ig.disabled).length !== 0 ? '' : 'disable'} ${(p === 'j' && i === 'shisui') ? 'active' : ''}`}
                  onClick={() => {
                    let newJi = [];
                    if (ji.indexOf(10) >= 0) {
                      ji.splice(ji.indexOf(10), 1);
                      newJi = ji;
                    } else {
                      newJi = [10, ...ji]  ;                  
                    }

                    this.setState({p: 'j', ji: newJi});
                  }}
                >
                  止水 {p === 'j' && i !== 'inline' && ji.indexOf(10) >= 0 && <div className="icon-check yellow">️</div>}
                </li>
                <li
                  className={`inspection-name-jointing ${inspectionGroups.filter(ig => ig.vehicle == '680A' && ig.part == mappingPartTypeId && ig.p == 'j' && ig.i == 'shiage' && !ig.disabled).length !== 0 ? '' : 'disable'} ${(p === 'j' && i === 'shiage') ? 'active' : ''}`}
                  onClick={() => {
                    let newJi = [];
                    if (ji.indexOf(11) >= 0) {
                      ji.splice(ji.indexOf(11), 1);
                      newJi = ji;
                    } else {
                      newJi = [11, ...ji]  ;                  
                    }

                    this.setState({p: 'j', ji: newJi});
                  }}
                >
                  仕上 {p === 'j' && i !== 'inline' && ji.indexOf(11) >= 0 && <div className="icon-check blue">️</div>}
                </li>
                <li
                  className={`inspection-name-jointing ${inspectionGroups.filter(ig => ig.vehicle == '680A' && ig.part == mappingPartTypeId && ig.p == 'j' && ig.i == 'kensa' && !ig.disabled).length !== 0 ? '' : 'disable'} ${(p === 'j' && i === 'kensa') ? 'active' : ''}`}
                  onClick={() => {
                    let newJi = [];
                    if (ji.indexOf(12) >= 0) {
                      ji.splice(ji.indexOf(12), 1);
                      newJi = ji;
                    } else {
                      newJi = [12, ...ji]  ;                  
                    }

                    this.setState({p: 'j', ji: newJi});
                  }}
                >
                  検査 {p === 'j' && i !== 'inline' && ji.indexOf(12) >= 0 && <div className="icon-check green">️</div>}
                </li>
                <li
                  className={`inspection-name-jointing ${inspectionGroups.filter(ig => ig.vehicle == '680A' && ig.part == mappingPartTypeId && ig.p == 'j' && ig.i == 'tenaoshi' && !ig.disabled).length !== 0 ? '' : 'disable'} ${(p === 'j' && i === 'tenaoshi') ? 'active' : ''}`}
                  onClick={() => {
                    let newJi = [];
                    if (ji.indexOf(14) >= 0) {
                      ji.splice(ji.indexOf(14), 1);
                      newJi = ji;
                    } else {
                      newJi = [14, ...ji]  ;                  
                    }

                    this.setState({p: 'j', ji: newJi});
                  }}
                >
                  手直 {p === 'j' && i !== 'inline' && ji.indexOf(14) >= 0 && <div className="icon-check purple">️</div>}
                </li>
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
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({}, partFamilyActions, mappingActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Association);
