import React, { Component } from 'react';
import axios from './axios'
import '../node_modules/spectre.css/dist/spectre.min.css';
import './styles.css';
import FormContainer from './containers/FormContainer';
import {Animated} from "react-animated-css";

class OptIn extends Component {
  constructor(props){
    super(props)
    this.state = {
      loading : true,
      showLoading : false,
      tour : {
        owner : {

        }
      }
    }
  }
  componentWillMount(){
    this.loadTour()
  }

  loadTour = async () => {

    let {match} = this.props
    let {params} = match
    let {tour_id} = params
    // console.log('tour_id', tour_id)

    axios().get(`/optin/tour/${tour_id}`)
    .then(res => {
      if ( res.data ){
        let {tour, success} = res.data
        this.setState( {tour, loading : false} )
      }
      console.log('response', res.data)
    })
    .catch(console.error)
  }

  showLoading(){
    this.setState( {showLoading : true} )
  }

  hideLoading(){
    this.setState( {showLoading : false} )
  }

  render( ) {
    let {tour, loading, showLoading} = this.state
    let {owner, title} = tour
    let {main_picture, full_name} = owner

    return (
      <div>
        <div className="gradient"></div>
        <div className="container">
          <div className="columns">
            <div className="col-md-9 col-sm-12 centered">
              {
                !loading ?
                [
                  <Animated animationIn="bounceIn" animationOut="bounceOut" isVisible={ true }>
                    <h1 className="to_h1">
                      <img src={main_picture} className="to_logo box_shadow_blink"/>
                      <span>{full_name}</span>
                    </h1>
                    <FormContainer tour={tour} showLoading={this.showLoading.bind(this)} hideLoading={this.hideLoading.bind(this)}/>
                    
                  </Animated>,
                  <div style={{color: 'white', fontSize: 13, display: 'block', textAlign: 'center', margin: '25px 0'}}>
                    Powered By<br/>
                    ExpeditionApp
                    <img style={{display: 'block', width: 70, margin: '10px auto'}} src={'https://app.expeditionguate.com/wp-content/uploads/2020/03/expedition_icon.png'}/>
                  </div>
                ]
                :
                null
              }
              {
                loading || showLoading ?
                <div style={{position: 'fixed',background: showLoading ? 'rgba(0,0,0,0.4)' : 'transparent',top: 0,bottom: 0,left: 0,right: 0}}>
                  <div style={{justifyContent:'center',alignItems : 'center', flex : 1, top: -40, left: 0, right: 0, bottom: 0, position : 'fixed' }}>
                    <div style={{color : 'white', position: 'absolute', top: '50%', left: '50%', 
                      transform: `translateX(-50%) translateY(-50%)`,
                      }}>
                      <div className="lds-ring"><div></div><div></div><div></div><div></div></div>
                      Cargando...
                    </div>
                  </div>
                </div>
                :
                null
              }
              
            </div>

          </div>
        </div>
      </div>
    );
  }
}

export default OptIn;
