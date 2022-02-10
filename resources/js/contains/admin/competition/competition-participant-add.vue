<script>
import Layout from "../subcomponent/layout";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";

import DatePicker from "vue2-datepicker";
import 'vue2-datepicker/locale/es';
import Multiselect from "vue-multiselect";

import { mapActions, mapGetters } from 'vuex';

import {
  required,
  email,
  minLength,
  sameAs,
  maxLength,
  minValue,
  maxValue,
  numeric,
  url,
  alphaNum
} from "vuelidate/lib/validators";

export default {
  page: {
    title: "AÑADIR PARTICIPANTE A COMPETICION",
    meta: [{ name: "description", content: appConfig.description }]
  },
  components: { DatePicker, Multiselect, Layout, PageHeader },
  data() {
    return {
      title: "AÑADIR PARTICIPANTE A COMPETICION",
      items: [
        {
          text: "Home",
          href: "/admin/competitions"
        },
        {
          text: "Listado Competiciones",
          href: "/admin/competitions"
        },
        {
          text: "Añadir Participante",
          active: true,
        },
      ],
      lang: {
        formatLocale: {
          firstDayOfWeek: 1,
        },
        es: 'es'
      },
      isError: false,
      Error: null,
      typeform: {
        name: "",
        surname: "",
        dni_ficha: "",
        birthday: "",
        sex: "Masculino",
        club: "",
        modality: [],
      },
      sexOptions: [
        "Masculino",
        "Femenino"
      ],
      modalityOptions: [
        "Corto",
        "Largo"
      ],
      typesubmit: false,
    };
  },
  validations: {
    typeform: {
      name: { required },
      surname: { required },
      dni_ficha: { required },
      birthday: { required },
      sex: { required },
      club: { required },
      modality: { required },
    }
  },
  mounted() {
    this.getClubOptions();
  },
  computed: {
    ...mapGetters([
      'clubOptions'
    ]),
  },
  methods: {
    ...mapActions([
        'addParticipantToCompetition',
        'getClubOptions',
      ]),
    /**
     * Validation type submit
     */
    // eslint-disable-next-line no-unused-vars
    typeForm(e) {
      this.typesubmit = true;
      this.isError = false;
      this.Error = null;
      // stop here if form is invalid
      this.$v.$touch()
      if (this.$v.typeform.name.$error || this.$v.typeform.surname.$error || this.$v.typeform.dni_ficha.$error || this.$v.typeform.birthday.$error || this.$v.typeform.sex.$error || this.$v.typeform.club.$error || this.$v.typeform.modality.$error) {
        return ;
      }
      return (
        this.addParticipantToCompetition({
            competitionId: this.$route.params.competitionId,
            name: this.typeform.name,
            surname: this.typeform.surname,
            dni_ficha: this.typeform.dni_ficha,
            birthday: this.typeform.birthday,
            sex: this.typeform.sex,
            club: this.typeform.club,
            modality: this.typeform.modality,
          })
          .then(() => {
            this.typesubmit = false;
          })
          .catch(error => {
            this.typesubmit = false;
            this.Error = error ? error : "";
            this.isError = true;
          })
      );
    }
  }
};
</script>

<template>
  <Layout>
    <PageHeader :title="title" :items="items" />

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <b-alert
              v-model="isError"
              variant="danger"
              class="mt-3"
              dismissible
            >{{ Error }}</b-alert>
            <form action="#" @submit.prevent="typeForm">
              <div class="form-group">
                <label>Nombre</label>
                <input
                  v-model="typeform.name"
                  type="name"
                  class="form-control"
                  name="name"
                  :class="{ 'is-invalid': typesubmit && $v.typeform.name.$error }"
                />
                <div v-if="typesubmit && $v.typeform.name.$error" class="invalid-feedback">
                  <span v-if="!$v.typeform.name.required">Este Campo es Obligatorio.</span>
                </div>
              </div>
              <div class="form-group">
                <label>Apellidos</label>
                <input
                  v-model="typeform.surname"
                  type="name"
                  class="form-control"
                  name="surname"
                  :class="{ 'is-invalid': typesubmit && $v.typeform.surname.$error }"
                />
                <div v-if="typesubmit && $v.typeform.surname.$error" class="invalid-feedback">
                  <span v-if="!$v.typeform.surname.required">Este Campo es Obligatorio.</span>
                </div>
              </div>
              <div class="form-group">
                <label>Dni Ficha</label>
                <input
                  v-model="typeform.dni_ficha"
                  type="text"
                  class="form-control"
                  name="dni_ficha"
                  :class="{ 'is-invalid': typesubmit && $v.typeform.dni_ficha.$error }"
                />
                <div v-if="typesubmit && $v.typeform.dni_ficha.$error" class="invalid-feedback">
                  <span v-if="!$v.typeform.dni_ficha.required">Este Campo es Obligatorio.</span>
                </div>
              </div>
              <div class="form-group mb-3">
                <label>Fecha Nacimiento</label>
                <br />
                <date-picker
                  v-model="typeform.birthday"
                  format="DD-MM-YYYY"
                  value-type="format"
                  :lang="lang"
                  placeholder="DD-MM-AAAA"
                  :class="{ 'is-invalid': typesubmit && $v.typeform.birthday.$error }"
                ></date-picker>
                <div v-if="typesubmit && $v.typeform.birthday.$error" class="invalid-feedback">
                  <span v-if="!$v.typeform.birthday.required">Este Campo es Obligatorio.</span>
                </div>
              </div>
              <div class="mb-3">
                <label>Sexo</label>
                <multiselect 
                  v-model="typeform.sex" 
                  :options="sexOptions"
                  :class="{ 'is-invalid': typesubmit && $v.typeform.sex.$error }"
                ></multiselect>
                <div v-if="typesubmit && $v.typeform.sex.$error" class="invalid-feedback">
                  <span v-if="!$v.typeform.sex.required">Este Campo es Obligatorio.</span>
                </div>
              </div>
              <div class="mb-3">
                <label>Club</label>
                <multiselect 
                  v-model="typeform.club"
                  :options="clubOptions"
                  :class="{ 'is-invalid': typesubmit && $v.typeform.club.$error }"  
                ></multiselect>
                <div v-if="typesubmit && $v.typeform.club.$error" class="invalid-feedback">
                  <span v-if="!$v.typeform.club.required">Este Campo es Obligatorio.</span>
                </div>
              </div>
              <div>
                <label>Modalidad</label>
                <multiselect 
                  v-model="typeform.modality"
                  :options="modalityOptions"
                  :multiple="true"
                  :class="{ 'is-invalid': typesubmit && $v.typeform.modality.$error }"
                ></multiselect>
                <div v-if="typesubmit && $v.typeform.modality.$error" class="invalid-feedback">
                  <span v-if="!$v.typeform.modality.required">Este Campo es Obligatorio.</span>
                </div>
              </div>
              <div class="form-group mt-5 mb-0">
                <div>
                  <button type="submit" class="btn btn-primary">Guardar</button>
                  <router-link :to="{ name: 'CompetitionParticipantRegist', params: { competitionId: this.$route.params.competitionId } }" class="btn btn-secondary m-l-5 ml-1">Cancelar</router-link>
                  <button type="reset" class="btn btn-warning m-l-5 ml-1">Vaciar</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>