<script>
import Layout from "../subcomponent/layout";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";

import DatePicker from "vue2-datepicker";
import 'vue2-datepicker/locale/es';
import Multiselect from "vue-multiselect";
import Switches from "vue-switches";

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
    title: "MODIFICAR COMPETICIÓN",
    meta: [{ name: "description", content: appConfig.description }]
  },
  components: { DatePicker, Multiselect, Switches, Layout, PageHeader },
  data() {
    return {
      title: "MODIFICAR COMPETICIÓN",
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
          text: "Modificar Competición",
          active: true
        }
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
        title: "",
        competition_type: "",
        description: "",
        place: "",
        date: "",
        time: "",
        organizer: "",
        ranking_score: "",
        status: "Si",
        lycra: "",
        modality: "",
        category: "",
        logo: null,
      },
      url: '/images/default.jpg',
      rankingScoreOptions: [
        "Si",
        "No"
      ],
      statusOptions: [
        "CERRADA",
        "REGISTRO ABIERTO",
        "EN CURSO"
      ],
      modalityOptions: [
        "Corto",
        "Largo",
      ],
      typesubmit: false,
    };
  },
  validations: {
    typeform: {
      title: { required },
      competition_type: { required },
      description: { required },
      place: { required },
      date: { required },
      time: { required },
      organizer: { required },
      ranking_score: { required },
      status: { required },
      lycra: { required },
      modality: { required },
      category: { required },
    }
  },
  computed: {
    ...mapGetters([
      'typeOptions',
      'categoryOptions',
      'lycraOptions',
      'getCompetition',
    ]),
  },
  watch: {
    getCompetition: function () {
      this.typeform.title = this.getCompetition.title
      this.typeform.competition_type = this.getCompetition.competition_type.name
      this.typeform.description = this.getCompetition.description
      this.typeform.place = this.getCompetition.place
      this.typeform.date = this.getCompetition.date
      this.typeform.time = this.getCompetition.time
      this.typeform.organizer = this.getCompetition.organizer
      this.typeform.ranking_score = this.getCompetition.ranking_score
      this.typeform.status = this.getCompetition.status.name
      this.typeform.lycra = this.getCompetition.lycraNames
      this.typeform.modality = this.getCompetition.modalityNames
      this.typeform.category = this.getCompetition.categoryNames
      if (this.getCompetition.logo != null) {
        this.url = '/storage/'+this.getCompetition.logo
      }
    },
  },
  mounted() {
    this.getTypeOptions();
    this.getLycraOptions();
    this.getCategoryOptions();
    this.getCompetitionById(this.$route.params.competitionId);
  },
  methods: {
    ...mapActions([
      'updateCompetition',
      'getCompetitionById',
      'getTypeOptions',
      'getLycraOptions',
      'getCategoryOptions',
    ]),
    selectFile(event) {
      // `files` is always an array because the file input may be in multiple mode
      this.typeform.logo = event.target.files[0];
      console.log(this.typeform.logo)
      this.url = URL.createObjectURL(this.typeform.logo);
    },
    /**
     * Validation type submit
     */
    // eslint-disable-next-line no-unused-vars
    typeForm(e) {
      // console.log(this.typeform.status)
      this.typesubmit = true;
      this.isError = false;
      this.Error = null;
      // stop here if form is invalid
      this.$v.$touch()
      if (this.$v.typeform.title.$error || this.$v.typeform.place.$error || this.$v.typeform.date.$error || this.$v.typeform.time.$error || this.$v.typeform.description.$error || this.$v.typeform.competition_type.$error || this.$v.typeform.organizer.$error || this.$v.typeform.status.$error || this.$v.typeform.lycra.$error || this.$v.typeform.modality.$error || this.$v.typeform.category.$error || this.$v.typeform.ranking_score.$error) {
        return ;
      }

      const formData = new FormData();
      formData.append('id', this.getCompetition.id);
      formData.append('title', this.typeform.title);
      formData.append('competition_type', this.typeform.competition_type);
      formData.append('description', this.typeform.description);
      formData.append('place', this.typeform.place);
      formData.append('date', this.typeform.date);
      formData.append('time', this.typeform.time);
      formData.append('organizer', this.typeform.organizer);
      formData.append('ranking_score', this.typeform.ranking_score);
      formData.append('status', this.typeform.status);
      formData.append('lycra', this.typeform.lycra);
      formData.append('modality', this.typeform.modality);
      formData.append('category', this.typeform.category);
      formData.append('logo', this.typeform.logo);
      return (
        this.updateCompetition(formData)
          .then((res) => {
            this.$router.push({name: "Competitions"});
            this.typesubmit = false;
          })
          .catch(error => {
            this.typesubmit = false;
            this.Error = error ? error.data.message : "";
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
            <form action="#" @submit.prevent="typeForm" enctype="multipart/form-data">
              <div class="row">
                <div class="col-lg-6 col-md-12">
                  <div class="form-group">
                    <label>Título</label>
                    <input
                      v-model="typeform.title"
                      type="text"
                      class="form-control"
                      placeholder="Competition Title"
                      name="title"
                      :class="{ 'is-invalid': typesubmit && $v.typeform.title.$error }"
                    />
                    <div v-if="typesubmit && $v.typeform.title.$error" class="invalid-feedback">
                      <span v-if="!$v.typeform.title.required">Este Campo es Obligatorio.</span>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label>Tipo Competición</label>
                    <multiselect 
                      v-model="typeform.competition_type" 
                      deselect-label=""
                      :options="typeOptions"
                      :class="{ 'is-invalid': typesubmit && $v.typeform.competition_type.$error }"
                    ></multiselect>
                    <div v-if="typesubmit && $v.typeform.competition_type.$error" class="invalid-feedback">
                      <span v-if="!$v.typeform.competition_type.required">Este Campo es Obligatorio.</span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Descripción</label>
                    <div>
                      <textarea
                        v-model="typeform.description"
                        class="form-control"
                        name="description"
                        :style="{ 'min-height': '100px' }"
                        :class="{ 'is-invalid': typesubmit && $v.typeform.description.$error }"
                      ></textarea>
                      <div v-if="typesubmit && $v.typeform.description.$error" class="invalid-feedback">
                        <span v-if="!$v.typeform.description.required">Este Campo es Obligatorio.</span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Lugar</label>
                    <input
                      v-model="typeform.place"
                      type="text"
                      class="form-control"
                      placeholder="Competition Place"
                      name="place"
                      :class="{ 'is-invalid': typesubmit && $v.typeform.place.$error }"
                    />
                    <div v-if="typesubmit && $v.typeform.place.$error" class="invalid-feedback">
                      <span v-if="!$v.typeform.place.required">Este Campo es Obligatorio.</span>
                    </div>
                  </div>
                  <div class="form-group mb-3">
                    <label>Fecha</label>
                    <br />
                    <date-picker
                      v-model="typeform.date"
                      format="DD-MM-YYYY"
                      value-type="format"
                      :lang="lang"
                      placeholder="dd-mm-yyyy"
                      :class="{ 'is-invalid': typesubmit && $v.typeform.date.$error }"
                    ></date-picker>
                    <div v-if="typesubmit && $v.typeform.date.$error" class="invalid-feedback">
                      <span v-if="!$v.typeform.date.required">Este Campo es Obligatorio.</span>
                    </div>
                  </div>
                  <div class="form-group mb-3">
                    <label>Hora</label>
                    <br />
                    <date-picker
                      v-model="typeform.time"
                      type="time"
                      placeholder="hh:mm:ss"
                      value-type="format"
                      :class="{ 'is-invalid': typesubmit && $v.typeform.time.$error }"
                    ></date-picker>
                    <div v-if="typesubmit && $v.typeform.time.$error" class="invalid-feedback">
                      <span v-if="!$v.typeform.time.required">Este Campo es Obligatorio.</span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Organizador</label>
                    <input
                      v-model="typeform.organizer"
                      type="text"
                      class="form-control"
                      placeholder="Organizer"
                      name="organizer"
                      :class="{ 'is-invalid': typesubmit && $v.typeform.organizer.$error }"
                    />
                    <div v-if="typesubmit && $v.typeform.organizer.$error" class="invalid-feedback">
                      <span v-if="!$v.typeform.organizer.required">Este Campo es Obligatorio.</span>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label>Puntúa Ranking</label>
                    <multiselect 
                      v-model="typeform.ranking_score" 
                      deselect-label=""
                      :options="rankingScoreOptions"
                      :class="{ 'is-invalid': typesubmit && $v.typeform.ranking_score.$error }"
                    ></multiselect>
                    <div v-if="typesubmit && $v.typeform.ranking_score.$error" class="invalid-feedback">
                      <span v-if="!$v.typeform.ranking_score.required">Este Campo es Obligatorio.</span>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 col-md-12">
                  <div class="form-group">
                    <label>Estado</label>
                    <multiselect 
                      v-model="typeform.status"
                      deselect-label=""
                      :options="statusOptions"
                      :class="{ 'is-invalid': typesubmit && $v.typeform.status.$error }"
                    ></multiselect>
                    <div v-if="typesubmit && $v.typeform.status.$error" class="invalid-feedback">
                      <span v-if="!$v.typeform.status.required">Este Campo es Obligatorio.</span>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label>Categoría</label>
                    <multiselect 
                      v-model="typeform.category" 
                      :options="categoryOptions"
                      :multiple="true"
                      :class="{ 'is-invalid': typesubmit && $v.typeform.category.$error }"
                    ></multiselect>
                    <div v-if="typesubmit && $v.typeform.category.$error" class="invalid-feedback">
                      <span v-if="!$v.typeform.category.required">Este Campo es Obligatorio.</span>
                    </div>
                  </div>
                  <div class="mb-3">
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
                  <div class="mb-3">
                    <label>Lycra</label>
                    <multiselect 
                      v-model="typeform.lycra" 
                      :options="lycraOptions"
                      :multiple="true"
                      :class="{ 'is-invalid': typesubmit && $v.typeform.lycra.$error }"
                    ></multiselect>
                    <div v-if="typesubmit && $v.typeform.lycra.$error" class="invalid-feedback">
                      <span v-if="!$v.typeform.lycra.required">Este Campo es Obligatorio.</span>
                    </div>
                  </div>
                  <div class="d-flex justify-content-between">
                    <div class="form-group">
                      <label for="logo">Logo</label>
                      <input type="file" class="form-control-file" id="logo" @change="selectFile" />
                    </div>
                    <div class="p-2" style="max-width: 270px;">
                      <img class="circle-logo" :src="url">
                    </div>
                  </div>
                  <div class="form-group mt-5 mb-0">
                    <div style="float: right;">
                      <button type="submit" class="btn btn-primary">Guardar</button>
                      <router-link to="/admin/competitions" class="btn btn-secondary m-l-5 ml-1">Cancelar</router-link>
                      <button type="reset" class="btn btn-warning m-l-5 ml-1">Vaciar</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>
<style>
  .circle-logo {
    /* border-radius: 50%; */
    overflow: hidden;
    width: 100%;
    object-fit: cover;
  }
</style>