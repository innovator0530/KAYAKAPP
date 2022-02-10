<script>
	import Layout from "../subcomponent/layout";
	import appConfig from "@/app.config";
  import PageHeader from "@/components/page-header";
  import jsPDF from 'jspdf'
  import VueHtml2pdf from 'vue-html2pdf'

  import { mapActions, mapGetters } from 'vuex';

	export default {
		page: {
        title: "Competition Heats",
        meta: [{ name: "description", content: appConfig.description }]
    },
    components: {
      Layout,
      PageHeader,
      VueHtml2pdf,
    },
    data() {
      return {
        competitionId: 0,
        categoryId: 0,
        modalityId: 0,
        all_round_heats: null,
        isFinal: false,
        final_results: [],
      }
    },
    watch: {
      allRoundHeats: function() {
        this.all_round_heats = this.allRoundHeats;
        let length = this.all_round_heats.length;
        if ((this.all_round_heats[length-1].length == 1) && (this.all_round_heats[length-1][0][0].status == 1)) {
          this.getCompetitionFinalResults({
            competitionId: this.$route.params.competitionId,
            categoryId: this.$route.params.categoryId,
            modalityId: this.$route.params.modalityId,
          })
          .then((res) => {
            this.isFinal = true;
            this.final_results = res.data.final_results
            console.log(this.final_results)
          })
        }
      },
    },
    computed: {
      ...mapGetters([
        'allRoundHeats',
        'competitionFinalResults'
      ]),
    },
    mounted() {
      this.competitionId = this.$route.params.competitionId;
      this.categoryId = this.$route.params.categoryId;
      this.modalityId = this.$route.params.modalityId;
      this.initCompetitionHeats({
        competitionId: this.competitionId,
        categoryId: this.categoryId,
        modalityId: this.modalityId,
      });
    },
    methods: {
      ...mapActions([
        'initCompetitionHeats',
        'setProgressStatus',
        'getCompetitionFinalResults',
      ]),
      chunkArray(myArray, chunk_size){
        var index = 0;
        var arrayLength = myArray.length;
        var tempArray = [];
        var myChunk = [];
        for (index = 0; index < arrayLength; index += chunk_size) {
          myChunk = myArray.slice(index, index+chunk_size);
          // Do something if you want with the group
          tempArray.push(myChunk);
        }
        return tempArray;
      },
      heatDetailsGo(round, heat) {
        this.setProgressStatus({
          competitionId: this.competitionId,
          categoryId: this.categoryId,
          modalityId: this.modalityId,
          round: round,
          heat: heat,
        })
        .then((res) => {
          // console.log(res.data)
          if (res.data.message == "success") {
            this.$router.push({ name: 'CompetitionHeatDetails', params: {competitionId: this.competitionId, categoryId: this.categoryId, modalityId: this.modalityId, round: round, heat: heat} })
          } else {
            this.$toastr.warning('No se puede empezar otra manga hasta terminar la que ya está empezada. Revisa el listado de categorías para detectar la manga activa.', '', {timeout: 5000,closeButton: true,closeMethod: 'fadeOut',closeDuration: 300});
          }
        }) 
      },
      back() {
        this.$router.go(-1);
      },
      printCompetitionHeats() {
        var pdf = new jsPDF('p', 'mm', 'a4');
        var element = document.getElementById('competition_heats');
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
      },
      printCompetitionFinalResults() {
        var pdf = new jsPDF('p', 'mm', 'a4');
        var element = document.getElementById('competition_final_results');
        const e_width = element.offsetWidth;
        const pdfWidth = pdf.internal.pageSize.getWidth();
        pdf.html(element, {
          x: 8,
          y: 8,
          callback: function (pdf) {
            window.open(pdf.output('bloburl'));
          },
          html2canvas: {
            scale: (pdfWidth-16)/e_width,
          },
        });
      },
	  }
  }
</script>
<template>
  <Layout>
    <div class="d-flex pt-4" v-if="all_round_heats != null">
      <b-img
        :src="'/storage/'+all_round_heats[0][0][0].com_cat_mod_participant.competition.logo"
        height="127"
        alt="logo"
      ></b-img>
      <div class="w-50" style="border: 1px solid #64676f;" v-if="all_round_heats != null">
        <h4 class="mb-0 text-center" style="border-bottom: 1px solid #64676f;padding: 5px 20px;">{{ all_round_heats[0][0][0].com_cat_mod_participant.competition.title }}</h4>
        <p class="mb-0" style="border-bottom: 1px solid #64676f;padding: 3px 20px;">{{ all_round_heats[0][0][0].com_cat_mod_participant.competition.description }}</p>
        <p class="mb-0" style="border-bottom: 1px solid #64676f;padding: 3px 20px;">
          {{ all_round_heats[0][0][0].com_cat_mod_participant.competition.place }}
          {{ all_round_heats[0][0][0].com_cat_mod_participant.competition.date }}
          {{ all_round_heats[0][0][0].com_cat_mod_participant.competition.time }}
        </p>
        <p class="mb-0" style="padding: 3px 20px;">Antolatzailea: {{ all_round_heats[0][0][0].com_cat_mod_participant.competition.organizer }}</p>
      </div>
      <button @click="back"
        class="btn btn-secondary"
        style="width: 10%;position: absolute;right: 24px;"
      >
        Volver
      </button>
    </div>

    <div class="text-center w-100 mt-4" v-if="all_round_heats != null">
      <h4 style="background: #32394e;padding: 10px 0;">
        {{ all_round_heats[0][0][0].com_cat_mod_participant.category.name }}
        {{ all_round_heats[0][0][0].com_cat_mod_participant.category.sex.name }}
        {{ all_round_heats[0][0][0].com_cat_mod_participant.modality.name }}
      </h4>
    </div>

    <div>
      <div class="row" v-for="(round, round_index) in all_round_heats" :key="round_index">
        <h4 class="my-4 col-12" v-if="round.length == 1">FINAL</h4>
        <h4 class="my-4 col-12" v-else-if="round.length == 2">SEMI FINAL</h4>
        <h4 class="my-4 col-12" v-else-if="round.length == 3">CUARTOS DE FINAL</h4>
        <h4 class="my-4 col-12" v-else>RONDA {{ round_index+1 }}</h4>
        <div class="col-lg-4 col-md-6 col-sm-6 mb-3" v-for="(heat, heat_index) in round" :key="heat_index">
          <div class="table-responsive mb-0">
            <table class="table table-bordered">
              <thead>
                <tr style="color: black;background: #b8e6e2;cursor: pointer;" :class="{active: heat[0].status==3}">
                  <th colspan="4" v-if="round.length == 1" @click="heatDetailsGo(round_index+1, heat_index+1)">
                    Final
                  </th>
                  <th colspan="4" v-else-if="round.length == 2" @click="heatDetailsGo(round_index+1, heat_index+1)">
                    Semi Finals Manga {{ heat_index+1 }}
                  </th>
                  <th colspan="4" v-else-if="round.length == 3" @click="heatDetailsGo(round_index+1, heat_index+1)">
                  Cuartos de Final Manga {{ heat_index+1 }}
                  </th>
                  <th colspan="4" v-else @click="heatDetailsGo(round_index+1, heat_index+1)">
                    Ronda {{ round_index+1 }} Manga {{ heat_index+1 }}
                  </th>
                </tr>
                <tr class="thead-light">
                  <th></th>
                  <th>Participante</th>
                  <th>Puntos</th>
                  <th>Posición</th>
                </tr>
              </thead>
              <tbody>
                <tr :class="{classified: round_heat.position==1 || round_heat.position==2}" v-for="(round_heat, round_heat_index) in heat" :key="round_heat_index">
                  <th scope="row" v-bind:style="{ background: round_heat.lycra.color }"></th>
                  <td v-if="round_heat.ranking > 0">{{ round_heat.com_cat_mod_participant.participant.name+' '+round_heat.com_cat_mod_participant.participant.surname+' ('+round_heat.ranking+')' }}</td>
                  <td v-else>{{ round_heat.com_cat_mod_participant.participant.name+' '+round_heat.com_cat_mod_participant.participant.surname }}</td>
                  <td>{{ parseFloat(round_heat.points).toFixed(2) }}</td>
                  <td>{{ round_heat.position }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
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
        <div id="competition_heats">
          <div class="text-center">
            <h2>
              {{ all_round_heats[0][0][0].com_cat_mod_participant.category.name }}
              {{ all_round_heats[0][0][0].com_cat_mod_participant.category.sex.name }}
              {{ all_round_heats[0][0][0].com_cat_mod_participant.modality.name }}
              {{ all_round_heats[0][0][0].com_cat_mod_participant.competition.title }}
            </h2>
          </div>
          <div v-for="(round, round_index) in all_round_heats" :key="round_index">
            <div>
              <h2 class="my-4" v-if="round.length == 1">FINAL</h2>
              <h2 class="my-4" v-else-if="round.length == 2">SEMI&nbsp;FINAL</h2>
              <h2 class="my-4" v-else-if="round.length == 3">CUARTOS&nbsp;DE&nbsp;FINAL</h2>
              <h2 class="my-4" v-else>RONDA&nbsp;{{ round_index+1 }}</h2>
            </div>
            <div class="row">
              <div class="col-lg-4 col-md-6 col-sm-6 mb-3" v-for="(heat, heat_index) in round" :key="heat_index">
                <div class="table-responsive mb-0">
                  <table class="table table-bordered">
                    <thead>
                      <tr style="background: #b8e6e2;font-size: 22px;">
                        <th colspan="4" v-if="round.length == 1">
                          Final
                        </th>
                        <th colspan="4" v-else-if="round.length == 2">
                          Semi&nbsp;Finals&nbsp;Manga&nbsp;{{ heat_index+1 }}
                        </th>
                        <th colspan="4" v-else-if="round.length == 3">
                        Cuartos&nbsp;de&nbsp;Final&nbsp;Manga&nbsp;{{ heat_index+1 }}
                        </th>
                        <th colspan="4" v-else>
                          Ronda&nbsp;{{ round_index+1 }}&nbsp;Manga&nbsp;{{ heat_index+1 }}
                        </th>
                      </tr>
                      <tr class="thead-light">
                        <th></th>
                        <th>Participante</th>
                        <th>Puntos</th>
                        <th>Posición</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(round_heat, round_heat_index) in heat" :key="round_heat_index">
                        <th scope="row" v-bind:style="{ background: round_heat.lycra.color }"></th>
                        <td v-if="round_heat.ranking > 0">{{ round_heat.com_cat_mod_participant.participant.name+' '+round_heat.com_cat_mod_participant.participant.surname+' ('+round_heat.ranking+')' }}</td>
                        <td v-else>{{ round_heat.com_cat_mod_participant.participant.name+' '+round_heat.com_cat_mod_participant.participant.surname }}</td>
                        <td>{{ parseFloat(round_heat.points).toFixed(2) }}</td>
                        <td>{{ round_heat.position }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </vue-html2pdf>

    <vue-html2pdf
      :show-layout="false"
      :float-layout="true"
      :enable-download="false"
      :preview-modal="true"
      :paginate-elements-by-height="1400"
      filename="competition_final_results"
      :pdf-quality="2"
      :manual-pagination="false"
      pdf-format="a4"
      pdf-orientation="portrait"
      pdf-content-width="100%"
      ref="html2PdfFinal"
    >
      <section slot="pdf-content">
        <div v-if="final_results.length > 0" id="competition_final_results" class="competition_final_results table-responsive table-bordered">
          <table class="table table-responsive-sm mb-0">
            <thead>
              <tr class="text-center" style="background: #b8e6e2;font-size: 24px;">
                <th colspan="3">
                  {{final_results[0].category.name+' '+final_results[0].category.sex.name+' '+final_results[0].modality.name+' '+final_results[0].competition.title}}
                </th>
              </tr>
              <tr>
                <th>Posición</th>
                <th>Participante</th>
                <th>Pts. Ranking Obtenidos</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(row, index) in final_results" :key="index">
                <td>{{ row.ranking }}</td>
                <td>{{ row.participant.name+' '+row.participant.surname }}</td>
                <td>{{ row.ranking_points }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </vue-html2pdf>

    <div class="d-flex justify-content-between align-items-center my-2">
      <button @click="back"
        class="btn btn-secondary"
        style="width: 10%;"
      >
        Volver
      </button>
      <div>
        <button @click="printCompetitionHeats"
          class="btn btn-sm btn-primary"
        >
          Imprimir Mangas
        </button>
        <button @click="printCompetitionFinalResults"
          class="btn btn-sm btn-info"
          v-if="isFinal"
        >
          Imprimir Clasificación
        </button>
      </div>
    </div>
  </Layout>
</template>

<style>
thead tr.active {
  background: #f78080 !important;
}
tbody tr.classified {
  background: #0c101d !important;
}
#competition_heats h2 {
  color: black;
}
#competition_heats .table {
  color: black;
  font-size: 20px;
}

.competition_final_results .table {
  text-align: center;
  color: black;
  font-size: 22px;
}
</style>