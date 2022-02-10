<script>
	import Layout from "../subcomponent/layout";
	import appConfig from "@/app.config";
  import PageHeader from "@/components/page-header";
  import Multiselect from "vue-multiselect";

  import { mapActions, mapGetters, mapState } from 'vuex';

	export default {
		page: {
        title: "CUADROS DE COMPETICIÓN",
        meta: [{ name: "description", content: appConfig.description }]
    },
    components: {
      Layout,
      PageHeader,
      Multiselect
    },
    data() {
      return {
        title: "CUADROS DE COMPETICIÓN",
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
            text: "Crear Cuadro de Competición",
            active: true
          }
        ],
        categoryModality: '',
        totalRows: 1,
        currentPage: 1,
        perPage: 25,
        pageOptions: [10, 25, 50, 100],
        filter: null,
        filterOn: [],
        sortBy: "",
        sortDesc: false,
        fields: [
          { label: "Nombre", key: "name", sortable: true },
          { label: "Apellidos", key: "surname", sortable: true },
          { label: "DNI Ficha", key: "dni_ficha", sortable: false },
          { label: "Fecha Nac.", key: "birthday", sortable: false },
          { label: "Club", key: "club", sortable: true },
          { label: "Acciones", key: "actions", sortable: false },
        ],
        modalityOptions: [
          "Corto",
          "Largo",
        ],
        availableCategoryOptions: [],
        isRequired: {
          modality: true,
          category: true,
        },
        edit_modalities: [],
        edit_categories: [],
        participantId: 0,
      }
    },
    watch: {
      categoryModalityWithPart: function () {
        this.categoryModality = this.categoryModalityWithPart[0];
        this.getParticipantsByCompetitionCategoryModality({
          competitionId: this.$route.params.competitionId,
          categoryModality: this.categoryModality.label,
        });
      },
      competition: function () {
        this.title = "CUADROS DE COMPETICIÓN" + " (" + this.competition.title + ")";
      },
    },
    computed: {
      ...mapGetters([
        'categoryModalityWithPart',
        'ParticipantsByCompetitionCategoryModality',
        'categoryId',
        'modalityId',
        'competition',
        'categoryStatus',
        'active_Status',
        'deleteStatus',
      ]),
      /**
       * Total no. of records
       */
      rows() {
        return this.ParticipantsByCompetitionCategoryModality.length;
      },
    },
    mounted() {
      // Set the initial number of items
      this.totalRows = this.ParticipantsByCompetitionCategoryModality.length;
      this.getCategoryModalityWithPart(this.$route.params.competitionId);
    },
    methods: {
      ...mapActions([
        'setProgressStatus',
        'getCategoryModalityWithPart',
        'getParticipantsByCompetitionCategoryModality',
        'unregistParticipantToCompetitionCategoryModality',
        'createFirstCompetitionBoxes',
        'getModAndCatOfParticipant',
        'getAvailableCategories',
        'updateParticipantToCompetition',
        'deleteCompetitionBoxes',
      ]),
      /**
       * Search the table data with search input
       */
      onFiltered(filteredItems) {
        // Trigger pagination to update the number of buttons/pages due to filtering
        this.totalRows = filteredItems.length;
        this.currentPage = 1;
      },
      setParticipantId(id) {
        this.participantId = id;
      },

      categoryModalityHandler() {
        console.log(this.active_Status);
        // console.log(this.categoryModality)
        if (this.categoryModality != null) {
          this.getParticipantsByCompetitionCategoryModality({
            competitionId: this.$route.params.competitionId,
            categoryModality: this.categoryModality.label,
          });
        }
      },
      getModAndCatOfParticipantIcon(id) {
        this.isRequired.modality = true;
        this.isRequired.category = true;
        this.setParticipantId(id);
        this.getModAndCatOfParticipant({
          competitionId: this.$route.params.competitionId,
          participantId: id,
        })
        .then((res) => {
          // console.log(res)
          this.edit_categories = res.data.category_participant;
          this.edit_modalities = res.data.modality_participant;
          this.availableCategoryOptions = res.data.available_category_options;
        })
      },
      editModalityHandler() {
        // console.log(this.edit_modalities)
        if (this.edit_modalities.length > 0) {
          this.getAvailableCategories({
            competitionId: this.$route.params.competitionId,
            participantId: this.participantId,
            modality: this.edit_modalities,
          })
          .then((res) => {
            console.log(res)
            this.availableCategoryOptions = res.data.available_category_options;
          })
        }
      },
      editParticipantWithModAndCat() {
        if (this.edit_modalities.length == 0) {
          this.isRequired.modality = false;
          return
        }
        if (this.edit_categories.length == 0) {
          this.isRequired.category = false;
          return
        }
        this.updateParticipantToCompetition({
          competitionId: this.$route.params.competitionId,
          participantId: this.participantId,
          modality: this.edit_modalities,
          category: this.edit_categories,
        })
        .then((res) => {
          this.$bvModal.hide('edit-modal')
          this.getParticipantsByCompetitionCategoryModality({
            competitionId: this.$route.params.competitionId,
            categoryModality: this.categoryModality.label,
          })
          .then((res) => {
            // console.log(res)
            if (res.participants_competition_category_modality.length == 0) {
              this.refresh();
            }
          });
        });
      },
      unregisterParticipant() {
        this.unregistParticipantToCompetitionCategoryModality({
          competitionId: this.$route.params.competitionId,
          participantId: this.participantId,
          categoryModality: this.categoryModality.label,
        })
        .then((res) => {
          this.$bvModal.hide('unregister-modal')
          if (res.data.participants_competition_category_modality.length == 0) {
            this.refresh();
          } else {
            this.getParticipantsByCompetitionCategoryModality({
              competitionId: this.$route.params.competitionId,
              categoryModality: this.categoryModality.label,
            });
          }
        });
      },
      createCompetitionBox() {
        this.createFirstCompetitionBoxes({
          competitionId: this.$route.params.competitionId,
          categoryId: this.categoryId,
          modalityId: this.modalityId,
        })
        .then((res) => {
          this.$router.push({ name: 'CompetitionHeats', params: { competitionId: this.$route.params.competitionId, categoryId: this.categoryId, modalityId: this.modalityId } })
        })
      },
        goActiveManga() {
          let active_manga;
          let category_id, modality_id;
          let round, heat;
          this.categoryModalityWithPart.forEach( category => {
              if (category.status==="active"){
                  active_manga = category.label;
                  round = category.round;
                  category_id = category.categoryid;
                  modality_id = category.modalityid;
                  heat = category.heat;
                  return false
              }

          })
          console.log(round + ' ' + heat + ' ' + category_id + ' ' + modality_id);
          this.setProgressStatus({
            competitionId: this.$route.params.competitionId,
            categoryId: category_id,
            modalityId: modality_id,
            round: round,
            heat: heat
          })
          .then((res) => {
            // console.log(res.data)
            if (res.data.message == "success") {
              this.$router.push({ name: 'CompetitionHeatDetails', params: {competitionId: this.$route.params.competitionId, categoryId: category_id, modalityId: modality_id, round: round, heat: heat} })
            } else {
              this.$toastr.warning('No se puede empezar otra manga hasta terminar la que ya está empezada. Revisa el listado de categorías para detectar la manga activa.', '', {timeout: 5000,closeButton: true,closeMethod: 'fadeOut',closeDuration: 300});
            }
          }) 


        },
      deleteCompetitionBox() {
        this.deleteCompetitionBoxes({
          competitionId: this.$route.params.competitionId,
          categoryId: this.categoryId,
          modalityId: this.modalityId,
        })
        .then((res) => {
          this.refresh();
        })
      },
      back() {
        this.$router.go(-1);
      },
      labelWithStatus ({ label, status }) {
        if (status == 'deactive') {
          return `${label} (MENOS DE 3 PALISTAS)`
        } else if (status == 'active') {
          return `${label} (MANGA ACTIVA)`
        } else if (status == 'finished') {
          return `${label} (Cuadro terminado)`
        }
        return `${label}`
      },
      getStatus ({label, status}) {
          return status
      },
      refresh() {
        window.location.reload();
      },
    }
	};
</script>
<template>
  <Layout>
    <PageHeader :title="title" :items="items">

      <div class="float-right d-flex">
          <button v-if="active_Status == 1"  @click="goActiveManga"
                   class="btn btn-success mr-1"
          >
              MANGA ACTIVA
          </button>
        <button v-if="deleteStatus && categoryStatus == 1" @click="deleteCompetitionBox"
          class="btn btn-info mr-lg-2 mr-1"
        >
          Eliminar Cuadro
        </button>
        <button v-if="categoryStatus == 0 && ParticipantsByCompetitionCategoryModality.length != 1" @click="createCompetitionBox"
          class="btn btn-info"
        >
          Crear Cuadro Competición
        </button>
        <button v-else-if="categoryStatus == 1" @click="createCompetitionBox"
          class="btn btn-success"
        >
          Acceder al cuadro de competición
        </button>
        <button v-else-if="categoryStatus == 2" @click="createCompetitionBox"
          class="btn btn-danger"
        >
          Ver Cuadro finalizado
        </button>
        <button @click="back"
          class="btn btn-secondary ml-lg-2 ml-1"
        >
          Volver
        </button>
      </div>
    </PageHeader>

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title mb-4">Selección Categorias</h4>
            <multiselect
              v-model="categoryModality"
              deselect-label=""
              label="label"
              :custom-label="labelWithStatus"
              :options="categoryModalityWithPart"
              @input="categoryModalityHandler"
            >
              <!-- <template slot="singleLabel" slot-scope="{ option }">
                <span style="color: red;">{{ option.label }}</span>
              </template> -->
            </multiselect>
          </div>
        </div>
      </div>

      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title mb-4">Listado Participantes ({{ competition.title + " " + categoryModality.label }})</h4>
            <div class="row mb-md-2">
              <div class="col-sm-12 col-md-6">
                <div id="tickets-table_length" class="dataTables_length">
                  <label class="d-inline-flex align-items-center">
                    Mostrar
                    <b-form-select v-model="perPage" size="sm" :options="pageOptions"></b-form-select>resgistros
                  </label>
                </div>
              </div>
              <!-- Search -->
              <div class="col-sm-12 col-md-6">
                <div id="tickets-table_filter" class="dataTables_filter text-md-right">
                  <label class="d-inline-flex align-items-center">
                    Buscar:
                    <b-form-input
                      v-model="filter"
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
                :items="ParticipantsByCompetitionCategoryModality"
                :fields="fields"
                responsive="sm"
                :per-page="perPage"
                :current-page="currentPage"
                :sort-by.sync="sortBy"
                :sort-desc.sync="sortDesc"
                :filter="filter"
                :filter-included-fields="filterOn"
                @filtered="onFiltered"
              >
                <template #cell(club)="row">
                  {{ row.item.club.name }}
                </template>
                <template #cell(actions)="row">
                  <b-button size="sm" v-if="ParticipantsByCompetitionCategoryModality.length < 3" :disabled="categoryStatus != 0" @click="getModAndCatOfParticipantIcon(row.item.id)" v-b-modal.edit-modal>
                    <i class="fas fa-user-edit"></i>
                  </b-button>
                  <b-button size="sm" :disabled="categoryStatus != 0" @click="setParticipantId(row.item.id)" v-b-modal.unregister-modal>
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
                    <b-pagination v-model="currentPage" :total-rows="rows" :per-page="perPage"></b-pagination>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 mb-4">
        <div class="float-left d-flex">
          <button @click="refresh"
            class="btn btn-warning ml-lg-4 ml-3"
          >
            Actualizar
          </button>
        </div>
        <div class="float-right d-flex">
          <button v-if="deleteStatus && categoryStatus == 1" @click="deleteCompetitionBox"
            class="btn btn-info mr-lg-2 mr-1"
          >
            Eliminar Cuadro
          </button>
          <button v-if="categoryStatus == 0 && ParticipantsByCompetitionCategoryModality.length != 1" @click="createCompetitionBox"
            class="btn btn-info"
          >
            Crear Cuadro Competición
          </button>
          <button v-else-if="categoryStatus == 1" @click="createCompetitionBox"
            class="btn btn-success"
          >
            Acceder al cuadro de competición
          </button>
          <button v-else-if="categoryStatus == 2" @click="createCompetitionBox"
            class="btn btn-danger"
          >
            Ver Cuadro finalizado
          </button>
          <button @click="back"
            class="btn btn-secondary ml-lg-2 ml-1"
          >
            Volver
          </button>
        </div>
      </div>
    </div>

    <b-modal
      id="edit-modal"
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
          @input="editModalityHandler"
        ></multiselect>
        <div class="invalid-feedback" :class="{ 'd-inline-block': !isRequired.modality }">
          <span>Este Campo es Obligatorio.</span>
        </div>
      </div>
      <div class="mb-2">
        <label>Categoría</label>
        <multiselect
          v-model="edit_categories"
          :options="availableCategoryOptions"
          :multiple="true"
        ></multiselect>
        <div class="invalid-feedback" :class="{ 'd-inline-block': !isRequired.category }">
          <span>Este Campo es Obligatorio.</span>
        </div>
      </div>
      <footer class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="$bvModal.hide('edit-modal')">Cancelar</button>
        <button type="button" class="btn btn-primary" @click="editParticipantWithModAndCat()">Guardar</button>
      </footer>
    </b-modal>

    <b-modal
      id="unregister-modal"
      centered
      title="Eliminar Participante"
      title-class="font-18"
      hide-footer
    >
      <p>¿Está seguro de eliminar al participante de esta Categoria?</p>
      <footer class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="$bvModal.hide('unregister-modal')">Cancelar</button>
        <button type="button" class="btn btn-primary" @click="unregisterParticipant()">Eliminar</button>
      </footer>
    </b-modal>
  </Layout>
</template>

<style>
  .select-field {
    text-align: center;
    padding-bottom: 15px;
  }
</style>
