const ID_API_TOKEN = 'id_api_token';
const ROLE = 'role';
const CURRENT_COMPETITION = 'current_competition';
const CURRENT_CATEGORY = 'current_category';
const CURRENT_MODALITY = 'current_modality';
const CURRENT_ROUND = 'current_round';
const CURRENT_HEAT = 'current_heat';

const JwtService = {
    getToken() {
        return window.localStorage.getItem(ID_API_TOKEN)
    },
    setToken(token) {
        window.localStorage.setItem(ID_API_TOKEN, token);
    },
    unsetToken() {
        window.localStorage.removeItem(ID_API_TOKEN);
    },

    getRole() {
        return window.localStorage.getItem(ROLE)
    },
    setRole(role) {
        window.localStorage.setItem(ROLE, role);
    },
    unsetRole() {
        window.localStorage.removeItem(ROLE);
    },

    getCurrentCompetition() {
        return window.localStorage.getItem(CURRENT_COMPETITION)
    },
    setCurrentCompetition(competition) {
        window.localStorage.setItem(CURRENT_COMPETITION, competition);
    },
    unsetCurrentCompetition() {
        window.localStorage.removeItem(CURRENT_COMPETITION);
    },

    getCurrentCategory() {
        return window.localStorage.getItem(CURRENT_CATEGORY)
    },
    setCurrentCategory(category) {
        window.localStorage.setItem(CURRENT_CATEGORY, category);
    },
    unsetCurrentCategory() {
        window.localStorage.removeItem(CURRENT_CATEGORY);
    },

    getCurrentModality() {
        return window.localStorage.getItem(CURRENT_MODALITY)
    },
    setCurrentModality(modality) {
        window.localStorage.setItem(CURRENT_MODALITY, modality);
    },
    unsetCurrentModality() {
        window.localStorage.removeItem(CURRENT_MODALITY);
    },

    getCurrentRound() {
        return window.localStorage.getItem(CURRENT_ROUND)
    },
    setCurrentRound(round) {
        window.localStorage.setItem(CURRENT_ROUND, round);
    },
    unsetCurrentRound() {
        window.localStorage.removeItem(CURRENT_ROUND);
    },

    getCurrentHeat() {
        return window.localStorage.getItem(CURRENT_HEAT)
    },
    setCurrentHeat(heat) {
        window.localStorage.setItem(CURRENT_HEAT, heat);
    },
    unsetCurrentHeat() {
        window.localStorage.removeItem(CURRENT_HEAT);
    },
}

export default JwtService