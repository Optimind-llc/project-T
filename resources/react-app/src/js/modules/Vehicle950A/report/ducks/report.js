import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../../middleware/fetchMiddleware';

export const REDUEST_REPORT_DATA = 'REDUEST_REPORT_DATA';
export const REDUEST_REPORT_DATA_SUCCESS = 'REDUEST_REPORT_DATA_SUCCESS';
export const REDUEST_REPORT_DATA_FAIL = 'REDUEST_REPORT_DATA_FAIL';
export const CLEAR_REPORT_DATA = 'CLEAR_REPORT_DATA';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case REDUEST_REPORT_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REDUEST_REPORT_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_REPORT_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    case CLEAR_REPORT_DATA:
      return Object.assign({}, state, {
        data: null
      });

    default:
      return state;
  }
}

export function getReportData(p, date, choku) {
  return {
    [CALL_API]: {
      types: [
        REDUEST_REPORT_DATA,
        REDUEST_REPORT_DATA_SUCCESS,
        REDUEST_REPORT_DATA_FAIL
      ],
      endpoint: 'manager/950A/report/check',
      method: 'POST',
      body: { p, date, choku}
    }
  };
}

export function clearReportData() {
  return {
    type: CLEAR_REPORT_DATA
  }
}

export const reportActions = {
  getReportData,
  clearReportData
};
