import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../middleware/fetchMiddleware';

export const REDUEST_FAILURES_DATA = 'REDUEST_FAILURES_DATA';
export const REDUEST_FAILURES_DATA_SUCCESS = 'REDUEST_FAILURES_DATA_SUCCESS';
export const REDUEST_FAILURES_DATA_FAIL = 'REDUEST_FAILURES_DATA_FAIL';

export const CREATE_FAILURES_DATA = 'CREATE_FAILURES_DATA';
export const CREATE_FAILURES_DATA_SUCCESS = 'CREATE_FAILURES_DATA_SUCCESS';
export const CREATE_FAILURES_DATA_FAIL = 'CREATE_FAILURES_DATA_FAIL';

export const UPDATE_FAILURES_DATA = 'UPDATE_FAILURES_DATA';
export const UPDATE_FAILURES_DATA_SUCCESS = 'UPDATE_FAILURES_DATA_SUCCESS';
export const UPDATE_FAILURES_DATA_FAIL = 'UPDATE_FAILURES_DATA_FAIL';

export const ACTIVATE_FAILURES_DATA = 'ACTIVATE_FAILURES_DATA';
export const ACTIVATE_FAILURES_DATA_SUCCESS = 'ACTIVATE_FAILURES_DATA_SUCCESS';
export const ACTIVATE_FAILURES_DATA_FAIL = 'ACTIVATE_FAILURES_DATA_FAIL';

export const DEACTIVATE_FAILURES_DATA = 'DEACTIVATE_FAILURES_DATA';
export const DEACTIVATE_FAILURES_DATA_SUCCESS = 'DEACTIVATE_FAILURES_DATA_SUCCESS';
export const DEACTIVATE_FAILURES_DATA_FAIL = 'DEACTIVATE_FAILURES_DATA_FAIL';

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
    case REDUEST_FAILURES_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REDUEST_FAILURES_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_FAILURES_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    case CREATE_FAILURES_DATA:
    case UPDATE_FAILURES_DATA:
    case ACTIVATE_FAILURES_DATA:
    case DEACTIVATE_FAILURES_DATA:
      return Object.assign({}, state, {
        updating: true,
        updated: false,
        message: '',
        meta: null
      });

    case CREATE_FAILURES_DATA_SUCCESS:
    case UPDATE_FAILURES_DATA_SUCCESS:
    case ACTIVATE_FAILURES_DATA_SUCCESS:
    case DEACTIVATE_FAILURES_DATA_SUCCESS:
      return Object.assign({}, state, {
        updating: false,
        updated: true
      });

    case CREATE_FAILURES_DATA_FAIL:
    case UPDATE_FAILURES_DATA_FAIL:
    case ACTIVATE_FAILURES_DATA_FAIL:
    case DEACTIVATE_FAILURES_DATA_FAIL:
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

export function getFailures(name, inspection, status) {
  return {
    [CALL_API]: {
      types: [
        REDUEST_FAILURES_DATA,
        REDUEST_FAILURES_DATA_SUCCESS,
        REDUEST_FAILURES_DATA_FAIL
      ],
      endpoint: 'maintenance/failures',
      method: 'POST',
      body: {name, inspection, status}
    }
  };
}

export function createFailure(name, label, inspections) {
  return {
    [CALL_API]: {
      types: [
        CREATE_FAILURES_DATA,
        CREATE_FAILURES_DATA_SUCCESS,
        CREATE_FAILURES_DATA_FAIL
      ],
      endpoint: 'maintenance/failure/create',
      method: 'POST',
      body: {name, label, inspections}
    }
  };
}

export function updateFailure(id, name, label, inspections) {
  console.log(id, name, label, inspections)
  return {
    [CALL_API]: {
      types: [
        UPDATE_FAILURES_DATA,
        UPDATE_FAILURES_DATA_SUCCESS,
        UPDATE_FAILURES_DATA_FAIL
      ],
      endpoint: 'maintenance/failure/update',
      method: 'POST',
      body: {id, name, label, inspections}
    }
  };
}

export function activateFailure(id) {
  return {
    [CALL_API]: {
      types: [
        ACTIVATE_FAILURES_DATA,
        ACTIVATE_FAILURES_DATA_SUCCESS,
        ACTIVATE_FAILURES_DATA_FAIL
      ],
      endpoint: `maintenance/failure/${id}/activate`,
      method: 'POST',
      body: null
    }
  };
}

export function deactivateFailure(id) {
  return {
    [CALL_API]: {
      types: [
        DEACTIVATE_FAILURES_DATA,
        DEACTIVATE_FAILURES_DATA_SUCCESS,
        DEACTIVATE_FAILURES_DATA_FAIL
      ],
      endpoint: `maintenance/failure/${id}/deactivate`,
      method: 'POST',
      body: null
    }
  };
}

export const failureActions = {
  getFailures,
  createFailure,
  updateFailure,
  activateFailure,
  deactivateFailure
};
