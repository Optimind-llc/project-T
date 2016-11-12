import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../middleware/fetchMiddleware';

export const REQUEST_FAILURES = 'REQUEST_FAILURES';
export const REQUEST_FAILURES_SUCCESS = 'REQUEST_FAILURES_SUCCESS';
export const REQUEST_FAILURES_FAIL = 'REQUEST_FAILURES_FAIL';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case REQUEST_FAILURES:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REQUEST_FAILURES_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REQUEST_FAILURES_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    default:
      return state;
  }
}

export function getFailures(itionGId) {
  return {
    [CALL_API]: {
      types: [
        REQUEST_FAILURES,
        REQUEST_FAILURES_SUCCESS,
        REQUEST_FAILURES_FAIL
      ],
      endpoint: `/show/failures/${itionGId}`,
      method: 'GET',
      body: null
    }
  };
}

export const failureActions = {
  getFailures
};
