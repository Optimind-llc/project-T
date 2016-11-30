import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../middleware/fetchMiddleware';

export const REDUEST_INSPECTORS_DATA = 'REDUEST_INSPECTORS_DATA';
export const REDUEST_INSPECTORS_DATA_SUCCESS = 'REDUEST_INSPECTORS_DATA_SUCCESS';
export const REDUEST_INSPECTORS_DATA_FAIL = 'REDUEST_INSPECTORS_DATA_FAIL';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case REDUEST_INSPECTORS_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REDUEST_INSPECTORS_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data.inspector,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_INSPECTORS_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    default:
      return state;
  }
}

export function getInspectors() {
  return {
    [CALL_API]: {
      types: [
        REDUEST_INSPECTORS_DATA,
        REDUEST_INSPECTORS_DATA_SUCCESS,
        REDUEST_INSPECTORS_DATA_FAIL
      ],
      endpoint: 'show?inspector=all',
      method: 'GET',
      body: null
    }
  };
}

export const inspectorActions = {
  getInspectors,
};
