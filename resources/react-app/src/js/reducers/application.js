import {fromJS, Map as iMap, List as iList} from 'immutable';

export const LOG_IN = 'LOG_IN';
export const LOG_OUT = 'LOG_OUT';

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

export const applicationActions = {
  login,
  logout
};
