const getters = {
    getRankingPoints(state) {
        // console.log(state.ranking_points)
        return state.ranking_points;
    },
    getRanking(state) {
        return state.ranking;
    },
    getRankings(state) {
        return state.rankings;
    },
  };
  
  export default getters;