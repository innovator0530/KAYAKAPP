<script>
import Layout from "../subcomponent/layout";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";

import { mapActions, mapGetters } from 'vuex';
import DatePicker from "vue2-datepicker";

import {
  required,
} from "vuelidate/lib/validators";

export default {
  page: {
    title: "PUNTOS RANKINGS",
    meta: [{ name: "description", content: appConfig.description }]
  },
  components: { DatePicker, Layout, PageHeader },
  data() {
    return {
      title: "PUNTOS RANKINGS",
      items: [
        {
          text: "Home",
          href: "/admin/competitions"
        },
        {
          text: "Listado Rankings",
          href: "/admin/ranking_points"
        },
        {
          text: "Modificar Ranking",
          active: true
        }
      ],
      totalRows: 1,
      sortBy: "position",
      sortDesc: false,
      fields: [
        { label: "Posición", key: "position", sortable: true },
        { label: "Puntos", key: "points", sortable: false },
      ],
      isError: false,
      Error: null,
      typeform: {
        name: "",
        year: "",
      },
      typesubmit: false,
    };
  },
  validations: {
    typeform: {
      name: { required },
      year: { required },
    }
  },
  mounted() {
    this.getRankingById(this.$route.params.rankingId);
    this.totalRows = this.getRankingPoints.length;
    this.getRankingPointsById(this.$route.params.rankingId);
  },
  computed: {
    ...mapGetters([
      'getRanking',
      'getRankingPoints'
    ]),
    rows() {
      return this.getRankingPoints.length;
    }
  },
  methods: {
    ...mapActions([
      'getRankingById',
      'updateRanking',
      'getRankingPointsById',
    ]),
    gstr(year) {
      return ToString(year);
    },
    /**
     * Validation type submit
     */
    // eslint-disable-next-line no-unused-vars
    typeForm(e) {
      // console.log(typeof(this.getRanking.year1))
      this.typesubmit = true;
      this.isError = false;
      this.Error = null;
      // stop here if form is invalid
      this.$v.$touch();
      if (this.$v.typeform.name.$error || this.$v.typeform.year.$error) {
        return ;
      }
      return (
        this.updateRanking({
            id: this.getRanking.id,
            name: this.typeform.name,
            year: this.typeform.year,
          })
          .then((res) => {
            this.$router.push({name: "RankingPoints"});
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
            <form action="#" @submit.prevent="typeForm">
              <div class="form-group">
                <label>Nombre</label>
                <input
                  v-model="typeform.name=getRanking.name"
                  type="text"
                  class="form-control"
                  placeholder="Nombre"
                  name="name"
                  :class="{ 'is-invalid': typesubmit && $v.typeform.name.$error }"
                />
                <div v-if="typesubmit && $v.typeform.name.$error" class="invalid-feedback">
                  <span v-if="!$v.typeform.name.required">Este Campo es Obligatorio.</span>
                </div>
              </div>
              <div class="form-group">
                <label>Año</label>
                <br />
                <date-picker
                  v-model="typeform.year=getRanking.year"
                  type="year"
                  value-type="format"
                  lang="en"
                  placeholder="año"
                  :class="{ 'is-invalid': typesubmit && $v.typeform.year.$error }"
                ></date-picker>
                <div v-if="typesubmit && $v.typeform.year.$error" class="invalid-feedback">
                  <span v-if="!$v.typeform.year.required">Este Campo es Obligatorio.</span>
                </div>
              </div>
              <div class="form-group mt-5 mb-0">
                <div>
                  <button type="submit" class="btn btn-primary">Guardar</button>
                  <router-link to="/admin/ranking_points" class="btn btn-secondary m-l-5 ml-1">Cancelar</router-link>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div>
          <!-- Table -->
          <div class="table-responsive table-dark mb-0">
            <b-table
              :items="getRankingPoints"
              :fields="fields"
              responsive="sm"
              :sort-by.sync="sortBy"
              :sort-desc.sync="sortDesc"
            >
              <template #thead-top="data">
                <b-tr>
                  <b-th variant="success" colspan="2" style="color: black;">{{ getRankingPoints[0].ranking.name + " " + getRankingPoints[0].ranking.year }}</b-th>
                </b-tr>
              </template>
            </b-table>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>