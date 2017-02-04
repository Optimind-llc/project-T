import {fromJS, Map as iMap, List as iList} from 'immutable';

export const LOG_IN = 'LOG_IN';
export const LOG_OUT = 'LOG_OUT';
export const CHANGE_VEHICLE = 'CHANGE_VEHICLE';

const initialState = {
  master: false,
  vehicle: '680A'
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

export const applicationActions = {
  login,
  logout,
  changeVehicle
};
