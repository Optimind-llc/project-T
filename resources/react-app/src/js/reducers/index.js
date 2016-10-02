import { combineReducers } from 'redux';
import { routeReducer } from 'react-router-redux';
//my reducers
// import application from './application';
import alert from './alert';
import VeItorGData from '../modules/report/ducks/report';
import ItionGData from '../modules/report/ducks/inspectionGroup';

const rootReducer = combineReducers(Object.assign({
  alert,
  VeItorGData,
  ItionGData
}));

export default rootReducer;
