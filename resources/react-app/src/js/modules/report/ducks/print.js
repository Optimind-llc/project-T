import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../middleware/fetchMiddleware';

export const REDUEST_PRINT_LIST = 'REDUEST_PRINT_LIST';
export const REDUEST_PRINT_LIST_SUCCESS = 'REDUEST_PRINT_LIST_SUCCESS';
export const REDUEST_PRINT_LIST_FAIL = 'REDUEST_PRINT_LIST_FAIL';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case REDUEST_PRINT_LIST:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REDUEST_PRINT_LIST_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_PRINT_LIST_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    default:
      return state;
  }
}

export function getPrintList() {
  return {
    [CALL_API]: {
      types: [
        REDUEST_PRINT_LIST,
        REDUEST_PRINT_LIST_SUCCESS,
        REDUEST_PRINT_LIST_FAIL
      ],
      endpoint: 'show?vehicle=all&inspectorG=all',
      method: 'GET',
      body: null
    }
  };
}

export const printActions = {
  getPrintList,
};
