import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../middleware/fetchMiddleware';

export const UPDATE_PartF_DATA = 'UPDATE_PartF_DATA';
export const UPDATE_PartF_DATA_SUCCESS = 'UPDATE_PartF_DATA_SUCCESS';
export const UPDATE_PartF_DATA_FAIL = 'UPDATE_PartF_DATA_FAIL';
export const CLEAR_MESSAGE = 'CLEAR_MESSAGE';

const initialState = {
  message: null,
  parts: [],
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
        message: action.payload.message,
        parts: action.payload.parts,
        isFetching: false,
        didInvalidate: false
      });

    case UPDATE_PartF_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    case CLEAR_MESSAGE:
      return Object.assign({}, state, {
        message: null
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

export function clearMessage() {
  console.log('aaaaaaa')
  return {
    type: CLEAR_MESSAGE
  }
}


export const updatePartFActions = {
  updatePartFamily,
  clearMessage
};
