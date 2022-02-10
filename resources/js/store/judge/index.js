import mutations from './mutation'
import actions from './action'
import getters from './getter'
import JwtService from "@/common/jwt.service"

const defaultState = {
  judge_round_heats: [],
  judge_heat_scores: [],
  isActiveStatus: 0,
  current_competition: JwtService.getCurrentCompetition()? JwtService.getCurrentCompetition() : 0,
  current_category: JwtService.getCurrentCategory()? JwtService.getCurrentCategory() : 0,
  current_modality: JwtService.getCurrentModality()? JwtService.getCurrentModality() : 0,
  current_round: JwtService.getCurrentRound()? JwtService.getCurrentRound() : 0,
  current_heat: JwtService.getCurrentHeat()? JwtService.getCurrentHeat() : 0,
};

export default {
  state: defaultState,
  getters,
  actions,
  mutations,
};
