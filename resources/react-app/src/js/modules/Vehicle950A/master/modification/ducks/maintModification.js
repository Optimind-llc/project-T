import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../../../middleware/fetchMiddleware';

export const REDUEST_MODIFICATION_DATA = 'REDUEST_MODIFICATION_DATA';
export const REDUEST_MODIFICATION_DATA_SUCCESS = 'REDUEST_MODIFICATION_DATA_SUCCESS';
export const REDUEST_MODIFICATION_DATA_FAIL = 'REDUEST_MODIFICATION_DATA_FAIL';

export const ACTIVATE_MODIFICATION_DATA = 'ACTIVATE_MODIFICATION_DATA';
export const ACTIVATE_MODIFICATION_DATA_SUCCESS = 'ACTIVATE_MODIFICATION_DATA_SUCCESS';
export const ACTIVATE_MODIFICATION_DATA_FAIL = 'ACTIVATE_MODIFICATION_DATA_FAIL';

export const DEACTIVATE_MODIFICATION_DATA = 'DEACTIVATE_MODIFICATION_DATA';
export const DEACTIVATE_MODIFICATION_DATA_SUCCESS = 'DEACTIVATE_MODIFICATION_DATA_SUCCESS';
export const DEACTIVATE_MODIFICATION_DATA_FAIL = 'DEACTIVATE_MODIFICATION_DATA_FAIL';

export const CREATE_MODIFICATION_DATA = 'CREATE_MODIFICATION_DATA';
export const CREATE_MODIFICATION_DATA_SUCCESS = 'CREATE_MODIFICATION_DATA_SUCCESS';
export const CREATE_MODIFICATION_DATA_FAIL = 'CREATE_MODIFICATION_DATA_FAIL';

export const UPDATE_MODIFICATION_DATA = 'UPDATE_MODIFICATION_DATA';
export const UPDATE_MODIFICATION_DATA_SUCCESS = 'UPDATE_MODIFICATION_DATA_SUCCESS';
export const UPDATE_MODIFICATION_DATA_FAIL = 'UPDATE_MODIFICATION_DATA_FAIL';

export const CLEAR_MESSAGE = 'CLEAR_MESSAGE';

const initialState = {
  data: null,
  message: null,
  isFetching: false,
  didInvalidate: false
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case REDUEST_MODIFICATION_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REDUEST_MODIFICATION_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_MODIFICATION_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    case CREATE_MODIFICATION_DATA:
      return Object.assign({}, state, {
        message: null,
        isFetching: true,
        didInvalidate: false
      });

    case CREATE_MODIFICATION_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        message: action.payload.message,
        isFetching: false,
        didInvalidate: false
      });

    case CREATE_MODIFICATION_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    case UPDATE_MODIFICATION_DATA:
      return Object.assign({}, state, {
        message: null,
        isFetching: true,
        didInvalidate: false
      });

    case UPDATE_MODIFICATION_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        message: action.payload.message,
        isFetching: false,
        didInvalidate: false
      });

    case UPDATE_MODIFICATION_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    case ACTIVATE_MODIFICATION_DATA:
      return Object.assign({}, state, {
        message: null,
        isFetching: true,
        didInvalidate: false
      });

    case ACTIVATE_MODIFICATION_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        message: action.payload.message,
        isFetching: false,
        didInvalidate: false
      });

    case ACTIVATE_MODIFICATION_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    case DEACTIVATE_MODIFICATION_DATA:
      return Object.assign({}, state, {
        message: null,
        isFetching: true,
        didInvalidate: false
      });

    case DEACTIVATE_MODIFICATION_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        message: action.payload.message,
        isFetching: false,
        didInvalidate: false
      });

    case DEACTIVATE_MODIFICATION_DATA_FAIL:
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

export function requestModifications() {
  return {
    [CALL_API]: {
      types: [
        REDUEST_MODIFICATION_DATA,
        REDUEST_MODIFICATION_DATA_SUCCESS,
        REDUEST_MODIFICATION_DATA_FAIL
      ],
      endpoint: 'manager/950A/maintenance/modificationTypes',
      method: 'GET',
      body: null
    }
  };
}

export function createModification(name, label, inspections) {
  return {
    [CALL_API]: {
      types: [
        CREATE_MODIFICATION_DATA,
        CREATE_MODIFICATION_DATA_SUCCESS,
        CREATE_MODIFICATION_DATA_FAIL
      ],
      endpoint: 'manager/950A/maintenance/modificationType/create',
      method: 'POST',
      body: {name, label, inspections}
    }
  };
}

export function updateModification(id, name, label, inspections) {
  return {
    [CALL_API]: {
      types: [
        UPDATE_MODIFICATION_DATA,
        UPDATE_MODIFICATION_DATA_SUCCESS,
        UPDATE_MODIFICATION_DATA_FAIL
      ],
      endpoint: 'manager/950A/maintenance/modificationType/update',
      method: 'POST',
      body: {id, name, label, inspections}
    }
  };
}

export function clearMessage() {
  return {
    type: CLEAR_MESSAGE
  }
}

export const maintModificationActions = {
  requestModifications,
  createModification,
  updateModification,
  clearMessage
};
