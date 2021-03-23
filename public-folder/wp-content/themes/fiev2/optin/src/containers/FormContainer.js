import React, {Component} from 'react';
// import CheckboxOrRadioGroup from '../components/CheckboxOrRadioGroup';
import SingleInput from '../components/SingleInput';
// import TextArea from '../components/TextArea';
// import Select from '../components/Select';
import axios from '../axios'
const number_format = require('locutus/php/strings/number_format')
var qs = require('qs');
// import { ImagePicker } from 'react-file-picker'
import ImageUploader from 'react-images-upload';
import Cards from 'react-credit-cards';
import 'react-credit-cards/es/styles-compiled.css';
import CreditCardInput from 'react-credit-card-input';
import OwlCarousel from 'react-owl-carousel2';
import 'react-owl-carousel2/lib/styles.css'; //Allows for server-side rendering.





class SegmentedControlTab extends Component {

	render (){
		let {values, selectedIndex} = this.props
		return (
			<div style={{display: 'flex', flexDirection : 'row', borderRadius:5, overflow : 'hidden'}}>
				{
					values.map( (value, index)=>{
						return (
							<div key={`option_${index}`} style={{flex:1, color : 'white', textAlign:'center', cursor : 'pointer',
								padding:'10px 0', opacity : selectedIndex == index ? 1 : 0.5, background: '#007dbe', }}
								onClick={()=>{ this.props.onTabPress(index) }}>
								{value}
							</div>
						)
					})
				}
			</div>
		)
	}
}

var defaultState = {
	first_name : '',
	last_name : '',
	age : '',
	dpi_passport : '',
	phone : '',
	email : '',
	collegiate: '', 
	emergency_contact: '', 
	emergency_contact_number: '',
	selectedPriceCategoryIndex : 0,
	selectedPaymentIndex: 0,
	showSuccess : false,
	showError : false,
	file: null,
	bank: '',
	receipt : '',

	fingerprint: '',
	name: '',
	type: '',
	credit_card_number: '',
	credit_card_expiry: '',
	credit_card_cvv: '',
	credit_card_brand: '',
	creditIsValid: false

}
class FormContainer extends Component {
	constructor(props) {
		super(props);
		this.state = defaultState
		this.handleFormSubmit = this.handleFormSubmit.bind(this);
		this.renderPrices = this.renderPrices.bind(this)
		this.renderPayment = this.renderPayment.bind(this)
		this.setStateForKey = this.setStateForKey.bind(this)	
		this.getTotalAndTitles = this.getTotalAndTitles.bind(this)
		this.updateFingerprint = this.updateFingerprint.bind(this)
	}
	
	componentDidMount() {
		// console.log('this.props.match.params.', this.props.match.params)
		//redirectParam

		// console.log('location.search', location.search.replace('?', ''));
		// 	//=> '?foo=bar'

		// const parsed = qs.parse(location.search.replace('?', ''));
		// console.log(parsed);
		this.updateFingerprint()
	}

	updateFingerprint(){
		let fingerprint = cybs_dfprofiler("VISANETGT_EXPEDITIONGROUP","development") //live 
		console.log('fingerprint is', fingerprint)
		this.setState({fingerprint})

		const that = this
		setTimeout(() => {
			that.updateFingerprint();
		}, 30000);
	}

	handleFormSubmit(e) {
		console.log('this.file', this.state.file)
		let {first_name, last_name, age, dpi_passport, phone, email, fingerprint, selectedPaymentIndex,
		nit, nit_name, nit_address, collegiate, emergency_contact, emergency_contact_number} = this.state
		let {tour} = this.props
		let {id, request_first_name, category, request_last_name, request_age, request_dpi_passport, request_phone, request_email, 
			request_collegiate, request_emergency_contact, request_emergency_contact_number, hide_payment} = tour
		e.preventDefault();


		var totalPrice = 0
		var fields = {}
		var qtySeats = 0
		if ( category && category.length ){
			category.forEach( ( categoryTemp, index) =>{
	      categoryTemp.prices.forEach( ( itemPrice, index) =>{
					qtySeats += parseInt(this.state[itemPrice.id] || 0)
					totalPrice += itemPrice.price * parseInt(this.state[itemPrice.id] || 0)
					if ( parseInt(this.state[itemPrice.id] || 0) ){
						fields[itemPrice.id] = parseInt(this.state[itemPrice.id] || 0)
					}
		    })

	    })
		}

		if ( totalPrice == 0 && qtySeats < 1){
			alert('Por favor agrega al menos una opción para continuar')
			return;
		}

		var errors = false
		if ( request_first_name && first_name.length < 1 ){
			errors = true
		}
		if ( request_last_name && last_name.length < 1 ){
			errors = true
		}
		if ( request_age && age.length < 1 ){
			errors = true
		}
		if ( request_dpi_passport && dpi_passport.length < 1 ){
			errors = true
		}
		if ( request_phone && phone.length < 1 ){
			errors = true
		}
		if ( request_email && email.length < 1 ){
			errors = true
		}

		if ( request_collegiate && collegiate.length < 1 ){
			errors = true
		}
		if ( request_emergency_contact && emergency_contact.length < 1 ){
			errors = true
		}
		if ( request_emergency_contact_number && emergency_contact_number.length < 1 ){
			errors = true
		}

		if (errors){
			alert('Por favor completa todos los campos para continuar')
			return;
		}


		const parsed = qs.parse(location.search.replace('?', ''));
		const {utm_medium, utm_source} = parsed
		fields.fingerprint = fingerprint
		
		let formPayload = {...fields,
			first_name, last_name, age, dpi_passport, phone, email, collegiate, emergency_contact, emergency_contact_number,
			utm_medium, utm_source : utm_source || 'web'
		}
		if ( !hide_payment ){
			if ( selectedPaymentIndex == 1 ){
				const {name, credit_card_number, credit_card_expiry, credit_card_cvv, type, creditIsValid} = this.state
				if ( !name ){
					return alert('Por favor incluye el nombre que aparece en la tarjeta')
				}
				if ( credit_card_number.length < 15 ){
					return alert('Por favor escribe un número de tarjeta válido')
				}
				if ( credit_card_expiry.length < 4 ){
					return alert('Por favor ingresa la fecha de expiración')
				}
				if ( credit_card_cvv.length < 3 ){
					return alert('Por favor ingresa el código que aparece atrás de tu tarjeta')
				}
				if ( !creditIsValid ){
					return alert('Por favor ingresa el código que aparece atrás de tu tarjeta')
				}
	
				// credit card
				formPayload = {
					...formPayload,
					payment_type: 'card',
					credit_card_name : name,
					credit_card_number,
					credit_card_expiry,
					credit_card_cvv,
					credit_card_brand : type,
					fingerprint,
					alias : 'Credit Card' 
				}
	
			}else{
				// deposit
				const {file, bank, receipt} = this.state
				if ( !file ){
					return alert('Por favor incluye el comprobante del depósito para continuar')
				}
				if ( receipt.length < 1 ){
					return alert('Por favor escribe el número de boleta')
				}
				if ( bank.length < 1 ){
					return alert('Por favor escribe el banco donde hiciste el depósito')
				}
				
				formPayload = {
					...formPayload,
					payment_type: 'deposit',
					file, 
					bank, 
					receipt
				}
			}
		}

		this.props.showLoading()

		console.log('Send this in a POST request:', formPayload);
		//axios().post(`/tours/${id}/booking_expeditioners_info/web`, formPayload)

		axios().defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded'
		var form = new FormData()
		if ( formPayload ){
				Object.keys(formPayload).forEach(key => {
						form.append(key, formPayload[key])
				})
		}
		axios().post(`/tours/${id}/book_and_pay/web`, form)
    .then(res => {
			console.log('response', res.data)

			this.props.hideLoading()
      if ( res.data.success ){
				this.setState( {showSuccess : true} )
				setTimeout( function(){
					this.setState(defaultState)
				}.bind(this), 3000)
      }else{
				this.setState( {showError : res.data.message} )
				setTimeout( function(){
					this.setState( {showError : false} )
				}.bind(this), 3000)
			}
      
    })
		.catch(console.error)

		axios().defaults.headers.post['Content-Type'] = 'application/json'
		
	}

	setStateForKey(key, value ){
		// console.log(key, ' is ', value);
		var stateCopy = Object.assign({}, this.state);
		stateCopy[key] = value;
		this.setState(stateCopy);
	}

	getTotalAndTitles(){
		let {tour} = this.props
		let {category} = tour
		var categoriesTitles = []
		var totalPrice = 0
		if ( category && category.length ){
			category.forEach( ( categoryTemp, index) =>{
	      categoriesTitles.push(categoryTemp.category)

	      categoryTemp.prices.forEach( ( itemPrice, index) =>{
					totalPrice += itemPrice.price * parseInt(this.state[itemPrice.id] || 0)
		    })
	    })
		}
		return {totalPrice, categoriesTitles}
	}
	
	renderPrices(){
		// console.log('this.props.tours', this.props.tours)
		let {tour} = this.props
		if ( !tour)
			return

		let {category, main_color, cancellation_policy, currency_symbol} = tour
		let {totalPrice, categoriesTitles} = this.getTotalAndTitles()
		
		// Config.CURRENCY_SYMBOL
		let percentPrice = currency_symbol+ number_format(totalPrice* (tour.credit_card_percents_fee*0.01)  , 2)
		let factor = this.state.selectedPaymentIndex == 1 ? (1 + (tour.credit_card_percents_fee*0.01) ) : 1
		totalPrice = currency_symbol + number_format(totalPrice*factor, 2)
		

		var item = null
		if ( this.state.selectedPriceCategoryIndex != null && category && category.length > 0 ){
			item = category[ this.state.selectedPriceCategoryIndex ]
		}



		


		const options = {
				items: 3,
				nav: true,
				dots: false,
				rewind: false,
				autoplay: false,
				autoWidth: true,
				margin: 2,
				navText: ['Atrás', 'Siguiente'],
		};

		const events = {
			onDragged: function(event) {},
			onChanged: function(event) {}
	};
		
		return(
			<div style={{flexDirection : 'column', overflow : 'hidden', marginTop :20, marginHorizontal : 10}}>

				<OwlCarousel ref="car" options={options} events={events} >
					{
						categoriesTitles.map((_cat, _index)=>{
							return (
							<div style={{backgroundColor: main_color, 
								lineHeight: 3, textAlign: 'center',
								padding: '0 20px',
								width: 205,
								opacity: _index == this.state.selectedPriceCategoryIndex ? 1 : 0.7,
								color: 'white', cursor: 'pointer', paddingVertical: 10, paddingHorizontal: 5}}
								onClick={()=>{
									this.setState({
										selectedPriceCategoryIndex: _index,
									});
								}}
								>
									{_cat}
								</div>
							)
						})
					}
				</OwlCarousel>

				{/* <SegmentedControlTab
	        values={categoriesTitles}
	        selectedIndex={this.state.selectedPriceCategoryIndex}
	        onTabPress={ (index) => {
			      this.setState({
			        selectedPriceCategoryIndex: index,
			      });
			    }}
			    tabsContainerStyle={{ marginHorizontal : 15}}
          tabStyle={{ borderColor: main_color }}
          activeTabStyle={{ backgroundColor: main_color,}}
          tabTextStyle={{ color: main_color, }}
          // activeTabTextStyle={{ color: '#888888' }} 
        /> */}

        <div style={{marginHorizontal : 10, paddingTop : 15, }} key={`price_category_id_${item.id}`}>
    			<p style={{fontSize : 12, marginBottom : 15, marginLeft : 5, backgroundColor : 'transparent', color : 'gray', }}>
	        	{item.desc}
	        </p>

        	{
		      	item.prices && item.prices.length > 0 && item.prices.map( function(itemPrice, index){

		      		var price = currency_symbol + number_format(0, 2)
							if ( itemPrice.price ){
								price = currency_symbol + number_format(itemPrice.price, 2)
							}

							let value = isNaN(this.state[itemPrice.id]) ? 0 : this.state[itemPrice.id]
							
	          	return (
	          		// <div
	          		// 	key={`price_id_${itemPrice.id}`}
				        // 	style={{paddingHorizontal : 15, paddingVertical : 10, flexDirection : 'row', width : '100%', justifyContent : 'space-between',
				        // 					backgroundColor : (index % 2 ? 'white' : 'rgba(0,0,0,0.05)'), position : 'relative' }}
								// 	>
								<div
	          			key={`price_id_${itemPrice.id}`}
				        	style={{paddingHorizontal : 15, paddingVertical : 10, flexDirection : 'row', width : '100%', justifyContent : 'space-between',
													backgroundColor : value ? main_color : 'white', 
													borderWidth: 1, borderColor: main_color, borderStyle: 'solid',
													borderRadius: 10, marginBottom: 5,
													cursor: 'pointer',
													position : 'relative',  }}
													onClick={()=>{
														if ( itemPrice.available < 1 ){
															alert('No hay espacio disponible, intenta otra opción')
															return
														}

														const _cat = category[this.state.selectedPriceCategoryIndex]
														let newObj = {}
														_cat.prices.forEach( ( _itemPrice, index) =>{
															newObj[_itemPrice.id] = 0
															// this.setStateForKey(_itemPrice.id, 0 )
															// console.log(_itemPrice.id, ' now 0')
														})
														newObj[itemPrice.id] = value + 1
														this.setState(newObj)
													}}
				        	>
									<p style={{ fontSize : 13, paddingRight : 75, paddingLeft: 10, paddingTop :9, fontWeight :'bold', minHeight : 30, marginTop : 5, marginLeft : 5, width : 210, 
										color : value ? 'white' : main_color, textAlign : 'left', fontWeight: 'bold' }}>
										{itemPrice.title}
					        </p>
									<p style={{fontSize : 13, marginTop : 5, 
										//marginLeft : 5, marginRight : 90,
										position : 'absolute',
										right : 20, top : 9,
										backgroundColor : 'transparent', 
										color : value ? 'white' : main_color, textAlign : 'right', width : 80, fontWeight: 'bold'}}>
					        	{itemPrice.available}/{itemPrice.max_capacity}
					        </p>
									
									{/* <p style={{fontSize : 13, marginTop : 5, 
										//marginLeft : 5, marginRight : 90,
										position : 'absolute',
										right : 100, top : 9,
					        	backgroundColor : 'transparent', color : 'gray', textAlign : 'right', width : 80, }}>
					        	{price}
					        </p> */}

					        {/* <div style={{position : 'absolute', right : 0, top : 5, width : 90, bottom : 5, justifyContent : 'space-between'}}>

					        	<div style={{backgroundColor : '#ccc', cursor : 'pointer', display :'inline-block', width : 27, fontSize : 20, color : 'gray', textAlign : 'center'}} onClick={()=>{
											if ( this.state[itemPrice.id] > 0 ){
												this.setStateForKey(itemPrice.id, parseInt(this.state[itemPrice.id]) - 1 )
											}
										}}>
											-
										</div>
										<p style={{fontSize : 16, color : 'gray', display :'inline-block', alignSelf : 'center', textAlign : 'center', width : 30,}}>
						        	{parseInt(this.state[itemPrice.id] || 0 )}
						        </p>
										<div style={{backgroundColor : '#ccc', cursor : 'pointer', display :'inline-block', width : 27, fontSize : 20, color : 'gray', textAlign : 'center'}} onClick={()=>{
												let value = isNaN(this.state[itemPrice.id]) ? 0 : this.state[itemPrice.id]
												this.setStateForKey(itemPrice.id, value + 1 )
										}}>
											+
										</div>
					        	
					        </div> */}


				        </div>
	          		)
	          }.bind(this))
		      }
        </div>

        <div style={{height : 1,  backgroundColor : '#eee', marginHorizontal : 10, marginVertical : 10}}/>
				
				{
					this.state.selectedPaymentIndex == 1 ?
					<div style={{marginHorizontal : 10, flexDirection : 'row', justifyContent : 'space-between', display :'flex', marginBottom : 20}}>
						<p style={{fontSize : 14, color : 'gray', fontWeight : 'bold', textAlign : 'right', marginLeft : 5}}>
								Recargo por tarjeta
						</p>
						<p style={{fontSize : 14, color : 'gray', fontWeight : 'bold', textAlign : 'right', width : 140}}>
								{percentPrice}
						</p>
					</div>
					: null
				}

        <div style={{marginHorizontal : 10, flexDirection : 'row', justifyContent : 'space-between', display :'flex', marginBottom : 20}}>
        	<p style={{fontSize : 20, color : 'gray', fontWeight : 'bold', textAlign : 'right', marginLeft : 5}}>
		        	Total
	        </p>
	        <p style={{fontSize : 20, color : 'gray', fontWeight : 'bold', textAlign : 'right', width : 140}}>
		        	{totalPrice}
	        </p>
        </div>
				
        {
        	// cancellation_policy.length > 0 && 
        	// <div style={{marginHorizontal : 10, paddingVertical : 20, flexDirection : 'column', justifyContent : 'space-between'}}>
	        // 	<p style={{marginVertical : 10, fontSize : 20, color : 'gray', textAlign : 'left', marginLeft : 5}}>
					// 		cancellation policy
		      //   </p>
		      //   <p style={{fontSize : 13, color : 'gray', }}>
			    //     	{cancellation_policy}
		      //   </p>
	        // </div>
        }

			</div>
			)
	}

	renderPayment(){
		// console.log('this.props.tours', this.props.tours)
		let {tour} = this.props
		if ( !tour)
			return

		let {category, main_color, cancellation_policy, currency_symbol} = tour
		let {totalPrice, categoriesTitles} = this.getTotalAndTitles()
		
		// Config.CURRENCY_SYMBOL
		totalPrice = currency_symbol + number_format(totalPrice, 2)

		var item = null
		if ( this.state.selectedPaymentIndex != null && category && category.length > 0 ){
			item = category[ this.state.selectedPaymentIndex ]
		}

		let segmentOptions = ['Depósito']
		if ( !tour.non_profit ){
			segmentOptions.push('Tarjeta')
		}

		return(
			<div style={{flexDirection : 'column', overflow : 'hidden', marginTop :20, marginHorizontal : 10}}>

				<SegmentedControlTab
	        values={segmentOptions}
	        selectedIndex={this.state.selectedPaymentIndex}
	        onTabPress={ (index) => {
			      this.setState({
			        selectedPaymentIndex: index,
			      });
			    }}
			    tabsContainerStyle={{ marginHorizontal : 15}}
          tabStyle={{ borderColor: main_color }}
          activeTabStyle={{ backgroundColor: main_color,}}
          tabTextStyle={{ color: main_color, }}
          // activeTabTextStyle={{ color: '#888888' }} 
        />

				{
					this.state.selectedPaymentIndex == 0 ?
					<div style={{ }}>
						<ImageUploader
										buttonText='Adjunta una imágen'
										label={'Tamaño máximo: 2mb, válidos: jpg|gif|png'}
										onChange={this.onDrop.bind(this)}
										imgExtension={['.jpg', '.gif', '.png', '.jpeg']}
										maxFileSize={15242880}
										withPreview={true}
										withIcon={false}
							/>
						<SingleInput
							inputType={'text'}
							title={'Número de boleta o transferencia *'}
							name={'receipt'}
							controlFunc={(e)=>{
								this.setState({ receipt: e.target.value })
							}}
							content={this.state.receipt}
							placeholder={'Por favor ingresa el número de boleta o transferencia'} />
						<SingleInput
							inputType={'text'}
							title={'Banco *'}
							name={'bank'}
							controlFunc={(e)=>{
								this.setState({ bank: e.target.value })
							}}
							content={this.state.bank}
							placeholder={'Por favor ingresa el banco *'} />
					</div>
					:
					<div style={{marginTop: 30}}>
						<Cards
							cvc={this.state.credit_card_cvv}
							expiry={this.state.credit_card_expiry.replace('/', '') || ''}
							// focus={this.state.focus}
							focused={this.state.focus}
							name={this.state.name}
							number={this.state.credit_card_number}
							callback={(issuer, isValid)=>{
								console.log( 'issuer, isValid', issuer, isValid )
								this.setState({type: issuer, creditIsValid: isValid})
							} }
						/>
						<div style={{height: 30}}/>
											
						<SingleInput
							inputType={'text'}
							title={'Nombre en la tarjeta *'}
							name={'name'}
							controlFunc={(e)=>{
								this.setState({ name: e.target.value })
							}}
							content={this.state.name}
							onFocus={(e => this.setState({ focus: 'name' }))}
							placeholder={'Por favor ingresa el nomre que aparece en la tarjeta'} />
						
							<CreditCardInput
								containerStyle={{width:'100%'}}
								customTextLabels={{
									invalidCardNumber: 'El número de la tarjeta es inválido',
									expiryError: {
										invalidExpiryDate: 'La fecha de expiración es inválida',
										monthOutOfRange: 'El mes de expiración debe estar entre 01 y 12',
										yearOutOfRange: 'El año de expiración no puede estar en el pasado',
										dateOutOfRange: 'La fecha de expiración no puede estar en el pasado'
									},
									invalidCvc: 'El código de seguridad es inválido',
									invalidZipCode: 'El código postal es inválido',
									cardNumberPlaceholder: 'Número de tarjeta',
									expiryPlaceholder: 'MM/AA',
									cvcPlaceholder: 'COD',
									zipPlaceholder: 'C.P.'
								}}
								
								cardNumberInputProps={{ value: this.state.credit_card_number, onChange: (e =>
									this.setState({credit_card_number: e.target.value})
								),
								onFocus:(e => this.setState({ focus: 'number' }))
							}}
								cardExpiryInputProps={{ value: this.state.credit_card_expiry, onChange: (e =>
									this.setState({credit_card_expiry: e.target.value})
								),
								onFocus:(e => this.setState({ focus: 'expiry' }))
							}}
								cardCVCInputProps={{ value: this.state.credit_card_cvv, onChange: (e =>
									this.setState({credit_card_cvv: e.target.value})
								),
								onFocus:(e => this.setState({ focus: 'cvc' })),
								onBlur:(e => this.setState({ focus: '' }))
							}}
								fieldClassName="input"
							/>

					</div>
				}

					

				

			</div>
			)
	}

	onDrop(pictureFiles, pictureDataURLs) {
		console.log('pictureFiles', pictureFiles)
		// console.log('pictureDataURLs', pictureDataURLs)
		this.setState({
			file: pictureFiles[ pictureFiles.length - 1 ],
		});
	}


	render() {
		let {showError, showSuccess} = this.state
		let {tour} = this.props
		let {request_first_name, request_last_name, request_age, request_email, request_dpi_passport, 
			request_collegiate, request_emergency_contact, request_emergency_contact_number,
			request_phone, title, main_color, gallery} = tour
		let {large} = gallery[0]
		let {url} = large

		return (
			<form className="form_container" onSubmit={this.handleFormSubmit}>
				<div style={{ backgroundImage : `url(${url})`, height : 150, backgroundSize:'cover', margin: '0 -20px', borderRadius : '10px 10px 0 0'}}></div>
				<h5>Program : {title}</h5>

				<div dangerouslySetInnerHTML={{__html:tour.desc}}></div>

				<p style={{fontWeight:'100', fontSize:16, marginBottom : 20}}>Por favor completa los campos a continuación:</p>

				{
					this.renderPrices()
				}

				<h4 style={{marginTop: 0}}>Voluntario</h4>

				{
					request_first_name && 
					<SingleInput
						inputType={'text'}
						title={'Nombres *'}
						name={'first_name'}
						controlFunc={(e)=>{
							this.setState({ first_name: e.target.value })
						}}
						content={this.state.first_name}
						placeholder={'Por favor ingresa tus nombres'} />
				}
				{
					request_last_name && 
					<SingleInput
						inputType={'text'}
						title={'Apellidos *'}
						name={'last_name'}
						controlFunc={(e)=>{
							this.setState({ last_name: e.target.value })
						}}
						content={this.state.last_name}
						placeholder={'Por favor ingresa tus apellidos'} />
				}
				{
					request_age && 
					<SingleInput
						inputType={'number'}
						title={'Edad *'}
						name={'age'}
						controlFunc={(e)=>{
							this.setState({ age: e.target.value })
						}}
						content={this.state.age}
						placeholder={'Por favor ingresa tu edad'} />
				}
				{
					request_dpi_passport && 
					<SingleInput
						inputType={'text'}
						title={'DPI o pasaporte *'}
						name={'dpi_passport'}
						controlFunc={(e)=>{
							this.setState({ dpi_passport: e.target.value })
						}}
						content={this.state.dpi_passport}
						placeholder={'Por favor ingresa tu DPI o pasaporte'} />
				}
				{
					request_phone && 
					<SingleInput
						inputType={'number'}
						title={'Teléfono *'}
						name={'phone'}
						controlFunc={(e)=>{
							this.setState({ phone: e.target.value })
						}}
						content={this.state.phone}
						placeholder={'Por favor ingresa tu teléfono'} />
				}

				{
					request_email && 
					<SingleInput
						inputType={'email'}
						title={'Correo electrónico *'}
						name={'email'}
						controlFunc={(e)=>{
							this.setState({ email: e.target.value })
						}}
						content={this.state.email}
						placeholder={'Por favor ingresa tu correo electrónico'} />
				}

				{
					request_collegiate && 
					<SingleInput
						inputType={'text'}
						title={'No. de colegiado *'}
						name={'collegiate'}
						controlFunc={(e)=>{
							this.setState({ collegiate: e.target.value })
						}}
						content={this.state.collegiate}
						placeholder={'Por favor ingresa tu no. de colegiado'} />
				}

				{
					request_emergency_contact && 
					<SingleInput
						inputType={'text'}
						title={'Contacto de emergencia *'}
						name={'emergency_contact'}
						controlFunc={(e)=>{
							this.setState({ emergency_contact: e.target.value })
						}}
						content={this.state.emergency_contact}
						placeholder={'Por favor ingresa tu contacto de emergencia'} />
				}

				{
					request_emergency_contact_number && 
					<SingleInput
						inputType={'text'}
						title={'No. de contacto de emergencia *'}
						name={'emergency_contact_number'}
						controlFunc={(e)=>{
							this.setState({ emergency_contact_number: e.target.value })
						}}
						content={this.state.emergency_contact_number}
						placeholder={'Por favor ingresa el no. de tu contacto de emergencia'} />
				}

				{
					!tour.hide_payment && 
					[
						<h4 style={{marginTop: 40}}>Forma de Pago</h4>,
						this.renderPayment()
					]
				}

				{
					tour.footer_info ?
					<div style={{marginTop: 20, marginBottom: 20}} dangerouslySetInnerHTML={{__html:tour.footer_info}} ></div>
					: null
				}

				{
					showError ?
					<div class="toast toast-error">
						<p style={{padding: 20, background: '#ff5858',borderRadius: 5,color: 'white',textAlign: 'center'}}>
							{showError}
						</p>
					</div>
					: null
				}
				{
					showSuccess ?
					<div>
						<p style={{padding: 20, background: '#5a9360',borderRadius: 5,color: 'white',textAlign: 'center'}}>
							Reserva realizada exitósamente!
						</p>
					</div>
					: null
				}
				
				<input
					style={{marginTop : 20}}
					type="submit"
					className="btn btn-primary"
					value="Enviar"/>

				{/* <div style={{background:'#00ac9f', height: 1, marginTop: 20}}></div>

				<div style={{color: '#00ac9f', fontSize: 16, marginTop: 20, textAlign:'center'}}>
					<p style={{}}>Reserva facilmente con</p>
					<p><span style={{background:'#00ac9f', color: 'white', 
					padding: '7px 15px', borderRadius: 5, display: 'inline-block'}}>Easy book</span></p>
					<p style={{color: '#00ac9f'}}>En</p>
				</div>

				<div className="text-center" style={{marginTop : 10}}>
					<a href="https://expeditioners.gt" target="_blank">
						<img src="https://app.expeditionguate.com/wp-content/uploads/2019/09/expedition_logo.png" width={270}/>
					</a>
				</div>

				
				<div className="app_links text-center">
					<a href="https://apps.apple.com/gt/app/expeditionapp/id1355510478" target="_blank"  className="app_link">
						<img src={'https://app.expeditionguate.com/wp-content/uploads/2019/03/Appstore_button.png'}/>
					</a>
					<a href="https://play.google.com/store/apps/details?id=gt.expeditioners.app" target="_blank"  className="app_link">
						<img src={'https://app.expeditionguate.com/wp-content/uploads/2019/03/Googleplay_button.png'}/>
					</a>
				</div>

				<div style={{color: '#00ac9f', fontSize: 16, margin:'20px 60px 0', textAlign:'center' }}>
					<p>Gana y acumula puntos con cada viaje y canjea por más aventuras</p>
				</div> */}

			</form>
		);
	}
}



function cybs_dfprofiler(merchantID, environment) {

  if (environment.toLowerCase() == 'live') {
    var org_id = 'k8vif92e';
  } else {
    var org_id = '1snn5n9w';
  }
  var sessionID = new Date().getTime();

  //One-Pixel Image Code
  var paragraphTM = document.createElement("p");
  var str = "";
  str = "background:url(https://h.online-metrix.net/fp/clear.png?org_id=" + org_id + "&session_id=" + merchantID + sessionID + "&m=1)";
  paragraphTM.styleSheets = str;
  document.body.appendChild(paragraphTM);
  var img = document.createElement("img");
  str = "https://h.online-metrix.net/fp/clear.png?org_id=" + org_id + "&session_id=" + merchantID + sessionID + "&m=2";
  img.src = str;
  img.alt = "";
  document.body.appendChild(img);

  //Flash Code
  var objectTM = document.createElement("object");
  objectTM.data = "https://h.online-metrix.net/fp/fp.swf?org_id=" + org_id + "&session_id=" + merchantID + sessionID;
  objectTM.type = "application/x-shockwave-flash";
  objectTM.width = "1";
  objectTM.height = "1";
  objectTM.id = "thm_fp";

  var param = document.createElement("param");
  param.name = "movie";
  param.value = "https://h.online-metrix.net/fp/fp.swf?org_id=" + org_id + "&session_id=" + merchantID + sessionID;
  objectTM.appendChild(param);
  document.body.appendChild(objectTM);
  //JavaScript Code
  var tmscript = document.createElement("script");
  tmscript.src = "https://h.online-metrix.net/fp/tags.js?org_id=" + org_id + "&session_id=" + merchantID + sessionID;
  tmscript.type = "text/javascript";
  document.body.appendChild(tmscript);
  return sessionID;

}


export default FormContainer;

