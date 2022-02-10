import mutations from './mutation'
import actions from './action'
import getters from './getter'
import JwtService from "@/common/jwt.service"

const defaultState = {
  all_category_modality: [],
  category_modality_with_results: [],
  category_ranking_points: [],
  competition_number: 0,
};

export default {
  state: defaultState,
  getters,
  actions,
  mutations,
};
