import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../middleware/fetchMiddleware';

export const REDUEST_HOLES_DATA = 'REDUEST_HOLES_DATA';
export const REDUEST_HOLES_DATA_SUCCESS = 'REDUEST_HOLES_DATA_SUCCESS';
export const REDUEST_HOLES_DATA_FAIL = 'REDUEST_HOLES_DATA_FAIL';

export const CREATE_HOLE_DATA = 'CREATE_HOLE_DATA';
export const CREATE_HOLE_DATA_SUCCESS = 'CREATE_HOLE_DATA_SUCCESS';
export const CREATE_HOLE_DATA_FAIL = 'CREATE_HOLE_DATA_FAIL';

export const UPDATE_HOLE_DATA = 'UPDATE_HOLE_DATA';
export const UPDATE_HOLE_DATA_SUCCESS = 'UPDATE_HOLE_DATA_SUCCESS';
export const UPDATE_HOLE_DATA_FAIL = 'UPDATE_HOLE_DATA_FAIL';

export const ACTIVATE_HOLE_DATA = 'ACTIVATE_HOLE_DATA';
export const ACTIVATE_HOLE_DATA_SUCCESS = 'ACTIVATE_HOLE_DATA_SUCCESS';
export const ACTIVATE_HOLE_DATA_FAIL = 'ACTIVATE_HOLE_DATA_FAIL';

export const DEACTIVATE_HOLE_DATA = 'DEACTIVATE_HOLE_DATA';
export const DEACTIVATE_HOLE_DATA_SUCCESS = 'DEACTIVATE_HOLE_DATA_SUCCESS';
export const DEACTIVATE_HOLE_DATA_FAIL = 'DEACTIVATE_HOLE_DATA_FAIL';


const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false,
  isUpdating: false,
  didUpdated: false,
  message: ''
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case REDUEST_HOLES_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REDUEST_HOLES_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_HOLES_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    case CREATE_HOLE_DATA:
    case UPDATE_HOLE_DATA:
    case ACTIVATE_HOLE_DATA:
    case DEACTIVATE_HOLE_DATA:
      return Object.assign({}, state, {
        updating: true,
        updated: false,
        message: '',
        meta: null
      });

    case CREATE_HOLE_DATA_SUCCESS:
    case UPDATE_HOLE_DATA_SUCCESS:
    case ACTIVATE_HOLE_DATA_SUCCESS:
    case DEACTIVATE_HOLE_DATA_SUCCESS:
      return Object.assign({}, state, {
        updating: false,
        updated: true
      });

    case CREATE_HOLE_DATA_FAIL:
    case UPDATE_HOLE_DATA_FAIL:
    case ACTIVATE_HOLE_DATA_FAIL:
    case DEACTIVATE_HOLE_DATA_FAIL:
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

export function getHoles(figureId, status) {
  return {
    [CALL_API]: {
      types: [
        REDUEST_HOLES_DATA,
        REDUEST_HOLES_DATA_SUCCESS,
        REDUEST_HOLES_DATA_FAIL
      ],
      endpoint: 'maintenance/holes',
      method: 'POST',
      body: {figureId, status}
    }
  };
}

export function createHole(name, label, inspections) {
  return {
    [CALL_API]: {
      types: [
        CREATE_HOLE_DATA,
        CREATE_HOLE_DATA_SUCCESS,
        CREATE_HOLE_DATA_FAIL
      ],
      endpoint: 'maintenance/hole/create',
      method: 'POST',
      body: {name, label, inspections}
    }
  };
}

export function updateHole(id, name, label, inspections) {
  return {
    [CALL_API]: {
      types: [
        UPDATE_HOLE_DATA,
        UPDATE_HOLE_DATA_SUCCESS,
        UPDATE_HOLE_DATA_FAIL
      ],
      endpoint: 'maintenance/hole/update',
      method: 'POST',
      body: {id, name, label, inspections}
    }
  };
}

export function activateHole(id) {
  return {
    [CALL_API]: {
      types: [
        ACTIVATE_HOLE_DATA,
        ACTIVATE_HOLE_DATA_SUCCESS,
        ACTIVATE_HOLE_DATA_FAIL
      ],
      endpoint: `maintenance/hole/${id}/activate`,
      method: 'POST',
      body: null
    }
  };
}

export function deactivateHole(id) {
  return {
    [CALL_API]: {
      types: [
        DEACTIVATE_HOLE_DATA,
        DEACTIVATE_HOLE_DATA_SUCCESS,
        DEACTIVATE_HOLE_DATA_FAIL
      ],
      endpoint: `maintenance/hole/${id}/deactivate`,
      method: 'POST',
      body: null
    }
  };
}

export const holeActions = {
  getHoles,
  createHole,
  updateHole,
  activateHole,
  deactivateHole
};