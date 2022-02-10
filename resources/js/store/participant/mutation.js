import type from './type'
import JwtService from '@/common/jwt.service'

const mutations = {
  [type.SET_ALL_PARTICIPANTS] (state, data) {
    state.participants = data.participants;
  },
  [type.SET_PARTICIPANT] (state, data) {
    state.participant = data.participant;
  },
};
export default mutations;