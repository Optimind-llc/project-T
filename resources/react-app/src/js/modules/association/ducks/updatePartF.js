import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../middleware/fetchMiddleware';

export const UPDATE_PartF_DATA = 'UPDATE_PartF_DATA';
export const UPDATE_PartF_DATA_SUCCESS = 'UPDATE_PartF_DATA_SUCCESS';
export const UPDATE_PartF_DATA_FAIL = 'UPDATE_PartF_DATA_FAIL';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case UPDATE_PartF_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case UPDATE_PartF_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case UPDATE_PartF_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    default:
      return state;
  }
}
export function updatePartFamily(body) {
  return {
    [CALL_API]: {
      types: [
        UPDATE_PartF_DATA,
        UPDATE_PartF_DATA_SUCCESS,
        UPDATE_PartF_DATA_FAIL
      ],
      endpoint: '/client/association/update',
      method: 'POST',
      body
    }
  };
}

export const updatePartFActions = {
  updatePartFamily,
};
