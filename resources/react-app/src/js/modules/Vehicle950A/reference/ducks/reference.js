import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../../middleware/fetchMiddleware';

export const REDUEST_REFERENCE_DATA = 'REDUEST_REFERENCE_DATA';
export const REDUEST_REFERENCE_DATA_SUCCESS = 'REDUEST_REFERENCE_DATA_SUCCESS';
export const REDUEST_REFERENCE_DATA_FAIL = 'REDUEST_REFERENCE_DATA_FAIL';
export const ADD_REFERENCE_DATA = 'ADD_REFERENCE_DATA';
export const ADD_REFERENCE_DATA_SUCCESS = 'ADD_REFERENCE_DATA_SUCCESS';
export const ADD_REFERENCE_DATA_FAIL = 'ADD_REFERENCE_DATA_FAIL';
export const CLEAR_REFERENCE_DATA = 'CLEAR_REFERENCE_DATA';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false
};

function distinct(fieldNames) {
  var self = this;
  return function(item, i, arr) {
    return i == indexOf(arr, item, equalsAllFields)
  }

  // arrのなかにobjが含まれていればそのインデックス番号を返す
  // 探し方はcomparatorを使って探す
  function indexOf(arr, obj, comparator) {
    for(var index in arr) {
      if(comparator(obj, arr[index]) == true) return index;
    }
    return -1;
  }

  // オブジェクトaとbが fieldNamesに当てられたプロパティーを比較して同じであればtrueを返す
  function equalsAllFields(a, b) {
    for(var i in fieldNames) {
      var f = fieldNames[i];
      if(a[f] !== b[f]) return false;
    }
    return true;
  }
}

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case REDUEST_REFERENCE_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REDUEST_REFERENCE_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_REFERENCE_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    case ADD_REFERENCE_DATA:
      return Object.assign({}, state, {
        didInvalidate: false
      });

    case ADD_REFERENCE_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: {
          count: state.data.count,
          fts: state.data.fts.concat(action.payload.data.fts).filter(distinct(['id'])),
          mts: state.data.mts.concat(action.payload.data.mts).filter(distinct(['id'])),
          hts: state.data.hts.concat(action.payload.data.hts).filter(distinct(['id'])),
          hmts: state.data.hmts.concat(action.payload.data.hmts).filter(distinct(['id'])),
          its: state.data.its.concat(action.payload.data.its).filter(distinct(['id'])),
          results: state.data.results.concat(action.payload.data.results).filter(distinct(['id']))
        },
        isFetching: false,
        didInvalidate: false
      });

    case ADD_REFERENCE_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    case CLEAR_REFERENCE_DATA:
      return Object.assign({}, state, {
        data: null,
        isFetching: false,
        didInvalidate: false
      });

    default:
      return state;
  }
}

export function advancedSearch(p, i, pn, chokus, status, start, end, fs, ms, take, skip) {
  return {
    [CALL_API]: {
      types: [
        REDUEST_REFERENCE_DATA,
        REDUEST_REFERENCE_DATA_SUCCESS,
        REDUEST_REFERENCE_DATA_FAIL
      ],
      endpoint: 'manager/950A/reference/advanced',
      method: 'POST',
      body: { p, i, pn, chokus, status, start, end, fs, ms, take, skip }
    } 
  };
}

export function panelIdSearch(p, i, pn, panelId, take, skip) {
  return {
    [CALL_API]: {
      types: [
        REDUEST_REFERENCE_DATA,
        REDUEST_REFERENCE_DATA_SUCCESS,
        REDUEST_REFERENCE_DATA_FAIL
      ],
      endpoint: 'manager/950A/reference/panelId',
      method: 'POST',
      body: { p, i, pn, panelId, take, skip }
    }
  };
}

export function advancedAdditionalSearch(p, i, pn, chokus, status, start, end, fs, ms, take, skip) {
  return {
    [CALL_API]: {
      types: [
        ADD_REFERENCE_DATA,
        ADD_REFERENCE_DATA_SUCCESS,
        ADD_REFERENCE_DATA_FAIL
      ],
      endpoint: 'manager/950A/reference/advanced',
      method: 'POST',
      body: { p, i, pn, chokus, status, start, end, fs, ms, take, skip }
    } 
  };
}


export function clearReferenceData() {
  return {
    type: CLEAR_REFERENCE_DATA
  }
}

export const referenceActions = {
  advancedSearch,
  panelIdSearch,
  advancedAdditionalSearch,
  clearReferenceData
};
