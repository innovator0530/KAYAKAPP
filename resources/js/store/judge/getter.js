const getters = {
    judge_round_heats(state) {
        return state.judge_round_heats;
    },
    judge_heat_scores(state) {
        return state.judge_heat_scores;
    },
    isActiveStatus(state) {
    	return state.isActiveStatus;
    },
};

export default getters;