import type from './type'
import JwtService from '@/common/jwt.service'

const mutations = {
  [type.GET_CATEGORY_MODALITY_WITH_PART] (state, data) {
    state.category_modality_with_part = data.category_modality_with_part;
  },
  [type.SET_PARTICIPANTS_COMPETITION_CATEGORY_MODALITY] (state, data) {
    state.participants_competition_category_modality = data.participants_competition_category_modality;
    state.category_id = data.category_id;
    state.modality_id = data.modality_id;
    state.active_status = data.status1;
    state.competition = {...data.competition};
    state.category_status = data.status;
    state.deleteStatus = data.deleteStatus;
  },
  [type.GET_ALL_ROUND_HEATS] (state, data) {
    state.all_round_heats = data.all_round_heats;
  },
  [type.GET_ROUND_HEAT_DETAILS] (state, data) {
    state.round_heats = data.round_heats;
    state.heat_scores = data.heat_scores;
  },
};
export default mutations;