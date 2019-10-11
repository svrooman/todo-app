import axios from "axios";

export default {
  fetch() {
    return axios.get("/api/todos");
  },
  store(formData) {
    return axios.post("/api/todos", formData);
  },
  update(formData, id) {
    return axios.patch("/api/todos/" + id, formData);
  },
  delete(id) {
    return axios.delete("/api/todos/" + id);
  }
};
