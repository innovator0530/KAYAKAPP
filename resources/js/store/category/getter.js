const getters = {
    getCategories(state) {
        return state.categories;
    },
    categoryOptions(state) {
        return state.categoryOptions;
    },
    getCategory(state) {
        return state.category;
    },
  };
  
  export default getters;