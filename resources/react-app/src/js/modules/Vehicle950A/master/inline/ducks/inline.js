import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../../../middleware/fetchMiddleware';

export const REDUEST_INLINES_DATA = 'REDUEST_INLINES_DATA';
export const REDUEST_INLINES_DATA_SUCCESS = 'REDUEST_INLINES_DATA_SUCCESS';
export const REDUEST_INLINES_DATA_FAIL = 'REDUEST_INLINES_DATA_FAIL';

export const UPDATE_INLINE_DATA = 'UPDATE_INLINE_DATA';
export const UPDATE_INLINE_DATA_SUCCESS = 'UPDATE_INLINE_DATA_SUCCESS';
export const UPDATE_INLINE_DATA_FAIL = 'UPDATE_INLINE_DATA_FAIL';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false,
  updating: false,
  updated: false,
  message: ''
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case REDUEST_INLINES_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false,
        message: ''
      });

    case REDUEST_INLINES_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_INLINES_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true,
        message: action.payload.message
      });

    case UPDATE_INLINE_DATA:
      return Object.assign({}, state, {
        updating: true,
        updated: false,
        message: ''
      });

    case UPDATE_INLINE_DATA_SUCCESS:
      return Object.assign({}, state, {
        updating: false,
        updated: true
      });

    case UPDATE_INLINE_DATA_FAIL:
      return Object.assign({}, state, {
        updating: false,
        updated: false,
        message: action.payload.message
      });

    default:
      return state;
  }
}

export function getInlines(partTypePns) {
  return {
    [CALL_API]: {
      types: [
        REDUEST_INLINES_DATA,
        REDUEST_INLINES_DATA_SUCCESS,
        REDUEST_INLINES_DATA_FAIL
      ],
      endpoint: 'manager/950A/maintenance/inlines',
      method: 'POST',
      body: {partTypePns}
    }
  };
}


export function updateInline(id, max, min) {
  return {
    [CALL_API]: {
      types: [
        UPDATE_INLINE_DATA,
        UPDATE_INLINE_DATA_SUCCESS,
        UPDATE_INLINE_DATA_FAIL
      ],
      endpoint: 'manager/950A/maintenance/inline/update',
      method: 'POST',
      body: {id, max, min}
    }
  };
}

export const inlineActions = {
  getInlines,
  updateInline,
};
