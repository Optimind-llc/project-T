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

import InspectorData from '../modules/inspector/ducks/inspector';

import AllFailureData from '../modules/failure/ducks/failure';

import AllHoleData from '../modules/hole/ducks/hole';


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
  InspectorData,
  AllFailureData,
  AllHoleData,
  routing: routerReducer
}));

export default rootReducer;
