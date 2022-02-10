import Vue from 'vue';
import Vuex from 'vuex';
import authModule from './auth';
import layoutModule from './layout';
import competitionModule from './competition';
import competitionTypeModule from './competition_type';
import categoryModule from './category';
import lycraModule from './lycra';
import clubModule from './club';
import participantModule from './participant';
import rankingPointsModule from './ranking_points';
import manageRankingModule from './manage_ranking';
import liveManagementModule from './live_management';
import judgeModule from './judge';
import userModule from './user';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    auth: authModule,
    layout: layoutModule,
    competition: competitionModule,
    competitionType: competitionTypeModule,
    category: categoryModule,
    lycra: lycraModule,
    club: clubModule,
    participant: participantModule,
    rankingPoints: rankingPointsModule,
    manageRanking: manageRankingModule,
    liveManagement: liveManagementModule,
    judge: judgeModule,
    user: userModule,
  },
});