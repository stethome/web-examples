import {DefaultSession, User} from "next-auth";
import {JWT} from "next-auth/jwt";

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
  interface Session {
    token?: JWT;
  }
}

declare module "next-auth/jwt" {
  interface JWT {
    user: User;
  }
}
