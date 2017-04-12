import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../../middleware/fetchMiddleware';

export const REDUEST_PART_FAMILY_DATA = 'REDUEST_PART_FAMILY_DATA';
export const REDUEST_PART_FAMILY_DATA_SUCCESS = 'REDUEST_PART_FAMILY_DATA_SUCCESS';
export const REDUEST_PART_FAMILY_DATA_FAIL = 'REDUEST_PART_FAMILY_DATA_FAIL';

export const UPDATE_PART_FAMILY_DATA = 'UPDATE_PART_FAMILY_DATA';
export const UPDATE_PART_FAMILY_DATA_SUCCESS = 'UPDATE_PART_FAMILY_DATA_SUCCESS';
export const UPDATE_PART_FAMILY_DATA_FAIL = 'UPDATE_PART_FAMILY_DATA_FAIL';

export const CLEAR_ERROR_PART = 'CLEAR_ERROR_PART';
export const CLEAR_PART_FAMILY_DATA = 'CLEAR_PART_FAMILY_DATA';


const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false,
  errorParts: null
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case REDUEST_PART_FAMILY_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REDUEST_PART_FAMILY_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_PART_FAMILY_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    case UPDATE_PART_FAMILY_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case UPDATE_PART_FAMILY_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false,
        errorParts: action.payload.parts
      });

    case UPDATE_PART_FAMILY_DATA_FAIL:
    console.log(action.payload);
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true,
        errorParts: null
      });

    case CLEAR_ERROR_PART:
      return Object.assign({}, state, {
        errorParts: null
      });

    case CLEAR_PART_FAMILY_DATA:
      return Object.assign({}, state, {
        data: null
      });

    default:
      return state;
  }
}

export function getPartFamilyByDate(type, start, end) {
  return {
    [CALL_API]: {
      types: [
        REDUEST_PART_FAMILY_DATA,
        REDUEST_PART_FAMILY_DATA_SUCCESS,
        REDUEST_PART_FAMILY_DATA_FAIL
      ],
      endpoint: '/manager/950A/association/family/date',
      method: 'POST',
      body: { type, start, end }
    }
  };
}

export function getPartFamilyByPanelId(pn, panelId) {
  return {
    [CALL_API]: {
      types: [
        REDUEST_PART_FAMILY_DATA,
        REDUEST_PART_FAMILY_DATA_SUCCESS,
        REDUEST_PART_FAMILY_DATA_FAIL
      ],
      endpoint: '/manager/950A/association/family/panelId',
      method: 'POST',
      body: { pn, panelId }
    }
  };
}

export function updatePartFamily(id, parts) {
  return {
    [CALL_API]: {
      types: [
        UPDATE_PART_FAMILY_DATA,
        UPDATE_PART_FAMILY_DATA_SUCCESS,
        UPDATE_PART_FAMILY_DATA_FAIL
      ],
      endpoint: '/manager/950A/association/family/update',
      method: 'POST',
      body: { id, parts }
    }
  };
}

export function clearErrorPart() {
  return {
    type: CLEAR_ERROR_PART
  }
}

export function clearPartFamilyData() {
  return {
    type: CLEAR_PART_FAMILY_DATA
  }
}

export const partFamilyActions = {
  getPartFamilyByDate,
  getPartFamilyByPanelId,
  updatePartFamily,
  clearErrorPart,
  clearPartFamilyData
};
