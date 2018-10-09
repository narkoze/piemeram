<template>
  <div class="field">
    <label :for="label">{{ label }}</label>
    <datepicker
      :id="label"
      :language="$i18n.locale === 'lv' ? lv : en"
      :full-month-name="true"
      :monday-first="true"
      :typeable="true"
      :value="value"
      input-class="input"
      format="yyyy-MM-dd"
      @selected="selected"
    >
    </datepicker>
  </div>
</template>

<script>
  import Datepicker from 'vuejs-datepicker'
  import {
    en,
    lv
  } from 'vuejs-datepicker/dist/locale'

  export default {
    components: {
      Datepicker
    },
    props: [
      'label',
      'value',
    ],
    data: () => ({
      en: en,
      lv: lv
    }),
    methods: {
      selected (date) {
        this.$emit('selected', date && window.moment(date).format('Y-MM-DD'))
      }
    }
  }
</script>
