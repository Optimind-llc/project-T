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

    props.PageData.data = null;
    getPageData(props.id);

    this.state = {
      intervalId: null,
      interval: 100000,
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

  formatHoles(holes) {
    const point = holes[0].point.split(',');
    const x = point[0]/2;
    const y = point[1]/2;
    let ly, lx;

    switch (holes[0].direction) {
      case 'top':
        lx =  - 10);
        ly = point[0]/2;
        break;
      case 'bottom':
        break;
      case 'left':
        break;
      case 'left':
        break;
      default:
        // 式の値にマッチするものが存在しない場合に実行する文
        [break;]
    }

    swith() 
    return {
      x, y, ly, lx,
      part: holes[0].part,
      label: holes[0].label,
      status: holes.map(h => h.status)
    }
  }

  render() {
    const { isFetching, data } = this.props.PageData;

    return (
      <div id="mapping-wrap" className="">
        {
          data !== null &&
          <div>
            <div className="mapping-header">
              <h4>
                <span>{`${data.process}工程`}</span>
                <span>{data.inspection}</span>
                <span>{`${data.line == '1' ? 'ライン①' : data.line == '2' ? 'ライン②' : ''}`}</span>
                <span>{`Page${data.number}`}</span>
              </h4>
            </div>
            <div className="mapping-body">
              <div className="figure-wrap">
                <ul className="parts-info">
                  {
                    data.parts.map(part =>
                      <li key={part.pn}>
                        <span className="small">品番：</span><span>{part.pn}</span>
                        <span className="small">品名：</span><span>{part.name}</span>
                      </li>
                    )
                  }
                </ul>
                <div className="figure">         
                  <img src={data.path}/>
                  <svg>
                    {
                      Object.keys(data.holes).map(key => {
                        const point = data.holes[key][0].point.split(',');
                        return (
                          <g>
                            <circle cx={x} cy={y} r={4} fill="red" />
                            <text x={} y="40" font-size="10" fill="black"/>
                          </g>
                        )
                      })
                    }
                  </svg>
                </div>
              </div>
              
              <div className="control-panel">
              </div>
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
