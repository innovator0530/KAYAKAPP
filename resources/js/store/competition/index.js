import mutations from './mutation'
import actions from './action'
import getters from './getter'
import JwtService from "@/common/jwt.service"

const defaultState = {
  competitions: [],
  competition: {},
  registered_participants: [],
  non_participants: [],
};

export default {
  state: defaultState,
  getters,
  actions,
  mutations,
};
