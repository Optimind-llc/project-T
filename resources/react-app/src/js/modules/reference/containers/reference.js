import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
// Actions
import { referenceActions } from '../ducks/reference';
// Material-ui Components
import { Paper, Dialog, RaisedButton, FlatButton } from 'material-ui';
import { grey50, indigo500 } from 'material-ui/styles/colors';
// Components
import Loading from '../../../components/loading/loading';

class Reference extends Component {
  constructor(props, context) {
    super(props, context);

    this.state = {
      open: true,
    };
  }

  serch() {
    const { actions: {getInspectionData} } = this.props;
    getInspectionData();
  }

  render() {
    // const { conference } = this.props;
    return (
      <div>
        <button onClick={() => this.serch() }>a</button>
      </div>
    );
  }
}

Reference.propTypes = {
  // id: PropTypes.string.isRequired,
  // application: PropTypes.object.isRequired,
  // conference: PropTypes.object.isRequired,
};

function mapStateToProps(state, ownProps) {
  return {
    // id: ownProps.params.id,
    // application: state.application,
    // conference: state.conference,
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({}, referenceActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Reference);
