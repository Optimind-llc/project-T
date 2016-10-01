import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../middleware/fetchMiddleware';

export const REDUEST_VEandITORG_DATA = 'REDUEST_VEandITORG_DATA';
export const REDUEST_VEandITORG_DATA_SUCCESS = 'REDUEST_VEandITORG_DATA_SUCCESS';
export const REDUEST_VEandITORG_DATA_FAIL = 'REDUEST_VEandITORG_DATA_FAIL';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case REDUEST_VEandITORG_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REDUEST_VEandITORG_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_VEandITORG_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    default:
      return state;
  }
}

export function getVEandITORGdata() {
  return {
    [CALL_API]: {
      types: [
        REDUEST_VEandITORG_DATA,
        REDUEST_VEandITORG_DATA_SUCCESS,
        REDUEST_VEandITORG_DATA_FAIL
      ],
      endpoint: 'show?vehicle=all&inspectorG=all',
      method: 'GET',
      body: null
    }
  };
}

export const reportActions = {
  getVEandITORGdata,
};
