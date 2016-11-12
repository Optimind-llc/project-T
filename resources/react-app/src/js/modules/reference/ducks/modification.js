
import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../middleware/fetchMiddleware';

export const REQUEST_MODIFICATIONS = 'REQUEST_MODIFICATIONS';
export const REQUEST_MODIFICATIONS_SUCCESS = 'REQUEST_MODIFICATIONS_SUCCESS';
export const REQUEST_MODIFICATIONS_FAIL = 'REQUEST_MODIFICATIONS_FAIL';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case REQUEST_MODIFICATIONS:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REQUEST_MODIFICATIONS_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REQUEST_MODIFICATIONS_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    default:
      return state;
  }
}

export function getModifications(itionGId) {
  return {
    [CALL_API]: {
      types: [
        REQUEST_MODIFICATIONS,
        REQUEST_MODIFICATIONS_SUCCESS,
        REQUEST_MODIFICATIONS_FAIL
      ],
      endpoint: `/show/modifications/${itionGId}`,
      method: 'GET',
      body: null
    }
  };
}

export const modificationActions = {
  getModifications
};
