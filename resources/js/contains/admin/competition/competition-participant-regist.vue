<script>
	import Layout from "../subcomponent/layout";
	import appConfig from "@/app.config";
  import PageHeader from "@/components/page-header";
  import Multiselect from "vue-multiselect";

  import { mapActions, mapGetters } from 'vuex';

	export default {
		page: {
        title: "AÑADIR PARTICIPANTES A COMPETICION",
        meta: [{ name: "description", content: appConfig.description }]
    },
    components: {
      Layout,
      PageHeader,
      Multiselect
    },
    data() {
      return {
        title: "AÑADIR PARTICIPANTES A COMPETICION",
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
            text: "Añadir Participantes a Competición",
            active: true,
          },
        ],
        totalRows_1: 1,
        currentPage_1: 1,
        perPage_1: 10,
        pageOptions: [10, 25, 50, 100],
        filter_1: null,
        filterOn_1: [],
        sortBy: "surname",
        sortDesc: false,
        fields: [
          { label: "Nombre", key: "name", sortable: true },
          { label: "Apellidos", key: "surname", sortable: true },
          { label: "Fecha Nac.", key: "birthday", sortable: false },
          { label: "Club", key: "club", sortable: true },
          { label: "Acciones", key: "actions", sortable: false },
        ],
        totalRows_2: 1,
        currentPage_2: 1,
        perPage_2: 10,
        filter_2: null,
        filterOn_2: [],
        isRequiredModality: {
          register: true,
          edit: true,
        },
        modalityOptions: [
          "Corto",
          "Largo",
        ],
        register_modalities: [
          "Corto",
          "Largo",
        ],
        edit_modalities: [],
        isRequiredCategory: {
          register: true,
          edit: true,
        },
        participantCategoryOptions: [],
        register_categories: [],
        edit_categories: [],
        participantId: 0,
      }
    },
    computed: {
      ...mapGetters([
        'getRegisteredParticipants',
        'getNonRegisteredParticipants',
      ]),
      /**
       * Total no. of records
       */
      rows_2() {
        return this.getRegisteredParticipants.length;
      },
      rows_1() {
        return this.getNonRegisteredParticipants.length;
      },
    },
    mounted() {
      // Set the initial number of items
      this.totalRows_2 = this.getRegisteredParticipants.length;
      this.totalRows_1 = this.getNonRegisteredParticipants.length;
      this.initParticipantsForCompetition(this.$route.params.competitionId);
    },
    methods: {
      ...mapActions([
        'initParticipantsForCompetition',
        'registParticipantToCompetition',
        'updateParticipantToCompetition',
        'unregistParticipantToCompetition',
        'getModAndCatOfParticipant',
        'getParticipantCategoryOptions',
      ]),
      /**
       * Search the table data with search input
       */
      onFiltered_1(filteredItems) {
        // Trigger pagination to update the number of buttons/pages due to filtering
        this.totalRows_1 = filteredItems.length;
        this.currentPage_1 = 1;
      },
      onFiltered_2(filteredItems) {
        // Trigger pagination to update the number of buttons/pages due to filtering
        this.totalRows_2 = filteredItems.length;
        this.currentPage_2 = 1;
      },
      setParticipantId(id) {
        this.participantId = id;
      },
      getParticipantCategoryOptionsIcon(id) {
        this.isRequiredModality.register = true;
        this.isRequiredCategory.register = true;
        this.setParticipantId(id);
        this.getParticipantCategoryOptions(id)
        .then((res) => {
          this.register_categories = res.data.participant_category_options;
          this.participantCategoryOptions = res.data.participant_category_options;
        })
      },
      getModAndCatOfParticipantIcon(id) {
        this.isRequiredModality.edit = true;
        this.isRequiredCategory.edit = true;
        this.setParticipantId(id);
        this.getModAndCatOfParticipant({
          competitionId: this.$route.params.competitionId,
          participantId: id,
        })
        .then((res) => {
          // console.log(res)
          this.edit_categories = res.data.category_participant;
          this.edit_modalities = res.data.modality_participant;
          this.participantCategoryOptions = res.data.participant_category_options;
        })
      },
      registerParticipantWithModAndCat() {
        if (this.register_modalities.length == 0) {
          this.isRequiredModality.register = false;
          return
        }
        this.isRequiredModality.register = true;
        if (this.register_categories.length == 0) {
          this.isRequiredCategory.register = false;
          return
        }
        this.isRequiredCategory.register = true;

        this.registParticipantToCompetition({
          competitionId: this.$route.params.competitionId,
          participantId: this.participantId,
          modality: this.register_modalities,
          category: this.register_categories,
        });
        this.$bvModal.hide('register-modality-modal');
        this.register_modalities = ["Corto", "Largo"];
      },
      editParticipantWithModAndCat() {
        if (this.edit_modalities.length == 0) {
          this.isRequiredModality.edit = false;
          return
        }
        this.isRequiredModality.edit = true;
        if (this.edit_categories.length == 0) {
          this.isRequiredCategory.edit = false;
          return
        }
        this.isRequiredCategory.edit = true;

        this.updateParticipantToCompetition({
          competitionId: this.$route.params.competitionId,
          participantId: this.participantId,
          modality: this.edit_modalities,
          category: this.edit_categories,
        });
        this.$bvModal.hide('edit-modality-modal');
      },
      unregisterParticipant() {
        this.unregistParticipantToCompetition({
          competitionId: this.$route.params.competitionId,
          participantId: this.participantId,
        });
        this.$bvModal.hide('unregister-modality-modal');
      },
      // back() {
      //   this.$router.go(-1);
      // },
    }
	};
</script>
<template>
  <Layout>
    <PageHeader :title="title" :items="items">
      <div class="float-right d-flex">
        <router-link :to="{ name: 'CompetitionParticipantAdd', params: { competitionId: this.$route.params.competitionId } }"
          class="btn btn-info btn-block d-inline-block"
        >
          <i class="fas fa-plus mr-1"></i> NUEVO PARTICIPANTE
        </router-link>
        <router-link :to="{ name: 'Competitions'}"
          class="btn btn-secondary ml-lg-4 ml-3"
        >
          Volver
        </router-link>
      </div>
    </PageHeader>

    <div class="row">
      <div class="col-lg-6 col-md-12">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">No Regitrados en la Competición</h4>
            <p class="card-title-desc"></p>
            <div class="row mb-md-2">
              <div class="col-sm-12 col-md-6">
                <div id="tickets-table_length" class="dataTables_length">
                  <label class="d-inline-flex align-items-center">
                    Mostrar
                    <b-form-select v-model="perPage_1" size="sm" :options="pageOptions"></b-form-select>registros
                  </label>
                </div>
              </div>
              <!-- Search -->
              <div class="col-sm-12 col-md-6">
                <div id="tickets-table_filter" class="dataTables_filter text-md-right">
                  <label class="d-inline-flex align-items-center">
                    Buscar:
                    <b-form-input
                      v-model="filter_1"
                      type="search"
                      placeholder="Buscar..."
                      class="form-control form-control-sm ml-2"
                    ></b-form-input>
                  </label>
                </div>
              </div>
              <!-- End search -->
            </div>
            <!-- Table -->
            <div class="table-responsive table-dark mb-0">
              <b-table
                :items="getNonRegisteredParticipants"
                :fields="fields"
                responsive="sm"
                :per-page="perPage_1"
                :current-page="currentPage_1"
                :sort-by.sync="sortBy"
                :sort-desc.sync="sortDesc"
                :filter="filter_1"
                :filter-included-fields="filterOn_1"
                @filtered="onFiltered_1"
              >
                <template #cell(sex)="row">
                  {{ row.item.sex.name }}
                </template>
                <template #cell(club)="row">
                  {{ row.item.club.name }}
                </template>
                <template #cell(actions)="row">
                  <b-button size="sm" @click="getParticipantCategoryOptionsIcon(row.item.id)" v-b-modal.register-modality-modal>
                    <i class="fas fa-user-plus"></i>
                  </b-button>
                </template>
              </b-table>
            </div>
            <div class="row">
              <div class="col">
                <div class="dataTables_paginate paging_simple_numbers float-right">
                  <ul class="pagination pagination-rounded mb-0">
                    <!-- pagination -->
                    <b-pagination v-model="currentPage_1" :total-rows="rows_1" :per-page="perPage_1"></b-pagination>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6 col-md-12">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Registrados en la Competición</h4>
            <p class="card-title-desc"></p>
            <div class="row mb-md-2">
              <div class="col-sm-12 col-md-6">
                <div id="tickets-table_length" class="dataTables_length">
                  <label class="d-inline-flex align-items-center">
                    Mostrar
                    <b-form-select v-model="perPage_2" size="sm" :options="pageOptions"></b-form-select>registros
                  </label>
                </div>
              </div>
              <!-- Search -->
              <div class="col-sm-12 col-md-6">
                <div id="tickets-table_filter" class="dataTables_filter text-md-right">
                  <label class="d-inline-flex align-items-center">
                    Buscar:
                    <b-form-input
                      v-model="filter_2"
                      type="search"
                      placeholder="Buscar..."
                      class="form-control form-control-sm ml-2"
                    ></b-form-input>
                  </label>
                </div>
              </div>
              <!-- End search -->
            </div>
            <!-- Table -->
            <div class="table-responsive table-dark mb-0">
              <b-table
                :items="getRegisteredParticipants"
                :fields="fields"
                responsive="sm"
                :per-page="perPage_2"
                :current-page="currentPage_2"
                :sort-by.sync="sortBy"
                :sort-desc.sync="sortDesc"
                :filter="filter_2"
                :filter-included-fields="filterOn_2"
                @filtered="onFiltered_2"
              >
                <template #cell(sex)="row">
                  {{ row.item.sex.name }}
                </template>
                <template #cell(club)="row">
                  {{ row.item.club.name }}
                </template>
                <template #cell(actions)="row">
                  <b-button size="sm" @click="getModAndCatOfParticipantIcon(row.item.id)" v-b-modal.edit-modality-modal>
                    <i class="fas fa-user-edit"></i>
                  </b-button>
                  <b-button size="sm" @click="setParticipantId(row.item.id)" v-b-modal.unregister-modality-modal>
                    <i class="fas fa-user-minus"></i>
                  </b-button>
                </template>
              </b-table>
            </div>
            <div class="row">
              <div class="col">
                <div class="dataTables_paginate paging_simple_numbers float-right">
                  <ul class="pagination pagination-rounded mb-0">
                    <!-- pagination -->
                    <b-pagination v-model="currentPage_2" :total-rows="rows_2" :per-page="perPage_2"></b-pagination>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <b-modal
      id="register-modality-modal"
      centered
      title="Elegir Participante"
      title-class="font-18"
      hide-footer
    >
      <div class="">
        <label>Modalidad</label>
        <multiselect 
          v-model="register_modalities"
          :options="modalityOptions"
          :multiple="true"
        ></multiselect>
        <div class="invalid-feedback" :class="{ 'd-inline-block': !isRequiredModality.register }">
          <span>Este Campo es Obligatorio.</span>
        </div>
      </div>
      <div class="mb-2">
        <label>Categoría</label>
        <multiselect 
          v-model="register_categories"
          :options="participantCategoryOptions"
          :multiple="true"
        ></multiselect>
        <div class="invalid-feedback" :class="{ 'd-inline-block': !isRequiredCategory.register }">
          <span>Este Campo es Obligatorio.</span>
        </div>
      </div>
      <footer class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="$bvModal.hide('register-modality-modal')">Cancelar</button>
        <button type="button" class="btn btn-primary" @click="registerParticipantWithModAndCat()">Guardar</button>
      </footer>
    </b-modal>

    <b-modal
      id="edit-modality-modal"
      centered
      title="Actualizar Participante"
      title-class="font-18"
      hide-footer
    >
      <div class="">
        <label>Modalidad</label>
        <multiselect 
          v-model="edit_modalities"
          :options="modalityOptions"
          :multiple="true"
        ></multiselect>
        <div class="invalid-feedback" :class="{ 'd-inline-block': !isRequiredModality.edit }">
          <span>Este Campo es Obligatorio.</span>
        </div>
      </div>
      <div class="mb-2">
        <label>Categoría</label>
        <multiselect 
          v-model="edit_categories"
          :options="participantCategoryOptions"
          :multiple="true"
        ></multiselect>
        <div class="invalid-feedback" :class="{ 'd-inline-block': !isRequiredCategory.edit }">
          <span>Este Campo es Obligatorio.</span>
        </div>
      </div>
      <footer class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="$bvModal.hide('edit-modality-modal')">Cancelar</button>
        <button type="button" class="btn btn-primary" @click="editParticipantWithModAndCat()">Guardar</button>
      </footer>
    </b-modal>

    <b-modal
      id="unregister-modality-modal"
      centered
      title="Eliminar Participante"
      title-class="font-18"
      hide-footer
    >
      <p>¿Está seguro de eliminar este aprticipante de la competición?</p>
      <footer class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="$bvModal.hide('unregister-modality-modal')">Cancelar</button>
        <button type="button" class="btn btn-primary" @click="unregisterParticipant()">Eliminar</button>
      </footer>
    </b-modal>
  </Layout>
</template>
<style scoped>
  @media (max-width: 1024px) {
    .table button {
      margin-bottom: 3px;
    }
  }
</style>