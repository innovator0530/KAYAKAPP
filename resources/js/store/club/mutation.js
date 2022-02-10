import type from './type'
import JwtService from '@/common/jwt.service'

const mutations = {
  [type.SET_ALL_CLUBS] (state, data) {
    state.clubs = data.clubs;
  },
  [type.SET_CLUB_OPTIONS] (state, data) {
    state.clubOptions = data.clubs;
  },
  [type.SET_CLUB] (state, data) {
    state.club = data.club;
  },
};
export default mutations;