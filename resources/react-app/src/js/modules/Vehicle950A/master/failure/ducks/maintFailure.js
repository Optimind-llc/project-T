import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../../../middleware/fetchMiddleware';

export const REDUEST_FAILURE_DATA = 'REDUEST_FAILURE_DATA';
export const REDUEST_FAILURE_DATA_SUCCESS = 'REDUEST_FAILURE_DATA_SUCCESS';
export const REDUEST_FAILURE_DATA_FAIL = 'REDUEST_FAILURE_DATA_FAIL';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case REDUEST_FAILURE_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REDUEST_FAILURE_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_FAILURE_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    default:
      return state;
  }
}

export function requestFailures() {
  return {
    [CALL_API]: {
      types: [
        REDUEST_FAILURE_DATA,
        REDUEST_FAILURE_DATA_SUCCESS,
        REDUEST_FAILURE_DATA_FAIL
      ],
      endpoint: 'maintenance/950A/failures',
      method: 'GET',
      body: null
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
      endpoint: 'maintenance/950A/failure/create',
      method: 'POST',
      body: {name, label, inspections}
    }
  };
}

export function updateFailure(id, name, label, inspections) {
  return {
    [CALL_API]: {
      types: [
        UPDATE_FAILURES_DATA,
        UPDATE_FAILURES_DATA_SUCCESS,
        UPDATE_FAILURES_DATA_FAIL
      ],
      endpoint: 'maintenance/950A/failure/update',
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
      endpoint: `maintenance/950A/failure/${id}/activate`,
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
      endpoint: `maintenance/failure/950A/${id}/deactivate`,
      method: 'POST',
      body: null
    }
  };
}

export const pageActions = {
  requestFailures,
};
