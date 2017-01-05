import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../middleware/fetchMiddleware';

export const REDUEST_MODIFICATIONS_DATA = 'REDUEST_MODIFICATIONS_DATA';
export const REDUEST_MODIFICATIONS_DATA_SUCCESS = 'REDUEST_MODIFICATIONS_DATA_SUCCESS';
export const REDUEST_MODIFICATIONS_DATA_FAIL = 'REDUEST_MODIFICATIONS_DATA_FAIL';

export const CREATE_MODIFICATIONS_DATA = 'CREATE_MODIFICATIONS_DATA';
export const CREATE_MODIFICATIONS_DATA_SUCCESS = 'CREATE_MODIFICATIONS_DATA_SUCCESS';
export const CREATE_MODIFICATIONS_DATA_FAIL = 'CREATE_MODIFICATIONS_DATA_FAIL';

export const UPDATE_MODIFICATIONS_DATA = 'UPDATE_MODIFICATIONS_DATA';
export const UPDATE_MODIFICATIONS_DATA_SUCCESS = 'UPDATE_MODIFICATIONS_DATA_SUCCESS';
export const UPDATE_MODIFICATIONS_DATA_FAIL = 'UPDATE_MODIFICATIONS_DATA_FAIL';

export const ACTIVATE_MODIFICATIONS_DATA = 'ACTIVATE_MODIFICATIONS_DATA';
export const ACTIVATE_MODIFICATIONS_DATA_SUCCESS = 'ACTIVATE_MODIFICATIONS_DATA_SUCCESS';
export const ACTIVATE_MODIFICATIONS_DATA_FAIL = 'ACTIVATE_MODIFICATIONS_DATA_FAIL';

export const DEACTIVATE_MODIFICATIONS_DATA = 'DEACTIVATE_MODIFICATIONS_DATA';
export const DEACTIVATE_MODIFICATIONS_DATA_SUCCESS = 'DEACTIVATE_MODIFICATIONS_DATA_SUCCESS';
export const DEACTIVATE_MODIFICATIONS_DATA_FAIL = 'DEACTIVATE_MODIFICATIONS_DATA_FAIL';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false,
  updating: false,
  updated: false,
  message: '',
  meta: null
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case REDUEST_MODIFICATIONS_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REDUEST_MODIFICATIONS_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_MODIFICATIONS_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    case CREATE_MODIFICATIONS_DATA:
    case UPDATE_MODIFICATIONS_DATA:
    case ACTIVATE_MODIFICATIONS_DATA:
    case DEACTIVATE_MODIFICATIONS_DATA:
      return Object.assign({}, state, {
        updating: true,
        updated: false,
        message: '',
        meta: null
      });

    case CREATE_MODIFICATIONS_DATA_SUCCESS:
    case UPDATE_MODIFICATIONS_DATA_SUCCESS:
    case ACTIVATE_MODIFICATIONS_DATA_SUCCESS:
    case DEACTIVATE_MODIFICATIONS_DATA_SUCCESS:
      return Object.assign({}, state, {
        updating: false,
        updated: true
      });

    case CREATE_MODIFICATIONS_DATA_FAIL:
    case UPDATE_MODIFICATIONS_DATA_FAIL:
    case ACTIVATE_MODIFICATIONS_DATA_FAIL:
    case DEACTIVATE_MODIFICATIONS_DATA_FAIL:
      return Object.assign({}, state, {
        updating: false,
        updated: false,
        message: action.payload.message,
        meta: action.payload.meta
      });

    default:
      return state;
  }
}

export function getModifications(name, inspection, status) {
  return {
    [CALL_API]: {
      types: [
        REDUEST_MODIFICATIONS_DATA,
        REDUEST_MODIFICATIONS_DATA_SUCCESS,
        REDUEST_MODIFICATIONS_DATA_FAIL
      ],
      endpoint: 'maintenance/modifications',
      method: 'POST',
      body: {name, inspection, status}
    }
  };
}

export function createModification(name, label, inspections) {
  return {
    [CALL_API]: {
      types: [
        CREATE_MODIFICATIONS_DATA,
        CREATE_MODIFICATIONS_DATA_SUCCESS,
        CREATE_MODIFICATIONS_DATA_FAIL
      ],
      endpoint: 'maintenance/modification/create',
      method: 'POST',
      body: {name, label, inspections}
    }
  };
}

export function updateModification(id, name, label, inspections) {
  console.log(id, name, label, inspections)
  return {
    [CALL_API]: {
      types: [
        UPDATE_MODIFICATIONS_DATA,
        UPDATE_MODIFICATIONS_DATA_SUCCESS,
        UPDATE_MODIFICATIONS_DATA_FAIL
      ],
      endpoint: 'maintenance/modification/update',
      method: 'POST',
      body: {id, name, label, inspections}
    }
  };
}

export function activateModification(id) {
  return {
    [CALL_API]: {
      types: [
        ACTIVATE_MODIFICATIONS_DATA,
        ACTIVATE_MODIFICATIONS_DATA_SUCCESS,
        ACTIVATE_MODIFICATIONS_DATA_FAIL
      ],
      endpoint: `maintenance/modification/${id}/activate`,
      method: 'POST',
      body: null
    }
  };
}

export function deactivateModification(id) {
  return {
    [CALL_API]: {
      types: [
        DEACTIVATE_MODIFICATIONS_DATA,
        DEACTIVATE_MODIFICATIONS_DATA_SUCCESS,
        DEACTIVATE_MODIFICATIONS_DATA_FAIL
      ],
      endpoint: `maintenance/modification/${id}/deactivate`,
      method: 'POST',
      body: null
    }
  };
}

export const modificationActions = {
  getModifications,
  createModification,
  updateModification,
  activateModification,
  deactivateModification
};
