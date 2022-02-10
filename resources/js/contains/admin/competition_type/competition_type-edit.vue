<script>
import Layout from "../subcomponent/layout";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";

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
    title: "EDITAR TIPO DE COMPETICION",
    meta: [{ name: "description", content: appConfig.description }]
  },
  components: { Layout, PageHeader },
  data() {
    return {
      title: "EDITAR TIPO DE COMPETICION",
      items: [
        {
          text: "Home",
          href: "/admin/competitions"
        },
        {
          text: "Listado Tipos Competición",
          href: "/admin/competition_types"
        },
        {
          text: "Editar Tipo Competición",
          active: true
        }
      ],
      isError: false,
      Error: null,
      typeform: {
        name: "",
        description: "",
      },
      typesubmit: false,
    };
  },
  validations: {
    typeform: {
      name: { required },
      description: { required },
    }
  },
  mounted() {
    this.getCompetitionTypeById(this.$route.params.competition_typeId);
  },
  computed: {
    ...mapGetters([
      'getCompetitionType'
    ]),
  },
  methods: {
    ...mapActions([
      'getCompetitionTypeById',
      'updateCompetitionType'
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
      this.$v.$touch();
      if (this.$v.typeform.name.$error || this.$v.typeform.description.$error) {
        return ;
      }
      return (
        this.updateCompetitionType({
            id: this.getCompetitionType.id,
            name: this.typeform.name,
            description: this.typeform.description,
          })
          .then((res) => {
            this.$router.push({name: "CompetitionTypes"});
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
                  v-model="typeform.name=getCompetitionType.name"
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
                <label>Descripción</label>
                <div>
                  <textarea
                    v-model="typeform.description=getCompetitionType.description"
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
              <div class="form-group mt-5 mb-0">
                <div>
                  <button type="submit" class="btn btn-primary">Guardar</button>
                  <router-link to="/admin/competition_types" class="btn btn-secondary m-l-5 ml-1">Cancelar</router-link>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>