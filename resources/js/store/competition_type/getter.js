const getters = {
    getCompetitionTypes(state) {
        return state.competition_types;
    },
    typeOptions(state) {
        return state.typeOptions;
    },
    getCompetitionType(state) {
        return state.competition_type;
    },
  };
  
  export default getters;