import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../middleware/fetchMiddleware';

export const REDUEST_INSPECTORS_DATA = 'REDUEST_INSPECTORS_DATA';
export const REDUEST_INSPECTORS_DATA_SUCCESS = 'REDUEST_INSPECTORS_DATA_SUCCESS';
export const REDUEST_INSPECTORS_DATA_FAIL = 'REDUEST_INSPECTORS_DATA_FAIL';
export const UPDATE_INSPECTORS_DATA = 'UPDATE_INSPECTORS_DATA';
export const UPDATE_INSPECTORS_DATA_SUCCESS = 'UPDATE_INSPECTORS_DATA_SUCCESS';
export const UPDATE_INSPECTORS_DATA_FAIL = 'UPDATE_INSPECTORS_DATA_FAIL';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false,
  updating: false,
  updated: false,
  message: ''
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case REDUEST_INSPECTORS_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false,
        message: ''
      });

    case REDUEST_INSPECTORS_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_INSPECTORS_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true,
        message: action.payload.message
      });

    case UPDATE_INSPECTORS_DATA:
      return Object.assign({}, state, {
        updating: true,
        updated: false,
        message: ''
      });

    case UPDATE_INSPECTORS_DATA_SUCCESS:
      return Object.assign({}, state, {
        updating: false,
        updated: true
      });

    case UPDATE_INSPECTORS_DATA_FAIL:
    console.log(action);
      return Object.assign({}, state, {
        updating: false,
        updated: false,
        message: action.payload.message
      });

    default:
      return state;
  }
}

export function getInspectors(yomi, choku, itionG, status) {
  return {
    [CALL_API]: {
      types: [
        REDUEST_INSPECTORS_DATA,
        REDUEST_INSPECTORS_DATA_SUCCESS,
        REDUEST_INSPECTORS_DATA_FAIL
      ],
      endpoint: 'maintenance/inspector',
      method: 'POST',
      body: {yomi, choku, itionG, status}
    }
  };
}

export function updateInspector(id, name, yomi, choku, itionG) {
  return {
    [CALL_API]: {
      types: [
        UPDATE_INSPECTORS_DATA,
        UPDATE_INSPECTORS_DATA_SUCCESS,
        UPDATE_INSPECTORS_DATA_FAIL
      ],
      endpoint: 'maintenance/inspector/update',
      method: 'POST',
      body: {id, name, yomi, choku, itionG}
    }
  };
}

export const inspectorActions = {
  getInspectors,
  updateInspector
};
