import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
// Actions
import { push } from 'react-router-redux';
import { maintWorkerActions } from '../ducks/maintWorker';
// Styles
import './worker.scss';
// import iconCheck from '../../../../../../assets/img/icon/check.png';
// Components
import Edit from '../components/edit/edit';
import Create from '../components/create/create';

class Failure extends Component {
  constructor(props, context) {
    super(props, context);
    const { Inspections, MappingData, actions } = props;
    actions.requestWorkers();

    this.state = {
      yomi: '',
      process: {label: '全て', value: 'all'},
      inspection: {label: '全て', value: 'all'},
      editModal: false,
      editting: null,
      createModal: false,
      sort: {
        key: 'label',
        asc: false,
        id: 0
      }
    };  }

  componentWillUnmount() {
   clearInterval(this.state.intervalId); 
  }

  componentWillReceiveProps(nextProps) {
    if (nextProps.Workers.message === 'success') {
      this.setState({editModal: false, createModal: false});
      this.props.actions.clearMessage();
      this.props.actions.requestWorkers();
    }
  }

  sortData(data) {
    const { sort } = this.state;

    return data.slice().filter(d => {
      if (this.state.process.value !== 'all') {
        return d.inspections.find(insp => insp.p === this.state.process.value);
      }
      return true;
    }).filter(d => {
      if (this.state.inspection.value !== 'all') {
        return d.inspections.find(insp => insp.i === this.state.inspection.value);
      }
      return true;
    }).filter(d => {
      if (this.state.yomi !== '') {
        return d.yomi.indexOf(this.state.yomi) !== -1;
      }
      return true;
    }).sort((a,b) => {
      let aaa = 0;
      let bbb = 0;

      if (sort.key == 'label') {
        aaa = a[sort.key];
        bbb = b[sort.key];
      }

      if (sort.asc) {
        if(aaa < bbb) return 1;
        else if(aaa > bbb) return -1;
      } else {
        if(aaa < bbb) return -1;
        else if(aaa > bbb) return 1;
      }
      return 0;
    });
  }

  render() {
    const { sort, editModal, editting, createModal } = this.state;
    const { Processes, Inspections, Combination, Workers, actions } = this.props;

    const processes = Processes.map(p => {
      return {label: p.name, value: p.en}
    })

    const filterdI = Combination.filter(c => {
      if (this.state.process.value !== 'all') {
        return c.process === this.state.process.value;
      }
      return true;
    }).map(c => 
      c.inspection
    ).filter((c, i, self) =>
      self.indexOf(c) === i
    );

    const inspections = Inspections.filter(i =>
      filterdI.indexOf(i.en) >= 0
    ).map(i => {
      return {label: i.name, value: i.en}
    });

    const dCombination = [
      {p: 'molding', i: 'gaikan', d: 'door', ds: 'D'},
      {p: 'molding', i: 'gaikan', d: 'luggage', ds: 'L'},

      {p: 'holing', i: 'maegaikan', d: 'doorr', ds: 'D'},
      {p: 'holing', i: 'maegaikan', d: 'luggager', ds: 'L'},

      {p: 'holing', i: 'atogaikan', d: 'doorInner', ds: 'D'},
      {p: 'holing', i: 'atogaikan', d: 'luggageInner', ds: 'L'},

      {p: 'holing', i: 'ana', d: 'door', ds: 'D'},
      {p: 'holing', i: 'ana', d: 'luggage', ds: 'L'},

      {p: 'holing', i: 'tenaoshi', d: 'door', ds: 'D'},
      {p: 'holing', i: 'tenaoshi', d: 'luggage', ds: 'L'},

      {p: 'jointing', i: 'kashimego', d: 'door', ds: 'D'},
      {p: 'jointing', i: 'kashimego', d: 'luggage', ds: 'L'},

      {p: 'jointing', i: 'gaishushiage', d: 'luggage', ds: 'LO'},

      {p: 'jointing', i: 'pateshufukugo', d: 'luggage', ds: 'LO'},

      {p: 'jointing', i: 'suikengo', d: 'luggage', ds: 'LO'},

      {p: 'jointing', i: 'tosoukeirego', d: 'luggage', ds: 'LO'},

      {p: 'jointing', i: 'setchakugo', d: 'door', ds: 'D'},
      {p: 'jointing', i: 'setchakugo', d: 'luggage', ds: 'L'},

      {p: 'jointing', i: 'gaikan', d: 'door', ds: 'D'},
      {p: 'jointing', i: 'gaikan', d: 'luggage', ds: 'L'},

      {p: 'jointing', i: 'tenaoshi', d: 'door', ds: 'D'},
      {p: 'jointing', i: 'tenaoshi', d: 'luggage', ds: 'L'},
    ];

    return (
      <div id="press-maint-failureType-wrap">
        <div className="filter-wrap bg-white">
          <div className="name">
            <p>ヨミ</p>
            <input
              type="text"
              value={this.state.yomi}
              onChange={e => this.setState(
                {yomi: e.target.value}
              )}
            />
          </div>
          <div className="process">
            <p>工程</p>
            <Select
              name="工程"
              placeholder="工程を選択"
              styles={{height: 30}}
              clearable={false}
              Searchable={true}
              value={this.state.process}
              options={[...processes, {label: '全て', value: 'all'}]}
              onChange={p => this.setState({process: p})}
            />
          </div>
          <div className="inspection">
            <p>検査</p>
            <Select
              name="検査"
              placeholder="検査を選択"
              styles={{height: 30}}
              clearable={false}
              Searchable={true}
              value={this.state.inspection}
              options={[...inspections, {label: '全て', value: 'all'}]}
              onChange={i => this.setState({inspection: i})}
            />
          </div>
        </div>
        <div className="result-wrap bg-white">
          {
            Workers.message === 'over limit' &&
            <p className="error-message-over-limit">不良区分の表示上限16を超えています</p>
          }
          <button
            className="create-btn"
            onClick={() => this.setState({createModal: true})}
          >
            新規登録
          </button>
          <table>
            <thead>
              <tr>
                <th colSpan={1} rowSpan={3}>No.</th>
                <th colSpan={1} rowSpan={3}>名前</th>
                <th colSpan={1} rowSpan={3}>ヨミ</th>
                <th colSpan={1} rowSpan={3}>直</th>
                <th colSpan={2} rowSpan={1}>成形</th>
                <th colSpan={8} rowSpan={1}>穴あけ</th>
                <th colSpan={12} rowSpan={1}>かしめ/接着</th>
                <th colSpan={1} rowSpan={3}>機能</th>
              </tr>
              <tr>
                <th colSpan={2} rowSpan={1}>外観検査</th>
                <th colSpan={2} rowSpan={1}>洗浄前外観検査</th>
                <th colSpan={2} rowSpan={1}>洗浄後外観検査</th>
                <th colSpan={2} rowSpan={1}>穴検査</th>
                <th colSpan={2} rowSpan={1}>手直</th>
                <th colSpan={2} rowSpan={1}>かしめ後検査</th>
                <th colSpan={1} rowSpan={1}>外周仕上</th>
                <th colSpan={1} rowSpan={1}>パテ補修</th>
                <th colSpan={1} rowSpan={1}>水研後</th>
                <th colSpan={1} rowSpan={1}>塗装後</th>
                <th colSpan={2} rowSpan={1}>接着後</th>
                <th colSpan={2} rowSpan={1}>外観検査</th>
                <th colSpan={2} rowSpan={1}>手直</th>
              </tr>
              <tr>
                <th colSpan={1} rowSpan={1}>D</th><th colSpan={1} rowSpan={1}>L</th>
                <th colSpan={1} rowSpan={1}>D</th><th colSpan={1} rowSpan={1}>L</th>
                <th colSpan={1} rowSpan={1}>D</th><th colSpan={1} rowSpan={1}>L</th>
                <th colSpan={1} rowSpan={1}>D</th><th colSpan={1} rowSpan={1}>L</th>
                <th colSpan={1} rowSpan={1}>D</th><th colSpan={1} rowSpan={1}>L</th>
                <th colSpan={1} rowSpan={1}>D</th><th colSpan={1} rowSpan={1}>L</th>
                <th colSpan={1} rowSpan={1}>L</th>
                <th colSpan={1} rowSpan={1}>L</th>
                <th colSpan={1} rowSpan={1}>L</th>
                <th colSpan={1} rowSpan={1}>L</th>
                <th colSpan={1} rowSpan={1}>D</th><th colSpan={1} rowSpan={1}>L</th>
                <th colSpan={1} rowSpan={1}>D</th><th colSpan={1} rowSpan={1}>L</th>
                <th colSpan={1} rowSpan={1}>D</th><th colSpan={1} rowSpan={1}>L</th>
              </tr>
            </thead>
            <tbody>
            {
              Workers.data && Workers.data.length !== 0 &&
              this.sortData(Workers.data).map((f, i)=> 
                {
                  return(
                    <tr className="content" key={i}>
                      <td>{i+1}</td>
                      <td>{f.name}</td>
                      <td>{f.yomi}</td>
                      <td>{f.choku}</td>
                      {
                        dCombination.map(dc => {
                          let num = '';
                          const target = f.inspections.find(fi => fi.p === dc.p && fi.i === dc.i &&  fi.d === dc.d);
                          if (target) {
                            num = target.sort;
                          }

                          return (<td>{num}</td>)
                        })
                      }
                      <td>
                        <button
                          className="dark edit"
                          onClick={() => this.setState({
                            editModal: true,
                            editting: f
                          })}
                        >
                          <p>編集</p>
                        </button>
                      </td>
                    </tr>
                  )
                }
              )
            }{
              Workers.data && Workers.data.length == 0 &&
              <tr className="content">
                <td colSpan="17">結果なし</td>
              </tr>
            }
            </tbody>
          </table>
          {
            editModal &&
            <Edit
              id={editting.id}
              name={editting.name}
              yomi={editting.yomi}
              choku={editting.choku}
              inspections={editting.inspections}
              message={Workers.message}
              dCombination={dCombination}
              close={() => {
                actions.clearMessage();
                this.setState({editModal: false});
                actions.requestWorkers()
              }}
              update={(id, name, yomi, choku, inspections) => actions.updateWorker(id, name, yomi, choku, inspections)}
            />
          }{
            createModal &&
            <Create
              message={Workers.message}
              dCombination={dCombination}
              close={() => {
                actions.clearMessage();
                this.setState({createModal: false});
                actions.requestWorkers();
              }}
              create={(name, yomi, choku, inspections) => actions.createWorker(name, yomi, choku, inspections)}
            />
          }
        </div>
      </div>
    );
  }
}

Failure.propTypes = {
  Processes: PropTypes.array.isRequired,
  Inspections: PropTypes.array.isRequired,
  Workers: PropTypes.array.isRequired
};

function mapStateToProps(state, ownProps) {
  return {
    Processes: state.Application.vehicle950A.processes,
    Inspections: state.Application.vehicle950A.inspections,
    Combination: state.Application.vehicle950A.combination,
    Workers: state.MaintWorker950A
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({push}, maintWorkerActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Failure);
