import { combineReducers } from 'redux';
import { routerReducer } from 'react-router-redux';
// My reducers
import alert from './alert';
import VehicleData from '../modules/dashboard/ducks/vehicle';
import ItorGData from '../modules/dashboard/ducks/itorG';
import PageTData from '../modules/dashboard/ducks/pageT';

import PageData from '../modules/dashboard/ducks/page';

import VeItorGProcData from '../modules/report/ducks/report';
import ItionGData from '../modules/report/ducks/inspectionGroup';

const rootReducer = combineReducers(Object.assign({
  alert,
  VehicleData,
  ItorGData,
  PageTData,
  PageData,
  VeItorGProcData,
  ItionGData,
  routing: routerReducer
}));

export default rootReducer;
