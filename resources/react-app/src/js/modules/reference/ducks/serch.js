import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../middleware/fetchMiddleware';

export const SERCH_RESULT = 'SERCH_RESULT';
export const SERCH_RESULT_SUCCESS = 'SERCH_RESULT_SUCCESS';
export const SERCH_RESULT_FAIL = 'SERCH_RESULT_FAIL';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case SERCH_RESULT:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case SERCH_RESULT_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case SERCH_RESULT_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    default:
      return state;
  }
}

export function panelIdSerch(partTypeId, itionGId, panelId) {
  return {
    [CALL_API]: {
      types: [
        SERCH_RESULT,
        SERCH_RESULT_SUCCESS,
        SERCH_RESULT_FAIL
      ],
      endpoint: `/show/panelIdSerch/${partTypeId}/${itionGId}/${panelId}`,
      method: 'GET',
      body: null
    }
  };
}

export function advancedSerch(partTypeId, itionGId, body) {
  return {
    [CALL_API]: {
      types: [
        SERCH_RESULT,
        SERCH_RESULT_SUCCESS,
        SERCH_RESULT_FAIL
      ],
      endpoint: `/show/advancedSerch/${partTypeId}/${itionGId}`,
      method: 'POST',
      body
    }
  };
}

export const serchActions = {
  panelIdSerch,
  advancedSerch
};
