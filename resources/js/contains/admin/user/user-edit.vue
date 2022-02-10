<script>
import Layout from "../subcomponent/layout";
import PageHeader from "@/components/page-header";
import appConfig from "@/app.config";

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
    title: "EDITAR USUARIO",
    meta: [{ name: "description", content: appConfig.description }]
  },
  components: { Layout, PageHeader, Multiselect },
  data() {
    return {
      title: "EDITAR USUARIO",
      items: [
        {
          text: "Home",
          href: "/admin/competitions"
        },
        {
          text: "Listado Usuario",
          href: "/admin/users"
        },
        {
          text: "Modificar Datos Usuario",
          active: true
        }
      ],
      roleOptions: [
        "Admin",
        "Judge",
        "User",
      ],
      isError: false,
      Error: null,
      typeform: {
        name: "",
        email: "",
        password: "",
        confirmPassword: "",
        roles: "",
      },
      typesubmit: false,
    };
  },
  validations: {
    typeform: {
      name: { required },
      password: {},
      confirmPassword: { sameAsPassword: sameAs("password") },
      email: { required, email },
      roles: { required },
    }
  },
  mounted() {
    this.getUserById(this.$route.params.userId);
  },
  computed: {
    ...mapGetters([
      'getUser'
    ]),
  },
  methods: {
    ...mapActions([
      'getUserById',
      'updateUser'
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
      if (this.$v.typeform.name.$error || this.$v.typeform.email.$error || this.$v.typeform.confirmPassword.$error || this.$v.typeform.roles.$error) {
        return ;
      }
      return (
        this.updateUser({
            id: this.getUser.id,
            name: this.typeform.name,
            email: this.typeform.email,
            password: this.typeform.password,
            password_confirmation: this.typeform.confirmPassword,
            roles: this.typeform.roles,
          })
          .then((res) => {
            this.$router.push({name: "Users"});
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
                  v-model="typeform.name=getUser.name"
                  type="text"
                  class="form-control"
                  placeholder="Nombre"
                  name="name"
                  :class="{ 'is-invalid': typesubmit && $v.typeform.name.$error }"
                />
                <div v-if="typesubmit && $v.typeform.name.$error" class="invalid-feedback">
                  <span v-if="!$v.typeform.name.required">Este Campo es obligatorio.</span>
                </div>
              </div>

              <div class="form-group">
                <label>E-Mail</label>
                <div>
                  <input
                    v-model="typeform.email=getUser.email"
                    type="email"
                    name="email"
                    class="form-control"
                    :class="{ 'is-invalid': typesubmit && $v.typeform.email.$error }"
                    placeholder="Enter a valid email"
                  />
                  <div v-if="typesubmit && $v.typeform.email.$error" class="invalid-feedback">
                    <span v-if="!$v.typeform.email.required">Este Campo es obligatorio.</span>
                    <span v-if="!$v.typeform.email.email">Debe ser un e-mail válido..</span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Contraseña</label>
                <div>
                  <input
                    v-model="typeform.password"
                    type="password"
                    name="password"
                    class="form-control"
                    :class="{ 'is-invalid': typesubmit && $v.typeform.password.$error }"
                    placeholder="Contraseña"
                  />
                  <div v-if="typesubmit && $v.typeform.password.$error" class="invalid-feedback">
                    <span v-if="!$v.typeform.password.required">Este Campo es obligatorio.</span>
                    <span
                      v-if="!$v.typeform.password.minLength"
                    >La contraseña debe tener al menos 6 cracteres.</span>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Confirmar Contraseña</label>
                <div>
                  <input
                    v-model="typeform.confirmPassword"
                    type="password"
                    name="confirmPassword"
                    class="form-control"
                    :class="{ 'is-invalid': typesubmit && $v.typeform.confirmPassword.$error }"
                    placeholder="Confirmar Contraseña"
                  />
                  <div
                    v-if="typesubmit && $v.typeform.confirmPassword.$error"
                    class="invalid-feedback"
                  >
                    <span v-if="!$v.typeform.confirmPassword.required">Este Campo es obligatorio.</span>
                    <span
                      v-else-if="!$v.typeform.confirmPassword.sameAsPassword"
                    >La confirmación de contraseña es erroena.</span>
                  </div>
                </div>
              </div>
              <div>
                <label>Tipo</label>
                <multiselect 
                  v-model="typeform.roles=getUser.roleNames"
                  :options="roleOptions"
                  :multiple="true"
                  :class="{ 'is-invalid': typesubmit && $v.typeform.roles.$error }"
                ></multiselect>
                <div v-if="typesubmit && $v.typeform.roles.$error" class="invalid-feedback">
                  <span v-if="!$v.typeform.roles.required">Este Campo es obligatorio.</span>
                </div>
              </div>
              
              <div class="form-group mt-5 mb-0">
                <div>
                  <button type="submit" class="btn btn-primary">Guardar</button>
                  <router-link to="/admin/users" class="btn btn-secondary m-l-5 ml-1">Cancelar</router-link>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>