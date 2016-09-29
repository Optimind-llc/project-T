import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../middleware/fetchMiddleware';

export const REDUEST_INSPECTION_DATA = 'REDUEST_INSPECTION_DATA';
export const REDUEST_INSPECTION_DATA_SUCCESS = 'REDUEST_INSPECTION_DATA_SUCCESS';
export const REDUEST_INSPECTION_DATA_FAIL = 'REDUEST_INSPECTION_DATA_FAIL';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false
};

export default function application(state = initialState, action) {
  switch (action.type) {
    case REDUEST_INSPECTION_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REDUEST_INSPECTION_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_INSPECTION_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    default:
      return state;
  }
}

export function getInspectionData() {
  return {
    [CALL_API]: {
      types: [
        REDUEST_INSPECTION_DATA,
        REDUEST_INSPECTION_DATA_SUCCESS,
        REDUEST_INSPECTION_DATA_FAIL
      ],
      endpoint: `manager/inspections`,
      method: 'GET',
      body: null
    }
  };
}

export const referenceActions = {
  getInspectionData,
};
