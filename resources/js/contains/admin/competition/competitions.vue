<script>
  import Layout from "../subcomponent/layout";
  import appConfig from "@/app.config";
  import PageHeader from "@/components/page-header";

  import { mapActions, mapGetters } from 'vuex';

  export default {
    page: {
        title: "LISTADO COMPETICIONES",
        meta: [{ name: "description", content: appConfig.description }]
    },
    components: {
      Layout,
      PageHeader,
    },
    data() {
      return {
        title: "LISTADO COMPETICIONES",
        items: [
          {
            text: "Home",
            href: "/admin/competitions"
          },
          {
            text: "Listado Competiciones",
            active: true
          }
        ],
        totalRows: 1,
        currentPage: 1,
        perPage: 10,
        pageOptions: [10, 25, 50, 100],
        filter: null,
        filterOn: [],
        sortBy: "date",
        sortDesc: true,
        fields: [
          { label: "Título", key: "title", sortable: true },
          { label: "Tipo Competición",  key: "competition_type", sortable: true },
          { label: "Lugar", key: "place", sortable: false },
          { label: "Fecha", key: "date", sortable: true },
          { label: "Hora", key: "time", sortable: true },
          { label: "Organizador", key: "organizer", sortable: false },
          { label: "Puntúa Ranking", key: "ranking_score", sortable: false },
          { label: "Estado", key: "status", sortable: false },
          { label: "Acciones", key: "actions", sortable: false }
        ],
        deletingId: 0,
      }
    },
    computed: {
      ...mapGetters([
        'getCompetitions',
      ]),
      rows() {
        return this.getCompetitions.length;
      },
    },
    mounted() {
      // Set the initial number of items
      this.totalRows = this.getCompetitions.length;
      this.initCompetitions();
    },
    methods: {
      ...mapActions ([
        'initCompetitions',
        'deleteCompetition',
        'changeStatus',
      ]),
      /**
       * Search the table data with search input
       */
      onFiltered(filteredItems) {
        // Trigger pagination to update the number of buttons/pages due to filtering
        this.totalRows = filteredItems.length;
        this.currentPage = 1;
      },
      setId(id) {
        this.deletingId = id;
      },
      /**
       * quito la funcion de eliminar competiciones.
       */
      //realDelete() {
      //  this.deleteCompetition(this.deletingId);
      //  this.$bvModal.hide('delete-modal');
     // },
      // rowClicked(item, index, event) {
      //   // console.log(item.status.name)
      //   if (item.status.name == "REGISTRATION OPEN") {
      //     this.$router.push({name: "CompetitionParticipantCreate", params: { competitionId: item.id }});
      //   } else if (item.status.name == "COMPETITION IN PROGRESS") {
      //     alert("COMPETITION IN PROGRESS")
      //   } else if (item.status.name == "CLOSED") {
      //     alert("CLOSED")
      //   }
      // }
    }
  };
</script>
<template>
  <Layout>
    <PageHeader :title="title" :items="items">
      <div class="float-right">
        <router-link to="/admin/competition/create"
          class="btn btn-info btn-block d-inline-block"
        >
          <i class="fas fa-plus mr-1"></i> CREAR COMPETICIÓN
        </router-link>
      </div>
    </PageHeader>

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Listado Competiciones</h4>
            <p class="card-title-desc"></p>
            <div class="row mb-md-2">
              <div class="col-sm-12 col-md-6">
                <div id="tickets-table_length" class="dataTables_length">
                  <label class="d-inline-flex align-items-center">
                    Mostrar
                    <b-form-select v-model="perPage" size="sm" :options="pageOptions"></b-form-select>registros
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
                :items="getCompetitions"
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
                <template #cell(competition_type)="row">
                  {{ row.item.competition_type.name }}
                </template>
                <template #cell(ranking_score)="row">
                  <span class="badge badge-info" v-if="row.item.ranking_score=='Si'">{{ row.item.ranking_score }}</span>
                  <span class="badge badge-warning" v-if="row.item.ranking_score=='No'">{{ row.item.ranking_score }}</span>
                </template>
                <template #cell(status)="row">
                  <select class="custom-select" v-model="row.item.status.name" @change="changeStatus({id: row.item.id, status: row.item.status.name})">
                    <option value="CERRADA">CERRADA</option>
                    <option value="REGISTRO ABIERTO" :disabled="!row.item.isOpening">REGISTRO ABIERTO</option>
                    <option value="EN CURSO">EN CURSO</option>
                  </select>
                </template>
                <template #cell(actions)="row">
                  <router-link :to="{ name: 'CompetitionEdit', params: { competitionId: row.item.id } }" class="btn btn-sm btn-secondary" v-b-tooltip.hover.top="'Edit'" v-if="row.item.status.id == 2">
                    <i class="far fa-edit"></i>
                  </router-link>
                  <router-link to="#" class="btn btn-sm btn-secondary disabled" v-else-if="row.item.status.id == 1">
                    <i class="far fa-edit"></i>
                  </router-link>
                  <router-link :to="{ name: 'DeterminedParticipants', params: { competitionId: row.item.id } }" class="btn btn-sm btn-secondary" v-b-tooltip.hover.top="'Empezar Competición'" v-else>
                    <i class="fas fa-trophy"></i>
                  </router-link>
                  <router-link :to="{ name: 'CompetitionParticipantRegist', params: { competitionId: row.item.id } }" class="btn btn-sm btn-secondary" v-b-tooltip.hover.top="'Añadir Participantes'" v-if="row.item.status.id == 2">
                    <i class="fas fa-user-plus"></i>
                  </router-link>
                  <router-link to="#" class="btn btn-sm btn-secondary disabled" v-else>
                    <i class="fas fa-user-plus"></i>
                  </router-link>
                 <!-- No mostramos el botón eliminar competición para que no se borren por error. 
                 <b-button size="sm" @click="setId(row.item.id)" v-b-modal.delete-modal v-b-tooltip.hover.top="'Eliminar'">
                    <i class="fas fa-trash"></i>
                  </b-button>
                -->
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
    </div>
    <!-- SI no hay botón neliminar no hace falta la ventana qeu pregunta 
    <b-modal
      id="delete-modal"
      centered
      title="Eliminar Competición"
      title-class="font-18"
      hide-footer
    >
      <p>¿Está seguro de eliminar esta competición?</p>
      <footer id="delete-modal___BV_modal_footer_" class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="$bvModal.hide('delete-modal')">Cancelar</button>
        <button type="button" class="btn btn-primary" @click="realDelete()">OK</button>
      </footer>
    </b-modal>
     -->
  </Layout>
</template>
<style scoped>
  @media (max-width: 1024px) {
    .table a {
      margin-bottom: 3px;
    }
  }
</style>