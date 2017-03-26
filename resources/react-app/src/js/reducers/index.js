import { combineReducers } from 'redux';
import { routerReducer } from 'react-router-redux';
// My reducers
import Application from './application';
import alert from './alert';

import VehicleData from '../modules/dashboard/ducks/vehicle';
import ItorGData from '../modules/dashboard/ducks/itorG';
import PageTData from '../modules/dashboard/ducks/pageT';
import PageData from '../modules/dashboard/ducks/page';

import SerchedData from '../modules/reference/ducks/serch';
import FailureData from '../modules/reference/ducks/failure';
import ModificationData from '../modules/reference/ducks/modification';

import VeItorGProcData from '../modules/report/ducks/report';
import ItionGData from '../modules/report/ducks/inspectionGroup';

import PartFData from '../modules/association/ducks/partF';
import UpdatePartFData from '../modules/association/ducks/updatePartF';
import MappingData from '../modules/association/ducks/mapping';

import InspectorData from '../modules/inspector/ducks/inspector';
import maintFailureData from '../modules/failure/ducks/failure';
import maintModificationData from '../modules/modification/ducks/modification';
import maintHoleData from '../modules/hole/ducks/hole';

// 950A
import MappingData950A from '../modules/Vehicle950A/mapping/ducks/mapping';
import ReferenceData950A from '../modules/Vehicle950A/reference/ducks/reference';
import ReportData950A from '../modules/Vehicle950A/report/ducks/report';

const rootReducer = combineReducers(Object.assign({
  Application,
  alert,
  VehicleData,
  ItorGData,
  PageTData,
  PageData,
  SerchedData,
  FailureData,
  ModificationData,
  VeItorGProcData,
  ItionGData,
  PartFData,
  UpdatePartFData,
  MappingData,
  InspectorData,
  maintFailureData,
  maintModificationData,
  maintHoleData,
  MappingData950A,
  ReferenceData950A,
  ReportData950A,
  routing: routerReducer
}));

export default rootReducer;
