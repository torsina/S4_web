const axios = require("axios");

module.exports = () => {
	return axios.create({
		baseURL: "http://" + document.location.host + "/api",
		withCredentials: false,
		headers: {
			'Accept': 'application/json',
			'accept': 'application/json',
			'Content-Type': 'application/json'
		}
	})
};
