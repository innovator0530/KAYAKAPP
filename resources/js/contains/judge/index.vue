<script>
import Layout from "./subcomponent/layout";
import appConfig from "@/app.config";

import {mapActions, mapGetters} from 'vuex';

export default {
    page: {
        title: "Juez",
        meta: [{name: "description", content: appConfig.description}]
    },
    components: {
        Layout,
    },
    data() {
        return {}
    },
    watch: {},
    computed: {
        ...mapGetters([
            'judge_round_heats',
            'judge_heat_scores',
            'isActiveStatus',
        ]),
    },
    mounted() {
        this.initJudge();
    },
    methods: {
        ...mapActions([
            'initJudge',
            'storeHeatResults',
        ]),
        // window.location.reload()
        saveResults() {
            this.storeHeatResults({
                judge_heat_scores: this.judge_heat_scores,
            });
        },
        closeResults() {
            this.storeHeatResults({
                judge_heat_scores: this.judge_heat_scores,
            })
                .then((res) => {
                    this.refresh();
                })
        },
        refresh() {
            window.location.reload();
        }
    }
};
</script>
<template>
    <Layout>
        <div v-if="isActiveStatus == 1">
            <div class="text-center w-100 mt-4">
                <h4 class="mb-0" style="background: #4a5471;padding: 10px 0;">
                    {{ judge_round_heats[0].com_cat_mod_participant.category.name }}
                    {{ judge_round_heats[0].com_cat_mod_participant.category.sex.name }}
                    {{ judge_round_heats[0].com_cat_mod_participant.modality.name }}
                    - Ronda {{ judge_round_heats[0].round }} Manga {{ judge_round_heats[0].heat }}
                </h4>
            </div>


            <div class="row">
                <div class="col-12">
                    <div class="table-responsive mb-0">
                        <table class="table table-bordered text-center">
                            <thead class="thead-light">
                            <tr>
                                <th>PARTICIPANTE</th>
                                <th v-for="n in 10" :key="n">{{ n }}</th>
                                <th>Pena</th>
                            </tr>
                            </thead>
                            <tbody v-for="(judge_heat_score, index) in judge_heat_scores" :key="index">
                            <tr>
                                <td :style="{ background: judge_heat_score.round_heat.lycra.color, }"></td>
                                <td><input v-model="judge_heat_score.wave_1" type="number" value="" max="10" step="0.1"
                                           class="custom-input"
                                           :class="{ 'is-invalid': judge_heat_score.wave_1 > 10 }"/></td>
                                <td><input v-model="judge_heat_score.wave_2"  type="number" value="" max="10" step="0.1"
                                           class="custom-input"
                                           :class="{ 'is-invalid': judge_heat_score.wave_2 > 10 }"/></td>
                                <td><input v-model="judge_heat_score.wave_3" type="number" value="" max="10" step="0.1"
                                           class="custom-input"
                                           :class="{ 'is-invalid': judge_heat_score.wave_3 > 10 }"/></td>
                                <td><input v-model="judge_heat_score.wave_4" type="number" value="" max="10" step="0.1"
                                           class="custom-input"
                                           :class="{ 'is-invalid': judge_heat_score.wave_4 > 10 }"/></td>
                                <td><input v-model="judge_heat_score.wave_5" type="number" value="" max="10" step="0.1"
                                           class="custom-input"
                                           :class="{ 'is-invalid': judge_heat_score.wave_5 > 10 }"/></td>
                                <td><input v-model="judge_heat_score.wave_6" type="number" value="" max="10" step="0.1"
                                           class="custom-input"
                                           :class="{ 'is-invalid': judge_heat_score.wave_6 > 10 }"/></td>
                                <td><input v-model="judge_heat_score.wave_7" type="number" value="" max="10" step="0.1"
                                           class="custom-input"
                                           :class="{ 'is-invalid': judge_heat_score.wave_7 > 10 }"/></td>
                                <td><input v-model="judge_heat_score.wave_8" type="number" value="" max="10" step="0.1"
                                           class="custom-input"
                                           :class="{ 'is-invalid': judge_heat_score.wave_8 > 10 }"/></td>
                                <td><input v-model="judge_heat_score.wave_9" type="text" value="" max="10" step="0.1"
                                           class="custom-input"
                                           :class="{ 'is-invalid': judge_heat_score.wave_9 > 10 }"/></td>
                                <td><input v-model="judge_heat_score.wave_10" type="text" value="" max="10" step="0.1"
                                           class="custom-input"
                                           :class="{ 'is-invalid': judge_heat_score.wave_10 > 10 }"/></td>
                                <td><input v-model="judge_heat_score.penal" type="text" step="1" min="0" max="2"
                                           class="custom-input"/></td>

                            </tr>
                            <tr style="height: 5px;">
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-4">
                <button @click="closeResults"
                        class="btn btn-info"
                >
                    Terminar
                </button>
                <button @click="saveResults"
                        class="btn btn-orange"
                >
                    Guardar
                </button>
            </div>
        </div>

        <div v-else>
            <div class="text-right">
                <button @click="refresh"
                        class="btn btn-warning mt-4"
                >
                    Actualizar
                </button>
            </div>
            <div class="text-center mt-5">
                <h3 v-if="isActiveStatus == 3">NO HAY MANGAS ACTIVAS. ACTUALICE CON EL BOTÓN.</h3>
                <h3 v-if="isActiveStatus == 2">ESPERE A QUE SE ACTIVE LA SIGUIENTE MANGA Y ACTUALICE ESTA PANTALLA.</h3>
            </div>
        </div>
    </Layout>
</template>

<style>
.custom-input {
    background: transparent;
    border: 0;
    color: #a8b2bc;
    text-align: center;
    max-width: 40px;
}

.custom-input:focus {
    outline: none;
}

.custom-input.is-invalid {
    border: 1px solid #ec4561;
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.table th {
    padding: 10px 0;
}

.table td {
    padding: 10px 0;
}
</style>
