import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
// Styles
import './dashboard.scss';
// Actions
import { push } from 'react-router-redux';
import { processActions } from '../ducks/process';
import { pageTActions } from '../ducks/pageType';
// Material-ui Components
import { Paper, Dialog, RaisedButton, FlatButton } from 'material-ui';
import { grey50, indigo500 } from 'material-ui/styles/colors';
// Components
import Loading from '../../../components/loading/loading';

class Dashboard extends Component {
  constructor(props, context) {
    super(props, context);

    console.log();


    const vehicle = '680A';
    props.actions.getAllItionGData(vehicle);
    this.state = {
      vehicle,
      pages: false,
      process: '',
      pageTitle: ''
    };
  }

  serch(groupId) {
    const { state } = this;
    const { actions: {getPageTData} } = this.props;
    getPageTData(groupId);
  }

  render() {
    const { state } = this;
    const { AllItionGData, PageTData } = this.props;
    return (
      <div id="dashboardWrap" className="bg-white">
        <h4><span>車種：{state.vehicle}</span></h4>
        {
          AllItionGData.data !== null && !state.pages &&
          <div className="main-panel">
            {
              AllItionGData.data.map(p =>
                <div key={p.id} className={`process col-${p.inspections.length}`}>
                  <p>{p.name}</p>
                  <div className={p.id}>
                  {
                    p.inspections.map(i =>
                      <div key={i.en} className={`inspection col-${i.groups.length}`}>
                        <p>{i.name}</p>
                        {
                          i.groups.map(g =>
                            <div
                              key={g.id}
                              className="group"
                              onClick={() => {
                                this.serch(g.id);
                                this.setState({
                                  pages: true,
                                  pageTitle: `${p.name} ${i.name} ${g.division.name} ${g.line == '1' ? 'ライン①' : g.line == '2' ? 'ライン②' : ''}`,
                                  process: p.id
                                });
                              }}
                            >
                              <p>{g.division.name}<span>{g.line == '1' ? 'ライン①' : g.line == '2' ? 'ライン②' : ''}</span></p>
                              <p>{g.countF}<span>件</span></p>
                            </div>
                          )
                        }
                      </div>
                    )
                  }
                  </div>
                </div>
              )
            }
          </div>
        }
        {
          state.pages && !PageTData.isFetching && PageTData.data !== null &&
          <div>
            <h4 className={state.process}>{state.pageTitle}</h4>
            <div className="pages">
              {
                PageTData.data.map(page =>
                  <div
                    className="pageWrap"
                    onClick={() => this.props.actions.push(`/manager/mapping/${page.id}`)}
                  >
                    <p><span>Page </span>{page.number}</p>
                    <figure>
                      <img src={page.path}/>
                    </figure>
                  </div>
               )
              }
            </div>
          </div>
        }
      </div>
    );
  }
}

Dashboard.propTypes = {
  AllItionGData: PropTypes.object.isRequired,
  PageTData: PropTypes.object.isRequired,
};

function mapStateToProps(state, ownProps) {
  return {
    AllItionGData: state.AllItionGData,
    PageTData: state.PageTData,
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({}, {push}, processActions, pageTActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Dashboard);
