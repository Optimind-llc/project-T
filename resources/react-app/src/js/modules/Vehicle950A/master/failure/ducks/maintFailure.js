import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../../../middleware/fetchMiddleware';

export const REDUEST_FAILURE_DATA = 'REDUEST_FAILURE_DATA';
export const REDUEST_FAILURE_DATA_SUCCESS = 'REDUEST_FAILURE_DATA_SUCCESS';
export const REDUEST_FAILURE_DATA_FAIL = 'REDUEST_FAILURE_DATA_FAIL';

export const ACTIVATE_FAILURES_DATA = 'ACTIVATE_FAILURES_DATA';
export const ACTIVATE_FAILURES_DATA_SUCCESS = 'ACTIVATE_FAILURES_DATA_SUCCESS';
export const ACTIVATE_FAILURES_DATA_FAIL = 'ACTIVATE_FAILURES_DATA_FAIL';

export const DEACTIVATE_FAILURES_DATA = 'DEACTIVATE_FAILURES_DATA';
export const DEACTIVATE_FAILURES_DATA_SUCCESS = 'DEACTIVATE_FAILURES_DATA_SUCCESS';
export const DEACTIVATE_FAILURES_DATA_FAIL = 'DEACTIVATE_FAILURES_DATA_FAIL';

export const CREATE_FAILURE_DATA = 'CREATE_FAILURE_DATA';
export const CREATE_FAILURE_DATA_SUCCESS = 'CREATE_FAILURE_DATA_SUCCESS';
export const CREATE_FAILURE_DATA_FAIL = 'CREATE_FAILURE_DATA_FAIL';

export const UPDATE_FAILURE_DATA = 'UPDATE_FAILURE_DATA';
export const UPDATE_FAILURE_DATA_SUCCESS = 'UPDATE_FAILURE_DATA_SUCCESS';
export const UPDATE_FAILURE_DATA_FAIL = 'UPDATE_FAILURE_DATA_FAIL';

export const CLEAR_MESSAGE = 'CLEAR_MESSAGE';

const initialState = {
  data: null,
  message: null,
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

    case CREATE_FAILURE_DATA:
      return Object.assign({}, state, {
        message: null,
        isFetching: true,
        didInvalidate: false
      });

    case CREATE_FAILURE_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        message: action.payload.message,
        isFetching: false,
        didInvalidate: false
      });

    case CREATE_FAILURE_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    case UPDATE_FAILURE_DATA:
      return Object.assign({}, state, {
        message: null,
        isFetching: true,
        didInvalidate: false
      });

    case UPDATE_FAILURE_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        message: action.payload.message,
        isFetching: false,
        didInvalidate: false
      });

    case UPDATE_FAILURE_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    case ACTIVATE_FAILURES_DATA:
      return Object.assign({}, state, {
        message: null,
        isFetching: true,
        didInvalidate: false
      });

    case ACTIVATE_FAILURES_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        message: action.payload.message,
        isFetching: false,
        didInvalidate: false
      });

    case ACTIVATE_FAILURES_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    case DEACTIVATE_FAILURES_DATA:
      return Object.assign({}, state, {
        message: null,
        isFetching: true,
        didInvalidate: false
      });

    case DEACTIVATE_FAILURES_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        message: action.payload.message,
        isFetching: false,
        didInvalidate: false
      });

    case DEACTIVATE_FAILURES_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    case CLEAR_MESSAGE:
      return Object.assign({}, state, {
        message: null
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
      endpoint: 'press/master/failureTypes',
      method: 'GET',
      body: null
    }
  };
}

export function createFailure(name, label) {
  return {
    [CALL_API]: {
      types: [
        CREATE_FAILURE_DATA,
        CREATE_FAILURE_DATA_SUCCESS,
        CREATE_FAILURE_DATA_FAIL
      ],
      endpoint: 'press/master/failureType/create',
      method: 'POST',
      body: {name, label}
    }
  };
}

export function updateFailure(id, name, label) {
  return {
    [CALL_API]: {
      types: [
        UPDATE_FAILURE_DATA,
        UPDATE_FAILURE_DATA_SUCCESS,
        UPDATE_FAILURE_DATA_FAIL
      ],
      endpoint: 'press/master/failureType/update',
      method: 'POST',
      body: {id, name, label}
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
      endpoint: 'press/master/failureType/activate',
      method: 'POST',
      body: {id}
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
      endpoint: 'press/master/failureType/deactivate',
      method: 'POST',
      body: {id}
    }
  };
}

export function clearMessage() {
  return {
    type: CLEAR_MESSAGE
  }
}

export const maintFailureActions = {
  requestFailures,
  createFailure,
  updateFailure,
  activateFailure,
  deactivateFailure,
  clearMessage
};
