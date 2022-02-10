const getters = {
    getCompetitions(state) {
        return state.competitions;
    },
    getCompetition(state) {
        return state.competition;
    },
    getRegisteredParticipants(state) {
        return state.registered_participants;
    },
    getNonRegisteredParticipants(state) {
        return state.non_participants;
    },
  };
  
  export default getters;