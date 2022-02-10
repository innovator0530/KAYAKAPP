<script>
	import Layout from "./subcomponent/layout";
	import appConfig from "@/app.config";
  import Multiselect from "vue-multiselect";

  import { mapActions, mapGetters } from 'vuex';

	export default {
		page: {
        title: "Competición en Curso",
        meta: [{ name: "description", content: appConfig.description }]
    },
    components: {
      Layout,
      Multiselect,
    },
    data() {
      return {
        competitionId: 0,
        categoryId: 0,
        modalityId: 0,
        competition: {},
        categoryModalityWithResults: [],
        categoryModality: "",
        all_home_round_heats: [],
      }
    },
    watch: {
      
    },
    computed: {
      ...mapGetters([
      ]),
    },
    mounted() {
      this.initHome()
      .then((res) => {
        // console.log(res)
        this.categoryModalityWithResults = res.category_modality
        this.competition = res.competition
        this.competitionId = res.competition.id
      });
    },
    methods: {
      ...mapActions([
        'initHome',
        'getCompetitionHeats',
      ]),

      categoryModalityHandler() {
        this.getCompetitionHeats({
          competitionId: this.competitionId,
          categoryModality: this.categoryModality,
        })
        .then((res) => {
          // console.log(res)
          this.all_home_round_heats = res.all_round_heats
          this.categoryId = this.all_home_round_heats[0][0][0].com_cat_mod_participant.category_id
          this.modalityId = this.all_home_round_heats[0][0][0].com_cat_mod_participant.modality_id
        })
      },
      heatDetailsGo(round, heat) {
        this.$router.push({ name: 'Details', params: {competitionId: this.competitionId, categoryId: this.categoryId, modalityId: this.modalityId, round: round, heat: heat} })
      },
      refresh() {
        window.location.reload();
      },
    }
	};
</script>
<template>
  <Layout>
    <div style="position: relative; height:100px; flex-direction: column; align-items:center; display:flex;">
      
      
      <button @click="refresh"
        class="btn btn-warning"
        style="width: 100px;"
      >
        Actualizar
      </button>
    </div>

    <div class="row mt-4">
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
      </div>
    </div>

    <div class="row mb-4" v-if="all_home_round_heats.length > 0" v-for="(round, round_index) in all_home_round_heats" :key="round_index">
      <h4 class="mb-4 col-12" v-if="round.length == 1">FINAL</h4>
      <h4 class="mb-4 col-12" v-else-if="round.length == 2">SEMI FINALES</h4>
      <h4 class="mb-4 col-12" v-else-if="round.length == 3">CUARTOS DE FINAL</h4>
      <h4 class="mb-4 col-12" v-else>RONDA {{ round_index+1 }}</h4>
      <div class="col-lg-4 col-md-6 col-sm-6 mb-3" v-for="(heat, heat_index) in round" :key="heat_index">
        <div class="table-responsive mb-0">
          <table class="table table-bordered">
            <thead>
              <tr style="color: black;background: #b8e6e2;cursor: pointer;">
                <th colspan="4" v-if="round.length == 1" @click="heatDetailsGo(round_index+1, heat_index+1)">
                  Manga Final
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
                <th>Posicion</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(round_heat, round_heat_index) in heat" :key="round_heat_index">
                <th scope="row" v-bind:style="{ background: round_heat.lycra.color }"></th>
                <td>{{ round_heat.com_cat_mod_participant.participant.name+' '+round_heat.com_cat_mod_participant.participant.surname }}</td>
                <td>{{ parseFloat(round_heat.points).toFixed(2) }}</td>
                <td>{{ round_heat.position }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </Layout>
</template>

<style>
</style>