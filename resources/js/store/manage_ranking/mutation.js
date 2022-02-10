import type from './type'
import JwtService from '@/common/jwt.service'

const mutations = {
  [type.SET_ALL_CATEGORY_MODALITY] (state, data) {
    state.all_category_modality = data.all_category_modality;
    state.category_modality_with_results = data.category_modality_with_results;
  },
  [type.SET_CATEGORY_RANKING_POINTS] (state, data) {
    state.category_ranking_points = data.category_ranking_points;
    state.competition_number = data.competition_number;
  },
};
export default mutations;