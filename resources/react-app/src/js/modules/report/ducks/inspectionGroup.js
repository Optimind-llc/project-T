import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../middleware/fetchMiddleware';

export const REDUEST_ItionG_DATA = 'REDUEST_ItionG_DATA';
export const REDUEST_ItionG_DATA_SUCCESS = 'REDUEST_ItionG_DATA_SUCCESS';
export const REDUEST_ItionG_DATA_FAIL = 'REDUEST_ItionG_DATA_FAIL';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case REDUEST_ItionG_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REDUEST_ItionG_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_ItionG_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    default:
      return state;
  }
}

export function getItionG(vehicle, date, itorG) {
  return {
    [CALL_API]: {
      types: [
        REDUEST_ItionG_DATA,
        REDUEST_ItionG_DATA_SUCCESS,
        REDUEST_ItionG_DATA_FAIL
      ],
      endpoint: `show/inspectionGroup?vehicle=${vehicle}&date=${date}&inspectorG=${itorG}`,
      method: 'GET',
      body: null
    }
  };
}

export const getItionGActions = {
  getItionG,
};
