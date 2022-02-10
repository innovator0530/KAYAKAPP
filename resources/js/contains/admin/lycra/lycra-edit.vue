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
    title: "EDITAR LYCRA",
    meta: [{ name: "description", content: appConfig.description }]
  },
  components: { Layout, PageHeader },
  data() {
    return {
      title: "EDIT LYCRA",
      items: [
        {
          text: "Home",
          href: "/admin/competitions"
        },
        {
          text: "Listado Lycras",
          href: "/admin/lycras"
        },
        {
          text: "Editar Lycra",
          active: true
        }
      ],
      isError: false,
      Error: null,
      typeform: {
        color: "",
        name: "",
      },
      typesubmit: false,
    };
  },
  validations: {
    typeform: {
      name: { required },
      color: { required },
    }
  },
  mounted() {
    this.getLycraById(this.$route.params.lycraId);
  },
  computed: {
    ...mapGetters([
      'getLycra'
    ]),
  },
  methods: {
    ...mapActions([
      'getLycraById',
      'updateLycra'
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
      if (this.$v.typeform.color.$error || this.$v.typeform.name.$error) {
        return ;
      }
      return (
        this.updateLycra({
            id: this.getLycra.id,
            color: this.typeform.color,
            name: this.typeform.name,
          })
          .then((res) => {
            this.$router.push({name: "Lycras"});
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
                  v-model="typeform.name=getLycra.name"
                  type="text"
                  class="form-control"
                  placeholder="Color Name"
                  name="name"
                  :class="{ 'is-invalid': typesubmit && $v.typeform.name.$error }"
                />
                <div v-if="typesubmit && $v.typeform.name.$error" class="invalid-feedback">
                  <span v-if="!$v.typeform.name.required">Este Campo es Obligatorio.</span>
                </div>
              </div>
              <div class="form-group">
                <label>Color</label>
                <input
                  v-model="typeform.color=getLycra.color"
                  type="color"
                  class="form-control"
                  name="color"
                  :class="{ 'is-invalid': typesubmit && $v.typeform.color.$error }"
                />
                <div v-if="typesubmit && $v.typeform.color.$error" class="invalid-feedback">
                  <span v-if="!$v.typeform.color.required">Este Campo es Obligatorio.</span>
                </div>
              </div>             
              <div class="form-group mt-5 mb-0">
                <div>
                  <button type="submit" class="btn btn-primary">Guardar</button>
                  <router-link to="/admin/lycras" class="btn btn-secondary m-l-5 ml-1">Cancelar</router-link>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>