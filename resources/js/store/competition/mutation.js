import type from './type'
import JwtService from '@/common/jwt.service'

const mutations = {
  [type.SET_ALL_COMPETITION] (state, data) {
    state.competitions = data.competitions;
  },
  [type.SET_COMPETITION] (state, data) {
    state.competition = data.competition;
  },
  [type.SET_REGISTERED_AND_NON_PARTICIPANTS] (state, data) {
    state.registered_participants = data.registered_participants;
    state.non_participants = data.non_participants;
  },
};
export default mutations;