import type from './type'
import JwtService from '@/common/jwt.service'

const mutations = {
  [type.SET_ALL_CATEGORIES] (state, data) {
    state.categories = data.categories;
  },
  [type.SET_CATEGORY] (state, data) {
    state.category = data.category;
  },
  [type.SET_CATEGORY_OPTIONS] (state, data) {
    state.categoryOptions = data.categories;
  },
};
export default mutations;