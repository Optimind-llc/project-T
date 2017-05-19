import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
// Actions
import { push } from 'react-router-redux';
import { maintModificationActions } from '../ducks/maintModification';
// Styles
import './modification.scss';
// import iconCheck from '../../../../../../assets/img/icon/check.png';
// Components
import Edit from '../components/edit/edit';
import Create from '../components/create/create';

class Modification extends Component {
  constructor(props, context) {
    super(props, context);
    const { Inspections, MappingData, actions } = props;
    actions.requestModifications();

    this.state = {
      name: '',
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

  sortData(data) {
    const { sort } = this.state;

    return data.slice().filter(d => {
      if (this.state.process.value !== 'all') {
        console.log(this.state.process.value);
        return d.inspections.find(insp => insp.p === this.state.process.value);
      }
      return true;
    }).filter(d => {
      if (this.state.inspection.value !== 'all') {
        return d.inspections.find(insp => insp.i === this.state.inspection.value);
      }
      return true;
    }).filter(d => {
      if (this.state.name !== '') {
        return d.name.indexOf(this.state.name) !== -1;
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
    const { Processes, Inspections, Combination, ModificationTypes, actions } = this.props;

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
      {p: 'molding', i: 'gaikan', d: 'doorInner', ds: 'DI'},
      {p: 'molding', i: 'gaikan', d: 'reinforce', ds: 'RF'},
      {p: 'molding', i: 'gaikan', d: 'luggageInner', ds: 'LI'},
      {p: 'molding', i: 'gaikan', d: 'luggageOuter', ds: 'LO'},

      {p: 'holing', i: 'maegaikan', d: 'doorInner', ds: 'DI'},
      {p: 'holing', i: 'maegaikan', d: 'reinforce', ds: 'RF'},
      {p: 'holing', i: 'maegaikan', d: 'luggageInner', ds: 'LI'},
      {p: 'holing', i: 'maegaikan', d: 'luggageOuter', ds: 'LO'},

      {p: 'holing', i: 'atogaikan', d: 'doorInner', ds: 'DI'},
      {p: 'holing', i: 'atogaikan', d: 'reinforce', ds: 'RF'},
      {p: 'holing', i: 'atogaikan', d: 'luggageInner', ds: 'LI'},
      {p: 'holing', i: 'atogaikan', d: 'luggageOuter', ds: 'LO'},

      {p: 'holing', i: 'ana', d: 'doorInner', ds: 'DI'},
      {p: 'holing', i: 'ana', d: 'reinforce', ds: 'RF'},
      {p: 'holing', i: 'ana', d: 'luggageInner', ds: 'LI'},
      {p: 'holing', i: 'ana', d: 'luggageOuter', ds: 'LO'},

      {p: 'holing', i: 'tenaoshi', d: 'doorInner', ds: 'DI'},
      {p: 'holing', i: 'tenaoshi', d: 'reinforce', ds: 'RF'},
      {p: 'holing', i: 'tenaoshi', d: 'luggageInner', ds: 'LI'},
      {p: 'holing', i: 'tenaoshi', d: 'luggageOuter', ds: 'LO'},

      {p: 'jointing', i: 'kashimego', d: 'doorInner', ds: 'DI'},
      {p: 'jointing', i: 'kashimego', d: 'reinforce', ds: 'RF'},
      {p: 'jointing', i: 'kashimego', d: 'luggageInner', ds: 'LI'},
      {p: 'jointing', i: 'kashimego', d: 'luggageOuter', ds: 'LO'},

      {p: 'jointing', i: 'gaishushiage', d: 'luggageOuter', ds: 'LO'},

      {p: 'jointing', i: 'pateshufukugo', d: 'luggageOuter', ds: 'LO'},

      {p: 'jointing', i: 'suikengo', d: 'luggageOuter', ds: 'LO'},

      {p: 'jointing', i: 'tosoukeirego', d: 'luggageOuter', ds: 'LO'},

      {p: 'jointing', i: 'setchakugo', d: 'doorASSY', ds: 'DA'},
      {p: 'jointing', i: 'setchakugo', d: 'luggageASSY', ds: 'LA'},

      {p: 'jointing', i: 'gaikan', d: 'doorASSY', ds: 'DA'},
      {p: 'jointing', i: 'gaikan', d: 'luggageASSY', ds: 'LA'},

      {p: 'jointing', i: 'tenaoshi', d: 'doorASSY', ds: 'DA'},
      {p: 'jointing', i: 'tenaoshi', d: 'luggageASSY', ds: 'LA'},
    ];

    return (
      <div id="press-maint-failureType-wrap">
        <div className="filter-wrap bg-white">
          <div className="name">
            <p>不良名</p>
            <input
              type="text"
              value={this.state.name}
              onChange={e => this.setState(
                {name: e.target.value},
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
            ModificationTypes.message === 'over limit' &&
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
                <th colSpan={1} rowSpan={3}>不良名</th>
                <th colSpan={1} rowSpan={3}>表示<br/>番号</th>
                <th colSpan={4} rowSpan={1}>成形</th>
                <th colSpan={16} rowSpan={1}>穴あけ</th>
                <th colSpan={14} rowSpan={1}>かしめ/接着</th>
                <th colSpan={1} rowSpan={3}>機能</th>
              </tr>
              <tr>
                <th colSpan={4} rowSpan={1}>外観検査</th>
                <th colSpan={4} rowSpan={1}>洗浄前外観検査</th>
                <th colSpan={4} rowSpan={1}>洗浄後外観検査</th>
                <th colSpan={4} rowSpan={1}>穴検査</th>
                <th colSpan={4} rowSpan={1}>手直</th>
                <th colSpan={4} rowSpan={1}>かしめ後検査</th>
                <th colSpan={1} rowSpan={1}>外周仕上</th>
                <th colSpan={1} rowSpan={1}>パテ補修</th>
                <th colSpan={1} rowSpan={1}>水研後</th>
                <th colSpan={1} rowSpan={1}>塗装後</th>
                <th colSpan={2} rowSpan={1}>接着後</th>
                <th colSpan={2} rowSpan={1}>外観検査</th>
                <th colSpan={2} rowSpan={1}>手直</th>
              </tr>
              <tr>
                <th colSpan={1} rowSpan={1}>DI</th><th colSpan={1} rowSpan={1}>LF</th><th colSpan={1} rowSpan={1}>LI</th><th colSpan={1} rowSpan={1}>LO</th>
                <th colSpan={1} rowSpan={1}>DI</th><th colSpan={1} rowSpan={1}>LF</th><th colSpan={1} rowSpan={1}>LI</th><th colSpan={1} rowSpan={1}>LO</th>
                <th colSpan={1} rowSpan={1}>DI</th><th colSpan={1} rowSpan={1}>LF</th><th colSpan={1} rowSpan={1}>LI</th><th colSpan={1} rowSpan={1}>LO</th>
                <th colSpan={1} rowSpan={1}>DI</th><th colSpan={1} rowSpan={1}>LF</th><th colSpan={1} rowSpan={1}>LI</th><th colSpan={1} rowSpan={1}>LO</th>
                <th colSpan={1} rowSpan={1}>DI</th><th colSpan={1} rowSpan={1}>LF</th><th colSpan={1} rowSpan={1}>LI</th><th colSpan={1} rowSpan={1}>LO</th>
                <th colSpan={1} rowSpan={1}>DI</th><th colSpan={1} rowSpan={1}>LF</th><th colSpan={1} rowSpan={1}>LI</th><th colSpan={1} rowSpan={1}>LO</th>
                <th colSpan={1} rowSpan={1}>LO</th>
                <th colSpan={1} rowSpan={1}>LO</th>
                <th colSpan={1} rowSpan={1}>LO</th>
                <th colSpan={1} rowSpan={1}>LO</th>
                <th colSpan={1} rowSpan={1}>DA</th><th colSpan={1} rowSpan={1}>LA</th>
                <th colSpan={1} rowSpan={1}>DA</th><th colSpan={1} rowSpan={1}>LA</th>
                <th colSpan={1} rowSpan={1}>DA</th><th colSpan={1} rowSpan={1}>LA</th>
              </tr>
            </thead>
            <tbody>
            {
              ModificationTypes.data && ModificationTypes.data.length !== 0 &&
              this.sortData(ModificationTypes.data).map((f, i)=> 
                {
                  return(
                    <tr className="content" key={i}>
                      <td>{i+1}</td>
                      <td>{f.name}</td>
                      <td>{f.label}</td>
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
              ModificationTypes.data && ModificationTypes.data.length == 0 &&
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
              label={editting.label}
              inspections={editting.inspections}
              message={ModificationTypes.message}
              dCombination={dCombination}
              close={() => {
                actions.clearMessage();
                this.setState({editModal: false});
                actions.requestModifications()
              }}
              update={(id, name, label, inspections) => actions.updateModification(id, name, label, inspections)}
            />
          }{
            createModal &&
            <Create
              message={ModificationTypes.message}
              dCombination={dCombination}
              close={() => {
                actions.clearMessage();
                this.setState({createModal: false});
                actions.requestModifications();
              }}
              create={(name, label, inspections) => actions.createModification(name, label, inspections)}
            />
          }
        </div>
      </div>
    );
  }
}

Modification.propTypes = {
  Processes: PropTypes.array.isRequired,
  Inspections: PropTypes.array.isRequired,
  ModificationTypes: PropTypes.array.isRequired
};

function mapStateToProps(state, ownProps) {
  return {
    Processes: state.Application.vehicle950A.processes,
    Inspections: state.Application.vehicle950A.inspections,
    Combination: state.Application.vehicle950A.combination,
    ModificationTypes: state.MaintModification950A
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({push}, maintModificationActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Modification);
