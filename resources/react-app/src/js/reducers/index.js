import { combineReducers } from 'redux';
import { routeReducer } from 'react-router-redux';
//my reducers
// import application from './application';
import alert from './alert';
import VEandITORGdata from '../modules/report/ducks/report';


const rootReducer = combineReducers(Object.assign({
  alert,
  VEandITORGdata
}));

export default rootReducer;
