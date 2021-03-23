<template>
    <vx-card class="" title="">
      <vs-row vs-type="flex" vs-justify="center" class="mt-8">
        <vs-col vs-type="flex" vs-justify="center" vs-align="center">
        </vs-col>
      </vs-row>  
        <div class="mt-5">
            <form-wizard color="#197CBF" :title="null" :subtitle="null" backButtonText="Atras" nextButtonText="Siguiente" finishButtonText="Pagar" @on-complete="validateStep3">

                <tab-content title="Registro" class="mb-5 mt-2" icon="feather icon-user-check" :before-change="validateStep1">
                    <vs-row>
                        <vs-col  vs-type="flex" vs-justify="center" vs-align="center" vs-lg="5" vs-md="4" vs-sm="12">
                              <div class="mr-8"
                                :style="{
                                    'background-image':`url(${this.tour.gallery[0].full.url})`,
                                    'width': '100%',
                                    'height': '100%',
                                    'border-radius': '5px',
                                    'background-position-x': 'center',
                                    'background-position-y': 'bottom',
                                    'background-size': 'cover'
                                  }"></div>
                        </vs-col>
                        <vs-col  vs-justify="center" vs-align="center" vs-lg="7" vs-md="8" vs-sm="12">
                            <vs-row vs-type="flex" vs-justify="center" class="mb-4">
                                <vs-col  vs-type="flex" vs-justify="center" vs-align="center">
                                    <h1 class="">{{this.tour.title}}</h1>
                                </vs-col>
                            </vs-row>
                            <vs-row vs-type="flex" vs-justify="center">
                                <vs-col  vs-type="flex" vs-justify="center" vs-align="center">
                                    <h3 class="">Registro</h3>
                                </vs-col>
                            </vs-row>
                            <vs-row vs-justify="center">
                                <vs-col  vs-type="flex" vs-justify="center" vs-align="center">
                                    <h5 class="">Ingresa tu Información general</h5>
                                </vs-col>
                            </vs-row>
                            <!-- tab 1 content -->
                            <div class="vx-row">
                                <!-- First Name -->
                                <div class="vx-col md:w-1/2 w-full mt-5" v-if="tour.request_first_name">
                                    <vs-input
                                    label="Nombre" color="#197CBF"
                                    name="fistName"
                                    v-model="formData.name" class="w-full" />
                                    <span class="text-danger text-sm"  v-show="errors.name">El nombre es obligatorio</span>
                                </div>
                                <!-- Apellido -->
                                <div class="vx-col md:w-1/2 w-full mt-5" v-if="tour.request_last_name">
                                    <vs-input label="Apellido"  color="#197CBF" v-model="formData.lastName" class="w-full" />
                                    <span class="text-danger text-sm"  v-show="errors.lastName">El apellido es obligatorio</span>
                                </div>
                                <!-- Email -->
                                <div class="vx-col md:w-1/2 w-full mt-5" v-if="tour.request_email">
                                    <vs-input type="email" color="#197CBF" label="Correo"  v-model="formData.email" class="w-full" />
                                    <span class="text-danger text-sm"  v-show="errors.email">El correo es obligatorio</span>
                                </div>
                                <!-- sex -->
                                <div class="vx-col md:w-1/2 w-full mt-5" v-if="tour.request_sex">
                                    <vs-select v-model="formData.sex" color="#197CBF" class="w-full select-large" label="Sexo">
                                        <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item,index) in sexOptions" class="w-full" />
                                    </vs-select>
                                    <span class="text-danger text-sm"  v-show="errors.sex">Seleccion pendiente</span>
                                </div>
                                <!-- age -->
                                <div class="vx-col md:w-1/2 w-full mt-5" v-if="tour.request_age">
                                    <vs-input type="number" color="#197CBF" label="Edad"  v-model="formData.age" class="w-full" />
                                    <span class="text-danger text-sm"  v-show="errors.age">La edad es obligatoria</span>
                                </div>
                                <!-- nacionalidad -->
                                <div class="vx-col md:w-1/2 w-full mt-5" v-if="tour.request_nationality">
                                    <vs-select v-model="formData.nationality" color="#197CBF" class="w-full select-large" label="Nacionalidad">
                                        <vs-select-item :key="index" :value="item" :text="item" v-for="(item,index) in nationalityOptions" class="w-full" />
                                    </vs-select>
                                    <span class="text-danger text-sm"  v-show="errors.nationality">La nacionalidad es obligatoria</span>
                                </div>
                                <!-- Pais residencia -->
                                <div class="vx-col md:w-1/2 w-full mt-5" v-if="tour.request_nationality">
                                    <vs-input label="País de residencia" color="#197CBF" v-model="formData.residence" class="w-full" />
                                    <span class="text-danger text-sm"  v-show="errors.residence">El país de residencia es obligatorio</span>
                                </div>
                                <!-- Departamento - estado -->
                                <div class="vx-col md:w-1/2 w-full mt-5" v-if="tour.request_nationality">
                                    <vs-input label="Departamento / Estado" color="#197CBF" v-model="formData.depState" class="w-full" />
                                    <span class="text-danger text-sm"  v-show="errors.depState">El país de residencia es obligatorio</span>
                                </div>
                                <!-- Ciudad - Municipio -->
                                <div class="vx-col md:w-1/2 w-full mt-5" v-if="tour.request_nationality">
                                    <vs-input label="Ciudad / Municipio" color="#197CBF" v-model="formData.citMun" class="w-full" />
                                    <span class="text-danger text-sm"  v-show="errors.citMun">La ciudad o municipio es obligatorio</span>
                                </div>
                                <!-- <div class="vx-col md:w-1/2 w-full mt-5 ">
                                    <vs-input
                                        label="Dirección" color="#197CBF" v-model="formData.direcction_nit" class="w-full"
                                    />
                                    <small>Aquí enviaremos tu kit del participante, aplica restricciones.</small>
                                    <span
                                        class="text-danger text-sm"
                                        v-show="errors.direcction_nit"
                                    >Por favor agrega la dirección</span>
                                </div> -->
                                <!-- dpi -->
                                <div class="vx-col md:w-1/2 w-full mt-5" v-if="tour.request_dpi_passport">
                                    <vs-input type="number" label="DPI/Pasaporte" color="#197CBF" v-model="formData.dpiPass" class="w-full" />
                                    <span class="text-danger text-sm"  v-show="errors.dpiPass">El DPI o pasaporte es obligatorio</span>
                                </div>
                                <!-- telefono -->
                                <div class="vx-col md:w-1/2 w-full mt-5" v-if="tour.request_phone">
                                    <vs-input type="number" label="Teléfono Celular" color="#197CBF" v-model="formData.phone" class="w-full" />
                                    <small>No olvides agregar el prefijo numérico de tu país</small>
                                    <span class="text-danger text-sm"  v-show="errors.phone">El teléfono es obligatorio</span>
                                </div>
                                <!-- ocupacion -->
                                <div class="vx-col md:w-1/2 w-full mt-5" v-if="tour.request_occupation">
                                    <vs-select v-model="formData.occupation" color="#197CBF" class="w-full select-large" label="Ocupación">
                                        <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item,index) in ocupationOptions" class="w-full" />
                                    </vs-select>
                                    <span class="text-danger text-sm"  v-show="errors.occupation">La ocupación es obligatoria</span>
                                </div>
                                
                            </div>
                            <!-- terms and cons | checkboxes -->
                            <vs-row vs-type="flex" class="mt-5" vs-justify="center">
                                <vs-col class="mt-4"  vs-type="flex" vs-justify="center" vs-align="center">
                                <ul class="centerex">
                                    <li class="mt-5" style="margin-left:50px;">
                                        <a href="https://fie.gt/terminos-y-condiciones/" target="_blank">Términos y Condiciones</a>
                                    </li>
                                    <li class="mt-5">
                                        <vs-checkbox color="success" v-model="formData.termsCond">Acepto los términos y condiciones</vs-checkbox>
                                    </li>
                                    <li>
                                        <span class="text-danger text-sm"  v-show="errors.termsCond">Debes aceptar los términos y condiciones para continuar</span>
                                    </li>
                                    <li class="mt-5">
                                        <vs-checkbox color="success">Deseo ser parte del boletín semanal</vs-checkbox>
                                    </li>
                                </ul>
                                </vs-col>
                                
                            </vs-row>
                            <!-- END: tab 1 content -->
                        </vs-col>
                    </vs-row>
                    <!-- --- -->
                </tab-content>

                <!-- tab 2 content -->
                <tab-content title="Detalles" class="mb-5" icon="feather icon-edit-1" :before-change="validateStep2" v-if="extra_info">
                    <vs-row vs-type="flex" vs-justify="center">
                        <vs-col  vs-type="flex" vs-justify="center" vs-align="center">
                            <h1 class="">Preguntas</h1>
                        </vs-col>
                    </vs-row>
                    <vs-row vs-type="flex" vs-justify="center">
                        <vs-col  vs-type="flex" vs-justify="center" vs-align="center">
                            <h5 class="">Responde las siguientes preguntas.</h5>
                        </vs-col>
                    </vs-row>

                    <div class="vx-row mb-5" v-for="(question, index) in extra_questions" :key="index">
                        <h5>{{question.titulo}}</h5>
                        <div class="vx-col w-full mt-3 flex-col flex items-center">
                            <vs-input color="#197CBF" 
                                v-model="extra_answers[index]" :placeholder="question.placeholder" class="w-4/5"
                            />
                            <!-- <span
                                class="text-danger text-sm"
                                v-show="errors.extra_answers[index]"
                            >Por favor específica.</span> -->
                        </div>
                    </div>

                </tab-content>

                <!-- tab 3 content -->
                <tab-content title="Pago" class="mb-5" icon="feather icon-credit-card" :before-change="validateStep3">
                    <vs-divider color="#197CBF"></vs-divider>
                    <div class="vx-row">
                        <div class="vx-col w-full mt-5">
                            <h2>Precios</h2>
                            <div v-if="this.tour.payment_instructions" v-html="this.tour.payment_instructions.replace(/(?:\r\n|\r|\n)/g, '<br />')"></div>
                        </div>
                        <div class="vx-col w-full mt-5">
                            <ul class="centerx" v-if="this.tour">
                                <li v-for="(price, index) in this.tour.category[0].prices" :key="index">
                                    <vs-radio v-model="formData.total" :vs-value="price.price" @change="setPrice(index)">
                                        {{`${price.title} - Precio: Q${price.price.toFixed(2)}` }}
                                    </vs-radio>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <vs-divider color="#197CBF"></vs-divider>
                    <div class="vx-row">
                        <div class="vx-col md:w-1/2 w-full mt-5">
                            <h2>Sub-total</h2>
                        </div>
                        <div class="vx-col md:w-1/2 w-full mt-5">
                            <h2>Q{{(formData.total).toFixed(2)}}</h2>
                        </div>
                    </div>
                    <div class="vx-row mt-6">
                        <div  vs-type="flex" vs-justify="center" vs-align="center" vs-w="12">
                            <div class="vx-row">
                                <div class="vx-col  w-full">
                                    <h4>¿Posees un código de descuento?</h4>
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <div class="vx-row mt-6">
                        <div class="vx-col md:w-1/2 w-full mt-3">
                        <div class="vx-row">
                            <vs-input class="inputx w-full" placeholder="Código" color="#197CBF" v-model="formData.discountCode"  />                           
                        </div>
                        </div>
                        <div class="vx-col md:w-1/2 w-full mt-3" >
                            <div class="vx-row">
                                <vs-button color="#197CBF" class="px-10 ml-4" type="filled" @click="validateDiscount(formData.discountCode)">Validar</vs-button>
                                <vs-chip color="danger" class="mt-3 ml-3" v-show="invalidDiscount">
                                    <vs-avatar icon="error" />
                                    Descuento inválido
                                </vs-chip>
                                <vs-chip color="success" class="mt-3 ml-3" v-show="validDiscount">
                                    <vs-avatar icon="check" />
                                    Descuento de {{totalDiscount}}%
                                </vs-chip>
                            </div>

                        </div>
                    </div>
                    <vs-divider color="#197CBF" class="mt-6"></vs-divider>
                    <div class="vx-row">
                        <div class="vx-col md:w-1/2 w-full mt-5">
                            <h2>Total</h2>
                        </div>
                        <div class="vx-col md:w-1/2 w-full mt-5">
                            <h2>Q{{totalWDiscount}}</h2>
                        </div>
                    </div >
                    <div class="vx-row" v-if="this.tour.payment_instructions">
                        <div class="vx-col w-full mt-4">
                            <div v-if="this.tour.payment_instructions" v-html="this.tour.payment_instructions.replace(/(?:\r\n|\r|\n)/g, '<br />')"></div>
                        </div>
                    </div>
                    <vs-divider color="#197CBF" class="mt-6"></vs-divider>
                    <div class="vx-row">
                        <div class="vx-col w-full mt-3">
                            <h3>Datos para recibo deducible de impuestos</h3>
                        </div>
                    </div>
                    <div class="vx-row">
                        <div class="vx-col md:w-1/2 w-full mt-5">
                            <vs-input
                                label="NIT" color="#197CBF" v-model="formData.nit" class="w-full"
                            />
                            <span
                                class="text-danger text-sm"
                                v-show="errors.nit"
                            >Por favor ingresa el NIT.</span>
                        </div>
                        <div class="vx-col md:w-1/2 w-full mt-5">
                            <vs-input
                                label="Nombre" color="#197CBF" v-model="formData.nit_name" class="w-full"
                            />
                            <span
                                class="text-danger text-sm"
                                v-show="errors.nit_name"
                            >Por favor ingresa el nombre de facturación.</span>
                        </div>
                    </div>
                    <vs-divider color="#197CBF" class="mt-6"></vs-divider>
                    <div class="vx-row">
                        <vs-col class="py-3" vs-type="flex" vs-justify="center" vs-align="center">
                            <div class="btn-group mt-4 flex ">
                                <vs-button color="#197CBF" :disabled="isTarjeta" @click="setTarjeta">Tarjeta</vs-button>
                                <vs-button color="#197CBF" :disabled="!isTarjeta" @click="setDeposito">Depósito</vs-button>
                            </div>
                        </vs-col>
                        <vs-col vs-lg="12" vs-sm="12" vs-md="12" v-if="isTarjeta">
                            <card-form class="mt-6 mb-5"
                            @input-card-number="formData.cardNumber = $event"
                            @input-card-name="formData.cardName = $event"
                            @input-card-month="formData.cardMonth = $event"
                            @input-card-year="formData.cardYear = $event"
                            @input-card-cvv="formData.cardCvv = $event"
                        ></card-form>
                        
                        </vs-col>
                        <vs-col vs-lg="12" vs-sm="12" vs-md="12" v-if="isTarjeta" class="text-right">
                            <span class="text-danger text-sm mr-4"
                                v-show="isTarjeta && (!formData.cardCvv || !formData.cardMonth || !formData.cardYear || !formData.cardName || !formData.cardNumber)"
                            >Por favor agrega todos los campos para el pago con tarjeta para continuar.</span>
                        </vs-col>
                        <vs-col vs-lg="12" vs-sm="12" vs-md="12" v-else>
                            <h1 class="my-3">Depósito</h1>
                            <vs-row vs-type="flex" vs-justify="center">
                                <vs-col class="py-3" vs-lg="12" vs-sm="12" vs-md="12" vs-type="flex" vs-justify="center" vs-align="center">
                                    <div v-if="this.tour.monetary_deposit_copy" v-html="this.tour.monetary_deposit_copy.replace(/(?:\r\n|\r|\n)/g, '<br />')"></div>
                                </vs-col>
                                <vs-col class="py-3" vs-type="flex" vs-justify="center" vs-align="center">
                                    <vs-input label="Banco donde se realizó el depósito" color="#197CBF" v-model="formData.bank" class="w-full" />
                                    <span class="text-danger text-sm"  v-show="errors.bank">El banco es obligatorio</span>
                                </vs-col>
                                <vs-col class="py-3" vs-type="flex" vs-justify="center" vs-align="center">
                                    <vs-input label="Número de comprobante de pago" color="#197CBF" v-model="formData.receipt" class="w-full" />
                                    <span class="text-danger text-sm"  v-show="errors.receipt">El comprobante de pago es obligatorio</span>
                                </vs-col>
                                <vs-col class="py-3" vs-lg="4" vs-sm="9" vs-md="3" vs-type="flex" vs-justify="center" vs-align="center">
                                    <!-- <vs-upload class="mb-4"  :show-upload-button="false" :data="formData.file" @change="selectFile" /> -->
                                    <input type="file"  id="file" ref="file" @change="selectFile">
                                </vs-col>

                            </vs-row>
                        </vs-col>

                    </div>
                </tab-content>
                <template slot="footer" slot-scope="props">
                    <div class="wizard-footer-left">
                        
                        <wizard-button v-if="props.activeTabIndex > 0 " @click.native="props.prevTab()" :style="props.fillButtonStyle">Anterior</wizard-button>
                        </div>
                        <div class="wizard-footer-right">
                        <wizard-button v-if="!props.isLastStep" @click.native="props.nextTab()" class="wizard-footer-right" :style="props.fillButtonStyle">Siguiente</wizard-button>
                        <wizard-button 
                            v-else-if="(isTarjeta && formData.cardCvv && formData.cardMonth && formData.cardYear && formData.cardName && formData.cardNumber) || (!isTarjeta)"
                            @click.native="validateStep3()" :disabled="showLoading"
                            id="button-with-loading" color="197CBF" class="wizard-footer-right finish-button vs-con-loading__container p-3"
                            type="relief" :style="props.fillButtonStyle">{{props.isLastStep ? 'Pagar' : 'Siguiente'}}
                        </wizard-button>
                    </div>
                </template>
            </form-wizard>
        </div>
    </vx-card>
</template>

<script>

// --- Imports ---
import {FormWizard, TabContent, WizardButton} from 'vue-form-wizard'
import vSelect from 'vue-select'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
import CardForm from './payment-components/CardForm'
import axios from "axios";

// --- Imports ---e

export default {
  data () {
    return {
    // --
    extra_info: false,
    extra_questions: [],
    extra_answers: [],
    extra_container: {
        'ex_field_01': '',
        'ex_field_02': '',
        'ex_field_03': '',
        'ex_field_04': '',
        'ex_field_05': '',
        'ex_field_06': '',
        'ex_field_07': '',
        'ex_field_08': '',
        'ex_field_09': '',
        'ex_field_10': ''
    },
    valid: true,
    name1: "Nombre Completo",
    type: "text",
    showLoading: false,
    postSent: false,
    postSuccess: null,
    isTarjeta: true,
    selectedIndex: 0,
    // input fields data
    formData: {
        // extra
        extra_fnegocios: '',
        extra_fut: '',
        extra_region: '',
        extra_talentos: '',
        // --receipt details--
        nit: '',
        nit_name: '',
        // direcction_nit: '',
        // ----
        name: '',
        lastName: '',
        email: '',
        sex: '',
        age: '',
        nationality: '',
        residence: '',
        citMun: '',
        depState: '',
        dpiPass: '',
        phone: '',
        occupation: '',
        total: 0,
        discount: 0,
        // --- payment method fields
        alias: 'Credit Card',
        cardName: '',
        cardNumber: '',
        cardMonth: '',
        cardYear: '',
        cardCvv: '',
        fingerprint: '',
        // deposit
        receipt: '',
        bank: '',
        file: undefined, 
        // --- utm source info
        utmMedium: '',
        utmSource: '',
        // ip address
        shopperIp: '',
        // terms and conditions value
        termsCond: false,
        discountCode: '',
        totalDiscounted: 0,
      },
      //this: demoData,
      // tour space
      tour: null,
      tour_id: null,
      // ---- Discount ---
      validDiscount: false,
      invalidDiscount: false,
      totalDiscount: 0,
      totalWDiscount: 0,
      // -----------------

      selectedMethod: 'card-form',
      paymentType: 'epay',
    // --
      firstName: '',
      lastName: '',
      email: '',
      city: 'new-york',
      proposalTitle: '',
      jobTitle: '',
      textarea: '',
      eventName: '',
      eventLocation: 'san-francisco',
      status: 'plannning',
      cityOptions: [
        {text: 'New York', value:'new-york'},
        {text: 'Chicago', value:'chicago'},
        {text: 'San Francisco', value:'san-francisco'},
        {text: 'Boston', value:'boston'}
      ],
      statusOptions: [
        {text: 'Plannning', value:'plannning'},
        {text: 'In Progress', value:'in progress'},
        {text: 'Finished', value:'finished'}
      ],
      LocationOptions: [
        {text: 'New York', value:'new-york'},
        {text: 'Chicago', value:'chicago'},
        {text: 'San Francisco', value:'san-francisco'},
        {text: 'Boston', value:'boston'}
      ],
      // --- select options ---
      dietOptions: [
        {text: 'Ninguna en específico', value:'Ninguna en específico'},
        {text: 'Vegana', value:'Vegana'},
        {text: 'Vegetariana', value:'Vegetariana'},
        {text: 'Kosher', value:'Kosher'},
        {text: 'Otra', value:'Otra'},
      ],
      ocupationOptions: [
        {text: 'Estudiante', value:'Estudiante'},
        {text: 'Empleado', value:'Empleado'},
        {text: 'Empresario', value:'Empresario'},
        {text: 'Otro', value:'Otro'},
      ],
      sexOptions: [
          {text: 'Masculino', value:'masculino'},
          {text: 'Femenino', value:'femenino'},
          {text: 'Otro', value:'otro'} 
      ],
      boolOptions: [
        'Si', 'No',
      ],
      shirtOptions: [
        'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', 
      ],
      bloodOptions: [
        'O-','O+','A-','A+','B-','B+','AB-','AB+'
      ],
      extraOptions: [
        'Si','No','No se'
      ],
      irtraOptions: [
        'Xetulul', 'Xocomil','Xejuyup'
      ],
      nationalityOptions: [
        "Afganistán","Albania","Alemania","Andorra","Angola","Antigua y Barbuda","Arabia Saudita","Argelia",
        "Argentina","Armenia","Australia","Austria","Azerbaiyán","Bahamas","Bangladés","Barbados","Baréin",
        "Bélgica","Belice","Benín","Bielorrusia","Birmania","Bolivia","Bosnia y Herzegovina","Botsuana",
        "Brasil","Brunéi","Bulgaria","Burkina Faso","Burundi","Bután","Cabo Verde","Camboya","Camerún",
        "Canadá","Catar","Chad","Chile","China","Chipre","Ciudad del Vaticano","Colombia","Comoras",
        "Corea del Norte","Corea del Sur","Costa de Marfil","Costa Rica","Croacia","Cuba","Dinamarca",
        "Dominica","Ecuador","Egipto","El Salvador","Emiratos Árabes Unidos","Eritrea","Eslovaquia",
        "Eslovenia","España","Estados Unidos","Estonia","Etiopía","Filipinas","Finlandia","Fiyi","Francia",
        "Gabón","Gambia","Georgia","Ghana","Granada","Grecia","Guatemala","Guyana","Guinea","Guinea ecuatorial",
        "Guinea-Bisáu","Haití","Honduras","Hungría","India","Indonesia","Irak","Irán","Irlanda","Islandia",
        "Islas Marshall","Islas Salomón","Israel","Italia","Jamaica","Japón","Jordania","Kazajistán","Kenia",
        "Kirguistán","Kiribati","Kuwait","Laos","Lesoto","Letonia","Líbano","Liberia","Libia","Liechtenstein",
        "Lituania","Luxemburgo","Madagascar","Malasia","Malaui","Maldivas","Malí","Malta","Marruecos","Mauricio",
        "Mauritania","México","Micronesia","Moldavia","Mónaco","Mongolia","Montenegro","Mozambique","Namibia",
        "Nauru","Nepal","Nicaragua","Níger","Nigeria","Noruega","Nueva Zelanda","Omán","Países Bajos","Pakistán",
        "Palaos","Panamá","Papúa Nueva Guinea","Paraguay","Perú","Polonia","Portugal","Reino Unido",
        "República Centroafricana","República Checa","República de Macedonia","República del Congo",
        "República Democrática del Congo","República Dominicana","República Sudafricana","Ruanda","Rumanía",
        "Rusia","Samoa","San Cristóbal y Nieves","San Marino","San Vicente y las Granadinas","Santa Lucía",
        "Santo Tomé y Príncipe","Senegal","Serbia","Seychelles","Sierra Leona","Singapur","Siria","Somalia",
        "Sri Lanka","Suazilandia","Sudán","Sudán del Sur","Suecia","Suiza","Surinam","Tailandia","Tanzania",
        "Tayikistán","Timor Oriental","Togo","Tonga","Trinidad y Tobago","Túnez","Turkmenistán","Turquía","Tuvalu",
        "Ucrania", "Uganda","Uruguay","Uzbekistán","Vanuatu","Venezuela","Vietnam","Yemen","Yibuti","Zambia","Zimbabue"
      ],
      // ---- select options end ---
      errors: {
          name: false,
          lastName: false,
          email: false,
          sex: false,
          age: false,
          nationality: false,
          dpiPass: false,
          phone: false,
          ocupation: false,
          termsCond: false,
          nit: false,
          nit_name: false,
        //   direcction_nit: false,
          citMun: false,
          depState: false,
          residence: false,
          extra_answers: []
      }
    }
  },
  methods: {
    setPrice(i){
        console.log('fijando precio para'+i);
        this.totalWDiscount = (this.tour.category[0].prices[i].price).toFixed(2);
        this.selectedIndex = i;
    },
    selectFile() {
            // `files` is always an array because the file input may be in multiple mode
            // this.formData.file = event.target.files[0];
            this.formData.file  = this.$refs.file.files[0];
            console.log(this.formData.file);
    },
    setTarjeta(){
        this.isTarjeta = true;
        this.paymentType = 'epay';
    },
    setDeposito(){
        this.isTarjeta = false;
        this.paymentType = 'deposit';
    },
    formSubmit2(){
        console.log(this.formData);
    },
    formSubmit() {
        // loading animatino
        this.$vs.loading({
            background: '#197CBF',
            color: '#FFFFFF',
            container: "#button-with-loading",
            scale: 0.6
        });
        // show loading
        this.showLoading = true;

        // header configuration
        let config = {
            header : {
            'Content-Type' : 'multipart/form-data'
            }
        }

        // --------- FORM DATA --------- 
        const formData = new FormData()
        formData.append('tour_id', this.tour_id);
        formData.append([this.tour.category[0].prices[this.selectedIndex].id], 1);
        formData.append('first_name', this.formData.name);
        formData.append('last_name', this.formData.lastName);
        formData.append('email', this.formData.email);
        formData.append('sex', this.formData.sex);
        formData.append('age', this.formData.age);
        formData.append('nationality', this.formData.nationality);
        formData.append('dpi_passport', this.formData.dpiPass);
        formData.append('phone', this.formData.phone);
        formData.append('occupation', this.formData.occupation);
        formData.append('nit_name', this.formData.nit_name);
        formData.append('nit', this.formData.nit);
        formData.append('receipt_address', this.formData.direcction_nit+', '+this.formData.residence+', '+this.formData.depState+', '+this.formData.citMun);
        formData.append('shopper_ip', this.formData.shopperIp);

        // dynamic extra questions
        formData.append('ex_field_01', 
            this.extra_answers[0] ? 
            JSON.stringify({value : this.extra_answers[0],
                 title: this.extra_questions[0] ? this.extra_questions[0].titulo : '' }): '');

        formData.append('ex_field_02', 
            this.extra_answers[1] ? 
            JSON.stringify({value : this.extra_answers[1],
                 title: this.extra_questions[1] ? this.extra_questions[1].titulo : '' }): '');
        
        formData.append('ex_field_03', 
            this.extra_answers[2] ? 
            JSON.stringify({value : this.extra_answers[2],
                 title: this.extra_questions[2] ? this.extra_questions[2].titulo : '' }): '');

        formData.append('ex_field_04', 
            this.extra_answers[3] ? 
            JSON.stringify({value : this.extra_answers[3],
                 title: this.extra_questions[3] ? this.extra_questions[3].titulo : '' }): '');

        formData.append('ex_field_05', 
            this.extra_answers[4] ? 
            JSON.stringify({value : this.extra_answers[4],
                 title: this.extra_questions[4] ? this.extra_questions[4].titulo : '' }): '');
        
        formData.append('ex_field_06', 
            this.extra_answers[5] ? 
            JSON.stringify({value : this.extra_answers[5],
                 title: this.extra_questions[5] ? this.extra_questions[5].titulo : '' }): '');
                 
        formData.append('ex_field_07', 
            this.extra_answers[6] ? 
            JSON.stringify({value : this.extra_answers[6],
                 title: this.extra_questions[6] ? this.extra_questions[6].titulo : '' }): '');

        formData.append('ex_field_08', 
            this.extra_answers[7] ? 
            JSON.stringify({value : this.extra_answers[7],
                 title: this.extra_questions[7] ? this.extra_questions[7].titulo : '' }): '');
        
        formData.append('ex_field_09', 
            this.extra_answers[8] ? 
            JSON.stringify({value : this.extra_answers[8],
                 title: this.extra_questions[8] ? this.extra_questions[8].titulo : '' }): '');

        formData.append('ex_field_10', 
            this.extra_answers[9] ? 
            JSON.stringify({value : this.extra_answers[9],
                 title: this.extra_questions[9] ? this.extra_questions[9].titulo : '' }): '');


        // totals
        formData.append('total_discount', this.totalDiscount);
        formData.append('total_discounted', this.formData.totalDiscounted);
        formData.append('total_amount', this.totalWDiscount);
        formData.append('discount_code', this.formData.discountCode);

        // payment
        formData.append('payment_type', this.paymentType);
        
        // card
        formData.append('credit_card_cvv', this.formData.cardCvv);
        formData.append('credit_card_expiry', String(this.formData.cardYear).substring(2,4)+String(this.formData.cardMonth));
        formData.append('credit_card_name', this.formData.cardName);
        formData.append('credit_card_number', this.formData.cardNumber);
        // deposit
        formData.append('receipt', this.formData.receipt);  
        formData.append('bank', this.formData.bank);
        formData.append('file', this.formData.file);
        // source
        formData.append('utm_medium', this.formData.utmMedium);
        formData.append('utm_source', 'link');
        // -----------------------------

        console.log('Data to submit:', this.formData);
        axios.post('https://eventos.avantlife.gt/api/v1/tours/'+this.tour_id+'/book_and_pay/web',
        formData,
        config
        )
        .then((response) => {
          console.log(response);
          this.$vs.loading.close("#button-with-loading > .con-vs-loading");
          if((response.data.code == "epay_success"  && response.data.status == 200) || response.data.success){
            this.postSuccess = true;
            this.showLoading = false;
            this.postSent = true;
            window.location.href = "/fie/success";
            console.log(response);
          } else {
            this.postSuccess = false;
            this.showLoading = false;
            this.postSent = true;
            window.location.href = "/fie/error";
            console.log(response);
          }
          
        })
        .catch((error) => {
            setTimeout( ()=> {
                this.$vs.loading.close("#button-with-loading > .con-vs-loading");
                this.showLoading = false;
                console.log(error);
                window.location.href = "/fie/error";
                
            }, 1500);
            console.log(error);
            this.postSuccess = false;
            this.postSent = true;
        });
    },
    // Form steps validation
    validateStep1() {
        // return true;
        if(!this.formData.name && this.tour.request_first_name){
            this.errors.name = true;
        } else {
            this.errors.name = false;
        }
        if(!this.formData.lastName && this.tour.request_last_name){
            this.errors.lastName = true;
        } else {
            this.errors.lastName = false;
        }
        if(!this.formData.email && this.tour.request_email){
            this.errors.email = true;
        } else {
            this.errors.email = false;
        }
        if(!this.formData.sex && this.tour.request_sex){
            this.errors.sex = true;
        } else {
            this.errors.sex = false;
        }
        if(!this.formData.age && this.tour.request_age){
            this.errors.age = true;
        } else {
            this.errors.age = false;
        }
        if(!this.formData.nationality && this.tour.request_nationality){
            this.errors.nationality = true;
        } else {
            this.errors.nationality = false;
        }

        if(!this.formData.dpiPass && this.tour.request_dpi_passport){
            this.errors.dpiPass = true;
        } else {
            this.errors.dpiPass = false;
        }
        if(!this.formData.phone && this.tour.request_phone){
            this.errors.phone = true;
        } else {
            this.errors.phone = false;
        }
        if(!this.formData.occupation && this.tour.request_occupation){
            this.errors.occupation = true;
        } else {
            this.errors.occupation = false;
        }
        if(!this.formData.termsCond){
            this.errors.termsCond = true;
        } else {
            this.errors.termsCond = false;
        }
        // new fields
        // if(!this.formData.direcction_nit){
        //     this.errors.direcction_nit = true;
        // } else {
        //     this.errors.direcction_nit = false;
        // }
        // residence
        if(!this.formData.residence && this.tour.request_nationality){
            this.errors.residence = true;
        } else {
            this.errors.residence = false;
        }
        if(!this.formData.depState && this.tour.request_nationality){
            this.errors.depState = true;
        } else {
            this.errors.depState = false;
        }
        if(!this.formData.citMun && this.tour.request_nationality){
            this.errors.citMun = true;
        } else {
            this.errors.citMun = false;
        }
        // verification
        if (Object.values(this.errors).indexOf(true) > -1) {
            return false;
        } else {
            return true;
        }
    },
    validateStep2() {
        return true;
        if(!this.formData.encargadoName && this.tour.request_emergencyContact){
            this.errors.encargadoName = true;
        } else {
            this.errors.encargadoName = false;
        }
        
        if(!this.formData.shirtSize && this.tour.request_shirtSize){
            this.errors.shirtSize = true;
        } else {
            this.errors.shirtSize = false;
        }
        if (Object.values(this.errors).indexOf(true) > -1) {
            return false;
        } else {
            return true;
        }
    },
    validateStep3() {
        if(!this.formData.nit_name){
            this.errors.nit_name = true;
        } else {
            this.errors.nit_name = false;
        }
        if(!this.formData.nit){
            this.errors.nit = true;
        } else {
            this.errors.nit = false;
        }
        if (Object.values(this.errors).indexOf(true) > -1) {
            return false;
        } else {
            this.formSubmit();
            return true;
        }
    },
    validateDiscount(code){
        console.log(code);
        let myObj = this.tour.discount_codes;
        this.validDiscount = false;
        var itemsProcessed = 0;

        Object.keys(myObj).forEach(key => {
            itemsProcessed += 1;
            if(code === myObj[key].codigo){
                this.validDiscount = true;
                this.invalidDiscount = false;
                this.totalDiscount = parseInt(myObj[key].percentage); 
                
                this.formData.discountCode = code;
                // let calc = (this.tour.category[0].prices[0].price)*(1-(this.totalDiscount/100));
                let calc = (this.formData.total)*(1-(this.totalDiscount/100));
                this.formData.totalDiscounted = this.formData.total - calc;
                this.totalWDiscount = calc.toFixed(2);
            }
            if(itemsProcessed == myObj.length && !this.validDiscount){
                this.invalidDiscount = true;
                this.formData.discountCode = '';
                this.formData.totalDiscounted = 0;
                this.totalDiscount = 0;
            }
        });
        
    },
    getUserIp(){
      fetch('https://api.ipify.org?format=json')
      .then(x => x.json())
      .then(({ ip }) => {
        console.log(ip);
        this.formData.shopperIp = ip;
      });
    }
  },
  beforeMount(){
    const queryString = window.location.search;
    

    const urlParams = new URLSearchParams(queryString);

    this.tour_id = urlParams.get('tour_id');
    this.formData.utmMedium = urlParams.get('utm_source');
    this.formData.utmMedium = urlParams.get('utm_medium');
    console.log(urlParams.get('tour_id'));
    console.log(urlParams.get('utm_source'));
    console.log(urlParams.get('utm_medium'));

    this.getUserIp();

    console.log(this.tour_id);
    const baseURI = 'https://eventos.avantlife.gt/api/v1/optin/tour/'+this.tour_id;
      this.$http.get(baseURI)
      .then((result) => {
        this.tour = result.data.tour;
        console.log(this.tour.informacion_a_solicitar);
        if(this.tour.solicitar_informacion_extra) {
            this.extra_info = true;
            this.extra_questions = this.tour.informacion_a_solicitar;
            this.extra_answers = new Array(this.tour.informacion_a_solicitar.length);
        }
        this.totalWDiscount = (this.tour.category[0].prices[0].price).toFixed(2);
        this.formData.total = (this.tour.category[0].prices[0].price);
        console.log(this.tour)
      });
  },
  components: {
    FormWizard,
    WizardButton,
    TabContent,
    CardForm,
    vSelect
  }
}
// --- Validation ---  

// --- Validation --- 

</script>

<style lang="scss">
.fill-row-loading {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-wrap: wrap;
  .loading-example {
    width: 120px;
    float: left;
    height: 120px;
    box-shadow: 0px 5px 20px 0px rgba(0, 0, 0, 0.05);
    border-radius: 10px;
    margin: 8px;
    transition: all 0.3s ease;
    cursor: pointer;
    &:hover {
      box-shadow: 0px 0px 0px 0px rgba(0, 0, 0, 0.05);
      transform: translate(0, 4px);
    }
    h4 {
      z-index: 40000;
      position: relative;
      text-align: center;
      padding: 10px;
    }
    &.activeLoading {
      opacity: 0 !important;
      transform: scale(0.5);
    }
  }
}
</style>
