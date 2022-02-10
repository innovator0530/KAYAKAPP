import type from './type'
import JwtService from '@/common/jwt.service'

const mutations = {
  [type.SET_ALL_COMPETITION_TYPES] (state, data) {
    state.competition_types = data.competition_types;
  },
  [type.SET_TYPE_OPTIONS] (state, data) {
    state.typeOptions = data.competition_types;
  },
  [type.SET_COMPETITION_TYPE] (state, data) {
    state.competition_type = data.competition_type;
  },
};
export default mutations;