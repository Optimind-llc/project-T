import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../../middleware/fetchMiddleware';

export const REDUEST_REFERENCE_DATA = 'REDUEST_REFERENCE_DATA';
export const REDUEST_REFERENCE_DATA_SUCCESS = 'REDUEST_REFERENCE_DATA_SUCCESS';
export const REDUEST_REFERENCE_DATA_FAIL = 'REDUEST_REFERENCE_DATA_FAIL';
export const CLEAR_REFERENCE_DATA = 'CLEAR_REFERENCE_DATA';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case REDUEST_REFERENCE_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REDUEST_REFERENCE_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_REFERENCE_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    case CLEAR_REFERENCE_DATA:
      return Object.assign({}, state, {
        data: null,
        isFetching: false,
        didInvalidate: false
      });

    default:
      return state;
  }
}

export function advancedSearch(p, i, pn, chokus, status, start, end, fs, ms, take, skip) {
  return {
    [CALL_API]: {
      types: [
        REDUEST_REFERENCE_DATA,
        REDUEST_REFERENCE_DATA_SUCCESS,
        REDUEST_REFERENCE_DATA_FAIL
      ],
      endpoint: 'manager/950A/reference/advanced',
      method: 'POST',
      body: { p, i, pn, chokus, status, start, end, fs, ms, take, skip }
    } 
  };
}

export function panelIdSearch(p, i, pn, panelId, take, skip) {
  return {
    [CALL_API]: {
      types: [
        REDUEST_REFERENCE_DATA,
        REDUEST_REFERENCE_DATA_SUCCESS,
        REDUEST_REFERENCE_DATA_FAIL
      ],
      endpoint: 'manager/950A/reference/panelId',
      method: 'POST',
      body: { p, i, pn, panelId, take, skip }
    }
  };
}

export function clearReferenceData() {
  return {
    type: CLEAR_REFERENCE_DATA
  }
}

export const referenceActions = {
  advancedSearch,
  panelIdSearch,
  clearReferenceData
};
