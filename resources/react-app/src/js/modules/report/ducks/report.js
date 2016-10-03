import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../middleware/fetchMiddleware';

export const REDUEST_Ve_ItorG_Proc_DATA = 'REDUEST_Ve_ItorG_Proc_DATA';
export const REDUEST_Ve_ItorG_Proc_DATA_SUCCESS = 'REDUEST_Ve_ItorG_Proc_DATA_SUCCESS';
export const REDUEST_Ve_ItorG_Proc_DATA_FAIL = 'REDUEST_Ve_ItorG_Proc_DATA_FAIL';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case REDUEST_Ve_ItorG_Proc_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REDUEST_Ve_ItorG_Proc_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_Ve_ItorG_Proc_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    default:
      return state;
  }
}

export function getVeItorGProc() {
  return {
    [CALL_API]: {
      types: [
        REDUEST_Ve_ItorG_Proc_DATA,
        REDUEST_Ve_ItorG_Proc_DATA_SUCCESS,
        REDUEST_Ve_ItorG_Proc_DATA_FAIL
      ],
      endpoint: 'show?vehicle=all&inspectorG=all&process=all',
      method: 'GET',
      body: null
    }
  };
}

export const reportActions = {
  getVeItorGProc,
};
