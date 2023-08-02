import jwtDecode from "jwt-decode";
import { ENTRYPOINT } from "../../config/entrypoint";
import {AuthModel} from "../../types/auth";
import {AuthProvider} from "react-admin";

interface LoginCredentials {
  username: string;
  password: string
}

export const ADMIN_TOKEN = 'admin_token';

const authProvider: AuthProvider = {
  login: ({ username, password }: LoginCredentials) => {
    const loginPayload: AuthModel = { email: username, password };
    const request = new Request(`${ENTRYPOINT}/api/auth`, {
      method: 'POST',
      body: JSON.stringify(loginPayload),
      headers: new Headers({ 'Content-Type': 'application/json' }),
    });
    return fetch(request)
      .then((response) => {
        if (response.status < 200 || response.status >= 300) {
          throw new Error(response.statusText);
        }
        return response.json();
      })
      .then(({ token }) => {
        localStorage.setItem(ADMIN_TOKEN, token);
      });
  },
  logout: () => {
    localStorage.removeItem(ADMIN_TOKEN);
    return Promise.resolve();
  },
  checkAuth: () => {
    try {
      if (
        !localStorage.getItem(ADMIN_TOKEN) ||
        new Date().getTime() / 1000 >
        // @ts-ignore
        jwtDecode(localStorage.getItem(ADMIN_TOKEN))?.exp
      ) {
        return Promise.reject();
      }
      return Promise.resolve();
    } catch (e) {
      // override possible jwtDecode error
      return Promise.reject();
    }
  },
  checkError: (err: { status: any; response: { status: any; }; }) => {
    if ([401, 403].includes(err?.status || err?.response?.status)) {
      localStorage.removeItem(ADMIN_TOKEN);
      return Promise.reject();
    }
    return Promise.resolve();
  },
  getPermissions: () => Promise.resolve(),
};

export default authProvider;
