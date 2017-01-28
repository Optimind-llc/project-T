import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../middleware/fetchMiddleware';

export const REDUEST_Page_DATA = 'REDUEST_Page_DATA';
export const REDUEST_Page_DATA_SUCCESS = 'REDUEST_Page_DATA_SUCCESS';
export const REDUEST_Page_DATA_FAIL = 'REDUEST_Page_DATA_FAIL';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case REDUEST_Page_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REDUEST_Page_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_Page_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    default:
      return state;
  }
}

export function panelIdMapping(partT, itionG, itorG, panel) {
  return {
    [CALL_API]: {
      types: [
        REDUEST_Page_DATA,
        REDUEST_Page_DATA_SUCCESS,
        REDUEST_Page_DATA_FAIL
      ],
      endpoint: `/show/mapping/panelId/${partT}/${itionG}/${itorG}/${panel}`,
      method: 'GET',
      body: null
    }
  };
}

export function advancedMapping(partT, itionG, itorG, s = null, e = null) {
  return {
    [CALL_API]: {
      types: [
        REDUEST_Page_DATA,
        REDUEST_Page_DATA_SUCCESS,
        REDUEST_Page_DATA_FAIL
      ],
      endpoint: `/show/mapping/advanced/${partT}/${itionG}/${itorG}${(s && e)?`?start=${s}&end=${e}`:''}`,
      method: 'GET',
      body: null
    }
  };
}

export const pageActions = {
  panelIdMapping,
  advancedMapping
};
