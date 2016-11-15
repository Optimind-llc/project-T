import React, { Component, PropTypes } from 'react';
import { Table } from 'reactable';
import './customTable.scss';

class CustomTable extends Component {
  constructor(props, context) {
    super(props, context);
    this.state = {
    };
  }

  render() {
    return (
      <Table
        className="table"
        sortable={true}
        defaultSort={{column: '車種', direction: 'desc'}}
        data={this.props.data.parts}
      />
    );
  }
};

CustomTable.propTypes = {
  data: PropTypes.object
};

export default CustomTable;
