import { combineReducers } from 'redux';
import { routerReducer } from 'react-router-redux';
//my reducers
// import application from './application';
import alert from './alert';
import VeItorGProcData from '../modules/report/ducks/report';
import ItionGData from '../modules/report/ducks/inspectionGroup';
import AllItionGData from '../modules/dashboard/ducks/process';
import PageTData from '../modules/dashboard/ducks/pageType';
import PageData from '../modules/mapping/ducks/page';

const rootReducer = combineReducers(Object.assign({
  alert,
  VeItorGProcData,
  ItionGData,
  AllItionGData,
  PageTData,
  PageData,
  routing: routerReducer
}));

export default rootReducer;
