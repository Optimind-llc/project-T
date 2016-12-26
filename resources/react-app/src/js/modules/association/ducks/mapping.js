import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../middleware/fetchMiddleware';

export const REDUEST_MAPPING_DATA = 'REDUEST_MAPPING_DATA';
export const REDUEST_MAPPING_DATA_SUCCESS = 'REDUEST_MAPPING_DATA_SUCCESS';
export const REDUEST_MAPPING_DATA_FAIL = 'REDUEST_MAPPING_DATA_FAIL';
export const CLEAR_MAPPING_DATA = 'CLEAR_MAPPING_DATA';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case REDUEST_MAPPING_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REDUEST_MAPPING_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_MAPPING_DATA_FAIL:
      return Object.assign({}, state, {
        data: null,
        isFetching: false,
        didInvalidate: true
      });

    case CLEAR_MAPPING_DATA:
      return Object.assign({}, state, {
        data: null
      });

    default:
      return state;
  }
}

export function getMappingData(partId, itionGId) {
  return {
    [CALL_API]: {
      types: [
        REDUEST_MAPPING_DATA,
        REDUEST_MAPPING_DATA_SUCCESS,
        REDUEST_MAPPING_DATA_FAIL
      ],
      endpoint: 'manager/association/mapping',
      method: 'POST',
      body: { partId, itionGId }
    }
  };
}

export function clearMappingData() {
  return {
    type: CLEAR_MAPPING_DATA
  }
}

export const mappingActions = {
  getMappingData,
  clearMappingData
};
