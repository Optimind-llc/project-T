import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../../middleware/fetchMiddleware';

export const REDUEST_PART_FAMILY_DATA = 'REDUEST_PART_FAMILY_DATA';
export const REDUEST_PART_FAMILY_DATA_SUCCESS = 'REDUEST_PART_FAMILY_DATA_SUCCESS';
export const REDUEST_PART_FAMILY_DATA_FAIL = 'REDUEST_PART_FAMILY_DATA_FAIL';

export const UPDATE_PART_FAMILY_DATA = 'UPDATE_PART_FAMILY_DATA';
export const UPDATE_PART_FAMILY_DATA_SUCCESS = 'UPDATE_PART_FAMILY_DATA_SUCCESS';
export const UPDATE_PART_FAMILY_DATA_FAIL = 'UPDATE_PART_FAMILY_DATA_FAIL';

export const CLEAR_PART_FAMILY_DATA = 'CLEAR_PART_FAMILY_DATA';


const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false
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

export function getPartFamilyByPanelId(type, pn, panelId) {
  return {
    [CALL_API]: {
      types: [
        REDUEST_PART_FAMILY_DATA,
        REDUEST_PART_FAMILY_DATA_SUCCESS,
        REDUEST_PART_FAMILY_DATA_FAIL
      ],
      endpoint: '/manager/950A/association/family/panelId',
      method: 'POST',
      body: { type, start, end }
    }
  };
}

export function clearPartFamilyData() {
  return {
    type: CLEAR_PART_FAMILY_DATA
  }
}

export const partFActions = {
  getPartFamilyByDate,
  getPartFamilyByPanelId,
  clearPartFamilyData
};
