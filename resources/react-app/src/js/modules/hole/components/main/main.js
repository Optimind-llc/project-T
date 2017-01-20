import React, { Component, PropTypes } from 'react';
import iconCheck from '../../../../../assets/img/icon/check.svg';
// Styles
import './main.scss';
// Components
import Edit from '../edit/edit';
// import Create from '../create/create';

class Main extends Component {
  constructor(props, context) {
    super(props, context);
    this.state = {
      editModal: false,
      editting: null,
      createModal: false
    };
  }

  formatHole(hole) {
    const point = hole.point.split(',');
    const x = point[0]/2;
    const y = point[1]/2;
    let lx = x;
    let ly = y;

    switch (hole.direction) {
      case 'left':   lx = lx-22; break;
      case 'right':  lx = lx+22; break;
      case 'top':    ly = ly-22; break;
      case 'bottom': ly = ly+22; break;
      default: break;
    }

    const checkTxtColor = function(cR,cG,cB) {
      return (0.3*cR + 0.6*cG + 0.1*cB > 127) ? "#000000" : "#FFFFFF";
    }

    const tColor = checkTxtColor(parseInt(hole.color.slice(0,2), 16), parseInt(hole.color.slice(2,4), 16), parseInt(hole.color.slice(4,6), 16));

    return { x, y, ly, lx, tColor, ...hole}
  }

  render() {
    const { path, holes, activateHole, deactivateHole } = this.props;
    const { editModal, createModal, editting } = this.state;

    return (
      <div className="body bg-white">
        <div className="figure-wrap">
          <img src={path} width={1740/2}/>
          <svg>
          {
            holes.map(hole => {
              const h = this.formatHole(hole);
              return(
                <g key={h.id}>
                  <circle cx={h.x} cy={h.y} r={3} fill="red" />
                  {
                    h.shape === 'square' &&
                    <g>
                      <rect x={h.lx-10} y={h.ly-9} width="20" height="18" fill="#000000"/>
                      <rect x={h.lx-9} y={h.ly-8} width="18" height="16" fill={`#${h.color}`}/>
                      <text
                        x={h.lx}
                        y={h.ly}
                        dy="4"
                        fontSize="10"
                        fill={h.tColor}
                        textAnchor="middle"
                        fontWeight="bold"
                        >
                          {h.label}
                        </text>
                    </g>
                  }{
                    h.shape === 'circle' &&
                    <g>
                      <circle cx={h.lx} cy={h.ly} r={10} fill="#000000"/>
                      <circle cx={h.lx} cy={h.ly} r={9} fill={`#${h.color}`}/>
                      <text
                        x={h.lx}
                        y={h.ly}
                        dy="4"
                        fontSize="10"
                        fill={h.tColor}
                        textAnchor="middle"
                        fontWeight="bold"
                        >
                          {h.label}
                      </text>
                    </g>
                  }
                </g>
              )
            })
          }
          </svg>
        </div>
        <div className="holes-wrap">
          <table>
            <thead>
              <tr>
                <th>No.</th>
                <th>番号</th>
                <th>部品</th>
                <th>iPad<br/>表示</th>
                <th>機能</th>
              </tr>
            </thead>
            <tbody>
            {
              holes.map((h, i) =>
                <tr className="content" key={h.id}>
                  <td>{i+1}</td>
                  <td>{h.label}</td>
                  <td>{h.partName}</td>
                  <td>
                  {
                    h.status == 1 ?
                    <img
                      className="icon-checked"
                      src={iconCheck}
                      alt="iconCheck"
                      onClick={() => deactivateHole(h.id)}
                    /> :
                    <div
                      className="icon-check"
                      onClick={() => activateHole(h.id)}
                    ></div>
                  }
                  </td>
                  <td>
                    <button
                      className="dark edit"
                      onClick={() => this.setState({
                        editModal: true,
                        editting: h
                      })}
                    >
                      <p>編集</p>
                    </button>
                  </td>
                </tr>
              )
            }
            </tbody>
          </table>
        </div>
        {
          editModal &&
          <Edit
            hole={editting}
            path={path}
            message={'aaa'}
            meta={{}}
            close={() => this.setState({editModal: false})}
            update={(hole) => this.updateHole(hole)}
          />
        }{
          createModal &&
          <Create
            close={() => this.setState({createModal: false})}
            create={(hole) => this.createHole(hole)}
          />
        }
      </div>
    );
  }
};

Main.propTypes = {
  path: PropTypes.string.isRequired,
  holes: PropTypes.array.isRequired,
  activateHole: PropTypes.func.isRequired,
  deactivateHole: PropTypes.func.isRequired,
  editModal: PropTypes.bool.isRequired,
  createModal: PropTypes.bool.isRequired,
};

export default Main;
