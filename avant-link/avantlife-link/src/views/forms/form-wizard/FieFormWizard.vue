<template>
    <div id="extra-component-form-wizard-demo">
      <vs-row vs-type="flex" vs-justify="center" class="mt-8">
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-md="10" vs-lg="10" vs-sm="12">
          <form-wizard-icon-tab></form-wizard-icon-tab>
        </vs-col>
      </vs-row>    
    </div>
</template>

<script>
import FormWizardIconTab from './FormWizardIconTab-fie.vue'

export default {
  components: {
    FormWizardIconTab,
  }
}
</script>
