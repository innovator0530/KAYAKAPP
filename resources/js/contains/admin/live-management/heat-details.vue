<script>
import Layout from "../subcomponent/layout";
import appConfig from "@/app.config";
import PageHeader from "@/components/page-header";
import jsPDF from 'jspdf'
import VueHtml2pdf from 'vue-html2pdf'

import {mapActions, mapGetters} from 'vuex';

export default {
    page: {
        title: "Datos Manga",
        meta: [{name: "description", content: appConfig.description}]
    },
    components: {
        Layout,
        PageHeader,
        VueHtml2pdf
    },
    data() {
        return {
            competitionId: 0,
            categoryId: 0,
            modalityId: 0,
            round: 0,
            heat: 0,
            max: [],
        }
    },
    watch: {
        heat_scores: function () {
            let max = [];
            this.heat_scores.forEach(function (heat_score) {
                let temp = [];
                for (var i = 1; i < 11; i++) {
                    let sum = 0;
                    let divider = 0;
                    for (var j = 0; j < 3; j++) {
                        if (heat_score[j]['wave_' + i] > 0) {
                            sum += heat_score[j]['wave_' + i] / 1
                            divider++
                        }
                    }
                    heat_score[3]['wave_' + i] = sum / ((divider == 0) ? 1 : divider)
                    temp.push(heat_score[3]['wave_' + i]);
                }
                temp.sort();
                max.push(temp[7])
            })  
            console.log(max);
            this.max = max
        },
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
        this.initHeatDetails({
            competitionId: this.competitionId,
            categoryId: this.categoryId,
            modalityId: this.modalityId,
            round: this.round,
            heat: this.heat,
        });
    },
    methods: {
        ...mapActions([
            'initHeatDetails',
            'storeFinalHeatResults',
        ]),

        penalHandler() {
            this.round_heats.forEach(function (round_heat) {
                if (round_heat.penal > 2) {
                    round_heat.penal = 2;
                }
                if (round_heat.penal == 2) {
                    round_heat.first_score = 0
                    round_heat.second_score = 0
                    round_heat.points = 0
                }
            })
        },
        drawHandler() {
            this.round_heats.forEach(function (round_heat) {
                if (round_heat.draw > 2) {
                    round_heat.draw = 2;
                }
                round_heat.points = round_heat.first_score + round_heat.second_score + round_heat.draw / 100;
            })
        },
        averageHandler() {
            // console.log(this.heat_scores)
            this.heat_scores.forEach(function (heat_score) {
                for (var i = 1; i < 11; i++) {
                    let sum = 0;
                    let divider = 0;
                    for (var j = 0; j < 3; j++) {
                        if (heat_score[j]['wave_' + i] > 0) {
                            sum += heat_score[j]['wave_' + i] / 1
                            divider++
                        }
                    }
                    heat_score[3]['wave_' + i] = sum / ((divider == 0) ? 1 : divider)
                }
            })
        },
        resetHeatDetails() {
             this.heat_scores.forEach(function (heat_score) {
                for (var j = 0; j < 3; j++) {
                    for (var i = 1; i < 11; i++) {
                        if (heat_score[j]['wave_' + i] > 0) {
                            heat_score[j]['wave_' + i] = 0;
                        }
                        heat_score[3]['wave_' + i] = 0;
                    }
                    heat_score[j]['penal'] = 0;

                }
            })
            this.storeFinalHeatResults({
                heat_scores: this.heat_scores,
                round_heats: this.round_heats,
                status: "save",
            })
            .then(() => {
                window.location.reload();
            });
        },
        saveResults() {
            this.storeFinalHeatResults({
                heat_scores: this.heat_scores,
                round_heats: this.round_heats,
                status: "save",
            })
                .then(() => {
                    window.location.reload();
                });
        },
        closeResults() {
            this.storeFinalHeatResults({
                heat_scores: this.heat_scores,
                round_heats: this.round_heats,
                status: "close",
            })
                .then(() => {
                    this.$router.go(-1);
                });
        },
        printHeatDetails() {
            var pdf = new jsPDF('p', 'mm', 'a4');
            var element = document.getElementById('heat_details');
            const e_width = element.offsetWidth;
            const pdfWidth = pdf.internal.pageSize.getWidth();
            pdf.html(element, {
                html2canvas: {
                    scale: (pdfWidth - 16) / e_width,
                },
                x: 8,
                y: 8,
                callback: function (pdf) {
                    window.open(pdf.output('bloburl'));
                }
            });
        },
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
        <div class="text-center w-100 mt-4" style="position: relative;">
            <h4 style="padding: 10px 0;">
                {{ round_heats[0].com_cat_mod_participant.category.name }}
                {{ round_heats[0].com_cat_mod_participant.category.sex.name }}
                {{ round_heats[0].com_cat_mod_participant.modality.name }}
                <span style="font-size: 16px;">(Ronda {{ round }} Manga {{ heat }})</span>
            </h4>
            <div style="position: absolute;right: 0px;bottom: 0;">
                <button class="btn btn-danger mr-2"
                    v-b-modal.reset-modal
                >
                    Reset Manga
                </button>
                
                <button @click="printHeatDetails"
                        class="btn btn-primary mr-2"
                >
                    Imprimir
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
                    <table class="reduced-table table table-bordered">
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
                            <td>{{
                                    round_heat.com_cat_mod_participant.participant.name + ' ' + round_heat.com_cat_mod_participant.participant.surname
                                }}
                            </td>
                            <td>{{ round_heat.position }}</td>
                            <td>{{ parseFloat(round_heat.first_score).toFixed(2) }}</td>
                            <td>{{ parseFloat(round_heat.second_score).toFixed(2) }}</td>
                            <td><input v-model="round_heat.penal" v-on:change="penalHandler" type="number" step="1"
                                       min="0" max="2" class="custom-input"/></td>
                            <td><input v-model="round_heat.draw" v-on:change="drawHandler" type="number" step="1"
                                       min="0" max="2" class="custom-input"/></td>
                            <td>{{ parseFloat(round_heat.points).toFixed(2) }}</td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between my-3">
                        <button @click="closeResults"
                                class="btn btn-info"
                        >
                            Terminar Manga
                        </button>
                        <button @click="refresh"
                                class="btn btn-warning"
                        >
                            Recibir Datos Juez
                        </button>
                        <button @click="saveResults"
                                class="btn btn-orange"
                        >
                            Guardar Datos
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-1">
                <div class="table-responsive mb-0">
                    <table class="table reduced-table table-bordered table-sm text-center">
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
                            <td rowspan="4" :style="{background: heat_score_row.round_heat.lycra.color}"
                                v-if="index_2==0"></td>
                            <td v-if="heat_score_row.judge_id != 'Average'">{{ heat_score_row.judge.name }}</td>
                            <td v-else style="background: #0c101d;">{{ heat_score_row.judge_id }}</td>
                            <td v-if="heat_score_row.judge_id != 'Average'"><input v-model="heat_score_row.wave_1"
                                                                                   v-on:change="averageHandler"
                                                                                   type="number" min="0" max="10"
                                                                                   step="0.1" class="custom-input"
                                                                                   :class="{ 'is-invalid': heat_score_row.wave_1 > 10 }"/>
                            </td>
                            <td v-else style="background: #0c101d;" :class="{'is-max': max[index_1]===heat_score_row.wave_1 && max[index_1] != 0} ">{{
                                    parseFloat(heat_score_row.wave_1).toFixed(2)
                                }}
                            </td>
                            <td v-if="heat_score_row.judge_id != 'Average'"><input v-model="heat_score_row.wave_2"
                                                                                   v-on:change="averageHandler"
                                                                                   type="number" min="0" max="10"
                                                                                   step="0.1" class="custom-input"
                                                                                   :class="{ 'is-invalid': heat_score_row.wave_2 > 10 }"/>
                            </td>
                            <td v-else style="background: #0c101d;" :class="{'is-max': max[index_1]===heat_score_row.wave_2 && max[index_1] != 0}">{{
                                    parseFloat(heat_score_row.wave_2).toFixed(2)
                                }}
                            </td>
                            <td v-if="heat_score_row.judge_id != 'Average'"><input v-model="heat_score_row.wave_3"
                                                                                   v-on:change="averageHandler"
                                                                                   type="number" min="0" max="10"
                                                                                   step="0.1" class="custom-input"
                                                                                   :class="{ 'is-invalid': heat_score_row.wave_3 > 10 }"/>
                            </td>
                            <td v-else style="background: #0c101d;" :class="{'is-max': max[index_1]===heat_score_row.wave_3 && max[index_1] != 0}">{{
                                    parseFloat(heat_score_row.wave_3).toFixed(2)
                                }}
                            </td>
                            <td v-if="heat_score_row.judge_id != 'Average'"><input v-model="heat_score_row.wave_4"
                                                                                   v-on:change="averageHandler"
                                                                                   type="number" min="0" max="10"
                                                                                   step="0.1" class="custom-input"
                                                                                   :class="{ 'is-invalid': heat_score_row.wave_4 > 10 }"/>
                            </td>
                            <td v-else style="background: #0c101d;" :class="{'is-max': max[index_1]===heat_score_row.wave_4 && max[index_1] != 0}">{{
                                    parseFloat(heat_score_row.wave_4).toFixed(2)
                                }}
                            </td>
                            <td v-if="heat_score_row.judge_id != 'Average'"><input v-model="heat_score_row.wave_5"
                                                                                   v-on:change="averageHandler"
                                                                                   type="number" min="0" max="10"
                                                                                   step="0.1" class="custom-input"
                                                                                   :class="{ 'is-invalid': heat_score_row.wave_5 > 10 }"/>
                            </td>
                            <td v-else style="background: #0c101d;" :class="{'is-max': max[index_1]===heat_score_row.wave_5 && max[index_1] != 0}">{{
                                    parseFloat(heat_score_row.wave_5).toFixed(2)
                                }}
                            </td>
                            <td v-if="heat_score_row.judge_id != 'Average'"><input v-model="heat_score_row.wave_6"
                                                                                   v-on:change="averageHandler"
                                                                                   type="number" min="0" max="10"
                                                                                   step="0.1" class="custom-input"
                                                                                   :class="{ 'is-invalid': heat_score_row.wave_6 > 10 }"/>
                            </td>
                            <td v-else style="background: #0c101d;" :class="{'is-max': max[index_1]===heat_score_row.wave_6 && max[index_1] != 0}">{{
                                    parseFloat(heat_score_row.wave_6).toFixed(2)
                                }}
                            </td>
                            <td v-if="heat_score_row.judge_id != 'Average'"><input v-model="heat_score_row.wave_7"
                                                                                   v-on:change="averageHandler"
                                                                                   type="number" min="0" max="10"
                                                                                   step="0.1" class="custom-input"
                                                                                   :class="{ 'is-invalid': heat_score_row.wave_7 > 10 }"/>
                            </td>
                            <td v-else style="background: #0c101d;" :class="{'is-max': max[index_1]===heat_score_row.wave_7 && max[index_1] != 0}">{{
                                    parseFloat(heat_score_row.wave_7).toFixed(2)
                                }}
                            </td>
                            <td v-if="heat_score_row.judge_id != 'Average'"><input v-model="heat_score_row.wave_8"
                                                                                   v-on:change="averageHandler"
                                                                                   type="number" min="0" max="10"
                                                                                   step="0.1" class="custom-input"
                                                                                   :class="{ 'is-invalid': heat_score_row.wave_8 > 10 }"/>
                            </td>
                            <td v-else style="background: #0c101d;" :class="{'is-max': max[index_1]===heat_score_row.wave_8 && max[index_1] != 0}">{{
                                    parseFloat(heat_score_row.wave_8).toFixed(2)
                                }}
                            </td>
                            <td v-if="heat_score_row.judge_id != 'Average'"><input v-model="heat_score_row.wave_9"
                                                                                   v-on:change="averageHandler"
                                                                                   type="number" min="0" max="10"
                                                                                   step="0.1" class="custom-input"
                                                                                   :class="{ 'is-invalid': heat_score_row.wave_9 > 10 }"/>
                            </td>
                            <td v-else style="background: #0c101d;" :class="{'is-max': max[index_1]===heat_score_row.wave_9 && max[index_1] != 0}">{{
                                    parseFloat(heat_score_row.wave_9).toFixed(2)
                                }}
                            </td>
                            <td v-if="heat_score_row.judge_id != 'Average'"><input v-model="heat_score_row.wave_10"
                                                                                   v-on:change="averageHandler"
                                                                                   type="number" min="0" max="10"
                                                                                   step="0.1" class="custom-input"
                                                                                   :class="{ 'is-invalid': heat_score_row.wave_10 > 10 }"/>
                            </td>
                            <td v-else style="background: #0c101d;" :class="{'is-max': max[index_1]===heat_score_row.wave_10 && max[index_1] != 0}">{{
                                    parseFloat(heat_score_row.wave_10).toFixed(2)
                                }}
                            </td>
                            <td v-if="heat_score_row.judge_id != 'Average'">{{ heat_score_row.penal }}</td>
                            <td v-else style="background: #0c101d;"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between my-4">
                    <button @click="closeResults"
                            class="btn btn-info"
                    >
                        Terminar Manga
                    </button>
                    <button @click="refresh"
                            class="btn btn-warning"
                    >
                        Recibir Datos Juez
                    </button>
                    <button @click="saveResults"
                            class="btn btn-orange"
                    >
                        Guardar Datos
                    </button>
                </div>
            </div>
        </div>

        <vue-html2pdf
            :show-layout="false"
            :float-layout="true"
            :enable-download="false"
            :preview-modal="true"
            :paginate-elements-by-height="1400"
            filename="heat_details"
            :pdf-quality="2"
            :manual-pagination="false"
            pdf-format="a4"
            pdf-orientation="portrait"
            pdf-content-width="100%"

            ref="html2Pdf"
        >
            <section slot="pdf-content">
                <div id="heat_details">
                    <div class="text-center w-100 mt-4">
                        <h2>
                            {{ round_heats[0].com_cat_mod_participant.category.name }}
                            {{ round_heats[0].com_cat_mod_participant.category.sex.name }}
                            {{ round_heats[0].com_cat_mod_participant.modality.name }}
                            <span style="font-size: 20px;">(Ronda {{ round }} Manga {{ heat }})</span>
                        </h2>
                    </div>

                    <div class="row mt-4">
                        <div class="col-lg-9 col-md-10 col-sm-12">
                            <div class="table-responsive mb-0">
                                <table class="table table-bordered text-center" style="font-size: 19px;">
                                    <thead>
                                    <tr class="">
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
                                        <td>{{
                                                round_heat.com_cat_mod_participant.participant.name + ' ' + round_heat.com_cat_mod_participant.participant.surname
                                            }}
                                        </td>
                                        <td>{{ round_heat.position }}</td>
                                        <td>{{ parseFloat(round_heat.first_score).toFixed(2) }}</td>
                                        <td>{{ parseFloat(round_heat.second_score).toFixed(2) }}</td>
                                        <td><input v-model="round_heat.penal" v-on:change="penalHandler" type="number"
                                                   step="1" min="0" max="2" class="custom-input"/></td>
                                        <td><input v-model="round_heat.draw" v-on:change="drawHandler" type="number"
                                                   step="1" min="0" max="2" class="custom-input"/></td>
                                        <td>{{ parseFloat(round_heat.points).toFixed(2) }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <div class="table-responsive mb-0">
                                <table class="table table-bordered text-center" style="font-size: 20px;">
                                    <thead class="">
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
                                        <td rowspan="4" :style="{background: heat_score_row.round_heat.lycra.color}"
                                            v-if="index_2==0"></td>
                                        <td v-if="heat_score_row.judge_id != 'Average'">{{
                                                heat_score_row.judge.name
                                            }}
                                        </td>
                                        <td v-else>{{ heat_score_row.judge_id }}</td>

                                        <td v-if="heat_score_row.judge_id != 'Average'"><input
                                            v-model="heat_score_row.wave_1" v-on:change="averageHandler" type="number"
                                            min="0" max="10" step="0.1" class="custom-input"
                                            :class="{ 'is-invalid': heat_score_row.wave_1 > 10  }"/></td>
                                        <td v-else>{{ parseFloat(heat_score_row.wave_1).toFixed(2) }}</td>
                                        <td v-if="heat_score_row.judge_id != 'Average'"><input
                                            v-model="heat_score_row.wave_2" v-on:change="averageHandler" type="number"
                                            min="0" max="10" step="0.1" class="custom-input"
                                            :class="{ 'is-invalid': heat_score_row.wave_2 > 10 }"/></td>
                                        <td v-else>{{ parseFloat(heat_score_row.wave_2).toFixed(2) }}</td>
                                        <td v-if="heat_score_row.judge_id != 'Average'"><input
                                            v-model="heat_score_row.wave_3" v-on:change="averageHandler" type="number"
                                            min="0" max="10" step="0.1" class="custom-input"
                                            :class="{ 'is-invalid': heat_score_row.wave_3 > 10 }"/></td>
                                        <td v-else>{{ parseFloat(heat_score_row.wave_3).toFixed(2) }}</td>
                                        <td v-if="heat_score_row.judge_id != 'Average'"><input
                                            v-model="heat_score_row.wave_4" v-on:change="averageHandler" type="number"
                                            min="0" max="10" step="0.1" class="custom-input"
                                            :class="{ 'is-invalid': heat_score_row.wave_4 > 10 }"/></td>
                                        <td v-else>{{ parseFloat(heat_score_row.wave_4).toFixed(2) }}</td>
                                        <td v-if="heat_score_row.judge_id != 'Average'"><input
                                            v-model="heat_score_row.wave_5" v-on:change="averageHandler" type="number"
                                            min="0" max="10" step="0.1" class="custom-input"
                                            :class="{ 'is-invalid': heat_score_row.wave_5 > 10 }"/></td>
                                        <td v-else>{{ parseFloat(heat_score_row.wave_5).toFixed(2) }}</td>
                                        <td v-if="heat_score_row.judge_id != 'Average'"><input
                                            v-model="heat_score_row.wave_6" v-on:change="averageHandler" type="number"
                                            min="0" max="10" step="0.1" class="custom-input"
                                            :class="{ 'is-invalid': heat_score_row.wave_6 > 10 }"/></td>
                                        <td v-else>{{ parseFloat(heat_score_row.wave_6).toFixed(2)  }}</td>
                                        <td v-if="heat_score_row.judge_id != 'Average'"><input
                                            v-model="heat_score_row.wave_7" v-on:change="averageHandler" type="number"
                                            min="0" max="10" step="0.1" class="custom-input"
                                            :class="{ 'is-invalid': heat_score_row.wave_7 > 10 }"/></td>
                                        <td v-else>{{ parseFloat(heat_score_row.wave_7).toFixed(2) }}</td>
                                        <td v-if="heat_score_row.judge_id != 'Average'"><input
                                            v-model="heat_score_row.wave_8" v-on:change="averageHandler" type="number"
                                            min="0" max="10" step="0.1" class="custom-input"
                                            :class="{ 'is-invalid': heat_score_row.wave_8 > 10 }"/></td>
                                        <td v-else>{{ parseFloat(heat_score_row.wave_8).toFixed(2) }}</td>
                                        <td v-if="heat_score_row.judge_id != 'Average'"><input
                                            v-model="heat_score_row.wave_9" v-on:change="averageHandler" type="number"
                                            min="0" max="10" step="0.1" class="custom-input"
                                            :class="{ 'is-invalid': heat_score_row.wave_9 > 10 }"/></td>
                                        <td v-else>{{ parseFloat(heat_score_row.wave_9).toFixed(2) }}</td>
                                        <td v-if="heat_score_row.judge_id != 'Average'"><input
                                            v-model="heat_score_row.wave_10" v-on:change="averageHandler" type="number"
                                            min="0" max="10" step="0.1" class="custom-input"
                                            :class="{ 'is-invalid': heat_score_row.wave_10 > 10 }"/></td>
                                        <td v-else>{{ parseFloat(heat_score_row.wave_10).toFixed(2) }}</td>
                                        <td v-if="heat_score_row.judge_id != 'Average'">{{ heat_score_row.penal }}</td>
                                        <td v-else></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <b-modal
                id="reset-modal"
                centered
                title="Resetear valores Manga"
                title-class="font-18"
                hide-footer
                >
                <p>¿Perderá todos los valores de esta manga?</p>
                <footer id="reset-modal___BV_modal_footer_" class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="$bvModal.hide('reset-modal')">Cancelar</button>
                    <button type="button" class="btn btn-primary" @click="resetHeatDetails()">OK</button>
                </footer>
                </b-modal>
            </section>
        </vue-html2pdf>
    </Layout>
    
</template>

<style scoped>
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

input[type=number] {
    -moz-appearance: textfield;
}

#heat_details h2 {
    color: black;
}

#heat_details .table {
    color: black;
}

#heat_details .custom-input {
    color: black;
}
.is-max {
    background-color: #f8b425 !important;
    color: red !important;
}
</style>
