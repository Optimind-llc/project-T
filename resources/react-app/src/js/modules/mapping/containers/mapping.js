import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
// Styles
import './mapping.scss';
// Actions
import { pageActions } from '../ducks/page';
// Material-ui Components
import { Paper, Dialog, RaisedButton, FlatButton } from 'material-ui';
import { grey50, indigo500 } from 'material-ui/styles/colors';
// Components
import Loading from '../../../components/loading/loading';

class Mapping extends Component {
  constructor(props, context) {
    super(props, context);
    const { actions: {getPageData} } = props;

    getPageData(props.id);

    this.state = {
      intervalId: null,
      interval: 10000,
      innerHeight: window.innerHeight
    };
  }

  componentDidMount() {
    const { id, actions: {getPageData} } = this.props;
    const { interval } = this.state;
    const intervalId = setInterval(()=> getPageData(id), interval);

    this.setState({intervalId});
  }

  componentWillUnmount() {
    clearInterval(this.state.intervalId);
  }

  serch(groupId) {
    const { state } = this;
    const { actions: {getPageTData} } = this.props;
    getPageTData(groupId);
  }

  render() {
    const { isFetching, data } = this.props.PageData;

    return (
      <div id="mapping-wrap" className="">
        {
          data !== null &&
          <div>
            <img src={data.path}/>
            <div className="control-panel">
            </div>
          </div>
        }
      </div>
    );
  }
}

Mapping.propTypes = {
  id: PropTypes.string.isRequired,
  PageData: PropTypes.object.isRequired,
};

function mapStateToProps(state, ownProps) {
  console.log(ownProps);
  return {
    id: ownProps.params.id,
    PageData: state.PageData,
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({}, pageActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Mapping);
