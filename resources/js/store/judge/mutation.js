import type from './type'
import JwtService from '@/common/jwt.service'

const mutations = {
  [type.GET_JUDGE_ROUND_HEATS] (state, data) {
    if (data.judge_round_heats.length > 0) {
      if ((state.current_competition == data.judge_round_heats[0].com_cat_mod_participant.competition_id) && 
        (state.current_category == data.judge_round_heats[0].com_cat_mod_participant.category_id) && 
        (state.current_modality == data.judge_round_heats[0].com_cat_mod_participant.modality_id) && 
        (state.current_round == data.judge_round_heats[0].round) && 
        (state.current_heat == data.judge_round_heats[0].heat))
      {
        state.isActiveStatus = 2; // there is no new heat
      } else {
        state.isActiveStatus = 1; // new heat
        state.current_competition == data.judge_round_heats[0].com_cat_mod_participant.competition_id
        JwtService.setCurrentCompetition(data.judge_round_heats[0].com_cat_mod_participant.competition_id);
        state.current_category == data.judge_round_heats[0].com_cat_mod_participant.category_id
        JwtService.setCurrentCategory(data.judge_round_heats[0].com_cat_mod_participant.category_id);
        state.current_modality == data.judge_round_heats[0].com_cat_mod_participant.modality_id
        JwtService.setCurrentModality(data.judge_round_heats[0].com_cat_mod_participant.modality_id);
        state.current_round == data.judge_round_heats[0].round
        JwtService.setCurrentRound(data.judge_round_heats[0].round);
        state.current_heat == data.judge_round_heats[0].heat
        JwtService.setCurrentHeat(data.judge_round_heats[0].heat);
      }
    } else {
      state.isActiveStatus = 3; // all heats' status is 1 or 2. 
    }
    state.judge_round_heats = data.judge_round_heats;
    state.judge_heat_scores = data.judge_heat_scores;
  },
  // [type.SET_ISACTIVESTATUS] (state, data) {
  //   state.isActiveStatus = 2;
  // },
};
export default mutations;