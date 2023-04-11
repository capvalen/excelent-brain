import axios from "axios";

export const saveExams = (body) =>
  axios.post('/api/exam', body)