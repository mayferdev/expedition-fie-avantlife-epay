<!-- =========================================================================================
  File Name: FormWizardValidation.vue
  Description: Form wizard with validation
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <vx-card title="Form wizard with validation" code-toggler>

    <p>Implement Form validation with form wizard using popular <strong> <router-link to="/forms/form-validation">VeeValidate</router-link></strong></p>

    <div class="mt-5">
      <form-wizard color="rgba(var(--vs-primary), 1)" errorColor="rgba(var(--vs-danger), 1)" :title="null" :subtitle="null" finishButtonText="Submit">
        <tab-content title="Step 1" class="mb-5" icon="feather icon-home" :before-change="validateStep1">

          <!-- tab 1 content -->
          <form data-vv-scope="step-1">
          <div class="vx-row">
            <div class="vx-col md:w-1/2 w-full mt-5">
              <vs-input label="First Name" v-model="firstName" class="w-full" name="first_name" v-validate="'required|alpha'" />
              <span class="text-danger">{{ errors.first('step-1.first_name') }}</span>
            </div>
            <div class="vx-col md:w-1/2 w-full mt-5">
              <vs-input label="Last Name"  v-model="lastName" class="w-full" name="last_name" v-validate="'required|alpha'" />
              <span class="text-danger">{{ errors.first('step-1.last_name') }}</span>
            </div>
            <div class="vx-col md:w-1/2 w-full mt-5">
              <vs-input type="email" label="Email"  v-model="email" class="w-full" name="email" v-validate="'required|email'" />
              <span class="text-danger">{{ errors.first('step-1.email') }}</span>
            </div>
            <div class="vx-col md:w-1/2 w-full mt-5">
              <vs-select v-model="city" class="w-full select-large" label="City">
                <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item,index) in cityOptions" class="w-full" />
              </vs-select>
            </div>
          </div>
          </form>
        </tab-content>

        <!-- tab 2 content -->
        <tab-content title="Step 2" class="mb-5" icon="feather icon-briefcase" :before-change="validateStep2">
          <form data-vv-scope="step-2">
          <div class="vx-row">
            <div class="vx-col md:w-1/2 w-full">
              <vs-input label="Proposal Title" v-model="proposalTitle" class="w-full mt-4" name="proposal_title" v-validate="'required|alpha_spaces'" />
              <span class="text-danger">{{ errors.first('step-2.proposal_title') }}</span>

              <vs-input label="Job Title"  v-model="jobTitle" class="w-full mt-4" name="job_title" v-validate="'required|alpha_spaces'" />
              <span class="text-danger">{{ errors.first('step-2.job_title') }}</span>
            </div>
            <div class="vx-col md:w-1/2 w-full">
              <vs-textarea v-model="textarea" label="Short discription" class="md:mt-10 mt-6 mb-0" rows="3" />
            </div>
          </div>
          </form>
        </tab-content>

        <!-- tab 3 content -->
        <tab-content title="Step 3" class="mb-5" icon="feather icon-image" :before-change="validateStep3">
          <form data-vv-scope="step-3">
          <div class="vx-row">
            <div class="vx-col md:w-1/2 w-full">
              <vs-input label="Event Name" v-model="eventName" class="w-full mt-5" name="event_name" v-validate="'required|alpha_spaces'" />
              <span class="text-danger">{{ errors.first('step-3.event_name') }}</span>
            </div>
            <div class="vx-col md:w-1/2 w-full">
              <vs-select v-model="city" class="w-full select-large mt-5" label="Event Location">
                <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item,index) in cityOptions" class="w-full" />
              </vs-select>
            </div>
            <div class="vx-col md:w-1/2 w-full mt-5">
              <vs-select v-model="status" class="w-full select-large" label="Event Status">
                <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item,index) in statusOptions" class="w-full" />
              </vs-select>
            </div>
            <div class="vx-col md:w-1/2 w-full md:mt-8">
              <div class="demo-alignment">
                <span>Requirements:</span>
                <div class="flex">
                  <vs-checkbox>Staffing</vs-checkbox>
                  <vs-checkbox>Catering</vs-checkbox>
                </div>
              </div>
            </div>
          </div>
          </form>
        </tab-content>
      </form-wizard>
    </div>
  </vx-card>
</template>

<script>
import { FormWizard, TabContent } from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'

// For custom error message
import { Validator } from 'vee-validate'
const dict = {
  custom: {
    first_name: {
      required: 'First name is required',
      alpha: 'First name may only contain alphabetic characters'
    },
    last_name: {
      required: 'Last name is required',
      alpha: 'Last name may only contain alphabetic characters'
    },
    email: {
      required: 'Email is required',
      email: 'Please enter valid email'
    },
    job_title: {
      required: 'Job title name is required',
      alpha: 'Job title may only contain alphabetic characters'
    },
    proposal_title: {
      required: 'Proposal title name is required',
      alpha: 'Proposal title may only contain alphabetic characters'
    },
    event_name: {
      required: 'Event name is required',
      alpha: 'Event name may only contain alphabetic characters'
    }
  }
}

// register custom messages
Validator.localize('en', dict)

export default {
  data () {
    return {
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
        { text: 'New York', value: 'new-york' },
        { text: 'Chicago', value: 'chicago' },
        { text: 'San Francisco', value: 'san-francisco' },
        { text: 'Boston', value: 'boston' }
      ],
      statusOptions: [
        { text: 'Plannning', value: 'plannning' },
        { text: 'In Progress', value: 'in progress' },
        { text: 'Finished', value: 'finished' }
      ],
      LocationOptions: [
        { text: 'New York', value: 'new-york' },
        { text: 'Chicago', value: 'chicago' },
        { text: 'San Francisco', value: 'san-francisco' },
        { text: 'Boston', value: 'boston' }
      ]
    }
  },
  methods: {
    validateStep1 () {
      return new Promise((resolve, reject) => {
        this.$validator.validateAll('step-1').then(result => {
          if (result) {
            resolve(true)
          } else {
            reject('correct all values')
          }
        })
      })
    },
    validateStep2 () {
      return new Promise((resolve, reject) => {
        this.$validator.validateAll('step-2').then(result => {
          if (result) {
            resolve(true)
          } else {
            reject('correct all values')
          }
        })
      })
    },
    validateStep3 () {
      return new Promise((resolve, reject) => {
        this.$validator.validateAll('step-3').then(result => {
          if (result) {
            alert('Form submitted!')
            resolve(true)
          } else {
            reject('correct all values')
          }
        })
      })
    }
  },
  components: {
    FormWizard,
    TabContent
  }
}
</script>
