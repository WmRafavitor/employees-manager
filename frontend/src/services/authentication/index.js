import http from "@/services/http";

const logout = () => {
  return http.post("auth/logout").then(response => {
    localStorage.removeItem("token");

    return response;
  });
}

const login = (email, password) => {
  return http
    .post("auth/login", {
      email,
      password
    })
    .then(response => {
      if (response.data.error) {
        return response;
      }
      const now = new Date();
      now.setTime(now.getTime() + (response.data.expires_in * 1000))
      const token = {
        ...response.data,
        expires_at: now.toISOString(),
        access_token: `Bearer ${response.data.access_token}`
      };
      localStorage.setItem("token", JSON.stringify(token));
      return response;
    });
}

const hasTokenValid = () => {
  const tokenJson = localStorage.getItem("token");
  if (tokenJson) {
    const token = JSON.parse(tokenJson);
    const expires_at = Date.parse(token.expires_at);
    return expires_at > new Date().getTime();
  }
  return false;
}

export default {
  logout,
  login,
  hasTokenValid,
}