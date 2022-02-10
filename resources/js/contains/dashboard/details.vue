<script>
	import Layout from "./subcomponent/layout";
	import appConfig from "@/app.config";
  import PageHeader from "@/components/page-header";

  import { mapActions, mapGetters } from 'vuex';

	export default {
		page: {
        title: "Resultados Competición",
        meta: [{ name: "description", content: appConfig.description }]
    },
    components: {
      Layout,
      PageHeader,
    },
    data() {
      return {
        competitionId: 0,
        categoryId: 0,
        modalityId: 0,
        round: 0,
        heat: 0,
      }
    },
    watch: {

    },
    computed: {
      ...mapGetters([
        'round_heats',
        'heat_scores',
      ]),
    },
    mounted() {
      this.competitionId = this.$route.params.competitionId;
      this.categoryId = this.$route.params.categoryId;
      this.modalityId = this.$route.params.modalityId;
      this.round = this.$route.params.round;
      this.heat = this.$route.params.heat;
      this.initHomeHeatDetails({
        competitionId: this.competitionId,
        categoryId: this.categoryId,
        modalityId: this.modalityId,
        round: this.round,
        heat: this.heat,
      });
    },
    methods: {
      ...mapActions([
        'initHomeHeatDetails',
      ]),

      back() {
        this.$router.go(-1);
      },
      refresh() {
        window.location.reload();
      },
    }
	};
</script>
<template>
  <Layout>
    <div class="row" style="position: relative;">
      <div class="col-md-9">
        <h4 style="padding: 10px 0;">
          {{ round_heats[0].com_cat_mod_participant.category.name }}
          {{ round_heats[0].com_cat_mod_participant.category.sex.name }}
          {{ round_heats[0].com_cat_mod_participant.modality.name }}
          <span style="font-size: 16px;">(Ronda {{ round }} Manga {{ heat }})</span>
        </h4>
      </div>
      <div class="col-md-3" >
        <button @click="refresh"
          class="btn btn-warning mr-2"
        >
          Actualizar
        </button>
        <button @click="back"
          class="btn btn-secondary"
        >
          Volver
        </button>
      </div>
    </div>
    <div class="row mt-4">
      <div class="col-lg-8 col-md-9 col-sm-12">
        <div class="table-responsive mb-0">
          <table class="table table-bordered">
            <thead>
              <tr class="thead-light">
                <th>Lycra</th>
                <th>Participante</th>
                <th>Posición</th>
                <th>1ª Mejor Ola</th>
                <th>2ª Mejor Ola</th>
                <th>Pena</th>
                <th>Desempate</th>
                <th>Suma</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(round_heat, round_heat_index) in round_heats" :key="round_heat_index">
                <th scope="row" v-bind:style="{ background: round_heat.lycra.color }"></th>
                <td>{{ round_heat.com_cat_mod_participant.participant.name+' '+round_heat.com_cat_mod_participant.participant.surname }}</td>
                <td>{{ round_heat.position }}</td>
                <td>{{ parseFloat(round_heat.first_score).toFixed(2) }}</td>
                <td>{{ parseFloat(round_heat.second_score).toFixed(2) }}</td>
                <td>{{ round_heat.penal }}</td>
                <td>{{ round_heat.draw }}</td>
                <td>{{ parseFloat(round_heat.points).toFixed(2) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="col-12 mt-4">
        <div class="table-responsive mb-0">
          <table class="table table-bordered table-sm text-center">
            <thead class="thead-light">
              <tr>
                <th rowspan="2">PARTICIPANTE</th>
                <th rowspan="2">JUEZ</th>
                <th colspan="10">OLAS</th>
                <th rowspan="2">Pena</th>
              </tr>
              <tr>
                <th v-for="n in 10" :key="n">{{ n }}</th>
              </tr>
            </thead>
            <tbody v-for="(heat_score, index_1) in heat_scores" :key="index_1">
              <tr v-for="(heat_score_row, index_2) in heat_score" :key="index_2">
                <td rowspan="4" :style="{background: heat_score_row.round_heat.lycra.color}" v-if="index_2==0"></td>
                <td v-if="heat_score_row.judge_id != 'Average'">{{ heat_score_row.judge.name }}</td>
                <td v-else style="background: #0c101d;">{{ heat_score_row.judge_id }}</td>
                <td v-if="heat_score_row.judge_id != 'Average'">{{ heat_score_row.wave_1 }}</td>
                <td v-else style="background: #0c101d;">{{ parseFloat(heat_score_row.wave_1).toFixed(2) }}</td>
                <td v-if="heat_score_row.judge_id != 'Average'">{{ heat_score_row.wave_2 }}</td>
                <td v-else style="background: #0c101d;">{{ parseFloat(heat_score_row.wave_2).toFixed(2) }}</td>
                <td v-if="heat_score_row.judge_id != 'Average'">{{ heat_score_row.wave_3 }}</td>
                <td v-else style="background: #0c101d;">{{ parseFloat(heat_score_row.wave_3).toFixed(2) }}</td>
                <td v-if="heat_score_row.judge_id != 'Average'">{{ heat_score_row.wave_4 }}</td>
                <td v-else style="background: #0c101d;">{{ parseFloat(heat_score_row.wave_4).toFixed(2) }}</td>
                <td v-if="heat_score_row.judge_id != 'Average'">{{ heat_score_row.wave_5 }}</td>
                <td v-else style="background: #0c101d;">{{ parseFloat(heat_score_row.wave_5).toFixed(2) }}</td>
                <td v-if="heat_score_row.judge_id != 'Average'">{{ heat_score_row.wave_6 }}</td>
                <td v-else style="background: #0c101d;">{{ parseFloat(heat_score_row.wave_6).toFixed(2) }}</td>
                <td v-if="heat_score_row.judge_id != 'Average'">{{ heat_score_row.wave_7 }}</td>
                <td v-else style="background: #0c101d;">{{ parseFloat(heat_score_row.wave_7).toFixed(2) }}</td>
                <td v-if="heat_score_row.judge_id != 'Average'">{{ heat_score_row.wave_8 }}</td>
                <td v-else style="background: #0c101d;">{{ parseFloat(heat_score_row.wave_8).toFixed(2) }}</td>
                <td v-if="heat_score_row.judge_id != 'Average'">{{ heat_score_row.wave_9 }}</td>
                <td v-else style="background: #0c101d;">{{ parseFloat(heat_score_row.wave_9).toFixed(2) }}</td>
                <td v-if="heat_score_row.judge_id != 'Average'">{{ heat_score_row.wave_10 }}</td>
                <td v-else style="background: #0c101d;">{{ parseFloat(heat_score_row.wave_10).toFixed(2) }}</td>
                <td v-if="heat_score_row.judge_id != 'Average'">{{ heat_score_row.penal }}</td>
                <td v-else style="background: #0c101d;"></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </Layout>
</template>

<style scoped>
</style>
