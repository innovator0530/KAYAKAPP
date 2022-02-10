const getters = {
    getAllCategoryModality(state) {
        return state.all_category_modality;
    },
    categoryModalityWithResults(state) {
        return state.category_modality_with_results;
    },
    categoryRankingPoints(state) {
        return state.category_ranking_points;
    },
    competitionNumber(state) {
        return state.competition_number;
    },
  };
  
  export default getters;