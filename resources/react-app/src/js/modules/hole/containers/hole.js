import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
// Actions
import { holeActions } from '../ducks/hole';
// Material-ui Components
import { Paper, Dialog, RaisedButton, FlatButton } from 'material-ui';
import { grey50, indigo500 } from 'material-ui/styles/colors';
// Styles
import './hole.scss';
// Components
import Main from '../components/main/main';

class Hole extends Component {
  constructor(props, context) {
    super(props, context);

    props.actions.getHoles(4, [1]);

    this.state = {
      figure: {label: 'インナーPage1', value: 4},
      status: {label: '表示中', value: [1]},
      editModal: false,
      createModal: false
    };
  }

  requestHoles() {
    const { getHoles } = this.props.actions;
    const { figure, status } = this.state;

    getHoles(figure.value, status.value);
  }

  componentWillReceiveProps(nextProps) {
    if (!this.props.maintHoleData.updated && nextProps.maintHoleData.updated) {
      this.requestHoles();
      this.setState({
        editModal: false,
        createModal: false
      });
    }
  }

  render() {
    const { maintHoleData } = this.props;
    const { figure, status, editModal, createModal, sort } = this.state;

    return (
      <div id="hole">
        <div className="refine-wrap bg-white">
          <div className="refine">
            <div className="inspection">
              <p>図面</p>
              <Select
                name="検査"
                placeholder="検査を選択"
                styles={{height: 30}}
                clearable={false}
                Searchable={true}
                value={this.state.figure}
                options={[
                  {label: 'インナーPage1', value: 4},
                  {label: 'インナーPage2', value: 5},
                  {label: 'インナーPage3', value: 6},
                  {label: 'インナーPage4', value: 7},
                  {label: 'アウターPage1', value: 8},
                  {label: 'アウターPage2', value: 13},
                  {label: 'アウターPage3', value: 14}
                ]}
                onChange={value => this.setState(
                  {figure: value},
                  () => this.requestHoles()
                )}
              />
            </div>
            <div className="status">
              <p>状態</p>
              <Select
                name="状態"
                placeholder="状態を選択"
                styles={{height: 30}}
                clearable={false}
                Searchable={true}
                value={this.state.status}
                options={[
                  {label: '全て', value: [0,1]},
                  {label: '非表示中', value: [0]},
                  {label: '表示中', value: [1]},
                ]}
                onChange={value => this.setState(
                  {status: value},
                  () => this.requestHoles()
                )}
              />
            </div>
          </div>
        </div>
        {
          maintHoleData.data !== null &&
          <Main
            path={maintHoleData.data.path}
            holes={maintHoleData.data.holes}
            activateHole={(id) => this.props.actions.activateHole(id)}
            deactivateHole={(id) => this.props.actions.deactivateHole(id)}
            editModal={editModal}
            createModal={createModal}
          />
        }
      </div>
    );
  }
}

Hole.propTypes = {
  maintHoleData: PropTypes.object.isRequired,
};

function mapStateToProps(state, ownProps) {
  return {
    maintHoleData: state.maintHoleData
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({}, holeActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Hole);
