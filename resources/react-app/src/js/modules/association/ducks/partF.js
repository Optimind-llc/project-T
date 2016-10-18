import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../middleware/fetchMiddleware';

export const REDUEST_PartF_DATA = 'REDUEST_PartF_DATA';
export const REDUEST_PartF_DATA_SUCCESS = 'REDUEST_PartF_DATA_SUCCESS';
export const REDUEST_PartF_DATA_FAIL = 'REDUEST_PartF_DATA_FAIL';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case REDUEST_PartF_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REDUEST_PartF_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_PartF_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    default:
      return state;
  }
}
export function getPartFData(date, tyoku) {
  return {
    [CALL_API]: {
      types: [
        REDUEST_PartF_DATA,
        REDUEST_PartF_DATA_SUCCESS,
        REDUEST_PartF_DATA_FAIL
      ],
      endpoint: `/show/partFamily/${date}/${tyoku}`,
      method: 'GET',
      body: null
    }
  };
}

export const partFActions = {
  getPartFData,
};
