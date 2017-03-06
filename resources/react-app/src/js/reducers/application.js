import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../middleware/fetchMiddleware';

export const LOG_IN = 'LOG_IN';
export const LOG_OUT = 'LOG_OUT';
export const CHANGE_VEHICLE = 'CHANGE_VEHICLE';

export const REDUEST_950A_INITIAL_DATA = 'REDUEST_950A_INITIAL_DATA';
export const REDUEST_950A_INITIAL_DATA_SUCCESS = 'REDUEST_950A_INITIAL_DATA_SUCCESS';
export const REDUEST_950A_INITIAL_DATA_FAIL = 'REDUEST_950A_INITIAL_DATA_FAIL';

const initialState = {
  master: false,
  vehicle: '680A',
  vehicle950A: {
    chokus: [],
    processes: [],
    inspections: [],
    partTypes: [],
    combination: []
  },
  isFetching: false,
  didInvalidate: false
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case LOG_IN:
      return Object.assign({}, state, {
        master: true
      });

    case LOG_OUT:
      return Object.assign({}, state, {
        master: false
      });

    case CHANGE_VEHICLE:
      return Object.assign({}, state, {
        vehicle: action.payload.vehicle
      });

    case REDUEST_950A_INITIAL_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REDUEST_950A_INITIAL_DATA_SUCCESS:
      return Object.assign({}, state, {
        vehicle950A: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_950A_INITIAL_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    default:
      return state;
  }
}

export function login() {
  return {
    type: LOG_IN
  };
}

export function logout() {
  return {
    type: LOG_OUT
  };
}

export function changeVehicle(vehicle) {
  return {
    type: CHANGE_VEHICLE,
    payload: { vehicle }
  };
}

export function get950AInitial() {
  return {
    [CALL_API]: {
      types: [
        REDUEST_950A_INITIAL_DATA,
        REDUEST_950A_INITIAL_DATA_SUCCESS,
        REDUEST_950A_INITIAL_DATA_FAIL
      ],
      endpoint: 'manager/950A/initial',
      method: 'GET',
      body: null
    }
  };
}

export const applicationActions = {
  login,
  logout,
  changeVehicle,
  get950AInitial
};
