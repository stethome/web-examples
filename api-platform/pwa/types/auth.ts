import {DefaultSession, User} from "next-auth";
import {DefaultJWT, JWT} from "next-auth/jwt";

export interface AuthModel {
  email: string;
  password: string;
}

export interface LoginCredentials {
  username: string;
  password: string
}

export interface LoginTokenResponse {
  token: string;
}

declare module "next-auth" {
  /**
   * Returned by `useSession`, `getSession` and received as a prop on the `SessionProvider` React Context
   */
  interface Session extends DefaultSession{
    user: User;
  }

  interface User {
    email: string;
    apiToken: string;
  }
}

declare module "next-auth/jwt" {
  interface JWT extends DefaultJWT {
    user: User;
  }
}
