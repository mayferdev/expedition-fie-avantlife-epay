import React, { Component } from 'react';
import '../node_modules/spectre.css/dist/spectre.min.css';
import './styles.css';

class Root extends Component {

  render() {
    return (
      <div className="container">
        <div className="columns">
          <div className="col-md-9 centered">
            <h3>Root</h3>
          </div>
        </div>
      </div>
    );
  }
}
export default Root;
