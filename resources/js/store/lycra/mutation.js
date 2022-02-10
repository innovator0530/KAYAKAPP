import type from './type'
import JwtService from '@/common/jwt.service'

const mutations = {
  [type.SET_ALL_LYCRAS] (state, data) {
    state.lycras = data.lycras;
  },
  [type.SET_LYCRA_OPTIONS] (state, data) {
    state.lycraOptions = data.lycras;
    state.lycraColorOptions = data.lycraColors;
  },
  [type.SET_LYCRA] (state, data) {
    state.lycra = data.lycra;
  },
};
export default mutations;