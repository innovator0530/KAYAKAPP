<script>
	import Layout from "../subcomponent/layout";
	import appConfig from "@/app.config";
  import PageHeader from "@/components/page-header";
  import Multiselect from "vue-multiselect";
  import jsPDF from 'jspdf'
  import VueHtml2pdf from 'vue-html2pdf'

  import { mapActions, mapGetters } from 'vuex';

	export default {
		page: {
        title: "CLASIFICACIONES RANKING",
        meta: [{ name: "description", content: appConfig.description }]
    },
    components: {
      Layout,
      PageHeader,
      Multiselect,
      VueHtml2pdf
    },
    data() {
      return {
        title: "CLASIFICACIONES RANKING",
        items: [
          {
            text: "Home",
            href: "/admin/competitions"
          },
          {
            text: "Clasificaciones Ranking",
            active: true
          }
        ],
        categoryModality: "",
        totalRows: 1,
        currentPage: 1,
        perPage: 50,
        pageOptions: [10, 25, 50, 100],
        filter: null,
        filterOn: [],
        sortBy: "",
        sortDesc: false,
        // fields: [
        //   { label: "POSITION", key: "position", sortable: false },
        //   { label: "PARTICIPANT", key: "participant", sortable: false },
        //   { label: "3 BEST SUM", key: "3_best_sum", sortable: false },
        //   { label: "SOPELANA", key: "sopelana", sortable: false },
        //   { label: "LEKEITIO", key: "lekeitio", sortable: false },
        //   { label: "COPA I", key: "copa1", sortable: false },
        //   { label: "COPA II", key: "copa2", sortable: false },
        //   { label: "COPA III", key: "copa3", sortable: false },
        //   { label: "BEST RESULT", key: "best_result", sortable: false },
        //   { label: "2nd RESULT", key: "2nd_result", sortable: false },
        //   { label: "3rd RESULT", key: "3rd_result", sortable: false },
        // ],
      }
    },
    watch: {
      getAllCategoryModality: function () {
        this.categoryModality = this.getAllCategoryModality[0];
        this.getCategoryRankingPoints({
          categoryModality: this.categoryModality,
        });
      },
    },
    computed: {
      ...mapGetters([
        'getAllCategoryModality',
        'categoryModalityWithResults',
        'categoryRankingPoints',
        'competitionNumber',
      ]),
      /**
       * Total no. of records
       */
      rows() {
        return this.categoryRankingPoints.length;
      }
    },
    mounted() {
      // Set the initial number of items
      this.totalRows = this.categoryRankingPoints.length;
      this.initRankingMenu();
    },
    methods: {
      ...mapActions([
        'initRankingMenu',
        'getCategoryRankingPoints',
        'finalRankingPDF',
      ]),
      /**
       * Search the table data with search input
       */
      onFiltered(filteredItems) {
        // Trigger pagination to update the number of buttons/pages due to filtering
        this.totalRows = filteredItems.length;
        this.currentPage = 1;
      },
      categoryModalityHandler() {
        this.getCategoryRankingPoints({
          categoryModality: this.categoryModality,
        });
      },
      printRankingTable() {
        var element = document.getElementById('ranking_table');
        const e_width = element.offsetWidth;
        var pdf = new jsPDF('p', 'px', [element.offsetWidth, element.offsetHeight]);
        const pdfWidth = pdf.internal.pageSize.getWidth();
        pdf.html(element, {
          html2canvas: {
            scale: (pdfWidth-16)/e_width,
          },
          x: 8,
          y: 8,
          margin:[10,10,10,10],
          callback: function (pdf) {
            window.open(pdf.output('bloburl'));
          }
        });
      },
      printFinalTable() {
        var pdf = new jsPDF('p', 'mm', 'a4');
        var element = document.getElementById('final_ranking_table');
        const e_width = element.offsetWidth;
        const pdfWidth = pdf.internal.pageSize.getWidth();
        pdf.html(element, {
          html2canvas: {
            scale: (pdfWidth-16)/e_width,
          },
          x: 8,
          y: 8,
          callback: function (pdf) {
            window.open(pdf.output('bloburl'));
          }
        });
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
            <h4 class="card-title mb-4">Seleccionar Categoría</h4>
            <multiselect 
              v-model="categoryModality"
              deselect-label=""
              :options="categoryModalityWithResults"
              @input="categoryModalityHandler"
            ></multiselect>
          </div>
        </div>

        <div>
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="card-title mb-0">Ranking Categoría "{{ categoryModality }}"</h4>
            <div>
              <b-button size="sm" variant="primary" @click="printRankingTable">
                Imprimir Ranking
              </b-button>
              <b-button size="sm" variant="info" @click="printFinalTable">
                Imprimir Clasificación
              </b-button>
            </div>
          </div>
          <div class="row mb-md-2">
            <div class="col-sm-12 col-md-6">
              <div id="tickets-table_length" class="dataTables_length">
                <label class="d-inline-flex align-items-center">
                  Mostrar 
                  <b-form-select v-model="perPage" size="sm" :options="pageOptions"></b-form-select> registros
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
          <div class="table-responsive table-bordered table-dark ranking-table mb-0">
            <b-table
              :items="categoryRankingPoints"
              responsive="sm"
              :per-page="perPage"
              :current-page="currentPage"
              :sort-by.sync="sortBy"
              :sort-desc.sync="sortDesc"
              :filter="filter"
              :filter-included-fields="filterOn"
              @filtered="onFiltered"
            >
              <template #thead-top="data">
                <b-tr>
                  <b-th variant="success" :colspan="competitionNumber+6" style="color: black;text-align: center;font-size: 18px;">{{ categoryModality }}</b-th>
                </b-tr>
                <b-tr>
                  <b-th  colspan="3" style="background: white;color: black;text-align: center;">RANKING 2021</b-th>
                  <b-th variant="primary" :colspan="competitionNumber" style="color: black;text-align: center;">COMPETICIONES PUNTUABLES</b-th>
                  <b-th variant="pink" colspan="3" style="color: black;text-align: center;">TRES MEJORES</b-th>
                </b-tr>
              </template>
            </b-table>
          </div>
          <vue-html2pdf
            :show-layout="false"
            :float-layout="true"
            :enable-download="false"
            :preview-modal="true"
            :paginate-elements-by-height="1400"
            filename="competition_heats"
            :pdf-quality="2"
            :manual-pagination="false"
            pdf-format="a4"
            pdf-orientation="portrait"
            pdf-content-width="100%"

            ref="html2Pdf"
          >
            <section slot="pdf-content">
              <div id="ranking_table" class="table-responsive table-bordered mb-0">
                <b-table
                  :items="categoryRankingPoints"
                  responsive="sm"
                  :per-page="perPage"
                  :current-page="currentPage"
                  :sort-by.sync="sortBy"
                  :sort-desc.sync="sortDesc"
                  :filter="filter"
                  :filter-included-fields="filterOn"
                  @filtered="onFiltered"
                >
                  <template #thead-top="data">
                    <b-tr>
                      <b-th variant="success" :colspan="competitionNumber+6" style="font-size: 24px;">{{ categoryModality }}</b-th>
                    </b-tr>
                    <b-tr>
                      <b-th colspan="3">RANKING&nbsp;2021</b-th>
                      <b-th :colspan="competitionNumber">COMPETICIONES&nbsp;PUNTUABLES</b-th>
                      <b-th colspan="3">TRES&nbsp;MEJORES</b-th>
                    </b-tr>
                  </template>
                </b-table>
              </div>
            </section>
          </vue-html2pdf>
          <vue-html2pdf
            :show-layout="false"
            :float-layout="true"
            :enable-download="false"
            :preview-modal="true"
            :paginate-elements-by-height="1400"
            filename="competition_heats"
            :pdf-quality="2"
            :manual-pagination="false"
            pdf-format="a4"
            pdf-orientation="portrait"
            pdf-content-width="100%"

            ref="html2Pdf"
          >
            <section slot="pdf-content">
              <div id="final_ranking_table" class="table-responsive table-bordered">
                <table class="table table-responsive-sm mb-0">
                  <thead>
                    <tr>
                      <th colspan="3" style="background: #b8e6e2;font-size: 24px;">
                        {{ categoryModality }}
                      </th>
                    </tr>
                    <tr>
                      <th colspan="3">
                        RANKING&nbsp;2021
                      </th>
                    </tr>
                    <tr>
                      <th>Posicion</th>
                      <th>Participante</th>
                      <th>Suma&nbsp;3&nbsp;Mejores</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(row, index) in categoryRankingPoints" :key="index">
                      <td>{{ row.posicion }}</td>
                      <td>{{ row.participante }}</td>
                      <td>{{ row.suma_3_mejores }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </section>
          </vue-html2pdf>
          <div class="row mt-2">
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
  </Layout>
</template>

<style>
  #ranking_table div {
    margin-bottom: 0px; 
  }
  #ranking_table .table {
    color: black;
    font-size: 22px;
    text-align: center;
  }
  #final_ranking_table .table {
    color: black;
    font-size: 22px;
    text-align: center;
  }
</style>