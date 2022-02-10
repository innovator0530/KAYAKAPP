import type from './type'
import JwtService from '@/common/jwt.service'

const mutations = {
  [type.SET_RANKING_POINTS] (state, data) {
    // console.log(data.ranking_points)
    state.ranking_points = data.ranking_points;
  },
  [type.SET_RANKING] (state, data) {
    state.ranking = data.ranking;
  },
  [type.SET_ALL_RANKINGS] (state, data) {
    state.rankings = data.rankings;
  },
};
export default mutations;