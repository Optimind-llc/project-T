import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../../../middleware/fetchMiddleware';

export const REDUEST_WORKER_DATA = 'REDUEST_WORKER_DATA';
export const REDUEST_WORKER_DATA_SUCCESS = 'REDUEST_WORKER_DATA_SUCCESS';
export const REDUEST_WORKER_DATA_FAIL = 'REDUEST_WORKER_DATA_FAIL';

export const CREATE_WORKER_DATA = 'CREATE_WORKER_DATA';
export const CREATE_WORKER_DATA_SUCCESS = 'CREATE_WORKER_DATA_SUCCESS';
export const CREATE_WORKER_DATA_FAIL = 'CREATE_WORKER_DATA_FAIL';

export const UPDATE_WORKER_DATA = 'UPDATE_WORKER_DATA';
export const UPDATE_WORKER_DATA_SUCCESS = 'UPDATE_WORKER_DATA_SUCCESS';
export const UPDATE_WORKER_DATA_FAIL = 'UPDATE_WORKER_DATA_FAIL';

export const ACTIVATE_WORKER_DATA = 'ACTIVATE_WORKER_DATA';
export const ACTIVATE_WORKER_DATA_SUCCESS = 'ACTIVATE_WORKER_DATA_SUCCESS';
export const ACTIVATE_WORKER_DATA_FAIL = 'ACTIVATE_WORKER_DATA_FAIL';

export const DEACTIVATE_WORKER_DATA = 'DEACTIVATE_WORKER_DATA';
export const DEACTIVATE_WORKER_DATA_SUCCESS = 'DEACTIVATE_WORKER_DATA_SUCCESS';
export const DEACTIVATE_WORKER_DATA_FAIL = 'DEACTIVATE_WORKER_DATA_FAIL';


export const CLEAR_MESSAGE = 'CLEAR_MESSAGE';

const initialState = {
  data: null,
  message: null,
  isFetching: false,
  didInvalidate: false
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case REDUEST_WORKER_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REDUEST_WORKER_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_WORKER_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    case CREATE_WORKER_DATA:
      return Object.assign({}, state, {
        message: null,
        isFetching: true,
        didInvalidate: false
      });

    case CREATE_WORKER_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        message: action.payload.message,
        isFetching: false,
        didInvalidate: false
      });

    case CREATE_WORKER_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    case UPDATE_WORKER_DATA:
      return Object.assign({}, state, {
        message: null,
        isFetching: true,
        didInvalidate: false
      });

    case UPDATE_WORKER_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        message: action.payload.message,
        isFetching: false,
        didInvalidate: false
      });

    case UPDATE_WORKER_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    case ACTIVATE_WORKER_DATA:
      return Object.assign({}, state, {
        message: null,
        isFetching: true,
        didInvalidate: false
      });

    case ACTIVATE_WORKER_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        message: action.payload.message,
        isFetching: false,
        didInvalidate: false
      });

    case ACTIVATE_WORKER_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    case DEACTIVATE_WORKER_DATA:
      return Object.assign({}, state, {
        message: null,
        isFetching: true,
        didInvalidate: false
      });

    case DEACTIVATE_WORKER_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        message: action.payload.message,
        isFetching: false,
        didInvalidate: false
      });

    case DEACTIVATE_WORKER_DATA_FAIL:
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

export function requestWorkers() {
  return {
    [CALL_API]: {
      types: [
        REDUEST_WORKER_DATA,
        REDUEST_WORKER_DATA_SUCCESS,
        REDUEST_WORKER_DATA_FAIL
      ],
      endpoint: 'manager/950A/maintenance/workers',
      method: 'GET',
      body: null
    }
  };
}

export function createWorker(name, yomi, choku, inspections) {
  return {
    [CALL_API]: {
      types: [
        CREATE_WORKER_DATA,
        CREATE_WORKER_DATA_SUCCESS,
        CREATE_WORKER_DATA_FAIL
      ],
      endpoint: 'manager/950A/maintenance/worker/create',
      method: 'POST',
      body: {name, yomi, choku, inspections}
    }
  };
}

export function updateWorker(id, name, yomi, choku, inspections) {
  return {
    [CALL_API]: {
      types: [
        UPDATE_WORKER_DATA,
        UPDATE_WORKER_DATA_SUCCESS,
        UPDATE_WORKER_DATA_FAIL
      ],
      endpoint: 'manager/950A/maintenance/worker/update',
      method: 'POST',
      body: {id, name, yomi, choku, inspections}
    }
  };
}

export function clearMessage() {
  return {
    type: CLEAR_MESSAGE
  }
}

export const maintWorkerActions = {
  requestWorkers,
  createWorker,
  updateWorker,
  clearMessage
};
