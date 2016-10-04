import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../middleware/fetchMiddleware';

export const REDUEST_PageT_DATA = 'REDUEST_PageT_DATA';
export const REDUEST_PageT_DATA_SUCCESS = 'REDUEST_PageT_DATA_SUCCESS';
export const REDUEST_PageT_DATA_FAIL = 'REDUEST_PageT_DATA_FAIL';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case REDUEST_PageT_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REDUEST_PageT_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_PageT_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    default:
      return state;
  }
}

export function getPageTData(groupId) {
  return {
    [CALL_API]: {
      types: [
        REDUEST_PageT_DATA,
        REDUEST_PageT_DATA_SUCCESS,
        REDUEST_PageT_DATA_FAIL
      ],
      endpoint: `/show/pageType?groupId=${groupId}`,
      method: 'GET',
      body: null
    }
  };
}

export const pageTActions = {
  getPageTData,
};
