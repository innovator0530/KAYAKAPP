import mutations from './mutation'
import actions from './action'
import getters from './getter'
import JwtService from "@/common/jwt.service"

const defaultState = {
  participants: [],
  participant: {}
};

export default {
  state: defaultState,
  getters,
  actions,
  mutations,
};
